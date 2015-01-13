<? $this->setFrameMode(true);?>
<h1 class="lookbook__title"><span><?=$arResult['SECTION']['PATH'][0]['NAME']?></span></h1>
<div style="background-image: url(<?=$arResult['ITEMS'][count($arResult['ITEMS'])-1]['PREVIEW_PICTURE']['SRC']?>)" data-direction="&lt;" class="lookbook__slider-preview lookbook__slider-preview--prev"><?=svg('slider-arrow-left')?></div>
<div style="background-image: url(<?=$arResult['ITEMS'][1]['PREVIEW_PICTURE']['SRC']?>)" data-direction="&gt;" class="lookbook__slider-preview lookbook__slider-preview--next"><?=svg('slider-arrow-right')?></div>
<div data-loop="true" data-width="120%" data-keyboard="true" data-nav="false" data-arrows="false" data-click="false" data-transition="crossfade" class="lookbook__slider">
<?foreach ($arResult['ITEMS'] as $key=>$item):
    ?>
      <div class="lookbook__slider-item">
        <div style="background-image: url(<?=$item['PREVIEW_PICTURE']['SRC']?>)" class="lookbook__picture <?=($item['PREVIEW_PICTURE']['HEIGHT'] > $item['PREVIEW_PICTURE']['WIDTH'] || ($item['PREVIEW_PICTURE']['HEIGHT']/$item['PREVIEW_PICTURE']['WIDTH']) > 0.7 ?"lookbook__picture--contain":"")?>" data-height="<?=$item['PREVIEW_PICTURE']['HEIGHT']?>"  data-width="<?=$item['PREVIEW_PICTURE']['WIDTH']?>"></div>
        <?if(count($item['PROPERTIES']['PRODUCTS']['VALUE'])>0 && isset($item['PROPERTIES']['PRODUCTS']['VALUE'][0])):?>
          <div class="lookbook__divider"><span>на этом фото</span></div>
          <div class="catalog catalog--full-width catalog--without-images">
          <?
          
            global $arrFilter;
            $arrFilter = array('=ID' => $item['PROPERTIES']['PRODUCTS']['VALUE']);
            $APPLICATION->IncludeComponent(
            	"bitrix:catalog.section", 
            	".default", 
            	array(
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
          
          ?>
          </div>
        <?endif;?>
      </div>
<?endforeach;?>
</div>