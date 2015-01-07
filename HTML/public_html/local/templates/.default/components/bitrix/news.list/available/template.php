<? if(count($arResult['ITEMS'])>0): ?>
<table width="100%" valign="middle" class="available__table" cellpadding="10">
	<thead>
		<tr>
			<th>Магазин</th>
			<th>Наличие</th>
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
			<td width="25%"><?=preg_replace('@\(.*?\)@', '', $item['NAME'])?></td>
			<td width="25%">
				<? if($arResult['COUNTS'][$store]):?>
					<span class="available__icon available__icon--true"></span>Есть в наличии
				<? else: ?>
					<span class="available__icon available__icon--false"></span>нет в наличии
				<? endif; ?>
			</td>
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
<? endif; ?>