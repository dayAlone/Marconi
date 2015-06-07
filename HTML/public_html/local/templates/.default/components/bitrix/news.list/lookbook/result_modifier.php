<?
require($_SERVER['DOCUMENT_ROOT'].'/include/phpQuery.php');
$IDs = array();
foreach ($arResult['ITEMS'] as &$item):
	if(count(array_filter($item['PROPERTIES']['PRODUCTS']['VALUE']))>0):
		if(count($IDs)>0) $IDs = array_merge($item['PROPERTIES']['PRODUCTS']['VALUE'], $IDs);
		else $IDs = $item['PROPERTIES']['PRODUCTS']['VALUE'];
	endif;
	if(isset($item['PREVIEW_PICTURE']['ID'])):
		$small = CFile::ResizeImageGet(CFile::GetFileArray($item['PREVIEW_PICTURE']['ID']), Array("width" => 570, "height" => 570), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), false, 75);
		$medium = CFile::ResizeImageGet(CFile::GetFileArray($item['PREVIEW_PICTURE']['ID']), Array("width" => 1140, "height" => 1140), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), false, 75);
		$item['PREVIEW_PICTURE']['SRC'] = $medium['src'];
		$item['PREVIEW_PICTURE']['SMALL'] = $small['src'];
	endif;
	endforeach;
if(count($IDs)>0):
	ob_start();
		global $APPLICATION;
		global $arrFilter;
	    $arrFilter = array('=ID' => $IDs);
	    $APPLICATION->IncludeComponent(
	    	"bitrix:catalog.section", 
	    	".default", 
	    	array(
			"HIDE_PRICE"          => (SITE_ID == 's1'?'N':'Y'),
			"HIDE_SIMMILAR"       => (SITE_ID == 's1'?'N':'Y'),
			"HIDE_MORE"           => (SITE_ID == 's1'?'N':'Y'),
			"SHOW_COUNT"          => (SITE_ID == 's1'?'N':'Y'),
			
			"IBLOCK_TYPE"         => "catalog",
			'HIDE_TOOLBAR'        => "Y",
			"IBLOCK_ID"           => "1",
			"ELEMENT_SORT_FIELD"  => "sort",
			"ELEMENT_SORT_ORDER"  => "asc",
			"ELEMENT_SORT_FIELD2" => "id",
			"ELEMENT_SORT_ORDER2" => "desc",
	        "BY_LINK" => "Y",
	    		"PROPERTY_CODE" => array(
	    			0 => "BRAND",
	    			1 => "PICTURES",
	    			2 => "",
	    		),
	        "FILTER_NAME"        => "arrFilter",
	        "CACHE_TYPE"         => "A",
	        "CACHE_TIME"         => "360000",
	        "CACHE_FILTER"       => "Y",
	        "CACHE_GROUPS"       => "Y",
	        "SET_TITLE"          => "N",
	        "SET_STATUS_404"     => "N",
	        "PAGE_ELEMENT_COUNT" => "900000",
	    		"PRICE_CODE" => array(
	    			0 => "RETAIL",
	    		),
	    		"USE_PRICE_COUNT" => "N",
	    		"SHOW_PRICE_COUNT" => "1",
	    		"PRICE_VAT_INCLUDE" => "N",
	    		"USE_PRODUCT_QUANTITY" => "N",
	    		"ADD_PROPERTIES_TO_BASKET" => "N",
	    		"PARTIAL_PRODUCT_PROPERTIES" => "N",
	    		"PRODUCT_PROPERTIES" => array(
	    		),
	    		"DISPLAY_TOP_PAGER" => "N",
	    		"DISPLAY_BOTTOM_PAGER" => "N",
	    		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
	    		"PAGER_SHOW_ALWAYS" => "N",
	    		"PAGER_TEMPLATE" => ".default",
	    		"PAGER_DESC_NUMBERING" => "N",
	    		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
	    		"PAGER_SHOW_ALL" => "N",
	    		"OFFERS_CART_PROPERTIES" => array(
	    		),
	    		"OFFERS_FIELD_CODE" => array(
	    			0 => "",
	    			1 => "",
	    		),
	    		"OFFERS_PROPERTY_CODE" => array(
	    			0 => "SIZE",
	    			1 => "",
	    		),
	    		"OFFERS_SORT_FIELD" => "id",
	    		"OFFERS_SORT_ORDER" => "asc",
	    		"OFFERS_SORT_FIELD2" => "sort",
	    		"OFFERS_SORT_ORDER2" => "asc",
	    		"OFFERS_LIMIT" => "0",
	    		"CONVERT_CURRENCY" => "N",
	    		"CURRENCY_ID" => $arParams["CURRENCY_ID"],
	    		"HIDE_NOT_AVAILABLE" => "N",
	    		"LABEL_PROP" => $arParams["LABEL_PROP"],
	    		"ADD_PICT_PROP" => $arParams["ADD_PICT_PROP"],
	    		"PRODUCT_DISPLAY_MODE" => $arParams["PRODUCT_DISPLAY_MODE"],
	    		"OFFER_ADD_PICT_PROP" => $arParams["OFFER_ADD_PICT_PROP"],
	    		"OFFER_TREE_PROPS" => $arParams["OFFER_TREE_PROPS"],
	    		"PRODUCT_SUBSCRIPTION" => $arParams["PRODUCT_SUBSCRIPTION"],
	    		"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
	    		"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
	    		"MESS_BTN_BUY" => $arParams["MESS_BTN_BUY"],
	    		"MESS_BTN_ADD_TO_BASKET" => $arParams["MESS_BTN_ADD_TO_BASKET"],
	    		"MESS_BTN_SUBSCRIBE" => $arParams["MESS_BTN_SUBSCRIBE"],
	    		"MESS_BTN_DETAIL" => $arParams["MESS_BTN_DETAIL"],
	    		"MESS_NOT_AVAILABLE" => $arParams["MESS_NOT_AVAILABLE"],
	    		"TEMPLATE_THEME" => (isset($arParams["TEMPLATE_THEME"])?$arParams["TEMPLATE_THEME"]:""),
	    		"ADD_SECTIONS_CHAIN" => "N",
	    		"ADD_TO_BASKET_ACTION" => $basketAction,
	    		"SHOW_CLOSE_POPUP" => isset($arParams["COMMON_SHOW_CLOSE_POPUP"])?$arParams["COMMON_SHOW_CLOSE_POPUP"]:"",
	    		"COMPARE_PATH" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["compare"],
	    		"SECTION_USER_FIELDS" => array(
	    			0 => "",
	    			1 => "",
	    		),
	    		"INCLUDE_SUBSECTIONS" => "Y",
	    		"SHOW_ALL_WO_SECTION" => "N",
	    		"LINE_ELEMENT_COUNT" => "3",
	    		"SECTION_ID_VARIABLE" => "SECTION_ID",
	    		"AJAX_MODE" => "N",
	    		"AJAX_OPTION_JUMP" => "N",
	    		"AJAX_OPTION_STYLE" => "N",
	    		"AJAX_OPTION_HISTORY" => "N",
	    		"SET_BROWSER_TITLE" => "N",
	    		"SET_META_KEYWORDS" => "N",
	    		"SET_META_DESCRIPTION" => "N",
	    		"ACTION_VARIABLE" => "action",
	    		"PRODUCT_ID_VARIABLE" => "id",
	    		"BASKET_URL" => "/personal/basket.php",
	    		"DISPLAY_COMPARE" => "N",
	    		"AJAX_OPTION_ADDITIONAL" => "",
	    		"BROWSER_TITLE" => "-",
	    		"META_KEYWORDS" => "-",
	    		"META_DESCRIPTION" => "-",
	    		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
	    		"PRODUCT_PROPS_VARIABLE" => "prop"
	    	),
	    	false
	    );
		
		$items = ob_get_contents();
	ob_end_clean();
	$doc = phpQuery::newDocument($items);
	$arResult['PRODUCTS'] = array();
	foreach ($doc['.product'] as $product):
		$arResult['PRODUCTS'][pq($product)->attr('data-id')] = pq($product)->parent()->html();
	endforeach;
endif;
?>