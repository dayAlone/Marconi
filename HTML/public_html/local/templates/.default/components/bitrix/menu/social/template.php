<? $this->setFrameMode(true);?>
<span class="social">
<?foreach ($arResult as $key=>$item):?>
	<a target="_blank" href="<?=$item['LINK']?>" class="social__item"><?=svg($item['TEXT'])?></a>
<?endforeach;?>
</span>