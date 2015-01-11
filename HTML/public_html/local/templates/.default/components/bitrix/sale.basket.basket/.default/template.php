<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arUrls = Array(
	"delete" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=delete&id=#ID#",
	"delay" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=delay&id=#ID#",
	"add" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=add&id=#ID#",
);

$brands = getHighloadElements('brands', 'UF_XML_ID', 'UF_NAME');

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
			            <div class="col-xs-2">Артикул</div>
			            <div class="<?=($showSale?'col-xs-1':'col-xs-3')?>">Цена</div>
			            <?=($showSale?'<div class="col-xs-1">скидка</div>':'')?>
			            <div class="col-xs-1">Кол-во</div>
			            <div class="col-xs-3">Сумма <?=($showSale?'СО СКИДКОЙ':'')?></div>
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
			        ?>
			        <div class="basket__item" data-id="<?=$item['ID']?>">
			          <div class="row">
			            <div class="col-xs-4 left">
			              <a href="/catalog/<?=$section['CODE']?>/<?=$item['CATALOG']['CODE']?>/"><div style="background-image: url(<?=($item['PREVIEW_PICTURE_SRC']?$item['PREVIEW_PICTURE_SRC']:'/layout/images/no-image.jpg')?>)" class="basket__picture"></div></a>
			              <div class="basket__name"><?=str_replace($brands[$item['PROPERTY_BRAND_VALUE']], "<br><span class='basket__brand'>".$brands[$item['PROPERTY_BRAND_VALUE']]."</span><br>", $item['NAME'])?></div>
			            </div>
			            <div class="col-xs-2"><?=$item['PROPERTY_ARTNUMBER_VALUE']?></div>
			            <div class="<?=($showSale?'col-xs-1':'col-xs-3')?>"> <strong class="sale" data-value="<?=$item['DISCOUNT_PRICE']?>"><?=number_format($item['FULL_PRICE'], 0, ' ', ' ')?> ₷</strong></div>
			            <?=($showSale?'<div class="col-xs-1"> <strong>'.$item["DISCOUNT_PRICE_PERCENT_FORMATED"].'</strong></div>':'')?>
			            <div class="col-xs-1"> 
			              <input value="<?=$item['QUANTITY']?>" class="basket__count" data-id="<?=$item['ID']?>" data-price="<?=$item['PRICE']?>">
			            </div>
			            <div class="col-xs-3">
			            	<strong><span class="total"><?=number_format($item['QUANTITY']*$item['PRICE'], 0, ' ', ' ')?></span> ₷</strong>
							<a href="#" class="basket__delete" data-id="<?=$item['ID']?>">
								<?=svg('close')?>
							</a>
			            </div>
			          </div>
			        </div>
			    	<? endforeach;?>
			        <div class="basket__footer">
			          <div class="row">
			            <div class="col-xs-1 col-xs-offset-7 center">
			            	<? if($arResult['DISCOUNT_PRICE_ALL']): ?>
			            	<strong data-text="скидка:" class="basket__sale-total"><span><?=number_format($arResult['DISCOUNT_PRICE_ALL'], 0, ' ', ' ')?></span> ₷</strong>
			            	<? endif;?>
			            </div>
			            <div class="col-xs-1"></div>
			            <div class="col-xs-3 center"><strong data-text="итого:" class="basket__total"><span><?=number_format($arResult['allSum'], 0, ' ', ' ')?></span> ₷</strong></div>
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