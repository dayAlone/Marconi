<?

	global $CITY, $setFilter, $arImages;
	require($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/bitrix/catalog.element/templates/.default/result_modifier.php');
	$arResult['BRANDS']     = getHighloadElements('brands', 'UF_XML_ID', 'UF_NAME');
	$arResult['COLORS']     = getHighloadElements('colors', 'UF_XML_ID', 'UF_NAME');
	$arResult['MATERIALS']  = getHighloadElements('materials', 'UF_XML_ID', 'UF_NAME');
	$arResult['SIZES']      = getHighloadElements('sizes', 'UF_XML_ID', 'UF_NAME');
	$arResult['TYPES']      = getHighloadElements('types', 'UF_XML_ID', 'UF_NAME');
	$arResult['TRADELINES'] = getHighloadElements('tradeline', 'UF_XML_ID', 'UF_NAME');
	$arResult['CATEGORIES'] = getHighloadElements('categories', 'UF_XML_ID', 'ID');
	$arResult['SECTIONS']   = array();
	$arResult['SET']        = false;
	if(SITE_ID == 's2'):
		if(CCatalogProductSet::isProductHaveSet($arResult['ID'])):
			$arResult['SET'] = array('TYPE' => false);
			$raw = array_values(CCatalogProductSet::getAllSetsByProduct($arResult['ID'], CCatalogProductSet::TYPE_SET));
			if(count($raw) > 0):
				$arResult['SET']['TYPE'] = CCatalogProductSet::TYPE_SET;
			elseif($raw = array_values(CCatalogProductSet::getAllSetsByProduct($arResult['ID'], CCatalogProductSet::TYPE_GROUP))):
				$arResult['SET']['TYPE'] = CCatalogProductSet::TYPE_GROUP;
			endif;

			if(count($raw[0]['ITEMS']) > 0):
				$arResult['SET']['SHOW']   = array();
				$arResult['SET']['ITEMS']  = array();
				$arResult['SET']['IMAGES'] = array();
				foreach ($raw[0]['ITEMS'] as $item) {
					$arResult['SET']['ITEMS'][$item['ITEM_ID']] = $item;
				}
				$res = CIBlockElement::GetList(Array(), array('ID' => array_keys($arResult['SET']['ITEMS'])), false, false, Array("ID", "PREVIEW_PICTURE", "PROPERTY_ARTNUMBER", "IBLOCK_CODE"));

				while ($arItem = $res->Fetch()) {
					$arResult['SET']['SHOW'][] = $arItem['ID'];
					$arResult['SET']['ITEMS'][$arItem['ID']] = array_merge($arResult['SET']['ITEMS'][$arItem['ID']], $arItem);
					$arResult['SET']['IMAGES'][] = $arItem['PREVIEW_PICTURE'];
				}
				$diff = array_diff(array_keys($arResult['SET']['ITEMS']), $arResult['SET']['SHOW']);
				if(count($diff) > 0):
					$res = CIBlockElement::GetList(Array(), array('IBLOCK_CODE'=>'offers', '=ID' => $diff), false, false, Array("ID", "CODE", "PROPERTY_ARTNUMBER", "PROPERTY_SIZE", "PROPERTY_CML2_LINK", "PROPERTY_CML2_LINK.PREVIEW_PICTURE"));
					while ($arItem = $res->Fetch()) {
						$item = &$arResult['SET']['ITEMS'][$arItem['ID']];
						$item = array_merge($item, $arItem);
						$item['PROPERTY_SIZE_VALUE'] = $arResult['SIZES'][$item['PROPERTY_SIZE_VALUE']];
						$arResult['SET']['IMAGES'][] = $arItem['PROPERTY_CML2_LINK_PREVIEW_PICTURE'];
						$arResult['SET']['SHOW'][]   = $arItem['PROPERTY_CML2_LINK_VALUE'];
					}
				endif;
				if($arResult['SET']['TYPE'] == CCatalogProductSet::TYPE_GROUP):
					$data = array();
					foreach ($arResult['SET']['ITEMS'] as $val) {
						$tmp = array('id' => $val['ITEM_ID'], 'artnumber'=> $val['PROPERTY_ARTNUMBER_VALUE'], 'quantity'=>$val['QUANTITY']);
						if(strlen($val['PROPERTY_SIZE_VALUE']) > 0)
							$tmp['size'] = $val['PROPERTY_SIZE_VALUE'];
						$data[] = $tmp;
					}
					$arResult['BUY_DATA'] = $data;
				endif;
				if(count($arResult['SET']['SHOW']) > 0):
					$setFilter = array('=ID'=>$arResult['SET']['SHOW']);
				endif;
			else:
				$arResult['SET'] = false;
			endif;
		else:
			$arIDS = array($arResult['ID']);
			foreach ($arResult['OFFERS'] as $key => $offer) {
				$arIDS[] = $offer['ID'];
			}
			$rsSets = CCatalogProductSet::getList(
		      array(),
		      array(
		        '@ITEM_ID' => $arIDS,
		        //'=SET_ID' => 0
		      ),
		      false,
		      false
		    );
			while($arItem = $rsSets->Fetch())
			{
				if(!$arResult['SET']) $arResult['SET'] = array('IN_SET' => true, 'ITEMS' => array(), 'SETS'=> array(), 'URL'=>"#", 'SHOW_BADGE'=>true);

				if(!isset($arResult['SET']['SETS'][$arItem['ITEM_ID']])) $arResult['SET']['SETS'][$arItem['ITEM_ID']] = array();
				$arResult['SET']['SETS'][$arItem['ITEM_ID']][] = intval($arItem['OWNER_ID']);
				$arResult['SET']['ITEMS'][$arItem['OWNER_ID']] = array('ID' => $arItem['OWNER_ID'], 'TYPE'=>$arItem['TYPE'], 'ITEM_ID' => $arItem['ITEM_ID']);
			}
			foreach (array($arResult['ID'], $arResult['OFFERS'][0]['ID']) as $value) {
				$set = $arResult['SET']['ITEMS'][$arResult['SET']['SETS'][$value][0]];
				if(isset($set))
					$arResult['SET']['TYPE'] = $set['TYPE'];
			}

			// Получение URL каждого комплекта
			if(count($arResult['SET']['ITEMS']) > 0):
				$res = CIBlockElement::GetList(Array(), array('ID' => array_keys($arResult['SET']['ITEMS'])), false, false, array("DETAIL_PAGE_URL"));
				while ($arItem = $res->GetNext()) {
					$arResult['SET']['ITEMS'][$arItem['ID']]['URL'] = $arItem["DETAIL_PAGE_URL"];
				}
			endif;

			// Сбор данный для JS
			foreach ($arResult['SET']['SETS'] as $key => $value) {
				$type = CCatalogProductSet::TYPE_GROUP;
				foreach ($value as $k)
					if($arResult['SET']['ITEMS'][$k]['TYPE'] == CCatalogProductSet::TYPE_SET)
						$type = CCatalogProductSet::TYPE_SET;
				$arResult['SET']['JSON'][$key] = array('url'=>count($value) == 1 ? $arResult['SET']['ITEMS'][$value[0]]['URL']:"/catalog/?q=+&id=".json_encode(array_keys($arResult['SET']['ITEMS'])),'type'=>$type);
			}

			// Стартовая URL
			if($arResult['SET']['JSON'][$arResult['ID']]['url'])
				$arResult['SET']['URL'] = $arResult['SET']['JSON'][$arResult['ID']]['url'];
			if(count($arResult['OFFERS']) == 1 && strlen($arResult['SET']['JSON'][$arResult['OFFERS'][0]['ID']]['url']) > 0)
				$arResult['SET']['URL'] = $arResult['SET']['JSON'][$arResult['OFFERS'][0]['ID']]['url'];

			if(!isset($arResult['SET']['SETS'][$arResult['ID']]) && !isset($arResult['SET']['SETS'][$arResult['OFFERS'][0]['ID']]) && $arResult['SET']){
				$arResult['SET']['SHOW_BADGE'] = false;
			}

		endif;
	endif;

	if(intval($arResult['MIN_PRICE']['VALUE']) == 0 || (isset($CITY['CLOSED']) && $arResult['PROPERTIES']['GENERAL']['VALUE'] != 'Y') || (SITE_ID == 's2' && $arResult['PROPERTIES']['GENERAL']['VALUE'] != 'Y'))
		$arResult['NOT_AVAILABLE'] = true;
	else
		$arResult['NOT_AVAILABLE'] = false;

	if($arResult['NOT_AVAILABLE'] && SITE_ID == 's2' && $arResult['PROPERTIES']['COMING']['VALUE'] == 'Y') $arResult['NOT_AVAILABLE'] = false;

	$raw = CIBlockElement::GetElementGroups($arResult['ID']);
	while($data = $raw->GetNext()) {
		if($data['CODE'] == 'sale30')
			$arResult['TOOLTIP'] = $data['DESCRIPTION'];

		if(!in_array($data['CODE'], array('all', 'sale', 'sales', 'sale30', 'new', 'best-sellers', 'coming', 'latest'))) {
			$arResult['IBLOCK_SECTION_ID'] = $data['ID'];
		}
	}

	$rsPath = GetIBlockSectionPath($arResult['IBLOCK_ID'], $arResult['IBLOCK_SECTION_ID']);
	while($arPath = $rsPath->GetNext())
		$arResult['SECTIONS'][] = $arPath;

	$arResult['IMAGES']    = array();
	if(!$arResult['PROPERTIES']['PICTURES']['VALUE'])
		$arResult['PROPERTIES']['PICTURES']['VALUE'] = array();

	$arImages = array_merge($arResult['PROPERTIES']['PICTURES']['VALUE'],(count($arResult['SET']['IMAGES'])>0?$arResult['SET']['IMAGES']:array()));

	$raw = CFile::GetList(array(),
		array(
			'@ID'=> implode($arImages,','))
	);

	while($img = $raw->Fetch()):
		$small = CFile::ResizeImageGet(CFile::GetFileArray($img['ID']), Array("width" => 200, "height" => 200), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), false, 75);
		$middle = CFile::ResizeImageGet(CFile::GetFileArray($img['ID']), Array("width" => 800, "height" => 800), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), false, 75);
		$arResult['IMAGES'][] = array('ID'=>$img['ID'], 'small'=>$small['src'], 'middle'=>$middle['src'], 'src'=>"/upload/".$img['SUBDIR']."/".$img['FILE_NAME'], 'h'=>$img['HEIGHT'], 'w'=>$img['WIDTH']);
	endwhile;

	function sortImages(){
		global $arImages;
		$a = array_search($a['ID'], $arImages);
		$b = array_search($b['ID'], $arImages);
		if ($a == $b) {
	        return 0;
	    }
	    return ($a < $b) ? -1 : 1;
	}
	if(SITE_ID == 's2' && count($arResult['SET']['IMAGES']) > 0) usort($arResult['IMAGES'], 'sortImages');

	$small = CFile::ResizeImageGet(CFile::GetFileArray($arResult['PREVIEW_PICTURE']['ID']), Array("width" => 800, "height" => 800), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), false, 75);
	$arResult['PREVIEW_PICTURE']['SMALL'] = $small['src'];

	$APPLICATION->SetPageProperty('description', strip_tags($arResult["PREVIEW_TEXT"]));

	if(isset($arResult['PREVIEW_PICTURE']['SRC'])):
		$wFilter = array(
			array("name" => "watermark", "fill" => "resize", "coefficient" => 1, "position" => "center", "width"=>630, "height"=>630, "alpha_level" => 100, "file"=>$_SERVER['DOCUMENT_ROOT']."/layout/images/watermark.png")
		);
		$watermark = CFile::ResizeImageGet(CFile::GetFileArray($arResult['PREVIEW_PICTURE']['ID']), Array("width" => 1200, "height" => 630), BX_RESIZE_IMAGE_PROPORTIONAL, false, $wFilter, false, 100);
		$arResult['WATERMARK'] = $watermark['src'];
		$watermark['src'] = '/include/crop.php?img='.$watermark['src'];
		if (isset($_REQUEST['buyme'])):


			$this->SetViewTarget('header');
				?>

				<link rel="image_src" href="http://<?=$_SERVER['SERVER_NAME']. $watermark['src']?>" />
				<meta content="http://<?=$_SERVER['SERVER_NAME']. $watermark['src']?>&vk=1" property="og:image">
				<meta property="og:title" content="<?=$arResult['NAME']?>"/>
				<meta property="og:type" content="blog"/>
				<meta property="og:description" content="Это будет лучшим подарком для меня!"/>

				<meta name="twitter:card" content="summary" />
				<meta name="twitter:site" content="@fmarconi_ru" />
				<meta name="twitter:title" content="<?=$arResult['NAME']?>">
				<meta name="twitter:description" content="Это будет лучшим подарком для меня!">
				<meta name="twitter:image" content="http://<?=$_SERVER['SERVER_NAME']. $watermark['src']?>">
				<?
			$this->EndViewTarget();
		else:
			$this->SetViewTarget('header');
				?>

				<link rel="image_src" href="http://<?=$_SERVER['SERVER_NAME']. $arResult['PREVIEW_PICTURE']['SRC']?>" />
				<meta content="http://<?=$_SERVER['SERVER_NAME']. $arResult['PREVIEW_PICTURE']['SRC']?>" property="og:image">
				<meta property="og:title" content="<?=$arResult['NAME']?>"/>
				<meta property="og:type" content="blog"/>
				<meta property="og:description" content="<?=(strlen(strip_tags($arResult["PREVIEW_TEXT"]))>0?strip_tags($arResult["PREVIEW_TEXT"]):"Эту модель и еще очень много интересного вы найдете на сайте fmarconi.ru")?>"/>

				<meta name="twitter:card" content="summary" />
				<meta name="twitter:site" content="@fmarconi_ru" />
				<meta name="twitter:title" content="<?=$arResult['NAME']?>">
				<meta name="twitter:description" content="<?=(strlen(strip_tags($arResult["PREVIEW_TEXT"]))>0?strip_tags($arResult["PREVIEW_TEXT"]):"Эту модель и еще очень много интересного вы найдете на сайте fmarconi.ru")?>">
				<meta name="twitter:image" content="http://<?=$_SERVER['SERVER_NAME']. $arResult['PREVIEW_PICTURE']['SRC']?>">
				<?
			$this->EndViewTarget();
		endif;
	endif;

	if(CModule::IncludeModule("sale")):
		$arBasketItems = array();
		$dbBasketItems = CSaleBasket::GetList(array("NAME" => "ASC", "ID" => "ASC"), array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"), false, false, array("ID", "PRODUCT_ID"));

		while ($arItems = $dbBasketItems->Fetch())
			$arBasketItems[] = $arItems['PRODUCT_ID'];

		$arResult['inCart'] = false;
		if(in_array($item['ID'],$arBasketItems))
			$arResult['inCart'] = true;
		foreach ($item['OFFERS'] as $offer)
			if(in_array($offer['ID'], $arBasketItems))
				$arResult['inCart'] = true;
	endif;

	if(count($arResult['OFFERS']) > 0):

		$offers = array();
		foreach($arResult['OFFERS'] as $offer) $offers[$offer['ID']] = array_merge($offer, array('COUNTS' => false));
		$raw = CCatalogStoreProduct::GetList(array('ID'=>'ASC'), array('ACTIVE' => 'Y', 'PRODUCT_ID'=>array_keys($offers)));
		while ($count = $raw->Fetch()):
			if (intval($count['AMOUNT']) > 0) {
				$offers[$count['PRODUCT_ID']]['COUNTS'] = true;
				if ($count['STORE_ID'] == 1) $offers[$count['PRODUCT_ID']]['OPT'] = true;
			}
		endwhile;
		if ($arResult['PROPERTIES']['COMING']['VALUE'] != 'Y') {
			foreach($offers as $offer)
				if(!$offer['COUNTS'])
					unset($offers[$offer['ID']]);
		}
		$arResult['OFFERS'] = $offers;

	endif;
?>
