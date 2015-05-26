<? $this->setFrameMode(true);?>
<nav class="<?=$arParams['CLASS']?>-nav"><?
foreach ($arResult as $key=>$item):
	if(isset($item['PARAMS']['GROUPS']) && !isUserAccept($item['PARAMS']['GROUPS'])) continue;
	?><a href="<?=$item['LINK']?>" class="<?=$arParams['CLASS']?>-nav__item <?=($item['SELECTED']?$arParams['CLASS'].'-nav__item--active':'')?>"><?=$item['TEXT']?></a><?
endforeach;?></nav>