<?
	foreach ($arResult['ITEMS'] as &$item):
		$item['VALUE'] = ($arParams["FIELD_NAME"] == 'BUYER_STORE' ? $item['PROPERTIES']['STORE']['VALUE'] : htmlspecialcharsbx(strip_tags(html_entity_decode($item['PROPERTIES']['ADDRESS']['VALUE']['TEXT']))));
	endforeach;
?>