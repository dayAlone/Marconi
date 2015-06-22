<? $this->setFrameMode(true);?>
<? if(count($arResult['ITEMS'])>1): ?>
<div class="catalog__banner">
<?foreach ($arResult['ITEMS'] as $key=>$item):
	if(isset($item['PREVIEW_PICTURE']['SRC'])):?>
	<a href="<?=$item['PROPERTIES']['LINK']['VALUE']?>">
		<img src="<?=$item['PREVIEW_PICTURE']['SRC']?>" alt="" class="visible-lg">
		<img src="<?=$item['DETAIL_PICTURE']['SRC']?>" alt="" class="hidden-lg">
	</a>
	<?
	endif;
endforeach;?>
</div>
<? endif; ?>