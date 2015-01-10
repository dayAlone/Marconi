<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arUrls = Array(
	"delete" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=delete&id=#ID#",
	"delay" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=delay&id=#ID#",
	"add" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=add&id=#ID#",
);
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
			            <div class="col-xs-3">Сумма СО СКИДКОЙ</div>
			          </div>
			        </div>
			        <? 
			        foreach($arResult['GRID']['ROWS'] as $item): 
			        	#var_dump($item);
			        ?>
			        <div class="basket__item">
			          <div class="row">
			            <div class="col-xs-4 left">
			              <div style="background-image: url(<?=($item['PREVIEW_PICTURE_SRC']?$item['PREVIEW_PICTURE_SRC']:'/layout/images/no-image.jpg')?>)" class="basket__picture"></div>
			              <div class="basket__name">Сумка женская <br><span class="basket__brand">francesco marconi <br></span>pelle arancione</div>
			            </div>
			            <div class="col-xs-2"><?=$item['PROPERTY_ARTNUMBER_VALUE']?></div>
			            <div class="<?=($showSale?'col-xs-1':'col-xs-3')?>"> <strong><?=number_format($item['PRICE'], 0, ' ', ' ')?> ₷</strong></div>
			            <?=($showSale?'<div class="col-xs-1"> <strong>'.$item["DISCOUNT_PRICE_PERCENT_FORMATED"].'</strong></div>':'')?>
			            <div class="col-xs-1"> 
			              <input value="1" class="basket__count">
			            </div>
			            <div class="col-xs-3"> <strong><?=number_format($item['FULL_PRICE'], 0, ' ', ' ')?> ₷</strong></div>
			          </div>
			        </div>
			    	<? endforeach;?>
			        <div class="basket__footer">
			          <div class="row">
			            <div class="col-xs-1 col-xs-offset-7 center">
			            	<? if($arResult['DISCOUNT_PRICE_ALL']): ?>
			            	<strong data-text="скидка:" class="basket__sale-total"><?=number_format($arResult['DISCOUNT_PRICE_ALL'], 0, ' ', ' ')?> ₷</strong>
			            	<? endif;?>
			            </div>
			            <div class="col-xs-1"></div>
			            <div class="col-xs-3 center"><strong data-text="итого:" class="basket__total"><?=number_format($arResult['allSum'], 0, ' ', ' ')?> ₷</strong></div>
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