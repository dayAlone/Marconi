<? $this->setFrameMode(true);
?>
<? if(count($arResult['ITEMS'])>0): ?>
<table width="100%" valign="middle" class="available__table" cellpadding="10">
	<thead>
		<tr>
			<th>Магазин</th>
			<?if(isset($arParams['CACHE_NOTES'])):?>
			<th>Наличие</th>
			<?endif;?>
			<?if(count($arParams['OFFERS'])>0):?>
			<th>Размеры в наличии</th>
			<?endif;?>
			<th>Адрес</th>
		</tr>
	</thead>
	<tbody>
	<?$frame = $this->createFrame()->begin();?>
		<?foreach ($arResult['ITEMS'] as $key=>$item):
			$store = $item['PROPERTIES']['STORE']['VALUE'];
			?>
			<tr>
				<td width="25%"><a href="/stores/<?=$item['CODE']?>/"><?=preg_replace('@\(.*?\)@', '', $item['NAME'])?></a></td>
				<?if(isset($arParams['CACHE_NOTES'])):?>
				<td width="25%">
					<? if(isset($arResult['COUNTS'][$store])):?>
						<span class="available__icon available__icon--true"></span>Есть в наличии
					<? else: ?>
						<span class="available__icon available__icon--false"></span>нет в наличии
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
	<?
	$frame->beginStub();
	$frame->end();
	?>
	</tbody>
</table>
<? endif; ?>