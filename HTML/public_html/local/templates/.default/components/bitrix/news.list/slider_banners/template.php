<? $this->setFrameMode(true);?>
<? if(count($arResult['ITEMS']) > 0): ?>
<div class="catalog__banner catalog__banner--slider">
<?foreach ($arResult['ITEMS'] as $key=>$item):
	if(isset($item['PREVIEW_PICTURE']['SRC'])):?>
	<a href="<?=$item['PROPERTIES']['LINK']['VALUE']?>">
		<?if(isset($item['PREVIEW_PICTURE']['SRC'])):?><img src="<?=$item['PREVIEW_PICTURE']['SRC']?>"><?endif;?>
	</a>

	<?
	endif;
endforeach;?>
</div>
<? endif; ?>
