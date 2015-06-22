<? $this->setFrameMode(true);?>
<? if(count($arResult['ITEMS']) > 0): ?>
<div class="catalog__banner">
<?foreach ($arResult['ITEMS'] as $key=>$item):
	if(isset($item['PREVIEW_PICTURE']['SRC'])):?>
	<a href="<?=$item['PROPERTIES']['LINK']['VALUE']?>">
		<?if(isset($item['PREVIEW_PICTURE']['SRC'])):?><img src="<?=$item['PREVIEW_PICTURE']['SRC']?>" alt="" <?if(isset($item['PREVIEW_PICTURE']['SRC'])):?>class="visible-lg"<?endif;?>><?endif;?>
		<?if(isset($item['DETAIL_PICTURE']['SRC'])):?><img src="<?=$item['DETAIL_PICTURE']['SRC']?>" alt="" class="hidden-lg"><?endif;?>
	</a>
	<?
	endif;
endforeach;?>
</div>
<? endif; ?>