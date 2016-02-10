<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
	use Bitrix\Main\Localization\Loc;

	Loc::loadMessages(__FILE__);

	// we dont trust input params, so validation is required
	$legalColors = array(
		'green' => true,
		'yellow' => true,
		'red' => true,
		'gray' => true
	);
	// default colors in case parameters unset
	$defaultColors = array(
		'N' => 'green',
		'P' => 'yellow',
		'F' => 'gray',
		'PSEUDO_CANCELLED' => 'red'
	);

	foreach ($arParams as $key => $val)
		if(strpos($key, "STATUS_COLOR_") !== false && !$legalColors[$val])
			unset($arParams[$key]);

	// to make orders follow in right status order
	if(is_array($arResult['INFO']) && !empty($arResult['INFO']))
	{
		foreach($arResult['INFO']['STATUS'] as $id => $stat)
		{
			$arResult['INFO']['STATUS'][$id]["COLOR"] = $arParams['STATUS_COLOR_'.$id] ? $arParams['STATUS_COLOR_'.$id] : (isset($defaultColors[$id]) ? $defaultColors[$id] : 'gray');
			$arResult["ORDER_BY_STATUS"][$id] = array();
		}
	}
	$arResult["ORDER_BY_STATUS"]["PSEUDO_CANCELLED"] = array();

	$arResult["INFO"]["STATUS"]["PSEUDO_CANCELLED"] = array(
		"NAME" => Loc::getMessage('SPOL_PSEUDO_CANCELLED'),
		"COLOR" => $arParams['STATUS_COLOR_PSEUDO_CANCELLED'] ? $arParams['STATUS_COLOR_PSEUDO_CANCELLED'] : (isset($defaultColors['PSEUDO_CANCELLED']) ? $defaultColors['PSEUDO_CANCELLED'] : 'gray')
	);
	$arResult['KEYS']  = array();
	$arResult['ITEMS'] = array();
	$arItems = array();
	foreach($arResult["ORDERS"] as $k => $order)
		foreach ($order["BASKET_ITEMS"] as $item)
			$arItems[] = $item['PRODUCT_ID'];

	$arSelect = Array("ID", "PROPERTY_CML2_LINK");
	$arFilter = Array("ID" => $arItems );
	$res      = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
	while($item = $res->Fetch()):
		$el = &$arResult['KEYS'][$item["PROPERTY_CML2_LINK_VALUE"]];
		if (!isset($el)):
			$el = $item['ID'];
		else:
			if (!is_array($el)) $el = array($el, $item['ID']);
			else $el[] = $item['ID'];
		endif;
		$key = array_search($item['ID'], $arItems);
		unset($arItems[$key]);
		if(!in_array($item["PROPERTY_CML2_LINK_VALUE"], $arItems)) $arItems[] = $item["PROPERTY_CML2_LINK_VALUE"];
	endwhile;

	$arSelect = Array("ID", "PREVIEW_PICTURE", "CODE", "PROPERTY_ARTNUMBER");
	$arFilter = Array("ID" => $arItems );
	$res      = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
	while($item = $res->Fetch()):
		if(isset($arResult['KEYS'][$item['ID']]))
			$key = $arResult['KEYS'][$item['ID']];
		else
			$key = $item['ID'];
		$small = CFile::ResizeImageGet(CFile::GetFileArray($item['PREVIEW_PICTURE']), Array("width" => 400, "height" => 400), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), false, 75);
		$item['PREVIEW_PICTURE'] = $small['src'];

		$raw = CIBlockElement::GetElementGroups($item['ID'], false, array('ID', 'CODE'));
		while($data = $raw->GetNext())
			if(!in_array($data['CODE'], array('all', 'sale')))
				$item['IBLOCK_SECTION_ID'] = $data['ID'];

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
		$arResult['ITEMS'][$key] = $item;
	endwhile;

	if(is_array($arResult["ORDERS"]) && !empty($arResult["ORDERS"]))
	{
		foreach ($arResult["ORDERS"] as $order)
		{
			$order['HAS_DELIVERY'] = intval($order["ORDER"]["DELIVERY_ID"]) || strpos($order["ORDER"]["DELIVERY_ID"], ":") !== false;

			$stat = $order['ORDER']['CANCELED'] == 'Y' ? 'PSEUDO_CANCELLED' : $order["ORDER"]["STATUS_ID"];
			$color = $arParams['STATUS_COLOR_'.$stat];
			$order['STATUS_COLOR_CLASS'] = empty($color) ? 'gray' : $color;

			$arResult["ORDER_BY_STATUS"][$stat][] = $order;
		}
	}
?>
