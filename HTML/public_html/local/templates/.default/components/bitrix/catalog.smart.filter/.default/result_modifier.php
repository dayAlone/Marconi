<?
require_once($_SERVER['DOCUMENT_ROOT'].'/include/fix_items.php');
foreach($arResult['ITEMS'] as &$item):
	if($arItem["PROPERTY_TYPE"] == "N" || isset($arItem["PRICE"])):
		if($arItem["VALUES"]["MIN"]["HTML_VALUE"] || $arItem["VALUES"]["MAX"]["HTML_VALUE"]):
			$arResult['CHECKED'] = "Y";
			$item['OPEN'] = "Y";
			
		endif;
	else:
		foreach($arItem["VALUES"] as $val => $ar):
			if($ar["CHECKED"]):
					$item['OPEN'] = "Y";
				endif;
		endforeach;
	endif;
	if(!empty($item["VALUES"]) && in_array($item['ID'], getFilterProperties())):
		getFilterStringValues($item['ID'], $arResult['SECTION']['ID'], $item["VALUES"]);
	endif;
endforeach;
$arResult['COLORS'] = getHighloadElements('colors', 'UF_NAME', 'UF_VALUE');
?>