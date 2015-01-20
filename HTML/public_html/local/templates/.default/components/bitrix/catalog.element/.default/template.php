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
$this->SetViewTarget('toolbar');
?>
<div class="breadcrumbs">
	<a class="breadcrumbs__brand" href="#" data-href="/catalog/<?=$item['SECTIONS'][0]['CODE']?>/" data-value="<?=$props['BRAND']['VALUE']?>"><?=$arResult['BRANDS'][$props['BRAND']['VALUE']]?></a>
	<span>&rsaquo;</span>
	<a href="/catalog/<?=$item['SECTIONS'][0]['CODE']?>/"><?=$item['SECTIONS'][0]['NAME']?></a>
	<?
		$category = $props['SECTION_'.$arResult['CATEGORIES'][$item['SECTIONS'][1]['XML_ID']]];
		$data = getFilterStringValues($category['ID'], $item['SECTION']['PATH'][0]['ID']);
	?>
	<span>&rsaquo;</span>
	<a href="/catalog/<?=$item['SECTION']['PATH'][0]['CODE']?>/?<?=$data?>&set_filter=Y"><?=$category['NAME']?></a>
	<span>&rsaquo;</span>
	<a href="/catalog/<?=$item['SECTION']['PATH'][0]['CODE']?>/?arrFilter_<?=$category['ID']?>_<?=abs(crc32($category['VALUE']))?>=Y&set_filter=Y"><?=$arResult['TYPES'][$category['VALUE']]?></a>
</div>
<?
$this->EndViewTarget();
?>
<div class="row">
	<div class="col-xs-6">
	  <div class="picture">
	    <div class="row">
	      <div class="<?=(count($item['IMAGES'])>1?"col-xs-9 col-lg-10 no-position":"col-xs-12")?>">
	      	<? if(count($item['IMAGES'])>0 || isset($item['PREVIEW_PICTURE']['SRC'])):
	      		$array = array_values($item['IMAGES']);?>	
	        	<div style="background-image:url(<?=(isset($array[0]['middle'])?$array[0]['middle']:(isset($item['PREVIEW_PICTURE']['SMALL'])?$item['PREVIEW_PICTURE']['SMALL']:"/layout/images/no-image.jpg"))?>)" class="picture__big">
					<a href="<?=(isset($array[0]['src'])?$array[0]['src']:(isset($item['PREVIEW_PICTURE']['SRC'])?$item['PREVIEW_PICTURE']['SRC']:"/layout/images/no-image.jpg"))?>">
				        <img src="<?=(isset($array[0]['middle'])?$array[0]['middle']:(isset($item['PREVIEW_PICTURE']['SMALL'])?$item['PREVIEW_PICTURE']['SMALL']:"/layout/images/no-image.jpg"))?>" alt="" />
				    </a>
	        	</div>
	        	<a data-pictures='<?=(count($item['IMAGES'])>0?json_encode($item['IMAGES']):json_encode(array(0=>array('src'=>$item['PREVIEW_PICTURE']['SRC'], 'w'=> $item['PREVIEW_PICTURE']['WIDTH'], 'h'=>$item['PREVIEW_PICTURE']['HEIGHT']))))?>' class="picture__zoom"><?=svg('zoom')?></a>
	        <? endif;?>
	      </div>
	      <? if(count($item['IMAGES'])>1):?>
	      <div class="col-xs-3 col-lg-2">
	      <? 
	      $i=0;
	      foreach ($item['IMAGES'] as $key => $image): ?>
	      	<a style="background-image:url(<?=$image['small']?>)" href="<?=$image['src']?>" data-middle="<?=$image['middle']?>" class="picture__small <?=($key==0?"picture__small--active":"")?>"></a>
	      <? 
	      	$i++;
	      	if ($i>4)
	      		break;
	      endforeach; ?>
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
						"CACHE_FILTER"  => "Y",
						"SORT_BY1"      => "ID",
						"SORT_ORDER1"   => "ASC",
						"DETAIL_URL"    => "/catalog/",
						"CACHE_TYPE"    => "A",
						'PROPERTY_CODE' => array('PICTURES'),
						"SET_TITLE"     => "N"
					),
					$component
				);
				if(isset($arResult['MIN_PRICE']['VALUE'])):
					if(count($item['OFFERS'])>1):
						?>
						<div class="sizes">
							<span class="sizes__title">
								выберите размер
							</span>
							<span class="dropdown" data-id="<?=$item['OFFERS'][0]['ID']?>">
								<a href="#" class="dropdown__trigger"><span class="dropdown__text dropdown__text--white"><?=$item['SIZES'][$item['OFFERS'][0]['PROPERTIES']['SIZE']['VALUE']]?></span><?=svg('arrow')?></a>
									
								<span class="dropdown__frame">
									<?foreach($item['OFFERS'] as $k=>$offer):?>
									<a href="#" data-id="<?=$offer['ID']?>" data-price="<?=$offer['MIN_PRICE']['VALUE']?>" class="dropdown__item"><?=$item['SIZES'][$offer['PROPERTIES']['SIZE']['VALUE']]?></a>
									<?endforeach;?>
								</span>
								<select class="dropdown__select">
									<?foreach($item['OFFERS'] as $k=>$offer):?>
									<option value="<?=$offer['ID']?>" data-price="<?=$offer['MIN_PRICE']['VALUE']?>"><?=$item['SIZES'][$offer['PROPERTIES']['SIZE']['VALUE']]?></a>
									<?endforeach;?>
								</select>
							</span>
						</div><?
					elseif(count($item['OFFERS'])==1):
						?>
						<div class="sizes">
							<span class="sizes__title">
								только в размере
							</span>
							<span class="dropdown dropdown--disable" data-id="<?=$item['OFFERS'][0]['ID']?>">
								<a href="#" data-id="<?=$item['OFFERS'][0]['ID']?>" class="dropdown__trigger"><span class="dropdown__text dropdown__text--white"><?=$item['SIZES'][$item['OFFERS'][0]['PROPERTIES']['SIZE']['VALUE']]?></span></a>
							</span>
						</div>
						<?
					endif;
				endif;
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
	        	if(strlen($colors)>0):
	        ?>
	          <div class="props__item">
	            <div class="props__name">цвет</div>
	            <div class="props__value"><?=$colors?></div>
	          </div>
	        <? 
	        	endif;
	        endif;?>
	        <? if(count($props['MATERIAL']['VALUE'])>0):
	        		$materials = "";
	        		foreach ($props['MATERIAL']['VALUE'] as $key=>$color)
	        			$materials .= ($key!=0?" / ":"").$arResult['MATERIALS'][$color];
	        		if(strlen($materials)>0):
	       	?>
	          <div class="props__item">
	            <div class="props__name">материал</div>
	            <div class="props__value"><?=$materials?></div>
	          </div>
	        <? 
	        		endif;
	        endif;?>
	        <? if(strlen($props['SIZE']['VALUE'])>0):?>
	          <div class="props__item">
	            <div class="props__name">размер</div>
	            <div class="props__value"><?=$props['SIZE']['VALUE']?></div>
	          </div>
	        <? endif; ?>
	          <div class="props__item props__item--price">
	            <div class="props__name">цена</div>
	            <div class="props__value">
	            <?
	            if(isset($arResult['MIN_PRICE']['VALUE'])&&intval($arResult['MIN_PRICE']['VALUE'])!=0): ?>
			      <strong><?=number_format($arResult['MIN_PRICE']['VALUE'], 0, '.', ' ')?></strong> ₷
			      <?if(strlen($props['SALE']['VALUE'])>0):?>
			      <div class="product__sale">
			      	<span>Уникальная</span><br><span>цена</span>
			      </div>
			      <?endif;?>
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

	      <?
	      $frame = $this->createFrame()->begin();
			if(isset($arResult['MIN_PRICE']['VALUE'])&&intval($arResult['MIN_PRICE']['VALUE'])!=0): 
				$inCart = false;
				if(in_array($item['ID'],$_SESSION['ELEMENS']))
					$inCart = true;
				foreach ($item['OFFERS'] as $offer)
					if(in_array($offer['ID'], $_SESSION['ELEMENS']))
						$inCart = true;
				if($inCart):?>
	      		<a href="#" class="product__big-button product__big-button--border product__big-button--disabled" data-id="<?=$item['ID']?>">Товар в корзине</a>
	      		<?else:?>
	      		<a href="#" class="product__big-button product__big-button--buy" data-id="<?=$item['ID']?>">В корзину</a>
	      	<? 
	      	endif; 
	      $frame->beginStub();
	      	if(isset($item['MIN_PRICE']['VALUE'])&&intval($item['MIN_PRICE']['VALUE'])!=0): ?>
	      		<a href="#" class="product__big-button product__big-button--buy" data-id="<?=$item['ID']?>">В корзину</a>
	      	<? endif; 
	      	endif; 
	      $frame->end();
	      ?>
	      	<a href="#"  data-id="<?=$item['ID']?>" class="product__big-button product__big-button--simmilar no-margin-right">сравнить</a>
	      </div>
	      <div class="col-lg-6"><a href="#available" data-toggle="modal" data-target="#available" class="product__big-button product__big-button--border">наличие в магазинах</a>
	        <div class="social-likes social-likes_notext"><div class="facebook"></div><div class="twitter"></div><div class="vkontakte"></div><div class="odnoklassniki"></div></div>
	      </div>
	    </div>
	    <div class="tabs">
	      <div class="tabs__title">
	      	<?if(strlen($item['DETAIL_TEXT'])>0):?>
	      		<a href="#description" class="tabs__trigger">описание</a>
	      	<?endif;?>
	      	<?if(strlen(COption::GetOptionString("grain.customsettings","payment"))>0):?>
	      		<a href="#payment" class="tabs__trigger">оплата</a>
	      	<?endif;?>
	      	<?if(strlen(COption::GetOptionString("grain.customsettings","delivery"))>0):?>
	      		<a href="#delivery" class="tabs__trigger">доставка</a>
	      	<?endif;?>
	      	<?if(strlen(COption::GetOptionString("grain.customsettings","refund"))>0):?>
	      		<a href="#refund" class="tabs__trigger">возврат</a>
	      	<?endif;?>
	      	
	      	<a href="#feedback" data-toggle="modal" data-target="#feedback" class="tabs__trigger">обратная связь</a>
	      	
	      </div>
	      <?if(strlen($item['DETAIL_TEXT'])>0):?>
	      <div id="description" class="tabs__content">
	        <p><?=$item['DETAIL_TEXT']?></p>
	      </div>
	      <?endif;?>
	      <?if(strlen(COption::GetOptionString("grain.customsettings","payment"))>0):?>
	      <div id="payment" class="tabs__content">
	        <?=COption::GetOptionString("grain.customsettings","payment")?>
	      </div>
	      <?endif;?>
	      <?if(strlen(COption::GetOptionString("grain.customsettings","delivery"))>0):?>
	      <div id="delivery" class="tabs__content">
	        <?=COption::GetOptionString("grain.customsettings","delivery")?>
	      </div>
	      <?endif;?>
	      <?if(strlen(COption::GetOptionString("grain.customsettings","refund"))>0):?>
	      <div id="refund" class="tabs__content">
	        <?=COption::GetOptionString("grain.customsettings","refund")?>
	      </div>
	      <?endif;?>
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



$this->SetViewTarget('footer');
?>
<div class="modal fade" id="available" tabindex="-1" role="dialog" aria-labelledby="available" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    	<a href="#" class="close" data-dismiss="modal" aria-label="Close"><?=svg('close')?></a>
    	<div class="modal-frame" data-title="где купить">
	    	<div class="row available__row">
	    		<div class="col-xs-3">
	    			<div class="available__picture" style="background-image: url(<?=(isset($array[0]['middle'])?$array[0]['middle']:(isset($item['PREVIEW_PICTURE']['SMALL'])?$item['PREVIEW_PICTURE']['SMALL']:"/layout/images/no-image.jpg"))?>)"></div>	
	    		</div>
	    		<div class="col-xs-9 no-position">
	    			<div class="available__title">
	    				<div class="available__product-name"><?=$item['NAME']?></div>
	    				<div class="available__product-artnumber">Арт. <?=$props['ARTNUMBER']['VALUE']?></div>
	    			</div>	
	    		</div>
	    	</div>
	    	<?
	    	global $arFilter;
        	$arFilter = array('!PROPERTY_STORE' => false);
        	$offers   = array();
        	foreach ($item['OFFERS'] as $offer)
        		$offers[$offer['ID']] = $item['SIZES'][$offer['PROPERTIES']['SIZE']['VALUE']];

        	$APPLICATION->IncludeComponent("bitrix:news.list", "available", 
				array(
					"IBLOCK_ID"     => 6,
					"NEWS_COUNT"    => "9999999",
					"CACHE_NOTES"   => $item['ID'],
					"FILTER_NAME"   => "arFilter",
					"SORT_BY1"      => "ID",
					"SORT_ORDER1"   => "ASC",
					"DETAIL_URL"    => "/catalog/",
					"CACHE_TYPE"    => "A",
					'PROPERTY_CODE' => array('ADDRESS'),
					'OFFERS'        => $offers,
					"SET_TITLE"     => "N"
				),
				$component
			);
			?>
    	</div>
    </div>
  </div>
</div>
<?
$this->EndViewTarget();
?>
<script type="text/javascript">
var <? echo $strObName; ?> = new JCCatalogElement(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
</script>