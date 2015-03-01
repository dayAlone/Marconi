<? $this->setFrameMode(true);
?>
<? if(count($arResult['ITEMS'])>0): ?>
<?$frame = $this->createFrame()->begin();?>
<table width="100%" valign="middle" class="available__table" cellpadding="10">
	<thead>
		<tr>
			<th>Магазин</th>
			<?if(isset($arParams['CACHE_NOTES'])):?>
			<th>
				<span class="hidden-xs">Наличие</span>
			</th>
			<?endif;?>
			<?if(count($arParams['OFFERS'])>0):?>
			<th>Размеры в наличии</th>
			<?endif;?>
			<th>Адрес</th>
		</tr>
	</thead>
	<tbody>
		<?foreach ($arResult['ITEMS'] as $key=>$item):
			$store = $item['PROPERTIES']['STORE']['VALUE'];
			?>
			<tr>
				<td width="25%"><a href="/stores/<?=$item['CODE']?>/"><?=preg_replace('@\(.*?\)@', '', $item['NAME'])?></a></td>
				<?if(isset($arParams['CACHE_NOTES'])):?>
				<td width="25%" class="xs-center sm-left">
					<? if(isset($arResult['COUNTS'][$store])):?>
						<span class="available__icon available__icon--true"></span><span class="hidden-xs">Есть в наличии</span>
					<? else: ?>
						<span class="available__icon available__icon--false"></span><span class="hidden-xs">нет в наличии</span>
					<? endif; ?>
				</td>
				<?endif;?>
				<?if(count($arParams['OFFERS'])>0):?>
				<td width="25%">
					<? if(isset($arResult['COUNTS'][$store])):
						foreach ($arResult['COUNTS'][$store] as $key => $value)
							echo "<span>".$arParams['OFFERS'][$key]."</span>";
					endif; ?>
				</td>
				<?endif;?>
				<td width="25%" class="available__address"><?=html_entity_decode($item['PROPERTIES']['ADDRESS']['VALUE']['TEXT'])?></td>
			
			</tr>
		<?endforeach;?>
	</tbody>
</table>
<?
	$frame->beginStub();
	$frame->end();
	?>
<? endif; ?>