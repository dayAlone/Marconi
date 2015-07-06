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
global $CITY;
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
	'ID'                 => $strMainID,
	'PICT'               => $strMainID.'_pict',
	'DISCOUNT_PICT_ID'   => $strMainID.'_dsc_pict',
	'STICKER_ID'         => $strMainID.'_sticker',
	'BIG_SLIDER_ID'      => $strMainID.'_big_slider',
	'BIG_IMG_CONT_ID'    => $strMainID.'_bigimg_cont',
	'SLIDER_CONT_ID'     => $strMainID.'_slider_cont',
	'SLIDER_LIST'        => $strMainID.'_slider_list',
	'SLIDER_LEFT'        => $strMainID.'_slider_left',
	'SLIDER_RIGHT'       => $strMainID.'_slider_right',
	'OLD_PRICE'          => $strMainID.'_old_price',
	'PRICE'              => $strMainID.'_price',
	'DISCOUNT_PRICE'     => $strMainID.'_price_discount',
	'SLIDER_CONT_OF_ID'  => $strMainID.'_slider_cont_',
	'SLIDER_LIST_OF_ID'  => $strMainID.'_slider_list_',
	'SLIDER_LEFT_OF_ID'  => $strMainID.'_slider_left_',
	'SLIDER_RIGHT_OF_ID' => $strMainID.'_slider_right_',
	'QUANTITY'           => $strMainID.'_quantity',
	'QUANTITY_DOWN'      => $strMainID.'_quant_down',
	'QUANTITY_UP'        => $strMainID.'_quant_up',
	'QUANTITY_MEASURE'   => $strMainID.'_quant_measure',
	'QUANTITY_LIMIT'     => $strMainID.'_quant_limit',
	'BASIS_PRICE'        => $strMainID.'_basis_price',
	'BUY_LINK'           => $strMainID.'_buy_link',
	'ADD_BASKET_LINK'    => $strMainID.'_add_basket_link',
	'BASKET_ACTIONS'     => $strMainID.'_basket_actions',
	'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
	'COMPARE_LINK'       => $strMainID.'_compare_link',
	'PROP'               => $strMainID.'_prop_',
	'PROP_DIV'           => $strMainID.'_skudiv',
	'DISPLAY_PROP_DIV'   => $strMainID.'_sku_prop',
	'OFFER_GROUP'        => $strMainID.'_set_group_',
	'BASKET_PROP_DIV'    => $strMainID.'_basket_prop',
);
$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
$templateData['JS_OBJ'] = $strObName;
$item = &$arResult;
$props = &$arResult['PROPERTIES'];
if(SITE_ID == 's1'):
	$this->SetViewTarget('toolbar');
	?>
	<div class="breadcrumbs">
		<a class="breadcrumbs__brand" href="#" data-href="/catalog/<?=$item['SECTIONS'][0]['CODE']?>/" data-value="<?=$props['BRAND']['VALUE']?>"><?=$arResult['BRANDS'][$props['BRAND']['VALUE']]?></a>
		<nobr>
		<span>&rsaquo;</span>
		<a href="/catalog/<?=$item['SECTIONS'][0]['CODE']?>/"><?=$item['SECTIONS'][0]['NAME']?></a>
		<?
			$category = $props['SECTION_'.$arResult['CATEGORIES'][$item['SECTIONS'][1]['XML_ID']]];
			$data = getFilterStringValues($category['ID'], $item['SECTION']['PATH'][0]['ID']);
		?>
		</nobr>
		<?if($data):?>
		<nobr>
		<span>&rsaquo;</span>
		<a href="/catalog/<?=$item['SECTION']['PATH'][0]['CODE']?>/?<?=$data?>&set_filter=Y"><?=$category['NAME']?></a>
		</nobr>
		<nobr>
		<span>&rsaquo;</span>
		<a href="/catalog/<?=$item['SECTION']['PATH'][0]['CODE']?>/?arrFilter_<?=$category['ID']?>_<?=abs(crc32($category['VALUE']))?>=Y&set_filter=Y"><?=$arResult['TYPES'][$category['VALUE']]?></a>
		</nobr>
		<?endif;?>
	</div>
	<?
	$this->EndViewTarget();
endif;

?>
<input type="hidden" name="XML_ID" value="<?=$item['XML_ID']?>">
<div class="row">
	<div class="col-md-7 col-lg-6">
	  <div class="picture">
	    <div class="row">
	      <div class="<?=(count($item['IMAGES'])>1?"col-sm-10 col-md-10 no-position":"col-sm-12")?>">
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
	      <div class="col-sm-2 col-md-2">
	      <? 
	      $i=0;
	      foreach ($item['IMAGES'] as $key => $image): 
	      	?><a style="background-image:url(<?=$image['small']?>)" href="<?=$image['src']?>" data-middle="<?=$image['middle']?>" class="picture__small <?=($key==0?"picture__small--active":"")?>"></a><? 
	      	$i++;
	      	if ($i>4)
	      		break;
	      endforeach; ?>
	      </div>
	      <? endif;?>
	    </div>
	  </div>
	</div>
	<div class="col-md-5 col-lg-6">
	  <div class="product__description">
	    <div class="row">
	      <div class="col-sm-6 col-md-12 col-lg-6">
	      <?if(SITE_ID == 's1'):?>
	      	<?if(strlen($arResult['PROPERTIES']['NOTE_SHORT']['VALUE']) > 0):?>
	      		<span class="product__type"><?=$arResult['PROPERTIES']['NOTE_SHORT']['VALUE']?></span>
	      	<?endif;?>
	        <h1 class="product__title">
	        	<?if(strlen($arResult['PROPERTIES']['NOTE_SHORT']['VALUE']) > 0):?>
	        	<?=str_replace($arResult['BRANDS'][$props['BRAND']['VALUE']], $arResult['BRANDS'][$props['BRAND']['VALUE']]. " " . $arResult['PROPERTIES']['ARTNUMBER']['VALUE'], str_replace($arResult['PROPERTIES']['NOTE_SHORT']['VALUE'], '', $arResult['NAME']))?>
	        	<?else:?>
	        		<?=$arResult['NAME']?>
	        	<?endif;?>
	        </h1>
	      <?else:
	      	?>
			<h1 class="product__title no-margin-top">
				<?if(strlen($arResult['PROPERTIES']['NOTE_SHORT']['VALUE']) > 0):?>
					<?=$arResult['PROPERTIES']['NOTE_SHORT']['VALUE']?>
					<?=$arResult['BRANDS'][$props['BRAND']['VALUE']]?>
				<?else:?>
	        		<?=$arResult['NAME']?>
	        	<?endif;?>
			</h1>
	      <?endif;?>
	      <?if(SITE_ID != 's1'):?>
		      <? if($arResult['PROPERTIES']['SHOWCASE']['VALUE'] == 'Y'):?>
		      	<div class="product__badge">Витринный экземпляр</div>
		      <? endif; ?>  	
			  <?if($arResult['SET']):?>
			  	<?if($arResult['SET']['IN_SET']):?>
			    	<div class="product__badge">В составе <?=($arResult['SET']['TYPE'] == CCatalogProductSet::TYPE_GROUP ? "разделяемого" : "неразделяемого")?> комплекта</div>
			    	<a href="<?=(count($arResult['SET']['ITEMS']) == 1 ? array_values($arResult['SET']['ITEMS'])[0]['URL']:"/catalog/?q=+&id=".json_encode(array_keys($arResult['SET']['ITEMS'])))?>" class="product__big-button product__big-button--border product__big-button--bigger product__big-button--set">Посмотреть комплект</a>
				<? else: ?>
		    		<div class="product__badge">
		    			<?=($arResult['SET']['TYPE'] == CCatalogProductSet::TYPE_GROUP ? "Разделяемый" : "Неразделяемый")?> комплект
		    		</div>
			  	<? endif; ?>
			  <? endif; ?>
			  
		  <? endif; ?>
	      <?
	        	global $arFilter;
	        	$arFilter = array('PROPERTY_ARTNUMBER' => $props['ARTNUMBER']['VALUE']);
	        	if(SITE_ID == 's1'):
					$arFilter[] = array(
				        "LOGIC" => "OR",
				        array("=PROPERTY_COMING" => false),
				        array("=PROPERTY_COMING" => "Y", "=PROPERTY_RETAIL" => "Y"),
				        array("=PROPERTY_COMING" => "Y", "=PROPERTY_GENERAL" => "Y")
				    );
				endif;
	        	if(strlen($props['ARTNUMBER']['VALUE'])>0):
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
	        	endif;
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
	      <div class="col-sm-6 col-md-12 col-lg-6">
	        <div class="props">
	        <?
	        $values = array(
				'артикул'  => (
					SITE_ID == 's1' ? $props['ARTNUMBER']['VALUE'] : (
						strlen($props['NOTE_SHORT']['VALUE']) > 0 && strlen($props['ARTNUMBER']['VALUE']) > 0 ? 
						"<strong>".$props['ARTNUMBER']['VALUE'] . " " . str_replace( array($props['NOTE_SHORT']['VALUE'], $arResult['BRANDS'][$props['BRAND']['VALUE']]), '', $item['NAME'])
						: ""
						)
					)."</strong>",
				'бренд'    => $arResult['BRANDS'][$props['BRAND']['VALUE']],
				'линия'    => str_replace('Линия ', '', $arResult['TRADELINES'][$props['TRADELINE']['VALUE']]),
				'цвет'     => $props['COLOR']['VALUE'],
	        	'материал' => $props['MATERIAL']['VALUE'],
	        	'размер'   => $props['SIZE']['VALUE'],
	        	
	        );
	        $i=0;
	        foreach ($values as $title => $text):
	        	if(strlen(strip_tags($text))>0||is_array($text)>0):
	        		if(is_array($text)):
	        			$text = str_replace(array_merge(array_keys($arResult['COLORS']),array_keys($arResult['MATERIALS'])), array_merge(array_values($arResult['COLORS']),array_values($arResult['MATERIALS'])), implode($text, ' / '));
	        		endif;
	        	?>
	        	<div class="props__item <?=($i==0?"props__item--medium":"")?>">
					<div class="props__name"><?=$title?></div>
					<div class="props__value"><span><?=$text?></span></div>
		        </div>
	        	<?
	        	endif;
	        	$i++;
	        endforeach;
	        ?>
	        <?if(SITE_ID == 's1' || isUserAccept()):?>
			<div class="props__item props__item--price">
				<div class="props__name">цена</div>
					<div class="props__value">
					<span>
						<?
						if(!$arResult['NOT_AVAILABLE']): ?>
						  <? if(SITE_ID == 's1'): ?>
						  	<strong><?=number_format($arResult['MIN_PRICE']['VALUE'], 0, '.', ' ')?></strong> ₷
						  <? else: ?>
						  	<? if($arResult['MIN_PRICE']['DISCOUNT_VALUE'] < $arResult['MIN_PRICE']['VALUE'] || $props['SALE']['VALUE']=="77ebb502-85d4-11e4-82e4-0025908101de"): ?>
						  		<?if($props['SALE']['VALUE']=="77ebb502-85d4-11e4-82e4-0025908101de"):?>
						  			<strong><?=number_format($arResult['MIN_PRICE']['VALUE']*.7, 0, '.', ' ')?></strong> ₷
						  		<?else:?>
						  			<strong><?=number_format($arResult['MIN_PRICE']['DISCOUNT_VALUE'], 0, '.', ' ')?></strong> ₷
						  		<? endif;?>
						  		<del><?=number_format($arResult['MIN_PRICE']['VALUE'], 0, '.', ' ')?> ₷</del>
							<? else: ?>
							<strong><?=number_format($arResult['MIN_PRICE']['VALUE'], 0, '.', ' ')?></strong> ₷
							<? endif;?>
						  <? endif;?>
						  
						  <?if($props['SALE']['VALUE']=="77ebb501-85d4-11e4-82e4-0025908101de"):?>
							  <div class="product__sale">
							  	<span>Уникальная</span><br><span>цена</span>
							  </div>
						  <?endif;?>
						<? else: ?>
						  <small><nobr>Товара нет в наличии</nobr></small>
						<? endif; ?>
					</span>
					</div>
				</div>
				<? if(($arResult['MIN_PRICE']['DISCOUNT_VALUE'] < $arResult['MIN_PRICE']['VALUE'] || $props['SALE']['VALUE']=="77ebb502-85d4-11e4-82e4-0025908101de") && SITE_ID != 's1'): ?>
					<div class="props__item props__item--medium">
						<div class="props__name">ваша скидка</div>
						<div class="props__value">
							<span>
								<?if($props['SALE']['VALUE']=="77ebb502-85d4-11e4-82e4-0025908101de"):?>
									<strong>30%</strong>
									<a href="#" class="props__help" data-toggle="tooltip" data-placement="bottom" title="<?=$arResult['TOOLTIP']?>">?</a>
								<?else:?>
									<strong><?=$arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT']?>%</strong>
								<?endif;?>
							</span>
						</div>

					</div>
					
		    	<? endif;?>
		    <?endif;?>
	        </div>

	        
	      </div>
	    </div>
	    <div class="row">
	      <div class="col-lg-6 center-lg">
			  <?
				if(SITE_ID == 's1' || isUserAccept()):
				    $frame = $this->createFrame()->begin();
					if(!$arResult['NOT_AVAILABLE'] && (!$arResult['SET']['IN_SET'] || ($arResult['SET']['IN_SET'] && $arResult['SET']['TYPE'] == CCatalogProductSet::TYPE_GROUP))): 

						if(SITE_ID != 's1'):
							?><div class="product__counter <?=($inCart?"product__counter--disabled":"")?>">
					    		<a href="#" class="product__counter-trigger product__counter-trigger--minus">-</a>
					    		<input type="text" class="product__counter-input" value="1">
					    		<a href="#" class="product__counter-trigger product__counter-trigger--plus">+</a>
					    	</div><?
					    endif;

						if($arResult['inCart']):
							?><a href="#" class="product__big-button product__big-button--border product__big-button--disabled" data-id="<?=$item['ID']?>">Товар в корзине</a><?
						else:
							?><a href="#" class="product__big-button product__big-button--buy" data-id="<?=$item['ID']?>" data-artnumber="<?=$props['ARTNUMBER']['VALUE']?>">В корзину</a><?
						endif;?>
						
					  	<?if(SITE_ID == 's1'):
					  		?><a href="#"  data-id="<?=$item['ID']?>" class="hidden-xs product__big-button product__big-button--simmilar no-margin-right"><?=(in_array($item['ID'],json_decode($_COOKIE['simmilar']))?"удалить":"сравнить")?></a><?
					  	endif;?>
					  	
					  	<script>initBigButton()</script>
						<?
						$frame->beginStub();
							if(isset($item['MIN_PRICE']['VALUE'])&&intval($item['MIN_PRICE']['VALUE'])!=0): ?>
								<a href="#" class="product__big-button product__big-button--buy" data-id="<?=$item['ID']?>">В корзину</a>
								<?if(SITE_ID == 's1'):
									?><a href="#"  data-id="<?=$item['ID']?>" class="hidden-xs product__big-button product__big-button--simmilar no-margin-right">сравнить</a><?endif;
								?>
							<? endif; 
							endif; 
						$frame->end();
		      endif;
		      ?>
	      	
	      </div>
	      <div class="col-lg-6">
	      <?if(SITE_ID == 's1'):?>
			<? if(!isset($CITY['CLOSED'])):?>
				<a href="#available" data-toggle="modal" data-target="#available" class="product__big-button product__big-button--border product__big-button--available">наличие в магазинах</a>
			<?endif;?>
	        <div class="social-likes social-likes_notext"><div class="facebook"></div><div class="twitter"></div><div class="vkontakte"></div><div class="odnoklassniki"></div></div>
	      <?endif;?>
	      </div>
	    </div>
	    <div class="tabs">
	      <div class="tabs__title">
	      	<?if(strlen($item['DETAIL_TEXT'])>0):?>
	      		<a href="#description" class="tabs__trigger">описание</a>
	      	<?endif;?>
	      	<?if(strlen(COption::GetOptionString("grain.customsettings","payment".(SITE_ID!='s1'?"_it":"")))>0):?>
	      		<a href="#payment" class="tabs__trigger">оплата</a>
	      	<?endif;?>
	      	<?if(strlen(COption::GetOptionString("grain.customsettings","delivery".(SITE_ID!='s1'?"_it":"")))>0):?>
	      		<a href="#delivery" class="tabs__trigger">доставка</a>
	      	<?endif;?>
	      	<?if(strlen(COption::GetOptionString("grain.customsettings","refund".(SITE_ID!='s1'?"_it":"")))>0):?>
	      		<a href="#refund" class="tabs__trigger">возврат</a>
	      	<?endif;?>
	      	
	      	<a href="#feedback" data-toggle="modal" data-target="#feedback" class="tabs__trigger">обратная связь</a>
	      	
	      </div>
	      <?if(strlen($item['DETAIL_TEXT'])>0):?>
	      <div id="description" class="tabs__content">
	        <p><?=$item['DETAIL_TEXT']?></p>
	      </div>
	      <?endif;?>
	      <?if(strlen(COption::GetOptionString("grain.customsettings","payment".(SITE_ID!='s1'?"_it":"")))>0):?>
	      <div id="payment" class="tabs__content">
	        <?=COption::GetOptionString("grain.customsettings","payment".(SITE_ID!='s1'?"_it":""))?>
	      </div>
	      <?endif;?>
	      <?if(strlen(COption::GetOptionString("grain.customsettings","delivery".(SITE_ID!='s1'?"_it":"")))>0):?>
	      <div id="delivery" class="tabs__content">
	        <?=COption::GetOptionString("grain.customsettings","delivery".(SITE_ID!='s1'?"_it":""))?>
	      </div>
	      <?endif;?>
	      <?if(strlen(COption::GetOptionString("grain.customsettings","refund".(SITE_ID!='s1'?"_it":"")))>0):?>
	      <div id="refund" class="tabs__content">
	        <?=COption::GetOptionString("grain.customsettings","refund".(SITE_ID!='s1'?"_it":""))?>
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
if(count(array_keys($arResult['SET']['ITEMS'])) > 0):
	global $setFilter;
	$setFilter = array('=ID'=>array_keys($arResult['SET']['ITEMS']));
?>
<div data-title="Состав комплекта" class="catalog__divider catalog__divider--title"></div>
<div class="catalog catalog--full-width catalog--one-line <?=(SITE_ID!='s1'?"catalog--italbags":"")?>">
<?
	$APPLICATION->IncludeComponent(
		"bitrix:catalog.section",
		"",
		array(
			"HIDE_PRICE"                      => $arParams['HIDE_PRICE'],
			"HIDE_SIMMILAR"                   => $arParams['HIDE_SIMMILAR'],
			"HIDE_MORE"                       => $arParams['HIDE_MORE'],
			"SHOW_COUNT"                      => $arParams['SHOW_COUNT'],
			'HIDE_TOOLBAR'                    => "Y",
			"IBLOCK_TYPE"                     => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID"                       => $arParams["IBLOCK_ID"],
			"ELEMENT_SORT_FIELD"              => 'ID',
			"SECTION_ID"                      => "",
			"SECTION_CODE"                    => "all",
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
			"FILTER_NAME"                     => 'setFilter',
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
<?endif;

$this->SetViewTarget('footer');
?>
<div class="modal fade" id="available" tabindex="-1" role="dialog" aria-labelledby="available" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    	<a href="#" class="close" data-dismiss="modal" aria-label="Close"><?=svg('close')?></a>
    	<div class="modal-frame" data-title="где купить">
	    	<div class="row available__row">
	    		<div class="col-sm-4 col-md-3 hidden-xs">
	    			<div class="available__picture" style="background-image: url(<?=(isset($array[0]['middle'])?$array[0]['middle']:(isset($item['PREVIEW_PICTURE']['SMALL'])?$item['PREVIEW_PICTURE']['SMALL']:"/layout/images/no-image.jpg"))?>)"></div>	
	    		</div>
	    		<div class="col-sm-8 col-md-9 no-position">
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
					"CACHE_TYPE"    => "N",
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
$(function (){
	initProduct()
})
</script>