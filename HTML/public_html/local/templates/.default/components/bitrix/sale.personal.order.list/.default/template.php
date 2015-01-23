<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!empty($arResult['ERRORS']['FATAL'])):?>

	<?foreach($arResult['ERRORS']['FATAL'] as $error):?>
		<?=ShowError($error)?>
	<?endforeach?>

<?else:?>

	<?if(!empty($arResult['ERRORS']['NONFATAL'])):?>

		<?foreach($arResult['ERRORS']['NONFATAL'] as $error):?>
			<?=ShowError($error)?>
		<?endforeach?>

	<?endif?>

	<?if(!empty($arResult['ORDERS'])):?>
		<div class="orders">
		<?foreach($arResult["ORDER_BY_STATUS"] as $key => $group):?>

			<?foreach($group as $k => $order):?>
				
				<div class="orders__item">
					<div class="orders__title">
						<div class="row">
							<div class="col-xs-5">
								<span class="orders__number">Заказ №<?=$order["ORDER"]["ACCOUNT_NUMBER"]?></span>
								<span class="orders__date">от <?=r_date($order["ORDER"]["DATE_INSERT_FORMATED"]);?></span>
							</div>
							<div class="col-xs-3 center">
								Статус:
								<span class="orders__status" data-toggle="tooltip" data-placement="top" title="<?=$arResult["INFO"]["STATUS"][$key]["DESCRIPTION"] ?>"><?=$arResult["INFO"]["STATUS"][$key]["NAME"] ?></span>
							</div>
							<div class="col-xs-4 right">
								На сумму: <span class="orders__price"><?=number_format($order["ORDER"]["PRICE"],0," "," ")?> ₷</span>
							</div>
						</div>
					</div>	
					<div class="orders__content">
						<div class="row">
							<div class="col-xs-4">
								<span class="orders__param-name"><?=GetMessage('SPOL_PAYED')?>:</span> <span class="orders__param-value"><?=GetMessage('SPOL_'.($order["ORDER"]["PAYED"] == "Y" ? 'YES' : 'NO'))?></span> <br />
							</div>	
							<div class="col-xs-4">
								<?if(intval($order["ORDER"]["PAY_SYSTEM_ID"])):?>
									<span class="orders__param-name"><?=GetMessage('SPOL_PAYSYSTEM')?>:</span> <span class="orders__param-value"><?=$arResult["INFO"]["PAY_SYSTEM"][$order["ORDER"]["PAY_SYSTEM_ID"]]["NAME"]?></span> <br />
								<?endif?>
							</div>
							<div class="col-xs-4 right">
								<? // DELIVERY SYSTEM ?>
									<?if($order['HAS_DELIVERY']):?>

										<span class="orders__param-name"><?=GetMessage('SPOL_DELIVERY')?>:</span>
										<span class="orders__param-value">
										<?if(intval($order["ORDER"]["DELIVERY_ID"])):?>
										
											<?=$arResult["INFO"]["DELIVERY"][$order["ORDER"]["DELIVERY_ID"]]["NAME"]?> <br />
										
										<?elseif(strpos($order["ORDER"]["DELIVERY_ID"], ":") !== false):?>
										
											<?$arId = explode(":", $order["ORDER"]["DELIVERY_ID"])?>
											<?=$arResult["INFO"]["DELIVERY_HANDLERS"][$arId[0]]["NAME"]?> (<?=$arResult["INFO"]["DELIVERY_HANDLERS"][$arId[0]]["PROFILES"][$arId[1]]["TITLE"]?>) <br />

										<?endif?>
										</span>
									<?endif?>
							</div>
						</div>
						<div class="orders__products">
						<?foreach ($order["BASKET_ITEMS"] as $item):
							$item = array_merge($item, $arResult['ITEMS'][$item['PRODUCT_ID']]);
						?>
							<div class="row no-gutter orders__product">
								<div class="col-xs-7">
									<div class="orders__product-picture" style="background-image: url(<?=$item['PREVIEW_PICTURE']?>)"></div>
									<a href="<?=$item["DETAIL_PAGE_URL"]?>" target="_blank" class="orders__product-name">
										<span><?=$item['NAME']?></span>
									</a> 
								</div>
								<div class="col-xs-2 center">
									<?=$item['PROPERTY_ARTNUMBER_VALUE']?>
								</div>
								<div class="col-xs-1 center"><?=$item['QUANTITY']?> <?=(isset($item["MEASURE_NAME"]) ? $item["MEASURE_NAME"] : GetMessage('SPOL_SHT'))?></div>
								<div class="col-xs-2 right">
									<?=number_format($item["PRICE"],0," "," ")?> ₷
								</div>
							</div>
						<?endforeach?>
						</div>
					</div>
				</div>
				
				<?/*
				<?if(!$k):?>

					<div class="bx_my_order_status_desc">

						<h2><?=GetMessage("SPOL_STATUS")?> "<?=$arResult["INFO"]["STATUS"][$key]["NAME"] ?>"</h2>
						<div class="bx_mos_desc"><?=$arResult["INFO"]["STATUS"][$key]["DESCRIPTION"] ?></div>

					</div>

				<?endif?>
				
				
				<div class="bx_my_order">
					
					<table class="bx_my_order_table">
						<thead>
							<tr>
								<td>
									<?=GetMessage('SPOL_ORDER')?> <?=GetMessage('SPOL_NUM_SIGN')?><?=$order["ORDER"]["ACCOUNT_NUMBER"]?>
									<?if(strlen($order["ORDER"]["DATE_INSERT_FORMATED"])):?>
										<?=GetMessage('SPOL_FROM')?> <?=$order["ORDER"]["DATE_INSERT_FORMATED"];?>
									<?endif?>
								</td>
								<td style="text-align: right;">
									<a href="<?=$order["ORDER"]["URL_TO_DETAIL"]?>"><?=GetMessage('SPOL_ORDER_DETAIL')?></a>
								</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<strong><?=GetMessage('SPOL_PAY_SUM')?>:</strong> <?=$order["ORDER"]["FORMATED_PRICE"]?> <br />

									<strong><?=GetMessage('SPOL_PAYED')?>:</strong> <?=GetMessage('SPOL_'.($order["ORDER"]["PAYED"] == "Y" ? 'YES' : 'NO'))?> <br />

									<? // PAY SYSTEM ?>
									<?if(intval($order["ORDER"]["PAY_SYSTEM_ID"])):?>
										<strong><?=GetMessage('SPOL_PAYSYSTEM')?>:</strong> <?=$arResult["INFO"]["PAY_SYSTEM"][$order["ORDER"]["PAY_SYSTEM_ID"]]["NAME"]?> <br />
									<?endif?>

									<? // DELIVERY SYSTEM ?>
									<?if($order['HAS_DELIVERY']):?>

										<strong><?=GetMessage('SPOL_DELIVERY')?>:</strong>

										<?if(intval($order["ORDER"]["DELIVERY_ID"])):?>
										
											<?=$arResult["INFO"]["DELIVERY"][$order["ORDER"]["DELIVERY_ID"]]["NAME"]?> <br />
										
										<?elseif(strpos($order["ORDER"]["DELIVERY_ID"], ":") !== false):?>
										
											<?$arId = explode(":", $order["ORDER"]["DELIVERY_ID"])?>
											<?=$arResult["INFO"]["DELIVERY_HANDLERS"][$arId[0]]["NAME"]?> (<?=$arResult["INFO"]["DELIVERY_HANDLERS"][$arId[0]]["PROFILES"][$arId[1]]["TITLE"]?>) <br />

										<?endif?>

									<?endif?>

									<strong><?=GetMessage('SPOL_BASKET')?>:</strong>
									<ul class="bx_item_list">

										<?foreach ($order["BASKET_ITEMS"] as $item):?>

											<li>
												<?if(strlen($item["DETAIL_PAGE_URL"])):?>
													<a href="<?=$item["DETAIL_PAGE_URL"]?>" target="_blank">
												<?endif?>
													<?=$item['NAME']?>
												<?if(strlen($item["DETAIL_PAGE_URL"])):?>
													</a> 
												<?endif?>
												<nobr>&nbsp;&mdash; <?=$item['QUANTITY']?> <?=(isset($item["MEASURE_NAME"]) ? $item["MEASURE_NAME"] : GetMessage('SPOL_SHT'))?></nobr>
											</li>

										<?endforeach?>

									</ul>

								</td>
								<td>
									<?=$order["ORDER"]["DATE_STATUS_FORMATED"];?>
									<div class="bx_my_order_status <?=$arResult["INFO"]["STATUS"][$key]['COLOR']?>"><?=$arResult["INFO"]["STATUS"][$key]["NAME"]?></div>

									<?if($order["ORDER"]["CANCELED"] != "Y"):?>
										<a href="<?=$order["ORDER"]["URL_TO_CANCEL"]?>" style="min-width:140px"class="bx_big bx_bt_button_type_2 bx_cart bx_order_action"><?=GetMessage('SPOL_CANCEL_ORDER')?></a>
									<?endif?>

									<a href="<?=$order["ORDER"]["URL_TO_COPY"]?>" style="min-width:140px"class="bx_big bx_bt_button_type_2 bx_cart bx_order_action"><?=GetMessage('SPOL_REPEAT_ORDER')?></a>
								</td>
							</tr>
						</tbody>
					</table>

				</div>
				*/?>
			<?endforeach?>

		<?endforeach?>
		</div>
		<?if(strlen($arResult['NAV_STRING'])):?>
			<?=$arResult['NAV_STRING']?>
		<?endif?>

	<?else:?>
		<?=GetMessage('SPOL_NO_ORDERS')?>
	<?endif?>

<?endif?>