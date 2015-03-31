<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
if(count($arResult['SECTIONS'])>1):?>
<div class="dropdown">
	<?foreach ($arResult['SECTIONS'] as $key => &$item):
		if($arParams["CACHE_NOTES"]==$item['ID']):?>
			<a href="#" class="dropdown__trigger"><span class="dropdown__text dropdown__text--white"><?=$item['NAME']?></span><?=svg('arrow')?></a>
		<?
		$active = $item;
		endif;
	endforeach;
	if(!isset($active)):
		?>
		<a href="#" class="dropdown__trigger"><span class="dropdown__text dropdown__text--white"><?=($arParams['LANG']!="EN"?'<span class="hidden-xs">выберите город</span><span class="visible-xs">Москва</span>':'<span class="hidden-xs">Select city</span><span class="visible-xs">Москва</span>')?><?=svg('arrow')?></a>
		<?
	endif;
	?>
	<span class="dropdown__frame">
		<?foreach ($arResult['SECTIONS'] as $key => &$item):?>
		<a href="#" data-code="<?=$item['CODE']?>" data-id="<?=$item['ID']?>" <?=($arParams["CACHE_NOTES"]==$item['ID']?'style="display:none"':'')?> class="dropdown__item"><?=$item['NAME']?></a>
		<?endforeach;?>
	</span>
	<select class="dropdown__select">
		<?foreach ($arResult['SECTIONS'] as $key => &$item):?>
		<option value="" data-code="<?=$item['CODE']?>" data-id="<?=$item['ID']?>"><?=$item['NAME']?></a>
		<?endforeach;?>
	</select>
</div>
<?
	if(count($active)>0):
		?>
			<script>
				currentCity = '<?=json_encode(array('code'=>$active['CODE'], 'name'=>$active['NAME']))?>';
			</script>
		<?
	endif;
endif;?>