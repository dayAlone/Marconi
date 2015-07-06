<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arUrls = Array(
	"delete" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=delete&id=#ID#",
	"delay" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=delay&id=#ID#",
	"add" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=add&id=#ID#",
);

$remove = array();
foreach ($arResult['SIZES'] as $i)
	$remove[] = "/(\s(".$i.")|_".strtolower(str_replace(array(',', '.'), '_', $i)).")$/";
$arResult['QUANTITY'] = 0;

function basketItem($item, $arResult, $showSale)
{
	?>
	<div class="basket__item <?=($item['SMALL']?"basket__item--small":"")?>" data-id="<?=$item['ID']?>" data-discount="<?=$item['DISCOUNT_PRICE_PERCENT']?>">
      <div class="row">
        <div class="col-xs-4 left">
        	<a href="/catalog/<?=$section['CODE']?>/<?=preg_replace($remove, '', $item['CATALOG']['CODE'])?>/"><div style="background-image: url(<?=($item['PREVIEW_PICTURE_SRC']?$item['PREVIEW_PICTURE_SRC']:'/layout/images/no-image.jpg')?>)" class="basket__picture <?=($item['PREVIEW_PICTURE_SRC']?'':'basket__picture--no-image')?>"></div></a>
			<div class="basket__name">
				<?if(strlen($item['PROPERTY_NOTE_SHORT_VALUE']) > 0):?>
					<?=$item['PROPERTY_NOTE_SHORT_VALUE']?>
					<br>
				<?endif;?>
				<?if(strlen($arResult['BRANDS'][$item['PROPERTY_BRAND_VALUE']]) > 0):?>
					<span class='basket__brand'><?=$arResult['BRANDS'][$item['PROPERTY_BRAND_VALUE']]?></span><br>
				<?else:?>
					<span class='basket__brand'><?=$item['NAME']?></span><br>
				<?endif;?>
				<?if(SITE_ID=='s1' && strlen($arResult['BRANDS'][$item['PROPERTY_BRAND_VALUE']]) > 0):?>
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
        <div class="col-md-<?=(SITE_ID=='s1'?"2":"1")?> visible-md visible-lg">
        	<span class="basket__artnumber">
        		<?=$item['PROPERTY_ARTNUMBER_VALUE']?> <?=(SITE_ID=='s2' && strlen($arResult['BRANDS'][$item['PROPERTY_BRAND_VALUE']]) > 0?$item['NAME']:"")?>
        	</span>
        </div>
        <?if(SITE_ID=='s2'):?>
        <div class="col-md-1 visible-md visible-lg">
        	<?if(isset($item['TYPE'])):?>
        	<span class="basket__section">
            	<a href="/catalog/<?=$item['TYPE']['CODE']?>/">
            		<?=$item['TYPE']['NAME']?>
            	</a>
        	</span>
        	<?endif;?>
        </div>
        <?endif;?>
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
          <input value="<?=$item['QUANTITY']?>" class="basket__count" data-id="<?=$item['ID']?>" data-price="<?=$item['PRICE']?>">
        </div>
        <div class="col-md-3">
        	<nobr>
        	<strong><span class="total"><?=number_format($item['QUANTITY']*$item['PRICE'], 0, ' ', ' ')?></span> ₷</strong>
			<a href="#" class="basket__delete" data-id="<?=$item['ID']?>">
				<?=svg('close')?>
			</a>
			</nobr>
        </div>
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
	<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form <?=($arResult['DISCOUNT_PRICE_ALL']==0?"basket--no-sale":".basket__frame--sale")?>" id="basket_form">
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
			            <div class="col-md-<?=(SITE_ID=='s1'?"2":"1")?> visible-md visible-lg">Артикул</div>
			            <?if(SITE_ID=='s2'):?>
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
			        	$section = $arResult['SECTIONS'][$item['CATALOG']['SECTION_ID'][0]];
			        	$arResult['QUANTITY'] += $item['QUANTITY'];
			        	if(isset($arResult['SETS'][$item['PRODUCT_ID']])):?>
			        	<div class="basket__set">
			        	<?endif;
			        	basketItem($item, $arResult, $showSale);
			        	if(isset($arResult['SETS'][$item['PRODUCT_ID']])):
			    			foreach ($arResult['SETS'][$item['PRODUCT_ID']]['ITEMS'] as $key => $value) {
			    				$value['SMALL'] = true;
			    				basketItem($value, $arResult, $showSale);
			    			}
			    			?>
			        	</div>
			        	<?endif;
				    endforeach;?>
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