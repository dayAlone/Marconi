<? if(count($arResult['ITEMS'])>0): ?>
<?foreach ($arResult['ITEMS'] as $key=>$item):?>
	<?var_dump($item['NAME'])?>
<?endforeach;?>
<? endif; ?>