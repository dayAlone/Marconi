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
<form action="<?=$APPLICATION->GetCurPage();?>" method="POST" name="ORDER_FORM" id="ORDER_FORM" enctype="multipart/form-data" data-parsley-validate>
	<?
		if(!empty($arResult["ERROR"]) && $arResult["USER_VALS"]["FINAL_STEP"] == "Y")
			foreach($arResult["ERROR"] as $v)
				echo ShowError($v);
	?>
	<div class="row">
		<div class="col-xs-4">
			<div class="basket__block">
				<div class="basket__block-title">доставка</div>
				<div class="delivery">
					<? 
					$showStores = false;
					$checked    = false;
					foreach ($arResult["DELIVERY"] as $delivery_id => $delivery):
						if(isset($delivery['PROFILES'])):
							foreach ($delivery['PROFILES'] as $profile_id => $arDelivery):
								if($arDelivery["CHECKED"]=="Y")
									$currenDelivery = $delivery_id;
							?>
							<nobr><input type="radio"
								id="ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>"
								name="<?=htmlspecialcharsbx($arDelivery["FIELD_NAME"])?>"
								value="<?=$delivery_id.":".$profile_id;?>" <?if ($arDelivery["CHECKED"]=="Y") echo " checked";?>
								/>
		    				<label for="ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>"><?=$delivery['TITLE']?> <?=($arDelivery["PRICE_FORMATED"]&&$arDelivery["CHECKED"]?$arDelivery["PRICE_FORMATED"]:"")?></label></nobr>
							<? 
							endforeach;
						else:
							if(isset($delivery["STORE"])&&$delivery["CHECKED"]=="Y")
								$showStores = $delivery["STORE"];
							?>
							<nobr><input type="radio"
									id="ID_DELIVERY_ID_<?= $delivery["ID"] ?>"
									name="<?=htmlspecialcharsbx($delivery["FIELD_NAME"])?>"
									value="<?= $delivery["ID"] ?>"<?if ($delivery["CHECKED"]=="Y") echo " checked";?>
									/>
		    				<label for="ID_DELIVERY_ID_<?=$delivery["ID"]?>"><?=$delivery['NAME']?></label></nobr>
							<?
						endif;
					endforeach; ?>
				</div>
				<small><strong>адрес доставки *</strong></small>
				<? foreach ($arResult['ORDER_PROP']['USER_PROPS_N'] as $prop):
					if ($prop["TYPE"] == "LOCATION"):
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
								"ID"                     => ($value>0?$value:$prop['DEFAULT_VALUE']),
								"CODE"                   => "",
								"CACHE_NOTE"             => $arResult["BUYER_STORE"],
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
				 	endif;
				endforeach;?>
				<div class="props">
				<? foreach ($arResult['ORDER_PROP']['RELATED'] as $prop):
					if($prop['CODE']=="index"):
						?>
						<input type="hidden" name="<?=$prop['FIELD_NAME']?>" value="<?=$prop["VALUE"]?>">
					<?
					elseif($prop['CODE']=="date"):
						?>
					<div class="row xs-margin-top">
						<div class="col-xs-6">
							<small><strong><?=$prop['NAME']?></strong></small>
							<input class="date" data-provide="datepicker" readonly data-date-format="dd.mm.yyyy" data-date-start-date="<?=date('d.m.Y', strtotime(date('d.m.Y') . "+1 days"))?>" data-date-language="ru" type="text" name="<?=$prop['FIELD_NAME']?>" value="<?=($prop["VALUE"]?$prop["VALUE"]:date('d.m.Y', strtotime(date('d.m.Y') . "+1 days")))?>" placeholder="<?=$prop['NAME']?><?=($prop['REQUIED']=='Y'?" *":"")?>" <?=($prop['REQUIED']=='Y'?"required":"")?>>
							<div class="blue-arrow"><?=svg('arrow')?></div>
						</div>
					<?
					elseif($prop['CODE']=="time"):
						?>
						<div class="col-xs-6">
							<small><strong><?=$prop['NAME']?></strong></small>
							<input type="hidden" name="<?=$prop['FIELD_NAME']?>" value="<?=$prop["VALUE"]?>">
							<div class="dropdown">
								<a href="#" class="dropdown__trigger"><span class="dropdown__text dropdown__text--white">с 9 до 15 часов</span><?=svg('arrow')?></a>
								<span class="dropdown__frame">
									<a href="#" class="dropdown__item">с 9 до 15 часов</a>
									<a href="#" class="dropdown__item">с 15 до 18 часов</a>
								</span>
							</div>
						</div>
					</div><?
					else:
						?><input class="<?=($prop['SIZE1']==2?"small":"")?>" type="text" name="<?=$prop['FIELD_NAME']?>" placeholder="<?=$prop['NAME']?><?=($prop['REQUIED']=='Y'?" *":"")?>" <?=($prop['REQUIED']=='Y'?"required":"")?> value="<?=$prop["VALUE"]?>"><?
					endif;
					endforeach ?>
				<?if($showStores):
					global $arFilter;
	        		$arFilter = array('PROPERTY_STORE' => $showStores);
					$APPLICATION->IncludeComponent("bitrix:news.list", "stores", 
						array(
							"IBLOCK_ID"     => 6,
							"NEWS_COUNT"    => "9999999",
							"FILTER_NAME"   => "arFilter",
							"SORT_BY1"      => "ID",
							"SORT_ORDER1"   => "ASC",
							"DETAIL_URL"    => "/catalog/",
							"CACHE_TYPE"    => "A",
							'PROPERTY_CODE' => array('STORE'),
							'OFFERS'        => $offers,
							"SET_TITLE"     => "N"
						),
						$component
					);
				endif;?>
				</div>
			</div>
		</div>
		<div class="col-xs-4">
			<div class="basket__block basket__block--profile">
				<div class="basket__block-title">контактная информация</div>
				<div class="row">
				<? foreach ($arResult['ORDER_PROP']['USER_PROPS_Y'] as $prop):?>
					<div class="col-xs-<?=($prop['SIZE1']==6?"6":"12")?>">
						<input type="text" value="<?=$prop["VALUE"]?>" name="<?=$prop['FIELD_NAME']?>" placeholder="<?=$prop['NAME']?><?=($prop['REQUIED']=="Y"?" *":"")?>" <?=($prop['REQUIED']=='Y'?"required":"")?>>
					</div>
				<? endforeach ?>
				</div>
				<textarea name="ORDER_DESCRIPTION" id="ORDER_DESCRIPTION" style="max-width:100%;min-height:120px" placeholder="комментарий к заказу"><?=$arResult["USER_VALS"]["ORDER_DESCRIPTION"]?></textarea>
				<? global $USER;
					if(!$USER->getID()):
				?>
				<input type="checkbox" name="register_user" id="register_user" value="Y" checked="checked"> <label for="register_user">Зарегистрироваться на сайте</label>
			<? endif;?>
			</div>
		</div>
		<div class="col-xs-4 no-position">
			<div class="basket__block">
				<div class="basket__block-title">способы оплаты</div>
				<div class="payment">
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
			<div class="total__frame">
				<div class="total">
					<div class="total__item <?=(intval($arResult['DELIVERY_PRICE'])>0?"":"hidden")?>">
						<div class="row">
							<div class="col-xs-7">ВАШ ЗАКАЗ НА СУММУ</div>
							<div class="col-xs-5 right total__counter"><span id="price-1"><?=number_format($arResult['ORDER_PRICE'], 0, " ", " ")?></span> ₷</div>
						</div>
					</div>
					<div class="total__item <?=(intval($arResult['DELIVERY_PRICE'])>0?"":"hidden")?>">
						<div class="row">
							<div class="col-xs-7">ДОСТАВКА</div>
							<div class="col-xs-5 right total__counter"><span id="price-2"><?=number_format($arResult['DELIVERY_PRICE'], 0, " ", " ")?></span> ₷</div>
						</div>
					</div>
					<div class="total__item total__item--big">
						<div class="row">
							<div class="col-xs-7">к оплате</div>
							<div class="col-xs-5 right total__counter"><span id="price-3"><?=number_format($arResult['ORDER_PRICE']+$arResult['DELIVERY_PRICE'], 0, " ", " ")?></span> ₷</div>
						</div>
					</div>
				</div>
				<input type="submit" class="basket__submit" value="оформить заказ">
			</div>
		</div>
	</div>
	
	<?=bitrix_sessid_post()?>
	<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/person_type.php");?>	
	<input type="hidden" name="confirmorder" id="confirmorder" value="N">
	<input type="hidden" name="profile_change" id="profile_change" value="N">
	<input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
	<input type="hidden" name="json" value="Y">

	
</form>
<?
if($_POST["is_ajax_post"] == "Y")
	die;
?>
