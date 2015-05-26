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
?>
<a href="#" class="page__side-trigger visible-xs-block visible-sm-block">
	<span class="true">Показать фильтр</span>
	<span class="false">Скрыть фильтр</span>
	<?=svg('arrow')?>
</a>
<form class="filter__form" name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" data-url="<?=$APPLICATION->GetCurDir()?>">

	<?if($arResult['CHECKED']=="Y"):?>
	<a href="<?=$APPLICATION->GetCurDir()?>?del_filter=Y" class="catalog__clear visible-md-block visible-lg-block">Сбросить фильтр</a>
	<?endif;?>
	<?foreach($arResult["HIDDEN"] as $arItem):
		if(!in_array($arItem["CONTROL_NAME"], array('range', 'SHOWALL_1', 'short', 'sort_param', 'sort_value', 'PAGEN_1')) && strlen($arItem["HTML_VALUE"])>0):?>
		<input
			type="hidden"
			name="<?echo $arItem["CONTROL_NAME"]?>"
			id="<?echo $arItem["CONTROL_ID"]?>"
			value="<?echo $arItem["HTML_VALUE"]?>"
		/>
	<?
		endif;
	endforeach;?>
		<div class="row">
		<?
		$i = 0;
		foreach($arResult["ITEMS"] as $key => $arItem):?>
			<?if(!empty($arItem["VALUES"])):?>
				<?
				if($i%2==0):?>
					<?if($i!=0):?></div><?endif?>
					<div class="col-sm-4 col-md-12">
				<?endif;
				$i++;
				?>
				<div class="filter <?=(!isset($_COOKIE[$arItem['CODE']]) || $_COOKIE[$arItem['CODE']] == 'Y' ? "filter--open" :"")?> <?=($arItem['OPEN']=='Y'?"active":"")?>" data-code="<?=$arItem['CODE']?>">
					<div class="filter__title <?=(preg_match("/SECTION_(.*)/", $arItem['CODE'])?"filter__title--big":"")?>"><?=($arItem["PROPERTY_TYPE"] == "N" || isset($arItem["PRICE"])?"Цена":$arItem['NAME'])?> <?=svg('arrow')?></div>
						<div class="filter__content">
						<?if($arItem["PROPERTY_TYPE"] == "N" || isset($arItem["PRICE"])):
							if($arItem["VALUES"]["MIN"]["VALUE"] !=$arItem["VALUES"]["MAX"]["VALUE"]):
							?>
							<div class="row">
			                  <div class="col-xs-6">
			                    <input
									class="min-price range range__from"
									type="text"
									name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
									id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
									value="<?=$arItem["VALUES"]["MIN"]["VALUE"]?>"
									size="5"
									onkeyup="smartFilter.keyup(this)"
								/>
			                  </div>
			                  <div class="col-xs-6 right">
			                    <input
									class="max-price range range__to"
									type="text"
									name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
									id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
									value="<?=$arItem["VALUES"]["MAX"]["VALUE"]?>"
									size="5"
									onkeyup="smartFilter.keyup(this)"
								/>
			                  </div>
			                </div>
			                <input type="text" name="range" data-min="<?=$arItem["VALUES"]["MIN"]["VALUE"]?>" data-max="<?=$arItem["VALUES"]["MAX"]["VALUE"]?>" data-from="<?=($arItem["VALUES"]["MIN"]["HTML_VALUE"]?$arItem["VALUES"]["MIN"]["HTML_VALUE"]:$arItem["VALUES"]["MIN"]["VALUE"])?>" data-to="<?=($arItem["VALUES"]["MAX"]["HTML_VALUE"]?$arItem["VALUES"]["MAX"]["HTML_VALUE"]:$arItem["VALUES"]["MAX"]["VALUE"])?>">
			               	<?else:?>
			               	<div class="center s-line-height xxs-margin-bottom"><small>Фильтр по ценам не доступен</small></div>
			                <?endif;?>
						<?else:?>
							<?foreach($arItem["VALUES"] as $val => $ar):?>
							<?/*$ar["DISABLED"]? ' lvl2_disabled': ''*/?>
							<?if($arItem['CODE']=='COLOR'):
								?>
								<div class="color-frame">
								<input
									type="checkbox"
									value="<?echo $ar["HTML_VALUE"]?>"
									name="<?echo $ar["CONTROL_NAME"]?>"
									id="<?echo $ar["CONTROL_ID"]?>"
									<?echo $ar["CHECKED"]? 'checked="checked"': ''?>
									onclick="smartFilter.click(this)"
									style="color: <?=$arResult['COLORS'][$ar['VALUE']]?>"
									data-color="<?=$arResult['COLORS'][$ar['VALUE']]?>"
									title="<?=$ar['VALUE']?>"
									class="color"
									<?=($ar["DISABLED"]&&!preg_match("/SECTION_(.*)/", $arItem['CODE'])&&!$ar['CHECKED']?"disabled":"")?>
								/>
								<label class="visible-xs color-label" for="<?echo $ar["CONTROL_ID"]?>"><?echo $ar["VALUE"];?></label>
								</div>
							<?else:
								?>
								<div class="filter__param">
									<input
										type="checkbox"
										value="<?echo $ar["HTML_VALUE"]?>"
										name="<?echo $ar["CONTROL_NAME"]?>"
										id="<?echo $ar["CONTROL_ID"]?>"
										<?echo $ar["CHECKED"]? 'checked="checked"': ''?>
										onclick="smartFilter.click(this)"
										<?=($ar["DISABLED"]&&!preg_match("/SECTION_(.*)/", $arItem['CODE'])&&!$ar['CHECKED']?"disabled":"")?>
									/>
									<label for="<?echo $ar["CONTROL_ID"]?>"><?echo $ar["VALUE"];?></label>
								</div>
							<?endif;?>
							<?endforeach;?>
						<?endif;?>
						</div>
				</div>
				<?endif;?>
		<?endforeach;?>
		<?if($i!=0):?></div><?endif?>
	</div>
	<?if($arResult['CHECKED']=="Y"):?>	
	<a href="<?=$APPLICATION->GetCurDir()?>?del_filter=Y" class="catalog__clear visible-sm-block visible-xs-block">Сбросить фильтр</a>
	<?endif;?>
	<div class="catalog__counter">
		Найдено: <strong class="catalog__counter-value">0</strong>. <a href="<?echo $arResult["FILTER_URL"]?>"><?echo GetMessage("CT_BCSF_FILTER_SHOW")?></a>
	</div>
	
</form>
<script>
	var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>');
</script>