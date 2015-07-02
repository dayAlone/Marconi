<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arUrls = Array(
	"delete" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=delete&id=#ID#",
	"delay" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=delay&id=#ID#",
	"add" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=add&id=#ID#",
);

$brands = getHighloadElements('brands', 'UF_XML_ID', 'UF_NAME');
$sizes  = getHighloadElements('sizes', 'UF_XML_ID', 'UF_NAME');
$remove = array();
foreach ($sizes as $i)
	$remove[] = "/(\s(".$i.")|_".strtolower(str_replace(array(',', '.'), '_', $i)).")$/";
$arResult['QUANTITY'] = 0;
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
	<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form" id="basket_form">
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
			            <div class="col-md-2 visible-md visible-lg">Артикул</div>
			            <div class="<?=($showSale?'col-xs-2 col-md-1':'col-xs-3 col-md-3')?>">Цена</div>
			            <?=($showSale?'<div class="col-xs-2 col-md-1">скидка</div>':'')?>
			            <div class="col-xs-2 col-md-1">Кол-во</div>
			            <div class="col-xs-2 col-md-3">Сумма <span class="visible-md-inline visible-lg-inline"><?=($showSale?'СО СКИДКОЙ':'')?></span></div>
			          </div>
			        </div>
			        <? 
			        $sections = array();
			        foreach($arResult['GRID']['ROWS'] as $item):
			        	if($sections[$item['CATALOG']['SECTION_ID'][0]]):
			        		$section = $sections[$item['CATALOG']['SECTION_ID'][0]];
			        	else:
			        		$section = CIBlockSection::GetByID($item['CATALOG']['SECTION_ID'][0])->Fetch();
			        		$sections[$item['CATALOG']['SECTION_ID'][0]] = $section;
			        	endif;
			        	$arResult['QUANTITY'] += $item['QUANTITY'];
			        	$item['NAME'] = preg_replace("/\s\s/", "", str_replace(array($brands[$item['PROPERTY_BRAND_VALUE']], $item['PROPERTY_NOTE_SHORT_VALUE']), '', $item['NAME']));
			        ?>
			        <div class="basket__item" data-id="<?=$item['ID']?>" data-discount="<?=$item['DISCOUNT_PRICE_PERCENT']?>">
			          <div class="row">
			            <div class="col-xs-4 left">
			              <a href="/catalog/<?=$section['CODE']?>/<?=preg_replace($remove, '', $item['CATALOG']['CODE'])?>/"><div style="background-image: url(<?=($item['PREVIEW_PICTURE_SRC']?$item['PREVIEW_PICTURE_SRC']:'/layout/images/no-image.jpg')?>)" class="basket__picture <?=($item['PREVIEW_PICTURE_SRC']?'':'basket__picture--no-image')?>"></div></a>
			              <div class="basket__name">
							<?=$item['PROPERTY_NOTE_SHORT_VALUE']?>
			              	<br><span class='basket__brand'><?=$brands[$item['PROPERTY_BRAND_VALUE']]?></span><br>
			              	<?=(SITE_ID=='s1'?$item['NAME']:"")?>
			              </div>
			            </div>
			            <div class="col-md-2 visible-md visible-lg">
			            	<?=$item['PROPERTY_ARTNUMBER_VALUE']?> <?=(SITE_ID=='s2'?$item['NAME']:"")?>
			            </div>
			            <div class="<?=($showSale?'col-xs-2 col-md-1':'col-xs-3 col-md-3')?>"><nobr><strong class="sale" data-value="<?=$item['DISCOUNT_PRICE']?>"><?=number_format($item['FULL_PRICE'], 0, ' ', ' ')?> ₷</strong></nobr></div>
			            <?=($showSale?'<div class="col-xs-2 col-md-1"> <strong class="sale-value">'.round($item["DISCOUNT_PRICE_PERCENT_FORMATED"]).'%</strong></div>':'')?>
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
			    	<? endforeach;?>
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
			            <div class="col-md-1 col-xs-2 <? if($arResult['DISCOUNT_PRICE_ALL']): ?>center<? endif;?>">
			            <?if(SITE_ID == 's2'):?>
			            	<strong data-text="кол-во:" class="basket__count-total"><span><?=$arResult['QUANTITY']?></span></strong>
			            <?endif;?>
			            </div>
			            <div class="col-xs-2 col-md-3 xs-right md-center"><strong data-text="итого:" class="basket__total"><span><?=number_format($arResult['allSum'], 0, ' ', ' ')?></span> ₷</strong></div>
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