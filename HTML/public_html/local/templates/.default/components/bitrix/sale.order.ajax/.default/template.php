<?
	//var_dump($arResult);
	if (!function_exists("cmpBySort"))
	{
		function cmpBySort($array1, $array2)
		{
			if (!isset($array1["SORT"]) || !isset($array2["SORT"]))
				return -1;

			if ($array1["SORT"] > $array2["SORT"])
				return 1;

			if ($array1["SORT"] < $array2["SORT"])
				return -1;

			if ($array1["SORT"] == $array2["SORT"])
				return 0;
		}
	}
?>
<form action="<?=$APPLICATION->GetCurPage();?>" method="POST" name="ORDER_FORM" id="ORDER_FORM" enctype="multipart/form-data">
	<div class="row">
		<div class="col-xs-4">
			<div class="basket__block">
				<div class="basket__block-title">доставка</div>
				<small><strong>адрес доставки *</strong></small>
				<? foreach ($arResult['ORDER_PROP']['USER_PROPS_N'] as $prop):
					if ($prop["TYPE"] == "LOCATION")
					{
						$value = 0;
						if (is_array($prop["VARIANTS"]) && count($prop["VARIANTS"]) > 0)
						{
							foreach ($prop["VARIANTS"] as $arVariant)
							{
								if ($arVariant["SELECTED"] == "Y")
								{
									$value = $arVariant["ID"];
									break;
								}
							}
						}
						$APPLICATION->IncludeComponent(
							"bitrix:sale.location.selector.search", 
							".default", 
							array(
								"ID"                     => $value,
								"CODE"                   => "",
								"INPUT_NAME"             => $prop['FIELD_NAME'],
								"PROVIDE_LINK_BY"        => "id",
								"SEARCH_BY_PRIMARY"      => "Y",
								"EXCLUDE_SUBTREE"        => "",
								"FILTER_BY_SITE"         => "Y",
								"SHOW_DEFAULT_LOCATIONS" => "Y",
								"CACHE_TYPE"             => "A",
								"CACHE_TIME"             => "36000000"
							),
							false
						);
					}
					else {
						?><input type="text" name="<?=$prop['FIELD_NAME']?>" placeholder="<?=$prop['NAME']?><?=($prop['REQUIED']?" *":"")?>" <?=($prop['REQUIED']?"required":"")?>><?
					}
					endforeach ?>

			</div>
		</div>
		<div class="col-xs-4">
			<div class="basket__block">
				<div class="basket__block-title">контактная информация</div>
				<? foreach ($arResult['ORDER_PROP']['USER_PROPS_Y'] as $prop):?>
					<input type="text" name="<?=$prop['FIELD_NAME']?>" placeholder="<?=$prop['NAME']?><?=($prop['REQUIED']?" *":"")?>" <?=($prop['REQUIED']?"required":"")?>>
				<? endforeach ?>
				<textarea name="ORDER_DESCRIPTION" id="ORDER_DESCRIPTION" style="max-width:100%;min-height:120px" placeholder="комментарий к заказу"><?=$arResult["USER_VALS"]["ORDER_DESCRIPTION"]?></textarea>
			</div>
		</div>
		<div class="col-xs-4">
			<div class="basket__block">
				<div class="basket__block-title">способы оплаты</div>
				<?
					uasort($arResult["PAY_SYSTEM"], "cmpBySort"); // resort arrays according to SORT value

					foreach($arResult["PAY_SYSTEM"] as $arPaySystem):
							?>
							<input 
								type="radio" name="PAY_SYSTEM_ID" 
								value="<?=$arPaySystem["ID"]?>" id="money-<?=$arPaySystem['ID']?>"
								<?if ($arPaySystem["CHECKED"]=="Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y")) echo " checked=\"checked\"";?>
								>
            				<label for="money-<?=$arPaySystem['ID']?>"><?=$arPaySystem['NAME']?></label><br>
							<?
					endforeach;
				?>
			</div>
		</div>
	</div>
	<?

		if(!empty($arResult["ERROR"]) && $arResult["USER_VALS"]["FINAL_STEP"] == "Y")
				foreach($arResult["ERROR"] as $v)
					echo ShowError($v);

	?>
	<?=bitrix_sessid_post()?>
	<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/person_type.php");?>	
	<input type="hidden" name="confirmorder" id="confirmorder" value="Y">
	<input type="hidden" name="profile_change" id="profile_change" value="N">
	<input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
	<input type="hidden" name="json" value="Y">

	<div class="bx_ordercart_order_pay_center"><a href="javascript:void();" onclick="submitForm('Y'); return false;" id="ORDER_CONFIRM_BUTTON" class="checkout"><?=GetMessage("SOA_TEMPL_BUTTON")?></a></div>
</form>
<?if(CSaleLocation::isLocationProEnabled()):?>

	<div style="display: none">
		<?// we need to have all styles for sale.location.selector.steps, but RestartBuffer() cuts off document head with styles in it?>
		<?$APPLICATION->IncludeComponent(
	"bitrix:sale.location.selector.steps", 
	".default", 
	array(
		"ID" => "",
		"CODE" => "",
		"INPUT_NAME" => "LOCATION",
		"PROVIDE_LINK_BY" => "id",
		"SEARCH_BY_PRIMARY" => "N",
		"FILTER_BY_SITE" => "Y",
		"SHOW_DEFAULT_LOCATIONS" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000"
	),
	false
);?>
		<?$APPLICATION->IncludeComponent(
	"bitrix:sale.location.selector.search", 
	".default", 
	array(
		"ID" => "",
		"CODE" => "",
		"INPUT_NAME" => "LOCATION",
		"PROVIDE_LINK_BY" => "id",
		"SEARCH_BY_PRIMARY" => "Y",
		"EXCLUDE_SUBTREE" => "",
		"FILTER_BY_SITE" => "Y",
		"SHOW_DEFAULT_LOCATIONS" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000"
	),
	false
);?>
	</div>

<?endif?>