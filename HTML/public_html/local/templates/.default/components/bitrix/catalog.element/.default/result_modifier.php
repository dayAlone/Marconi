<?
	global $CITY;
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
			else:
				$arResult['SET'] = false;
			endif;
		else:
			if(CCatalogProductSet::isProductInSet($arResult['ID'])):
				$arResult['SET'] = array('IN_SET' => true, 'ITEMS' => array());

				$raw = CCatalogProductSet::getList(array('ID'=>'DESC'), array('ITEM_ID' => $arResult['ID']));
				while($set = $raw->Fetch()):
					$arResult['SET']['ITEMS'][$set['OWNER_ID']] = array('ID' => $set['OWNER_ID'], 'TYPE'=>$set['TYPE']);
				endwhile;

				foreach ($arResult['SET']['ITEMS'] as $set)
					if($set['TYPE'] == CCatalogProductSet::TYPE_SET)
						$arResult['SET']['TYPE'] = $arResult['SET'];

				if(!isset($arResult['SET']['TYPE']))
					$arResult['SET']['TYPE'] = CCatalogProductSet::TYPE_GROUP;

				$res = CIBlockElement::GetList(Array(), array('ID' => array_keys($arResult['SET']['ITEMS'])), false, false, array("DETAIL_PAGE_URL"));
				while ($arItem = $res->GetNext()) {
					$arResult['SET']['ITEMS'][$arItem['ID']]['URL'] = $arItem["DETAIL_PAGE_URL"];
				}
			endif;
		endif;
	endif;

	if(intval($arResult['MIN_PRICE']['VALUE']) == 0 || (isset($CITY['CLOSED']) && $arResult['PROPERTIES']['GENERAL']['VALUE'] != 'Y'))
		$arResult['NOT_AVAILABLE'] = true;
	else
		$arResult['NOT_AVAILABLE'] = false;

	$raw = CIBlockElement::GetElementGroups($arResult['ID']);
	while($data = $raw->GetNext()) {
		if($data['CODE'] == 'sale30')
			$arResult['TOOLTIP'] = $data['DESCRIPTION'];

		if(!in_array($data['CODE'], array('all', 'sale', 'sale30', 'new', 'best-sellers', 'coming', 'latest'))) {
			$arResult['IBLOCK_SECTION_ID'] = $data['ID'];
		}
	}

	$rsPath = GetIBlockSectionPath($arResult['IBLOCK_ID'], $arResult['IBLOCK_SECTION_ID']);
	while($arPath = $rsPath->GetNext())
		$arResult['SECTIONS'][] = $arPath;

	$arResult['IMAGES']    = array();
	if(!$arResult['PROPERTIES']['PICTURES']['VALUE'])
		$arResult['PROPERTIES']['PICTURES']['VALUE'] = array();
	$raw = CFile::GetList(array(),
		array(
			'@ID'=> implode(
						array_merge(
							$arResult['PROPERTIES']['PICTURES']['VALUE'],
							(count($arResult['SET']['IMAGES'])>0?$arResult['SET']['IMAGES']:array())
							),','))
		);
	while($img = $raw->Fetch()):
		$small = CFile::ResizeImageGet(CFile::GetFileArray($img['ID']), Array("width" => 200, "height" => 200), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), false, 75);
		$middle = CFile::ResizeImageGet(CFile::GetFileArray($img['ID']), Array("width" => 800, "height" => 800), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), false, 75);
		$arResult['IMAGES'][] = array('small'=>$small['src'], 'middle'=>$middle['src'], 'src'=>"/upload/".$img['SUBDIR']."/".$img['FILE_NAME'], 'h'=>$img['HEIGHT'], 'w'=>$img['WIDTH']);
	endwhile;

	$small = CFile::ResizeImageGet(CFile::GetFileArray($arResult['PREVIEW_PICTURE']['ID']), Array("width" => 800, "height" => 800), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), false, 75);
	$arResult['PREVIEW_PICTURE']['SMALL'] = $small['src'];

	$APPLICATION->SetPageProperty('description', strip_tags($arResult["PREVIEW_TEXT"]));

	if(isset($arResult['PREVIEW_PICTURE']['SRC'])):
		$this->SetViewTarget('header');
			?>
			<link rel="image_src" href="http://<?=$_SERVER['SERVER_NAME']. $arResult['PREVIEW_PICTURE']['SRC']?>" />
			<meta content="http://<?=$_SERVER['SERVER_NAME']. $arResult['PREVIEW_PICTURE']['SRC']?>" property="og:image">
			<meta property="og:title" content="<?=$arResult['NAME']?>"/>
			<meta property="og:type" content="blog"/>
			<meta property="og:description" content="<?=(strlen(strip_tags($arResult["PREVIEW_TEXT"]))>0?strip_tags($arResult["PREVIEW_TEXT"]):"")?>"/>
			<?
		$this->EndViewTarget();
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
?>
