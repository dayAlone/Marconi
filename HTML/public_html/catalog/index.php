<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Купить сумку francesco marconi, сумки francesco marconi, сумки франческо маркони, купить францеско маркони, интернет-магазин франческо маркони");
$APPLICATION->SetTitle("Интернет-магазин сумок и аксессуаров");
$APPLICATION->SetPageProperty('body_class', "catalog ".(!isset($_REQUEST['v'])?"catalog--ajax":""));
?><?$APPLICATION->IncludeComponent(
	"bitrix:catalog", 
	".default", 
	array(
		"IBLOCK_TYPE"         => "catalog",
		"IBLOCK_ID"           => "1",
		"HIDE_NOT_AVAILABLE"  => "Y",
		"SEF_MODE"            => "Y",
		"SEF_FOLDER"          => "/catalog/",
		"AJAX_MODE"           => "N",
		"AJAX_OPTION_JUMP"    => "N",
		"AJAX_OPTION_STYLE"   => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE"          => "A",
		"CACHE_TIME"          => "36000000",
		"CACHE_FILTER"        => "Y",
		"CACHE_GROUPS"        => "Y",
		"SET_STATUS_404"      => "N",
		"SET_TITLE"           => "Y",
		"ADD_SECTIONS_CHAIN"  => "Y",
		"ADD_ELEMENT_CHAIN"   => "N",
		"USE_ELEMENT_COUNTER" => "Y",
		"USE_FILTER"          => "Y",
		"FILTER_NAME"         => "",
		"FILTER_FIELD_CODE" => array(
			0 => "CODE",
			1 => "",
		),
		"FILTER_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_PRICE_CODE" => array(
			0 => "RETAIL",
		),
		"FILTER_VIEW_MODE"    => "VERTICAL",
		"ACTION_VARIABLE"     => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"USE_COMPARE"         => "N",
		"PRICE_CODE" => array(
			0 => "RETAIL",
		),
		"USE_PRICE_COUNT"                  => "N",
		"SHOW_PRICE_COUNT"                 => "1",
		"PRICE_VAT_INCLUDE"                => "Y",
		"PRICE_VAT_SHOW_VALUE"             => "N",
		"CONVERT_CURRENCY"                 => "N",
		"BASKET_URL"                       => "/personal/basket.php",
		"USE_PRODUCT_QUANTITY"             => "N",
		"ADD_PROPERTIES_TO_BASKET"         => "Y",
		"PRODUCT_PROPS_VARIABLE"           => "prop",
		"PARTIAL_PRODUCT_PROPERTIES"       => "Y",
		"PRODUCT_PROPERTIES"               => array(
		),
		"USE_COMMON_SETTINGS_BASKET_POPUP" => "N",
		"TOP_ADD_TO_BASKET_ACTION"         => "ADD",
		"SECTION_ADD_TO_BASKET_ACTION"     => "ADD",
		"DETAIL_ADD_TO_BASKET_ACTION"      => "BUY",
		"SHOW_TOP_ELEMENTS"                => "N",
		"TOP_ELEMENT_COUNT"                => "9",
		"TOP_LINE_ELEMENT_COUNT"           => "3",
		"TOP_ELEMENT_SORT_FIELD"           => "id",
		"TOP_ELEMENT_SORT_ORDER"           => "desc",
		"TOP_ELEMENT_SORT_FIELD2"          => "sort",
		"TOP_ELEMENT_SORT_ORDER2"          => "desc",
		"TOP_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"TOP_VIEW_MODE"             => "SECTION",
		"SECTION_COUNT_ELEMENTS"    => "Y",
		"SECTION_TOP_DEPTH"         => "1",
		"SECTIONS_VIEW_MODE"        => "LIST",
		"SECTIONS_SHOW_PARENT_NAME" => "Y",
		"PAGE_ELEMENT_COUNT"        => (isset($_COOKIE['PER_PAGE'])?$_COOKIE['PER_PAGE']:40),
		"LINE_ELEMENT_COUNT"        => "3",
		"ELEMENT_SORT_FIELD"        => ($_REQUEST['sort_param']?$_REQUEST['sort_param']:"ID"),
		"ELEMENT_SORT_ORDER"        => ($_REQUEST['sort_value']?$_REQUEST['sort_value']:"desc"),
		"ELEMENT_SORT_FIELD2"       => "SORT",
		"ELEMENT_SORT_ORDER2"       => "asc",
		"LIST_PROPERTY_CODE" => array(
			0 => "BRAND",
			1 => "PICTURES",
		),
		"INCLUDE_SUBSECTIONS"   => "Y",
		"LIST_META_KEYWORDS"    => "-",
		"LIST_META_DESCRIPTION" => "-",
		"LIST_BROWSER_TITLE"    => "-",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "BRAND",
			1 => "PICTURES",
		),
		"DETAIL_META_KEYWORDS"             => "-",
		"DETAIL_META_DESCRIPTION"          => "-",
		"DETAIL_BROWSER_TITLE"             => "-",
		"SECTION_ID_VARIABLE"              => "SECTION_ID",
		"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
		"DETAIL_DISPLAY_NAME"              => "Y",
		"DETAIL_DETAIL_PICTURE_MODE"       => "IMG",
		"DETAIL_ADD_DETAIL_TO_SLIDER"      => "N",
		"DETAIL_DISPLAY_PREVIEW_TEXT_MODE" => "E",
		"LINK_IBLOCK_TYPE"                 => "",
		"LINK_IBLOCK_ID"                   => "",
		"LINK_PROPERTY_SID"                => "",
		"LINK_ELEMENTS_URL"                => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
		"USE_ALSO_BUY"                     => "N",
		"USE_STORE"                        => "N",
		"PAGER_TEMPLATE"                   => ".default",
		"DISPLAY_TOP_PAGER"                => "N",
		"DISPLAY_BOTTOM_PAGER"             => "Y",
		"PAGER_TITLE"                      => "Товары",
		"PAGER_SHOW_ALWAYS"                => "Y",
		"PAGER_DESC_NUMBERING"             => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME"  => "36000",
		"PAGER_SHOW_ALL"                   => "Y",
		"TEMPLATE_THEME"                   => "blue",
		"ADD_PICT_PROP"                    => "-",
		"LABEL_PROP"                       => "-",
		"COMMON_SHOW_CLOSE_POPUP"          => "N",
		"SHOW_DISCOUNT_PERCENT"            => "N",
		"SHOW_OLD_PRICE"                   => "N",
		"DETAIL_SHOW_MAX_QUANTITY"         => "N",
		"MESS_BTN_BUY"                     => "Купить",
		"MESS_BTN_ADD_TO_BASKET"           => "В корзину",
		"MESS_BTN_COMPARE"                 => "Сравнение",
		"MESS_BTN_DETAIL"                  => "Подробнее",
		"MESS_NOT_AVAILABLE"               => "Нет в наличии",
		"DETAIL_USE_VOTE_RATING"           => "N",
		"DETAIL_USE_COMMENTS"              => "N",
		"DETAIL_BRAND_USE"                 => "Y",
		"USE_SALE_BESTSELLERS"             => "N",
		"AJAX_OPTION_ADDITIONAL"           => "",
		"PRODUCT_QUANTITY_VARIABLE"        => "quantity",
		"COMMON_ADD_TO_BASKET_ACTION"      => "ADD",
		"DETAIL_BRAND_PROP_CODE" => array(
			0 => "BRAND",
			1 => "",
		),
		"FILTER_OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_CART_PROPERTIES" => array(
		),
		"LIST_OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"LIST_OFFERS_PROPERTY_CODE" => array(
			0 => "SIZE",
			1 => "",
		),
		"LIST_OFFERS_LIMIT" => "5",
		"DETAIL_OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_SORT_FIELD"    => "id",
		"OFFERS_SORT_ORDER"    => "asc",
		"OFFERS_SORT_FIELD2"   => "sort",
		"OFFERS_SORT_ORDER2"   => "asc",
		"PRODUCT_DISPLAY_MODE" => "N",
		"OFFER_ADD_PICT_PROP"  => "-",
		"OFFER_TREE_PROPS" => array(
			0 => "-",
		),
		"SEF_URL_TEMPLATES" => array(
			"sections" => "",
			"section"  => "#SECTION_CODE#/",
			"element"  => "#SECTION_CODE#/#ELEMENT_CODE#/",
			"compare"  => "compare.php?action=#ACTION_CODE#",
		),
		"VARIABLE_ALIASES" => array(
			"compare" => array(
				"ACTION_CODE" => "action",
			),
		)
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>