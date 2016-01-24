<?
global $CITY;
use Bitrix\Main;
use Bitrix\Main\Loader;
$arResult['QUANTITY'] = 0;
foreach ($arResult['BASKET_ITEMS'] as $item) {
	$arResult['QUANTITY'] += $item['QUANTITY'];
}
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
<form action="<?=$APPLICATION->GetCurPage();?>" method="POST" name="ORDER_FORM" id="ORDER_FORM" enctype="multipart/form-data" data-parsley-validate
	<?if(SITE_ID=='s2' && $APPLICATION->GetPageProperty('showPromotionAlert') != 'notVisible'):?>data-locked='true'<?endif;?>
	>
	<?
		if(!empty($arResult["ERROR"]) && $arResult["USER_VALS"]["FINAL_STEP"] == "Y")
			foreach($arResult["ERROR"] as $v)
				echo ShowError($v);
	?>
	<div class="row">

		<?if(SITE_ID=='s2'):?>
		<div class="col-sm-6 col-md-8">
			<div class="basket__block">
			<textarea name="ORDER_DESCRIPTION" id="ORDER_DESCRIPTION" style="max-width:100%;" placeholder="комментарий к заказу"><?=$arResult["USER_VALS"]["ORDER_DESCRIPTION"]?></textarea>
			</div>
		</div>
		<?endif;?>
		<div class="col-md-4 order__profile col-md-push-4">
			<div class="basket__block basket__block--profile">
				<div class="basket__block-title">контактная информация</div>
				<div class="row">
				<?
				$rsUser = CUser::GetByID($USER->GetID());
				$arUser = $rsUser->Fetch();
				foreach ($arResult['ORDER_PROP']['USER_PROPS_Y'] as $prop):
					switch ($prop['FIELD_NAME']) {
						case 'ORDER_PROP_20':
							$prop["VALUE"] = $arUser['EMAIL'];
							break;
						case 'ORDER_PROP_16':
							$prop["VALUE"] = $arUser['NAME'];
							break;
						case 'ORDER_PROP_18':
							$prop["VALUE"] = $arUser['LAST_NAME'];
							break;
						case 'ORDER_PROP_19':
							$prop["VALUE"] = $arUser['PERSONAL_PHONE'];
							break;
					}
				?>
					<div class="col-xs-<?=($prop['SIZE1']==6?"6":"12")?>">
						<input type="<?=(in_array($prop['FIELD_NAME'], array("ORDER_PROP_4", "ORDER_PROP_20"))?"email":"text")?>" value="<?=$prop["VALUE"]?>" name="<?=$prop['FIELD_NAME']?>" placeholder="<?=$prop['NAME']?><?=($prop['REQUIED']=="Y"?" *":"")?>" <?=($prop['REQUIED']=='Y'?"required":"")?>>
					</div>
					<?if(in_array($prop['FIELD_NAME'], array("ORDER_PROP_4", "ORDER_PROP_20"))):?>
					<div class="col-xs-12">
						<p class="order__login-error">
							<strong>Ваш e-mail уже зарегистрирован на нашем сайте. </strong><br>
							Чтобы оформить заказ, пожалуйста, <a href="#" class="order__change">снимите флаг</a> «Зарегистрироваться на сайте» или <a href="#login" data-toggle="modal" data-target="#login">авторизуйтесь</a>. В случае оформления покупки без авторизации, данный заказ не будет отражен в истории заказов.
						</p>
					</div>
					<?endif;?>
				<? endforeach ?>
				</div>
				<?if(SITE_ID=='s1'):?>
				<textarea name="ORDER_DESCRIPTION" id="ORDER_DESCRIPTION" style="max-width:100%;min-height:120px" placeholder="комментарий к заказу"><?=$arResult["USER_VALS"]["ORDER_DESCRIPTION"]?></textarea>
				<?endif;?>
				<? global $USER;
					if(!$USER->getID()):
				?>
				<input type="checkbox" name="register_user" id="register_user" value="Y" checked="checked"> <label for="register_user">Зарегистрироваться на сайте</label>
			<? endif;?>
			</div>
		</div>
		<div class="col-sm-6 order__delivery col-md-4 col-md-pull-4">
			<div class="basket__block">
				<div class="basket__block-title">доставка</div>
				<div class="delivery">
					<?
					$showStores = false;
					$checked    = false;
					foreach ($arResult["DELIVERY"] as $delivery_id => $delivery):
						if($delivery['ID'] == 6 && SITE_ID != 's1' && $arResult['QUANTITY'] < 30)
							continue;
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

						if(intval($CITY['LOCATION']) == 0) {
							Loader::includeModule('sale');
							include($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/bitrix/sale.location.selector.steps/class.php');
							$_REQUEST['SHOW'] = array(
								'PATH' => '1',
								'TYPE_ID' => '1',
							);
							$_REQUEST['FILTER'] = array(
								'QUERY'      => $CITY['NAME'],
								'EXCLUDE_ID' => '0',
								'SITE_ID'    => 's1',
								'TYPE_ID'    => '3',
							);
							$data = CBitrixLocationSelectorStepsComponent::processSearchRequest();

							if(count($data['ITEMS']) > 0) {$value = $data['ITEMS'][0]['ID'];}
							if(!isset($value)) { $value = $prop['DEFAULT_VALUE'];}
						} else {
							$value = $CITY['LOCATION'];
						}

						$APPLICATION->IncludeComponent(
							"bitrix:sale.location.selector.search",
							".default",
							array(
								"ID"                     => $value,
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
				<?
				uasort($arResult['ORDER_PROP']['RELATED'], "cmpBySort");
				 foreach ($arResult['ORDER_PROP']['RELATED'] as $prop):
				 	switch ($prop['CODE']) {
				 		case "date":
							$startDate = $prop["VALUE"] ? $prop["VALUE"] : date('d.m.Y', strtotime(date('d.m.Y') . "+1 day"));
							if (!$prop["VALUE"] && (strtotime("+1 day") > strtotime('28.12.'.date('Y')) || strtotime("+1 day") < strtotime('11.01.'.date('Y')))) {
								$startDate = '11.01.'.(strtotime("+1 day") < strtotime('11.01.'.date('Y')) ? date('Y') : date('Y', strtotime('+1 year')));
							}
				 			?>
							<div class="row xs-margin-top">
								<div class="col-xs-6">
									<small><strong><?=str_replace(" доставки", "<span class='hidden-xs'> доставки</span>", $prop['NAME'])?></strong></small>
									<input class="date"
										readonly
										data-provide="datepicker"
										data-date-format="dd.mm.yyyy"
										data-date-start-date="<?=$startDate?>"
										data-date-language="ru"
										data-date-dates-disabled='<?
											$dates = array();
											$dates[] = '30.12.'.date('Y');
											$dates[] = '31.12.'.date('Y');
											for ($i=1; $i < 11; $i++) {
												$dates[] = ($i < 10 ? '0' : '' ).$i.'.01.'.date('Y');
												$dates[] = ($i < 10 ? '0' : '' ).$i.'.01.'.date('Y', strtotime('+1 year'));
											}
											echo json_encode($dates);
										?>'
										type="text"
										name="<?=$prop['FIELD_NAME']?>"
										value="<?=$startDate?>"
										placeholder="<?=$prop['NAME']?><?=($prop['REQUIED']=='Y'?" *":"")?>"
										<?=($prop['REQUIED']=='Y'?"required":"")?>>
									<div class="blue-arrow"><?=svg('arrow')?></div>
								</div>
				 			<?
				 			break;
				 		case "time":
				 			?>
								<div class="col-xs-6 time-select">
									<small><strong><?=str_replace(" доставки", "<span class='hidden-xs'> доставки</span>", $prop['NAME'])?></strong></small>
									<div class="dropdown">
										<a href="#" class="dropdown__trigger"><span class="dropdown__text dropdown__text--white">с 12 до 19 часов</span><?=svg('arrow')?></a>
										<input type="hidden" name="<?=$prop['FIELD_NAME']?>" value="<?=(strlen($prop["VALUE"]) > 0 ? $prop["VALUE"]:'с 12 до 19 часов')?>"><!--
										<span class="dropdown__frame">
											<a href="#" class="dropdown__item">с 12 до 19 часов</a>
											<a href="#" class="dropdown__item">с 15 до 18 часов</a>
										</span>
										<select class="dropdown__select">
											<option value="" <?/*=($prop["VALUE"] == "с 12 до 19 часов"?"selected":"")*/?>>с 12 до 19 часов</option>
											<option value="" <?/*=($prop["VALUE"] == "с 15 до 18 часов"?"selected":"")*/?>>с 15 до 18 часов</option>-->
										</select>
									</div>
								</div>
							</div>
				 			<?
				 			break;
				 		case "pickup":
				 			if(isset($_REQUEST['ORDER_PROP_2'])):

					 			$section = findCityByLocation($_REQUEST['ORDER_PROP_2']);
					 			if($section):
						 			$APPLICATION->IncludeComponent("bitrix:news.list", "stores",
										array(
											"IBLOCK_ID"      => 6,
											"NEWS_COUNT"     => "9999999",
											"SORT_BY1"       => "ID",
											"SORT_ORDER1"    => "ASC",
											"DETAIL_URL"     => "/catalog/",
											"PARENT_SECTION" => $section['ID'],
											"CACHE_TYPE"     => "A",
											"CACHE_NOTE"     => rand(),
											'PROPERTY_CODE'  => array('STORE', 'ADDRESS'),
											"SET_TITLE"      => "N",
											"FIELD_NAME"     => $prop['FIELD_NAME']
										),
										$component
									);
						 		endif;
						 	endif;
				 		break;
						case "metro":
							require($_SERVER['DOCUMENT_ROOT'].'/include/metro.php');
							?>
							<div class="dropdown metro-select">
								<input type="text" name="<?=$prop['FIELD_NAME']?>" value="" placeholder="Станция метро" autocomplete="off">
								<span class="dropdown__frame">
									<? foreach ($metro as $key => $value) {?>
										<a href="#" class="dropdown__item"><?=$value?></a>
									<?}?>
								</span>
								
							</div>
						<?break;
				 		default:
				 			?><input
								data-sort="<?=$prop['SORT']?>"
								class="<?=($prop['SIZE1']==2?"small":"")?>"
								type="text" name="<?=$prop['FIELD_NAME']?>"
								value="<?=$prop["VALUE"]?>"
								<?=($prop['REQUIED']=='Y'?"required='required'":"")?>
								placeholder="<?=$prop['NAME']?><?=($prop['REQUIED']=='Y'?" *":"")?>" /><?
				 			break;
				 	}
					endforeach ?>
				<?if($showStores && in_array($_REQUEST['ORDER_PROP_2'], array(218, 222))):
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
							"CACHE_CODE"    => $_REQUEST['BUYER_STORE'],
							"CACHE_TYPE"    => "A",
							'PROPERTY_CODE' => array('STORE'),
							'OFFERS'        => $offers,
							"SET_TITLE"     => "N",
							"FIELD_NAME"    => "BUYER_STORE"
						),
						$component
					);
				endif;?>
				</div>
			</div>
		</div>

		<div class="col-sm-6  order__total col-md-4 no-position">
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
							<div class="col-xs-5 right total__counter"><nobr><span id="price-1"><?=number_format($arResult['ORDER_PRICE'], 0, " ", " ")?></span> <span class='rubl'>₽</span></nobr></div>
						</div>
					</div>
					<div class="total__item <?=(intval($arResult['DELIVERY_PRICE'])>0?"":"hidden")?>">
						<div class="row">
							<div class="col-xs-7">ДОСТАВКА</div>
							<div class="col-xs-5 right total__counter"><nobr><span id="price-2"><?=number_format($arResult['DELIVERY_PRICE'], 0, " ", " ")?></span> <span class='rubl'>₽</span></nobr></div>
						</div>
					</div>
					<div class="total__item total__item--big">
						<div class="row">
							<div class="col-xs-7">к оплате</div>
							<div class="col-xs-5 right total__counter"><nobr><span id="price-3"><?=number_format($arResult['ORDER_PRICE']+$arResult['DELIVERY_PRICE'], 0, " ", " ")?></span> <span class='rubl'>₽</span></nobr></div>
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
if(SITE_ID=='s2' && $APPLICATION->GetPageProperty('showPromotionAlert') != 'notVisible'):?>
<div class="usermodal hidden" id="addPromotionModal">
  <div class="usermodal__frame">
    <strong>Вы не забыли приобрести товар из раздела «Промоушен»?</strong><br>
    <a href="/catalog/promotion/" class="hello__button">Раздел «Промоушен»</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" id="addPromotionModalAction" class="hello__button hello__button--red">Оформить заказ</a>
  </div>
</div>
<?endif;?>
<?
if($_POST["is_ajax_post"] == "Y")
	die;
?>
