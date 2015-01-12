<? $this->setFrameMode(true);?>
<? if(count($arResult['ITEMS'])>1): ?>
<div class="colors">
<?foreach ($arResult['ITEMS'] as $key=>$item):
	if(isset($item['PREVIEW_PICTURE']['SRC'])||$item['PROPERTIES']['PICTURES']['VALUE'][0]['small']):?>
	<a style="background-image:url(<?=($item['PREVIEW_PICTURE']['SRC']?$item['PREVIEW_PICTURE']['SRC']:$item['PROPERTIES']['PICTURES']['VALUE'][0]['small'])?>)" href="<?=$item['DETAIL_PAGE_URL']?>" class="colors__item <?=($arParams['CACHE_NOTES']==$item['ID']?"colors__item--active":"")?>"></a>
<?
	endif;
endforeach;?>
</div>
<? endif; ?>