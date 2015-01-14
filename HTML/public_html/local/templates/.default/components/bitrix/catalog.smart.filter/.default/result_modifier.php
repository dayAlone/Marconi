<?
require_once($_SERVER['DOCUMENT_ROOT'].'/include/fix_items.php');
foreach($arResult['ITEMS'] as $item):
	if(!empty($item["VALUES"]) && in_array($item['ID'], getFilterProperties())):
		getFilterStringValues($item['ID'], $arResult['SECTION']['ID'], $item["VALUES"]);
	endif;
endforeach;
$arResult['COLORS'] = getHighloadElements('colors', 'UF_NAME', 'UF_VALUE');
?>