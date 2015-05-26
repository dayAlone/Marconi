<? $this->setFrameMode(true);?>
<nav class="<?=$arParams['CLASS']?>-nav"><?
if(isset($item['PARAMS']['GROUPS']) && !isUserAccept($item['PARAMS']['GROUPS'])) continue;
foreach ($arResult as $key=>$item):
	?><a href="<?=$item['LINK']?>" class="<?=$arParams['CLASS']?>-nav__item <?=($item['SELECTED']?$arParams['CLASS'].'-nav__item--active':'')?>"><?=$item['TEXT']?></a><?
endforeach;?></nav>