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
	$arResult['IN_SET']     = false;
	$arResult['SET']        = false;
	if(SITE_ID == 's2'):
		$arResult['SET']        = CCatalogProductSet::isProductHaveSet($arResult['ID']);
		$arResult['SET_IMAGES'] = array();
		
		if($arResult['SET']):
			$arSetItems = array_values(CCatalogProductSet::getAllSetsByProduct($arResult['ID'], CCatalogProductSet::TYPE_SET))[0];
			$arResult['SET_IDs'] = array();
			$arResult['SET_ITEMS'] = array();
			foreach ($arSetItems['ITEMS'] as $item) {
				$arResult['SET_IDs'][] = $item['ITEM_ID'];
			}
			$arSelect = Array("ID", "PREVIEW_PICTURE");
			$arFilter = Array('CHECK_PERMISSIONS' => 'N', 'ID' => $arResult['SET_IDs']);
			$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
			while ($arItem = $res->Fetch()) {
				$arResult['SET_ITEMS'][] = $arItem;
				$arResult['SET_IMAGES'][] = $arItem['PREVIEW_PICTURE'];
			}
			global $setFilter;
			$setFilter = array('=ID' => $arResult['SET_IDs']);
		else:
			$arResult['IN_SET'] = CCatalogProductSet::isProductInSet($arResult['ID']);
			if($arResult['IN_SET']):
				$arResult['SETS'] = array();
				$raw = CCatalogProductSet::getList(array('ID'=>'DESC'), array('ITEM_ID' => $arResult['ID']));
				while($set = $raw->Fetch()):
					$arResult['SETS'][$set['OWNER_ID']] = array('ID' => $set['OWNER_ID']);
				endwhile;
				$arSelect = Array("ID", "DETAIL_PAGE_URL");
				$arFilter = Array("IBLOCK_ID" => $id, 'CHECK_PERMISSIONS' => 'N', 'ID' => array_keys($arResult['SETS']));
				$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
				while ($arItem = $res->GetNext()) {
					$arResult['SETS'][$arItem['ID']]['URL'] = $arItem["DETAIL_PAGE_URL"];
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
							$arResult['SET_IMAGES']
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
