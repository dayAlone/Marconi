<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
if(count($arResult['SECTIONS'])>1):?>
<div class="dropdown">
	<?foreach ($arResult['SECTIONS'] as $key => &$item):
		if($arParams["CACHE_NOTES"]==$item['ID'] || (intval($arParams["CACHE_NOTES"])==0 && $key == 0)):?>
		<a href="#" class="dropdown__trigger"><span class="dropdown__text dropdown__text--white"><?=$item['NAME']?></span><?=svg('arrow')?></a>
		<?
		endif;
	endforeach;?>
	<span class="dropdown__frame">
		<?foreach ($arResult['SECTIONS'] as $key => &$item):?>
		<a href="/news/<?=$item['NAME']?>/" <?=($arParams["CACHE_NOTES"]==$item['ID']?'style="display:none"':'')?> class="dropdown__item"><?=$item['NAME']?></a>
		<?endforeach;?>
	</span>
	<select class="dropdown__select">
		<?foreach ($arResult['SECTIONS'] as $key => &$item):?>
		<option value="/news/<?=$item['NAME']?>/"><?=$item['NAME']?></a>
		<?endforeach;?>
	</select>
</div>
<?endif;?>
