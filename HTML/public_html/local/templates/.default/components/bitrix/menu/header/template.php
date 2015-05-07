<? $this->setFrameMode(true);?>
<?if(count($arResult)>0):?>
<div class="container">
	<nav class="nav <?=$arParams['CLASS']?>"><?foreach ($arResult as $key=>$item):?><a href="<?=$item['LINK']?>" class="nav__item <?=($item['SELECTED']?'nav__item--active':'')?>"><?=$item['TEXT']?></a><?endforeach;?></nav>
</div>
<?endif;?>