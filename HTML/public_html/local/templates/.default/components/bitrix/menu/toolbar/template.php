<? $this->setFrameMode(true);?>
<ul class="nav <?=$arParams['CLASS']?>"><?
	foreach ($arResult as $key=>$item):
		if(isset($item['PARAMS']['GROUPS']) && !isUserAccept($item['PARAMS']['GROUPS'])) continue;
		if ($arResult[$key-1]['DEPTH_LEVEL'] > $item['DEPTH_LEVEL'] && $key > 0) {?></ul></li><?}
		?><li><a href="<?=(isset($item['PARAMS']['URL'])?$item['PARAMS']['URL']:$item['LINK'])?>" class="nav__item <?=($item['SELECTED']?'nav__item--active':'')?>"><?=$item['TEXT']?></a><?
		if ($arResult[$key+1]['DEPTH_LEVEL'] > $item['DEPTH_LEVEL']) {?><ul><?}
		else {?></li><?}
	endforeach;
?></ul>