<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arUrls = Array(
	"delete" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=delete&id=#ID#",
	"delay" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=delay&id=#ID#",
	"add" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=add&id=#ID#",
);
global $remove, $showSale;
$remove = array();
foreach ($arResult['SIZES'] as $i)
	$remove[] = "/(\s(".$i.")|_".strtolower(str_replace(array(',', '.'), '_', $i)).")$/";
$arResult['QUANTITY'] = 0;

function basketItem($item, $arResult)
{
	global $remove, $showSale;
	if(isset($item['CATALOG']['SECTION_ID'][0])):
		$section = $arResult['SECTIONS'][$item['CATALOG']['SECTION_ID'][0]];
	else:
		$section = $arResult['SECTIONS'][$item['SECTIONS'][0]];
	endif;
	?>
	<div class="basket__item <?=($item['SET_TITLE']?"basket__item--set-title":"")?> <?=($item['SMALL']?"basket__item--small":"")?>" data-id="<?=$item['ID']?>" data-discount="<?=$item['DISCOUNT_PRICE_PERCENT']?>">
      <div class="row">
        <div class="col-xs-4 left">
        	<a href="/catalog/<?=$section['CODE']?>/<?=preg_replace($remove, '', ($item['CATALOG']['CODE']?$item['CATALOG']['CODE']:$item['CODE']))?>/"><div style="background-image: url(<?=($item['PREVIEW_PICTURE_SRC']?$item['PREVIEW_PICTURE_SRC']:'/layout/images/no-image.jpg')?>)" class="basket__picture <?=($item['PREVIEW_PICTURE_SRC']?'':'basket__picture--no-image')?>"></div></a>
			<div class="basket__name">
				<?
				if(strlen($item['PROPERTY_NOTE_SHORT_VALUE']) > 0 && $item['PROPERTY_NOTE_SHORT_VALUE'] != $item['NAME']):?>
					<?=$item['PROPERTY_NOTE_SHORT_VALUE']?>
					<br>
				<?endif;?>
				<?if(strlen($item['PROPERTY_BRAND_VALUE']) > 1):?>
					<span class='basket__brand'><?=$arResult['BRANDS'][$item['PROPERTY_BRAND_VALUE']]?></span><br>
				<?else:?>
					<span class='basket__brand'><?=$item['NAME']?></span><br>
				<?endif;?>
				<?if(SITE_ID=='s1' && strlen($item['PROPERTY_BRAND_VALUE']) > 1):?>
					<?=$item['NAME']?>
				<?endif;
				?>
				<div class="hidden-md hidden-lg">
        			<?=$item['PROPERTY_ARTNUMBER_VALUE']?> <?=(SITE_ID=='s2' && strlen($arResult['BRANDS'][$item['PROPERTY_BRAND_VALUE']]) > 0?$item['NAME']:"")?>
				</div>
				<?if(isset($arResult['SETS'][$item['PRODUCT_ID']])):?>
					<div class="basket__badge">
						<?=($arResult['SETS'][$item['PRODUCT_ID']]['TYPE'] == CCatalogProductSet::TYPE_GROUP ? "Разделяемый" : "Неразделяемый")?> комплект
					</div>
				<?endif;?>
			</div>
        </div>
        <div class="col-md-<?=(SITE_ID=='s1' || !$arResult['SHOW_TYPE']?"2":"1")?> visible-md visible-lg">
        	<span class="basket__artnumber">
        		<?=$item['PROPERTY_ARTNUMBER_VALUE']?> <?=(SITE_ID=='s2' && strlen($arResult['BRANDS'][$item['PROPERTY_BRAND_VALUE']]) > 0?$item['NAME']:"")?>
        	</span>
        </div>
        <?if(SITE_ID=='s2' && $arResult['SHOW_TYPE']):?>
        <div class="col-md-1 visible-md visible-lg">
        	<?if(is_array($item['TYPE'])):?>
        	<span class="basket__section">
            	<a href="/catalog/<?=$item['TYPE']['CODE']?>/">
            		<?=$item['TYPE']['NAME']?>
            	</a>
        	</span>
        	<?endif;?>
        </div>
        <?endif;
        ?>
        <?if($arResult['SETS'][$item['PRODUCT_ID']]['TYPE'] == CCatalogProductSet::TYPE_GROUP):?>

	    	<div class="<?=($showSale?'col-xs-2 col-md-1':'col-xs-3 col-md-3')?>"></div>
	    	<? if($showSale): ?><div class="col-xs-2 col-md-1"></div><?endif;?>
	    	<div class="col-xs-2 col-md-1"></div>
	    	<div class="col-md-3">
	        	<nobr>
		        	<strong><span class="total"><?=number_format($arResult['SETS'][$item['PRODUCT_ID']]['TOTAL'], 0, ' ', ' ')?></span> ₷</strong>
					<a href="#" class="basket__delete" data-id='<?=json_encode(array_merge(array(intval($item['ID'])), array_keys($arResult['SETS'][$item['PRODUCT_ID']]['ITEMS'])))?>'>
						<?=svg('close')?>
					</a>
				</nobr>
	        </div>
	    <?elseif(in_array(intval($item['ID']), array_keys($arResult['SETS']['LOCKED']))):?>
	    	<div class="<?=($showSale?'col-xs-2 col-md-1':'col-xs-3 col-md-3')?>">
				<nobr>
	        		<strong class="sale" data-value="<?=$item['DISCOUNT_PRICE']?>">
	        			<?=number_format($item['PRICE']['PRICE'], 0, ' ', ' ')?> ₷
	        		</strong>
	        	</nobr>
	    	</div>
	    	<? if($showSale): ?><div class="col-xs-2 col-md-1"></div><?endif;?>
	    	<div class="col-xs-2 col-md-1">
	    		<span class="basket__text-count" data-price="<?=$item['PRICE']['PRICE']?>"><?=$item['QUANTITY']?></span></div>
	    	<div class="col-md-3">
	        	<nobr>
		        	<strong><span class="total"><?=number_format($item['PRICE']['PRICE']*$item['QUANTITY'], 0, ' ', ' ')?></span> ₷</strong>
				</nobr>
	        </div>
	    <?else:?>
	    	<div class="<?=($showSale?'col-xs-2 col-md-1':'col-xs-3 col-md-3')?>">
	        	<nobr>
	        		<strong class="sale" data-value="<?=$item['DISCOUNT_PRICE']?>">
	        			<?=number_format($item['FULL_PRICE'], 0, ' ', ' ')?> ₷
	        		</strong>
	        	</nobr>
	        </div>
	        <? if($showSale): ?>
	        <div class="col-xs-2 col-md-1">
	        	<strong class="sale-value"><?=round($item["DISCOUNT_PRICE_PERCENT_FORMATED"])?> %</strong>
	        </div>
			<?endif;?>
	        <div class="col-xs-2 col-md-1">

	          <input value="<?=$item['QUANTITY']?>" class="basket__count <?if($item['CATALOG']['PROPERTIES']['SHOWCASE']['VALUE'] == "Y"):?>hidden<?endif;?>" data-id="<?=$item['ID']?>" data-price="<?=$item['PRICE']?>">

	        </div>
	        <div class="col-md-3">
	        	<nobr>
		        	<strong><span class="total"><?=number_format($item['QUANTITY']*$item['PRICE'], 0, ' ', ' ')?></span> ₷</strong>
					<a href="#" class="basket__delete" data-id="<?=$item['ID']?>">
						<?=svg('close')?>
					</a>
				</nobr>
	        </div>
		<?endif;?>

      </div>
    </div>
	<?
}
if (strlen($arResult["ERROR_MESSAGE"]) <= 0)
{
	?>
	<div id="warning_message">
		<?
		if (is_array($arResult["WARNING_MESSAGE"]) && !empty($arResult["WARNING_MESSAGE"]))
		{
			foreach ($arResult["WARNING_MESSAGE"] as $v)
				echo ShowError($v);
		}
		?>
	</div>
	<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form"  class="<?=(!$arResult['SHOW_TYPE']?"basket__frame--no-type":"")?> <?=($arResult['DISCOUNT_PRICE_ALL']==0?"basket--no-sale":"basket__frame--sale")?>" id="basket_form">
		<div id="basket_form_container">
			<div class="bx_ordercart">
				<?
				$showSale = false;
				foreach($arResult['GRID']['ROWS'] as $item)
					if($item["DISCOUNT_PRICE_PERCENT"] > 0)
						$showSale = true;
				?>
				<div data-text="КОРЗИНА покупателя" class="basket__frame <?=($showSale?'':'basket__frame--no-sale')?>">
			        <div class="basket__title">
			          <div class="row">
			            <div class="col-xs-4">Наименование</div>
			            <div class="col-md-<?=(SITE_ID=='s1' || !$arResult['SHOW_TYPE']?"2":"1")?> visible-md visible-lg">Артикул</div>
			            <?if(SITE_ID=='s2' && $arResult['SHOW_TYPE']):?>
			            <div class="col-md-1 visible-md visible-lg">Раздел</div>
			            <?endif;?>
			            <div class="<?=($showSale?'col-xs-2 col-md-1':'col-xs-3 col-md-3')?>">Цена</div>
			            <?=($showSale?'<div class="col-xs-2 col-md-1">скидка</div>':'')?>
			            <div class="col-xs-2 col-md-1">Кол-во</div>
			            <div class="col-xs-2 col-md-3">Сумма <span class="visible-md-inline visible-lg-inline"><?=($showSale?'СО СКИДКОЙ':'')?></span></div>
			          </div>
			        </div>
			        <?
			        $sections = array();
			        foreach($arResult['GRID']['ROWS'] as $item):
			        	if(isset($arResult['SETS'][$item['PRODUCT_ID']])):?>
			        	<div class="basket__set <?=($arResult['SETS'][$item['PRODUCT_ID']]['TYPE'] == CCatalogProductSet::TYPE_GROUP?"basket__set--calc":"basket__set--no-calc")?>">
			        	<?
			        	$item['SET_TITLE'] = true;
			        	endif;
			        	basketItem($item, $arResult);
			        	if(isset($arResult['SETS'][$item['PRODUCT_ID']])):
			    			foreach ($arResult['SETS'][$item['PRODUCT_ID']]['ITEMS'] as $key => $value) {
			    				$value['SMALL'] = true;
			    				basketItem($value, $arResult);
			    				$arResult['QUANTITY'] += $value['QUANTITY'];
			    			}
			    			?>
			        	</div>
			        	<?
			        	else:
			        		$arResult['QUANTITY'] += $item['QUANTITY'];
			        	endif;
				    endforeach;?>
					<?if(SITE_ID == 's1' && strtotime('10.11.2016') > time()):?>
					<div class="basket__item  " data-id="332" data-discount="10">
					</div>
					<? endif;?>
					<div class="basket__footer">
			          <div class="row">
			          	<div class="basket__coupon-frame col-xs-6 col-md-7">
			          		<?if(SITE_ID == 's1'):
			          		$coupon = $arResult['COUPON_LIST'][0]['COUPON'];
							if(strlen($coupon) == 0 && $USER->IsAuthorized()):
								$rsUser = CUser::GetByID($USER->GetByID());
								$arUser = $rsUser->Fetch();
								$coupon = $arUser['UF_VIP'];
							endif;
			          		?>
			          		<label class="basket__coupon-label"><span>VIP-карта:</span></label>
			          		<input type="text" <?=(strlen($coupon)>0?"disabled value='".$coupon."'":"") ?> class="basket__coupon" name="COUPON">
			          		<a href="#" class="basket__coupon-trigger <?=(strlen($coupon)>0?"basket__coupon-trigger--disabled value='".$coupon."'":"") ?>">Пересчитать</a>
			          		<?endif;?>
			          	</div>
			            <div class="col-xs-2 col-md-1 center">
			            	<? if($arResult['DISCOUNT_PRICE_ALL']): ?>
			            	<strong data-text="скидка:" class="basket__sale-total"><span><?=number_format($arResult['DISCOUNT_PRICE_ALL'], 0, ' ', ' ')?></span> ₷</strong>
			            	<? endif;?>
			            </div>
			            <div class="col-md-<?=($arResult['DISCOUNT_PRICE_ALL'] && SITE_ID=='s2'?"2":"1")?> col-xs-2 col-md-offset-1 center">
			            <?if(SITE_ID == 's2'):?>
			            	<strong data-text="товаров:" class="basket__count-total"><span><?=$arResult['QUANTITY']?></span></strong>
			            <?endif;?>
			            </div>
			            <div class="col-xs-2 col-md-3 center"><strong data-text="итого:" class="basket__total"><nobr><span><?=number_format($arResult['allSum'], 0, ' ', ' ')?></span> ₷</strong></nobr></div>
			          </div>
			        </div>
			      </div>
			</div>
		</div>
		<input type="hidden" name="BasketOrder" value="BasketOrder" />
		<!-- <input type="hidden" name="ajax_post" id="ajax_post" value="Y"> -->
	</form>
	<?
}
else
{
	ShowError($arResult["ERROR_MESSAGE"]);
}
?>
