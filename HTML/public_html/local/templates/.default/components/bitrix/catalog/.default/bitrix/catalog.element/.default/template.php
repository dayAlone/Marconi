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
$this->setFrameMode(true);
$templateLibrary = array('popup');
$currencyList = '';
if (!empty($arResult['CURRENCIES']))
{
	$templateLibrary[] = 'currency';
	$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}
$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME'],
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList
);
unset($currencyList, $templateLibrary);

$strMainID = $this->GetEditAreaId($arResult['ID']);
$arItemIDs = array(
	'ID' => $strMainID,
	'PICT' => $strMainID.'_pict',
	'DISCOUNT_PICT_ID' => $strMainID.'_dsc_pict',
	'STICKER_ID' => $strMainID.'_sticker',
	'BIG_SLIDER_ID' => $strMainID.'_big_slider',
	'BIG_IMG_CONT_ID' => $strMainID.'_bigimg_cont',
	'SLIDER_CONT_ID' => $strMainID.'_slider_cont',
	'SLIDER_LIST' => $strMainID.'_slider_list',
	'SLIDER_LEFT' => $strMainID.'_slider_left',
	'SLIDER_RIGHT' => $strMainID.'_slider_right',
	'OLD_PRICE' => $strMainID.'_old_price',
	'PRICE' => $strMainID.'_price',
	'DISCOUNT_PRICE' => $strMainID.'_price_discount',
	'SLIDER_CONT_OF_ID' => $strMainID.'_slider_cont_',
	'SLIDER_LIST_OF_ID' => $strMainID.'_slider_list_',
	'SLIDER_LEFT_OF_ID' => $strMainID.'_slider_left_',
	'SLIDER_RIGHT_OF_ID' => $strMainID.'_slider_right_',
	'QUANTITY' => $strMainID.'_quantity',
	'QUANTITY_DOWN' => $strMainID.'_quant_down',
	'QUANTITY_UP' => $strMainID.'_quant_up',
	'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
	'QUANTITY_LIMIT' => $strMainID.'_quant_limit',
	'BASIS_PRICE' => $strMainID.'_basis_price',
	'BUY_LINK' => $strMainID.'_buy_link',
	'ADD_BASKET_LINK' => $strMainID.'_add_basket_link',
	'BASKET_ACTIONS' => $strMainID.'_basket_actions',
	'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
	'COMPARE_LINK' => $strMainID.'_compare_link',
	'PROP' => $strMainID.'_prop_',
	'PROP_DIV' => $strMainID.'_skudiv',
	'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
	'OFFER_GROUP' => $strMainID.'_set_group_',
	'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
);
$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
$templateData['JS_OBJ'] = $strObName;
$item = &$arResult;
$props = &$arResult['PROPERTIES'];
?>

<div class="row">
	<div class="col-xs-6">
	  <div class="picture">
	    <div class="row">
	      <div class="<?=(count($item['IMAGES'])>1?"col-xs-9 col-lg-10":"col-xs-12")?>">
	      	<? if(count($item['IMAGES'])>0 || isset($item['PREVIEW_PICTURE']['SRC'])):
	      		$array = array_values($item['IMAGES']);
	      	?>
	        	<div style="background-image:url(<?=(isset($array[0]['middle'])?$array[0]['middle']:$item['PREVIEW_PICTURE']['SMALL'])?>)" class="picture__big"></div>
	        	<a data-pictures='<?=(count($item['IMAGES'])>0?json_encode($item['IMAGES']):json_encode(array(0=>array('src'=>$item['PREVIEW_PICTURE']['SRC'], 'w'=> $item['PREVIEW_PICTURE']['WIDTH'], 'h'=>$item['PREVIEW_PICTURE']['HEIGHT']))))?>' class="picture__zoom"><?=svg('zoom')?></a>
	        <? endif;?>
	      </div>
	      <? if(count($item['IMAGES'])>1):?>
	      <div class="col-xs-3 col-lg-2">
	      <? foreach ($item['IMAGES'] as $key => $image): ?>
	      	<a style="background-image:url(<?=$image['small']?>)" href="<?=$image['middle']?>" class="picture__small <?=($key==0?"picture__small--active":"")?>"></a>
	      <? endforeach; ?>

	      </div>
	      <? endif;?>
	    </div>
	  </div>
	</div>
	<div class="col-xs-6">
	  <div class="product__description">
	    <div class="row">
	      <div class="col-lg-6">
	        <h1 class="product__title"><?=$item['NAME']?></h1>
	        <?
	        	global $arFilter;
	        	$arFilter = array('PROPERTY_ARTNUMBER' => $props['ARTNUMBER']['VALUE']);
	        	$APPLICATION->IncludeComponent("bitrix:news.list", "colors", 
					array(
						"IBLOCK_ID"     => 1,
						"NEWS_COUNT"    => "9999999",
						"CACHE_NOTES"   => $item['ID'],
						"FILTER_NAME"   => "arFilter",
						"SORT_BY1"      => "ID",
						"SORT_ORDER1"   => "ASC",
						"DETAIL_URL"    => "/catalog/",
						"CACHE_TYPE"    => "A",
						'PROPERTY_CODE' => array(),
						"SET_TITLE"     => "N"
					),
					$component
				);
	        ?>
	      </div>
	      <div class="col-lg-6">
	        <div class="props">
	        <? if(strlen($props['ARTNUMBER']['VALUE'])>0):?>
	          <div class="props__item">
	            <div class="props__name">артикул</div>
	            <div class="props__value"><?=$props['ARTNUMBER']['VALUE']?></div>
	          </div>
	        <? endif; ?>
	        <? if(strlen($props['BRAND']['VALUE'])>0):?>
	          <div class="props__item">
	            <div class="props__name">бренд</div>
	            <div class="props__value"><?=$arResult['BRANDS'][$props['BRAND']['VALUE']]?></div>
	          </div>
	        <? endif; ?>
	        <? 
	        	if(count($props['COLOR']['VALUE'])>0):
	        		$colors = "";
	        		foreach ($props['COLOR']['VALUE'] as $key=>$color)
	        			$colors .= ($key!=0?" / ":"").$arResult['COLORS'][$color];

	        ?>
	          <div class="props__item">
	            <div class="props__name">цвет</div>
	            <div class="props__value"><?=$colors?></div>
	          </div>
	        <? endif;?>
	        <? if(count($props['MATERIAL']['VALUE'])>0):
	        		$materials = "";
	        		foreach ($props['MATERIAL']['VALUE'] as $key=>$color)
	        			$materials .= ($key!=0?" / ":"").$arResult['MATERIALS'][$color];
	       	?>
	          <div class="props__item">
	            <div class="props__name">материал</div>
	            <div class="props__value"><?=$materials?></div>
	          </div>
	        <? endif;?>
	        <? if(strlen($props['SIZE']['VALUE'])>0):?>
	          <div class="props__item">
	            <div class="props__name">размер</div>
	            <div class="props__value"><?=$props['SIZE']['VALUE']?></div>
	          </div>
	        <? endif; ?>
	          <div class="props__item props__item--price">
	            <div class="props__name">цена</div>
	            <div class="props__value">
	            <? if(isset($item['MIN_PRICE']['VALUE'])): ?>
			      <?=number_format($item['MIN_PRICE']['VALUE'], 0, '.', ' ')?> ₷
			    <? else: ?>
			      <small>Товара нет в наличии</small>
			    <? endif; ?>
	            </div>
	          </div>
	        </div>
	      </div>
	    </div>
	    <div class="row">
	      <div class="col-lg-6 center-lg">
	      	<a href="#" class="product__big-button product__big-button--width">В корзину</a>
	      	<? /*<a href="#" class="product__big-button">сравнить</a> */?>
	      </div>
	      <div class="col-lg-6"><a href="#" class="product__big-button product__big-button--border">наличие в магазинах</a>
	        <div class="social-likes social-likes_notext"><div class="facebook"></div><div class="twitter"></div><div class="vkontakte"></div><div class="odnoklassniki"></div></div>
	      </div>
	    </div>
	    <div class="tabs">
	      <div class="tabs__title"><a href="#description" class="tabs__trigger tabs__trigger--active">описание</a><a href="#payment" class="tabs__trigger">оплата</a><a href="#delivery" class="tabs__trigger">доставка</a><a href="#refund" class="tabs__trigger">возврат</a><a href="#feedback" class="tabs__trigger">обратная связь</a></div>
	      <div id="description" class="tabs__content tabs__content--active">
	        <p>Земля традиций и инноваций, фермеров и ремесленников, земля, где искусство встречается с технологиями, где хорошая кухня сочетается с гостеприимством местных жителей. Именно в Абруццо трое молодых братьев — Франческо, Назарено и Абрамо Тонелли, в небольшой мастерской объединив свои навыки и знания, основывают небольшую семейную фабрику в честь своих родителей.</p>
	      </div>
	      <div id="payment" class="tabs__content">
	        <p>Земля традиций и инноваций, фермеров и ремесленников, земля, где искусство встречается с технологиями, где хорошая кухня сочетается с гостеприимством местных жителей. Именно в Абруццо трое молодых братьев — Франческо, Назарено и Абрамо Тонелли, в небольшой мастерской объединив свои навыки и знания, основывают небольшую семейную фабрику в честь своих родителей.</p>
	      </div>
	      <div id="delivery" class="tabs__content">
	        <p>Земля традиций и инноваций, фермеров и ремесленников,</p>
	      </div>
	      <div id="refund" class="tabs__content">
	        <p>Земля традиций и инноваций, фермеров и ремесленников, земля, где искусство встречается</p>
	      </div>
	      <div id="feedback" class="tabs__content">
	        <p>Земля традиций и инноваций, фермеров и ремесленников, земля, где искусство встречается с технологиями, где </p>
	      </div>
	    </div>
	  </div>
	</div>
</div>

<?
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
{
	$arJSParams = array(
		'CONFIG' => array(
			'USE_CATALOG' => $arResult['CATALOG'],
			'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
			'SHOW_PRICE' => true,
			'SHOW_DISCOUNT_PERCENT' => ($arParams['SHOW_DISCOUNT_PERCENT'] == 'Y'),
			'SHOW_OLD_PRICE' => ($arParams['SHOW_OLD_PRICE'] == 'Y'),
			'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
			'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
			'OFFER_GROUP' => $arResult['OFFER_GROUP'],
			'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
			'SHOW_BASIS_PRICE' => ($arParams['SHOW_BASIS_PRICE'] == 'Y'),
			'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
			'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y')
		),
		'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
		'VISUAL' => array(
			'ID' => $arItemIDs['ID'],
		),
		'DEFAULT_PICTURE' => array(
			'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
			'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE']
		),
		'PRODUCT' => array(
			'ID' => $arResult['ID'],
			'NAME' => $arResult['~NAME']
		),
		'BASKET' => array(
			'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'BASKET_URL' => $arParams['BASKET_URL'],
			'SKU_PROPS' => $arResult['OFFERS_PROP_CODES'],
			'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
			'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
		),
		'OFFERS' => $arResult['JS_OFFERS'],
		'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
		'TREE_PROPS' => $arSkuProps
	);
	if ($arParams['DISPLAY_COMPARE'])
	{
		$arJSParams['COMPARE'] = array(
			'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
			'COMPARE_PATH' => $arParams['COMPARE_PATH']
		);
	}
}
else
{
	if ($arResult['MIN_PRICE']['DISCOUNT_VALUE'] != $arResult['MIN_PRICE']['VALUE'])
	{
		$arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'];
		$arResult['MIN_BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arResult['MIN_BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'];
	}
	$arJSParams = array(
		'CONFIG' => array(
			'USE_CATALOG' => $arResult['CATALOG'],
			'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
			'SHOW_PRICE' => (isset($arResult['MIN_PRICE']) && !empty($arResult['MIN_PRICE']) && is_array($arResult['MIN_PRICE'])),
			'SHOW_DISCOUNT_PERCENT' => ($arParams['SHOW_DISCOUNT_PERCENT'] == 'Y'),
			'SHOW_OLD_PRICE' => ($arParams['SHOW_OLD_PRICE'] == 'Y'),
			'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
			'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
			'SHOW_BASIS_PRICE' => ($arParams['SHOW_BASIS_PRICE'] == 'Y'),
			'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
			'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y')
		),
		'VISUAL' => array(
			'ID' => $arItemIDs['ID'],
		),
		'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
		'PRODUCT' => array(
			'ID' => $arResult['ID'],
			'PICT' => $arFirstPhoto,
			'NAME' => $arResult['~NAME'],
			'SUBSCRIPTION' => true,
			'PRICE' => $arResult['MIN_PRICE'],
			'BASIS_PRICE' => $arResult['MIN_BASIS_PRICE'],
			'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
			'SLIDER' => $arResult['MORE_PHOTO'],
			'CAN_BUY' => $arResult['CAN_BUY'],
			'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
			'QUANTITY_FLOAT' => is_double($arResult['CATALOG_MEASURE_RATIO']),
			'MAX_QUANTITY' => $arResult['CATALOG_QUANTITY'],
			'STEP_QUANTITY' => $arResult['CATALOG_MEASURE_RATIO'],
		),
		'BASKET' => array(
			'ADD_PROPS' => ($arParams['ADD_PROPERTIES_TO_BASKET'] == 'Y'),
			'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
			'EMPTY_PROPS' => $emptyProductProperties,
			'BASKET_URL' => $arParams['BASKET_URL'],
			'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
			'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
		)
	);
	if ($arParams['DISPLAY_COMPARE'])
	{
		$arJSParams['COMPARE'] = array(
			'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
			'COMPARE_PATH' => $arParams['COMPARE_PATH']
		);
	}
	unset($emptyProductProperties);
}
?>
<script type="text/javascript">
var <? echo $strObName; ?> = new JCCatalogElement(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
</script>