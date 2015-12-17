<?
$arResult['SIZES']      = getHighloadElements('sizes', 'UF_XML_ID', 'UF_NAME');
$arResult['BRANDS']     = getHighloadElements('brands', 'UF_XML_ID', 'UF_NAME');
$arResult['TRADELINES'] = getHighloadElements('tradeline', 'UF_XML_ID', 'UF_NAME');
$arResult['SETS']       = array('LOCKED'=>array(), 'LOCKED_OFFERS'=>array(), 'PRODUCTS'=>array());

$arParams['SHOW_PRICE'] = false;

if(($arParams['HIDE_PRICE'] == "Y" && isUserAccept()) || $arParams['HIDE_PRICE'] != "Y")
	$arParams['SHOW_PRICE'] = true;

$images   = array();
$sections = array();
$paths    = array();
$arIDS    = array();
$arOffers = array();

$arResult['IMAGES']   = array();
$arResult['SECTIONS'] = array();

foreach ($arResult['ITEMS'] as $key => &$item):
	if(count($item['PROPERTIES']) == 0) {
		unset($arResult['ITEMS'][$key]);
		continue;
	}

	$arIDS[$item['ID']] = $key;
	$brand = $arResult['BRANDS'][$item['PROPERTIES']['BRAND']['VALUE']];

	/*
	if (SITE_ID == 's2' && $USER->IsAuthorized()):
		$offers = array();
		foreach($item['OFFERS'] as $offer) $offers[$offer['ID']] = $offer;
		$raw = CCatalogStoreProduct::GetList(array('ID'=>'ASC'), array('ACTIVE' => 'Y', 'PRODUCT_ID'=>array_keys($offers)));
		$item['OFFERS'] = array();
		while ($count = $raw->Fetch()):
			if (intval($count['AMOUNT']) > 0) {
				$item['OFFERS'][] = $offers[$count['PRODUCT_ID']];
			}
		endwhile;

	endif;
	*/
	foreach($item['OFFERS'] as $k => $offer) {
		$arOffers[$offer['ID']] = $k;
	}

	$raw = CIBlockElement::GetElementGroups($item['ID'], false, array('ID', 'CODE'));
	while($data = $raw->GetNext()):
		if(SITE_ID == 's1' && !in_array($data['CODE'], array('all', 'sale', 'sale30', 'best-sellers', 'new', 'coming', 'latest')))
			$item['IBLOCK_SECTION_ID'] = $data['ID'];
		if(SITE_ID == 's2' && $data['CODE'] == 'all')
			$item['IBLOCK_SECTION_ID'] = $data['ID'];
	endwhile;

	if(!isset($paths[$item['IBLOCK_SECTION_ID']])):
		$rsPath = GetIBlockSectionPath($arResult['ID'], $item['IBLOCK_SECTION_ID']);
		$arPath = $rsPath->GetNext();
		$paths[$item['IBLOCK_SECTION_ID']] = $arPath;
	else:
		$arPath = $paths[$item['IBLOCK_SECTION_ID']];
	endif;

	if($arPath):
		$arResult['SECTIONS'][$item['IBLOCK_SECTION_ID']] = $arPath['CODE'];
		$item['DETAIL_PAGE_URL'] = "/catalog/".$arPath['CODE']."/".$item['CODE']."/";
	endif;

	if(count($item['PROPERTIES']['PICTURES']['VALUE'])>0)
		foreach ($item['PROPERTIES']['PICTURES']['VALUE'] as $img)
			$images[] = $img;

	$small = CFile::ResizeImageGet(CFile::GetFileArray($item['PREVIEW_PICTURE']['ID']), Array("width" => 400, "height" => 400), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), false, 75);
	$item['PREVIEW_PICTURE']['SRC'] = $small['src'];

	// Цена
	if(intval($item['MIN_PRICE']['VALUE']) > 0):
		if(SITE_ID == 's1'):
			$item['PRICE'] = $item['MIN_PRICE']['VALUE'];
		else:
			$item['PRICE'] = $item['MIN_PRICE']['DISCOUNT_VALUE'];
		endif;
	endif;

endforeach;

if(SITE_ID == 's2'):



	$rsSets = CCatalogProductSet::getList(
		array('SET_ID' => 'DESC'),
		array(
			array(
			   "LOGIC" => "OR",
				'@PRODUCT_ID' => array_merge(array_keys($arIDS),array_keys($arOffers)),
				'@OWNER_ID' => array_merge(array_keys($arIDS),array_keys($arOffers)),
			)
			//'=SET_ID' => 0
		),
		false,
		false,
		array('ID', 'OWNER_ID', 'ITEM_ID', 'TYPE', 'QUANTITY')
	);
	$arElements = array();
	while ($arSet = $rsSets->Fetch())
	{
		if(!isset($arResult['SETS'][$arSet['OWNER_ID']]))
			$arResult['SETS'][$arSet['OWNER_ID']] = array('TYPE' => $arSet['TYPE'], 'ITEMS'=>array(), 'IMAGES'=>array());
		$set = &$arResult['SETS'][$arSet['OWNER_ID']];

		if($arSet['OWNER_ID'] != $arSet['ITEM_ID']) {
			$arElements[$arSet['ITEM_ID']] = $arSet['OWNER_ID'];
			$set['ITEMS'][$arSet['ITEM_ID']] = $arSet;
			if($arSet['TYPE'] == CCatalogProductSet::TYPE_SET)
				$arResult['SETS']['LOCKED'][] = $arSet['ITEM_ID'];
		}
	}

	if(count($arElements) > 0):
		$res = CIBlockElement::GetList(Array(), array('ID' => array_keys($arElements)), false, false, Array("ID", "PROPERTY_ARTNUMBER", "PREVIEW_PICTURE", "IBLOCK_CODE"));
		while ($arItem = $res->Fetch()) {
			$set = &$arResult['SETS'][$arElements[$arItem['ID']]];
			$arResult['SETS']['PRODUCTS'][$arItem['ID']] = $arElements[$arItem['ID']];
			$item = &$set['ITEMS'][$arItem['ID']];
			$item = array_merge($item, $arItem);
			if(intval($arItem["PREVIEW_PICTURE"]) > 0):
				$set['IMAGES'][] = $arItem["PREVIEW_PICTURE"];
				$images[] = $arItem["PREVIEW_PICTURE"];
			endif;
		}
	endif;

	$diff = array_diff(array_keys($arElements), array_keys($arResult['SETS']['PRODUCTS']));
	if(count($diff) > 0):
		$res = CIBlockElement::GetList(Array(), array('IBLOCK_CODE'=>'offers', '=ID' => $diff), false, false, Array("ID", "CODE", "PROPERTY_ARTNUMBER", "PROPERTY_SIZE", "PROPERTY_CML2_LINK", "PROPERTY_CML2_LINK.PREVIEW_PICTURE"));
		while ($arItem = $res->Fetch()) {
			$set = &$arResult['SETS'][$arElements[$arItem['ID']]];

			if($set['TYPE'] == CCatalogProductSet::TYPE_SET):
				if(count($arResult['ITEMS'][$arIDS[$arItem['PROPERTY_CML2_LINK_VALUE']]]['OFFERS']) == 1):
					if(!in_array($arItem['PROPERTY_CML2_LINK_VALUE'], $arResult['SETS']['LOCKED'])):
						$arResult['SETS']['LOCKED'][] = $arItem['PROPERTY_CML2_LINK_VALUE'];
					endif;
				else:
					if(!in_array($arItem['ID'], $arResult['SETS']['LOCKED_OFFERS'])):
						$arResult['SETS']['LOCKED_OFFERS'][] = $arItem['ID'];
					endif;
				endif;
			endif;

			$item = &$set['ITEMS'][$arItem['ID']];
			$item = array_merge($item, $arItem);
			$item['PROPERTY_SIZE_VALUE'] = $arResult['SIZES'][$item['PROPERTY_SIZE_VALUE']];
			if(intval($arItem["PREVIEW_PICTURE"]) > 0):
				$set['IMAGES'][] = $arItem["PREVIEW_PICTURE"];
				$images[] = $arItem["PREVIEW_PICTURE"];
			endif;
		}
	endif;
endif;


$raw = CFile::GetList(array(), array('@ID'=>implode($images,',')));
while($img = $raw->Fetch()):
	$arResult['IMAGES'][$img['ID']] = array('src'=>"/upload/".$img['SUBDIR']."/".$img['FILE_NAME'], 'h'=>$img['HEIGHT'], 'w'=>$img['WIDTH']);
endwhile;

foreach ($arResult['ITEMS'] as &$item) {
	foreach ($item['PROPERTIES']['PICTURES']['VALUE'] as &$img) {
		$img = array_merge($arResult['IMAGES'][$img], array('title'=>$item['NAME']));
	}
	if(count($arResult['SETS'][$item['ID']]['IMAGES']) > 0):
		foreach ($arResult['SETS'][$item['ID']]['IMAGES'] as &$img) {
			$item['PROPERTIES']['PICTURES']['VALUE'][] = array_merge($arResult['IMAGES'][$img], array('title'=>$item['NAME']));
		}
	endif;
}

?>
