<? $this->setFrameMode(true);?>
<?if(count($arResult)>0):?>
<div class="container">
	<nav class="nav <?=$arParams['CLASS']?>">
		<span class="nav__content"><?foreach ($arResult as $key=>$item):?><a href="<?=$item['LINK']?>" class="nav__item <?=($item['SELECTED']?'nav__item--active':'')?>"><?=$item['TEXT']?></a><?endforeach;?></span>
		<div class="nav__line nav__line--after"></div>
		<div class="nav__line nav__line--before"></div>
	</nav>
</div>
<?endif;?>