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
	$raw = CFile::GetList(array(), array('@ID'=>implode($arResult['PROPERTIES']['PICTURES']['VALUE'],',')));
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
