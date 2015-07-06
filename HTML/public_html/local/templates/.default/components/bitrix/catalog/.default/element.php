<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
$this->setFrameMode(true);
if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y')
{
	$basketAction = (isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? $arParams['COMMON_ADD_TO_BASKET_ACTION'] : '');
}
else
{
	$basketAction = (isset($arParams['DETAIL_ADD_TO_BASKET_ACTION']) ? $arParams['DETAIL_ADD_TO_BASKET_ACTION'] : '');
}
?><?$ElementID = $APPLICATION->IncludeComponent(
	"bitrix:catalog.element",
	"",
	array(
		"HIDE_PRICE"                      => $arParams['HIDE_PRICE'],
		"HIDE_SIMMILAR"                   => $arParams['HIDE_SIMMILAR'],
		"HIDE_MORE"                       => $arParams['HIDE_MORE'],
		"SHOW_COUNT"                      => $arParams['SHOW_COUNT'],
		"IBLOCK_TYPE"                => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID"                  => $arParams["IBLOCK_ID"],
		"PROPERTY_CODE"              => $arParams["DETAIL_PROPERTY_CODE"],
		"META_KEYWORDS"              => $arParams["DETAIL_META_KEYWORDS"],
		"META_DESCRIPTION"           => $arParams["DETAIL_META_DESCRIPTION"],
		"BROWSER_TITLE"              => $arParams["DETAIL_BROWSER_TITLE"],
		"BASKET_URL"                 => $arParams["BASKET_URL"],
		"ACTION_VARIABLE"            => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE"        => $arParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE"        => $arParams["SECTION_ID_VARIABLE"],
		"CHECK_SECTION_ID_VARIABLE"  => (isset($arParams["DETAIL_CHECK_SECTION_ID_VARIABLE"]) ? $arParams["DETAIL_CHECK_SECTION_ID_VARIABLE"] : ''),
		"PRODUCT_QUANTITY_VARIABLE"  => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"PRODUCT_PROPS_VARIABLE"     => $arParams["PRODUCT_PROPS_VARIABLE"],
		"CACHE_TYPE"                 => $arParams["CACHE_TYPE"],
		"CACHE_TIME"                 => $arParams["CACHE_TIME"],
		"CACHE_GROUPS"               => $arParams["CACHE_GROUPS"],
		"SET_TITLE"                  => "Y",
		"SET_STATUS_404"             => $arParams["SET_STATUS_404"],
		"PRICE_CODE"                 => $arParams["PRICE_CODE"],
		"USE_PRICE_COUNT"            => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT"           => $arParams["SHOW_PRICE_COUNT"],
		"PRICE_VAT_INCLUDE"          => $arParams["PRICE_VAT_INCLUDE"],
		"PRICE_VAT_SHOW_VALUE"       => $arParams["PRICE_VAT_SHOW_VALUE"],
		"USE_PRODUCT_QUANTITY"       => $arParams['USE_PRODUCT_QUANTITY'],
		"PRODUCT_PROPERTIES"         => $arParams["PRODUCT_PROPERTIES"],
		"ADD_PROPERTIES_TO_BASKET"   => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
		"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
		"LINK_IBLOCK_TYPE"           => $arParams["LINK_IBLOCK_TYPE"],
		"LINK_IBLOCK_ID"             => $arParams["LINK_IBLOCK_ID"],
		"LINK_PROPERTY_SID"          => $arParams["LINK_PROPERTY_SID"],
		"LINK_ELEMENTS_URL"          => $arParams["LINK_ELEMENTS_URL"],
		
		"OFFERS_CART_PROPERTIES"     => $arParams["OFFERS_CART_PROPERTIES"],
		"OFFERS_FIELD_CODE"          => $arParams["DETAIL_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE"       => $arParams["DETAIL_OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD"          => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER"          => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_SORT_FIELD2"         => $arParams["OFFERS_SORT_FIELD2"],
		"OFFERS_SORT_ORDER2"         => $arParams["OFFERS_SORT_ORDER2"],
		
		"ELEMENT_ID"                 => $arResult["VARIABLES"]["ELEMENT_ID"],
		"ELEMENT_CODE"               => $arResult["VARIABLES"]["ELEMENT_CODE"],
		"SECTION_ID"                 => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_CODE"               => $arResult["VARIABLES"]["SECTION_CODE"],
		"SECTION_URL"                => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"DETAIL_URL"                 => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		'CONVERT_CURRENCY'           => $arParams['CONVERT_CURRENCY'],
		'CURRENCY_ID'                => $arParams['CURRENCY_ID'],
		'HIDE_NOT_AVAILABLE'         => $arParams["HIDE_NOT_AVAILABLE"],
		'USE_ELEMENT_COUNTER'        => $arParams['USE_ELEMENT_COUNTER'],
		
		'ADD_PICT_PROP'              => $arParams['ADD_PICT_PROP'],
		'LABEL_PROP'                 => $arParams['LABEL_PROP'],
		'OFFER_ADD_PICT_PROP'        => $arParams['OFFER_ADD_PICT_PROP'],
		'OFFER_TREE_PROPS'           => $arParams['OFFER_TREE_PROPS'],
		'PRODUCT_SUBSCRIPTION'       => $arParams['PRODUCT_SUBSCRIPTION'],
		'SHOW_DISCOUNT_PERCENT'      => $arParams['SHOW_DISCOUNT_PERCENT'],
		'SHOW_OLD_PRICE'             => $arParams['SHOW_OLD_PRICE'],
		'SHOW_MAX_QUANTITY'          => $arParams['DETAIL_SHOW_MAX_QUANTITY'],
		'MESS_BTN_BUY'               => $arParams['MESS_BTN_BUY'],
		'MESS_BTN_ADD_TO_BASKET'     => $arParams['MESS_BTN_ADD_TO_BASKET'],
		'MESS_BTN_SUBSCRIBE'         => $arParams['MESS_BTN_SUBSCRIBE'],
		'MESS_BTN_COMPARE'           => $arParams['MESS_BTN_COMPARE'],
		'MESS_NOT_AVAILABLE'         => $arParams['MESS_NOT_AVAILABLE'],
		'USE_VOTE_RATING'            => $arParams['DETAIL_USE_VOTE_RATING'],
		'VOTE_DISPLAY_AS_RATING'     => (isset($arParams['DETAIL_VOTE_DISPLAY_AS_RATING']) ? $arParams['DETAIL_VOTE_DISPLAY_AS_RATING'] : ''),
		'USE_COMMENTS'               => $arParams['DETAIL_USE_COMMENTS'],
		'BLOG_USE'                   => (isset($arParams['DETAIL_BLOG_USE']) ? $arParams['DETAIL_BLOG_USE'] : ''),
		'BLOG_URL'                   => (isset($arParams['DETAIL_BLOG_URL']) ? $arParams['DETAIL_BLOG_URL'] : ''),
		'BLOG_EMAIL_NOTIFY'          => (isset($arParams['DETAIL_BLOG_EMAIL_NOTIFY']) ? $arParams['DETAIL_BLOG_EMAIL_NOTIFY'] : ''),
		'VK_USE'                     => (isset($arParams['DETAIL_VK_USE']) ? $arParams['DETAIL_VK_USE'] : ''),
		'VK_API_ID'                  => (isset($arParams['DETAIL_VK_API_ID']) ? $arParams['DETAIL_VK_API_ID'] : 'API_ID'),
		'FB_USE'                     => (isset($arParams['DETAIL_FB_USE']) ? $arParams['DETAIL_FB_USE'] : ''),
		'FB_APP_ID'                  => (isset($arParams['DETAIL_FB_APP_ID']) ? $arParams['DETAIL_FB_APP_ID'] : ''),
		'BRAND_USE'                  => (isset($arParams['DETAIL_BRAND_USE']) ? $arParams['DETAIL_BRAND_USE'] : 'N'),
		'BRAND_PROP_CODE'            => (isset($arParams['DETAIL_BRAND_PROP_CODE']) ? $arParams['DETAIL_BRAND_PROP_CODE'] : ''),
		'DISPLAY_NAME'               => (isset($arParams['DETAIL_DISPLAY_NAME']) ? $arParams['DETAIL_DISPLAY_NAME'] : ''),
		'ADD_DETAIL_TO_SLIDER'       => (isset($arParams['DETAIL_ADD_DETAIL_TO_SLIDER']) ? $arParams['DETAIL_ADD_DETAIL_TO_SLIDER'] : ''),
		'TEMPLATE_THEME'             => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
		"ADD_SECTIONS_CHAIN"         => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : ''),
		"ADD_ELEMENT_CHAIN"          => (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : ''),
		"DISPLAY_PREVIEW_TEXT_MODE"  => (isset($arParams['DETAIL_DISPLAY_PREVIEW_TEXT_MODE']) ? $arParams['DETAIL_DISPLAY_PREVIEW_TEXT_MODE'] : ''),
		"DETAIL_PICTURE_MODE"        => (isset($arParams['DETAIL_DETAIL_PICTURE_MODE']) ? $arParams['DETAIL_DETAIL_PICTURE_MODE'] : ''),
		'ADD_TO_BASKET_ACTION'       => array($basketAction),
		'SHOW_CLOSE_POPUP'           => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
		'DISPLAY_COMPARE'            => (isset($arParams['USE_COMPARE']) ? $arParams['USE_COMPARE'] : ''),
		'COMPARE_PATH'               => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
		'SHOW_BASIS_PRICE'           => (isset($arParams['DETAIL_SHOW_BASIS_PRICE']) ? $arParams['DETAIL_SHOW_BASIS_PRICE'] : 'Y')
	),
	$component
);?><?
unset($basketAction);
if ($ElementID > 0)
{
	$APPLICATION->IncludeComponent(
	"bitrix:catalog.viewed.products", 
	".default", 
	array(
		"HIDE_PRICE"                      => $arParams['HIDE_PRICE'],
		"HIDE_SIMMILAR"                   => $arParams['HIDE_SIMMILAR'],
		"HIDE_MORE"                       => $arParams['HIDE_MORE'],
		"SHOW_COUNT"                      => $arParams['SHOW_COUNT'],
		
		"LINE_ELEMENT_COUNT" => "5",
		"TEMPLATE_THEME" => "blue",
		"DETAIL_URL" => "/catalog/#SECTION_CODE#/#ELEMENT_CODE#/",
		"BASKET_URL" => "/personal/basket.php",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "Y",
		"SHOW_OLD_PRICE" => "N",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"PRICE_CODE" => array(
			0 => "RETAIL",
		),
		"SHOW_PRICE_COUNT" => "1",
		"PRODUCT_SUBSCRIPTION" => "N",
		"PRICE_VAT_INCLUDE" => "Y",
		"USE_PRODUCT_QUANTITY" => "Y",
		"SHOW_NAME" => "Y",
		"SHOW_IMAGE" => "Y",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"PAGE_ELEMENT_COUNT" => "5",
		"SHOW_FROM_SECTION" => "N",
		"IBLOCK_ID" => "1",
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"SHOW_PRODUCTS_6" => "N",
		"PROPERTY_CODE_6" => "",
		"CART_PROPERTIES_6" => "",
		"ADDITIONAL_PICT_PROP_6" => "-",
		"LABEL_PROP_6" => "-",
		"PROPERTY_CODE_20" => "",
		"CART_PROPERTIES_20" => "",
		"ADDITIONAL_PICT_PROP_20" => "-",
		"OFFER_TREE_PROPS_20" => "-",
		"HIDE_NOT_AVAILABLE" => "Y",
		"CONVERT_CURRENCY" => "Y",
		"CURRENCY_ID" => "RUB",
		"IBLOCK_TYPE" => "catalog",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"SHOW_PRODUCTS_1" => "Y",
		"SECTION_ELEMENT_ID" => "",
		"SECTION_ELEMENT_CODE" => "",
		"DEPTH" => "2",
		"PROPERTY_CODE_1" => array(
			0 => "COLOR",
			1 => "BRAND",
			2 => "PICTURES",
			3 => "",
		),
		"CART_PROPERTIES_1" => array(
			0 => "",
			1 => "",
		),
		"ADDITIONAL_PICT_PROP_1" => "PICTURES",
		"LABEL_PROP_1" => "-",
		"PROPERTY_CODE_2" => array(
			0 => "SIZE",
			1 => "",
		),
		"CART_PROPERTIES_2" => array(
			0 => "SIZE",
			1 => "",
		),
		"ADDITIONAL_PICT_PROP_2" => "",
		"OFFER_TREE_PROPS_2" => array(
			0 => "-",
		)
	),
	$component
);
	?>
	<div data-title="<?=(SITE_ID=='s1'?"francesco marconi":"Новый Стиль Студио")?> рекомендует" class="catalog__divider catalog__divider--title"></div>
	<div class="catalog catalog--full-width catalog--one-line <?=(SITE_ID!='s1'?"catalog--italbags":"")?>">
	<?
	global $colorFilter;
	$APPLICATION->IncludeComponent(
		"bitrix:catalog.section",
		"",
		array(
			"HIDE_PRICE"                      => $arParams['HIDE_PRICE'],
			"HIDE_SIMMILAR"                   => $arParams['HIDE_SIMMILAR'],
			"HIDE_MORE"                       => $arParams['HIDE_MORE'],
			"SHOW_COUNT"                      => $arParams['SHOW_COUNT'],
			'HIDE_TOOLBAR' => "Y",
			"IBLOCK_TYPE"                     => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID"                       => $arParams["IBLOCK_ID"],
			"ELEMENT_SORT_FIELD"              => 'RAND',
			"ELEMENT_SORT_ORDER"              => $arParams["ELEMENT_SORT_ORDER"],
			"ELEMENT_SORT_FIELD2"             => $arParams["ELEMENT_SORT_FIELD2"],
			"ELEMENT_SORT_ORDER2"             => $arParams["ELEMENT_SORT_ORDER2"],
			"PROPERTY_CODE"                   => $arParams["LIST_PROPERTY_CODE"],
			"META_KEYWORDS"                   => $arParams["LIST_META_KEYWORDS"],
			"META_DESCRIPTION"                => $arParams["LIST_META_DESCRIPTION"],
			"BROWSER_TITLE"                   => $arParams["LIST_BROWSER_TITLE"],
			"INCLUDE_SUBSECTIONS"             => $arParams["INCLUDE_SUBSECTIONS"],
			"BASKET_URL"                      => $arParams["BASKET_URL"],
			"ACTION_VARIABLE"                 => $arParams["ACTION_VARIABLE"],
			"PRODUCT_ID_VARIABLE"             => $arParams["PRODUCT_ID_VARIABLE"],
			"SECTION_ID_VARIABLE"             => $arParams["SECTION_ID_VARIABLE"],
			"PRODUCT_QUANTITY_VARIABLE"       => $arParams["PRODUCT_QUANTITY_VARIABLE"],
			"PRODUCT_PROPS_VARIABLE"          => $arParams["PRODUCT_PROPS_VARIABLE"],
			"FILTER_NAME"                     => 'colorFilter',
			"CACHE_TYPE"                      => $arParams["CACHE_TYPE"],
			"CACHE_TIME"                      => $arParams["CACHE_TIME"],
			"CACHE_FILTER"                    => $arParams["CACHE_FILTER"],
			"CACHE_GROUPS"                    => $arParams["CACHE_GROUPS"],
			"SET_TITLE"                       => "N",
			"SET_STATUS_404"                  => $arParams["SET_STATUS_404"],
			"DISPLAY_COMPARE"                 => $arParams["USE_COMPARE"],
			"PAGE_ELEMENT_COUNT"              => 5,
			"LINE_ELEMENT_COUNT"              => $arParams["LINE_ELEMENT_COUNT"],
			"PRICE_CODE"                      => $arParams["PRICE_CODE"],
			"USE_PRICE_COUNT"                 => $arParams["USE_PRICE_COUNT"],
			"SHOW_PRICE_COUNT"                => $arParams["SHOW_PRICE_COUNT"],
			
			"PRICE_VAT_INCLUDE"               => $arParams["PRICE_VAT_INCLUDE"],
			"USE_PRODUCT_QUANTITY"            => $arParams['USE_PRODUCT_QUANTITY'],
			"ADD_PROPERTIES_TO_BASKET"        => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
			"PARTIAL_PRODUCT_PROPERTIES"      => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
			"PRODUCT_PROPERTIES"              => $arParams["PRODUCT_PROPERTIES"],
			
			"DISPLAY_TOP_PAGER"               => $arParams["DISPLAY_TOP_PAGER"],
			"DISPLAY_BOTTOM_PAGER"            => $arParams["DISPLAY_BOTTOM_PAGER"],
			"PAGER_TITLE"                     => $arParams["PAGER_TITLE"],
			"PAGER_SHOW_ALWAYS"               => $arParams["PAGER_SHOW_ALWAYS"],
			"PAGER_TEMPLATE"                  => $arParams["PAGER_TEMPLATE"],
			"PAGER_DESC_NUMBERING"            => $arParams["PAGER_DESC_NUMBERING"],
			"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
			"PAGER_SHOW_ALL"                  => $arParams["PAGER_SHOW_ALL"],
			
			"OFFERS_CART_PROPERTIES"          => $arParams["OFFERS_CART_PROPERTIES"],
			"OFFERS_FIELD_CODE"               => $arParams["LIST_OFFERS_FIELD_CODE"],
			"OFFERS_PROPERTY_CODE"            => $arParams["LIST_OFFERS_PROPERTY_CODE"],
			"OFFERS_SORT_FIELD"               => $arParams["OFFERS_SORT_FIELD"],
			"OFFERS_SORT_ORDER"               => $arParams["OFFERS_SORT_ORDER"],
			"OFFERS_SORT_FIELD2"              => $arParams["OFFERS_SORT_FIELD2"],
			"OFFERS_SORT_ORDER2"              => $arParams["OFFERS_SORT_ORDER2"],
			"OFFERS_LIMIT"                    => $arParams["LIST_OFFERS_LIMIT"],
			
			"SECTION_ID"                      => $arResult["VARIABLES"]["SECTION_ID"],
			"SECTION_CODE"                    => $arResult['VARIABLES']['SECTION_CODE'],
			
			"SECTION_URL"                     => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
			"DETAIL_URL"                      => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
			'CONVERT_CURRENCY'                => $arParams['CONVERT_CURRENCY'],
			'CURRENCY_ID'                     => $arParams['CURRENCY_ID'],
			'HIDE_NOT_AVAILABLE'              => $arParams["HIDE_NOT_AVAILABLE"],
			
			'LABEL_PROP'                      => $arParams['LABEL_PROP'],
			'ADD_PICT_PROP'                   => $arParams['ADD_PICT_PROP'],
			'PRODUCT_DISPLAY_MODE'            => $arParams['PRODUCT_DISPLAY_MODE'],
			
			'OFFER_ADD_PICT_PROP'             => $arParams['OFFER_ADD_PICT_PROP'],
			'OFFER_TREE_PROPS'                => $arParams['OFFER_TREE_PROPS'],
			'PRODUCT_SUBSCRIPTION'            => $arParams['PRODUCT_SUBSCRIPTION'],
			'SHOW_DISCOUNT_PERCENT'           => $arParams['SHOW_DISCOUNT_PERCENT'],
			'SHOW_OLD_PRICE'                  => $arParams['SHOW_OLD_PRICE'],
			'MESS_BTN_BUY'                    => $arParams['MESS_BTN_BUY'],
			'MESS_BTN_ADD_TO_BASKET'          => $arParams['MESS_BTN_ADD_TO_BASKET'],
			'MESS_BTN_SUBSCRIBE'              => $arParams['MESS_BTN_SUBSCRIBE'],
			'MESS_BTN_DETAIL'                 => $arParams['MESS_BTN_DETAIL'],
			'MESS_NOT_AVAILABLE'              => $arParams['MESS_NOT_AVAILABLE'],
			
			'TEMPLATE_THEME'                  => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
			"ADD_SECTIONS_CHAIN"              => "N",
			'ADD_TO_BASKET_ACTION'            => $basketAction,
			'SHOW_CLOSE_POPUP'                => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
			'COMPARE_PATH'                    => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare']
		)
	);
	?>
	</div>
	<div class="hidden">
	<?
	
	$APPLICATION->IncludeComponent(
		"bitrix:catalog.bigdata.products", 
		".default", 
		array(
			"RCM_TYPE" => "any_personal",
			"ID" => $ElementID,
			"IBLOCK_TYPE" => "catalog",
			"IBLOCK_ID" => "2",
			"HIDE_NOT_AVAILABLE" => "Y",
			"SHOW_DISCOUNT_PERCENT" => "Y",
			"PRODUCT_SUBSCRIPTION" => "N",
			"SHOW_NAME" => "Y",
			"SHOW_IMAGE" => "Y",
			"MESS_BTN_BUY" => "Купить",
			"MESS_BTN_DETAIL" => "Подробнее",
			"MESS_BTN_SUBSCRIBE" => "Подписаться",
			"PAGE_ELEMENT_COUNT" => "5",
			"LINE_ELEMENT_COUNT" => "5",
			"TEMPLATE_THEME" => "blue",
			"DETAIL_URL" => "/catalog/#SECTION_CODE#/#ELEMENT_CODE#/",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "36000000",
			"CACHE_GROUPS" => "Y",
			"SHOW_OLD_PRICE" => "N",
			"PRICE_CODE" => array(
				0 => "RETAIL",
			),
			"SHOW_PRICE_COUNT" => "1",
			"PRICE_VAT_INCLUDE" => "Y",
			"CONVERT_CURRENCY" => "Y",
			"BASKET_URL" => "/personal/cart/",
			"ACTION_VARIABLE" => "action",
			"PRODUCT_ID_VARIABLE" => "id",
			"ADD_PROPERTIES_TO_BASKET" => "Y",
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"PARTIAL_PRODUCT_PROPERTIES" => "N",
			"USE_PRODUCT_QUANTITY" => "N",
			"SHOW_PRODUCTS_2" => "Y",
			"CURRENCY_ID" => "RUB",
			"PROPERTY_CODE_2" => array(
				0 => "SIZE",
				1 => "NEWPRODUCT",
				2 => "MANUFACTURER",
				3 => "MATERIAL",
				4 => "COLOR",
				5 => "",
			),
			"CART_PROPERTIES_2" => array(
				0 => "SIZE",
				1 => "NEWPRODUCT",
				2 => "",
			),
			"ADDITIONAL_PICT_PROP_2" => "",
			"LABEL_PROP_2" => "NEWPRODUCT",
			"PROPERTY_CODE_3" => array(
				0 => "COLOR_REF",
				1 => "SIZES_SHOES",
				2 => "SIZES_CLOTHES",
				3 => "",
			),
			"CART_PROPERTIES_3" => array(
				0 => "COLOR_REF",
				1 => "SIZES_SHOES",
				2 => "SIZES_CLOTHES",
				3 => "",
			),
			"ADDITIONAL_PICT_PROP_3" => "MORE_PHOTO",
			"OFFER_TREE_PROPS_3" => array(
				0 => "COLOR_REF",
				1 => "SIZES_SHOES",
				2 => "SIZES_CLOTHES",
			),
			"PRODUCT_QUANTITY_VARIABLE" => "quantity",
			"SHOW_FROM_SECTION" => "N",
			"SHOW_PRODUCTS_1" => "Y",
			"PROPERTY_CODE_1" => array(
				0 => "COLOR",
				1 => "BRAND",
				2 => "PICTURES",
				3 => "",
			),
			"CART_PROPERTIES_1" => array(
				0 => "",
				1 => "",
			),
			"ADDITIONAL_PICT_PROP_1" => "PICTURES",
			"LABEL_PROP_1" => "-",
			"OFFER_TREE_PROPS_2" => array(
				0 => "SIZE",
			),
			"SECTION_ID" => "",
			"SECTION_CODE" => $arResult['VARIABLES']['SECTION_CODE'],
			"SECTION_ELEMENT_ID" => "",
			"SECTION_ELEMENT_CODE" => "",
			"DEPTH" => "2"
		),
		$component
	);
	?></div><?
}
?>