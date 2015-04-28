<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->SetPageProperty('body_class', "product");
/** @var array $templateData */
/** @var @global CMain $APPLICATION */
use Bitrix\Main\Loader;
global $APPLICATION, $colorFilter, $CITY;

$obCache   = new CPHPCache();
$cacheLife = 86400; 
$cacheID   = 'colorFilter_'.$arResult['ID'];
$cachePath = '/'.$cacheID;

if( $obCache->InitCache($cacheLife, $cacheID, $cachePath) ):

	$vars = $obCache->GetVars();
	$colorFilter = $vars['data'];

elseif( $obCache->StartDataCache() ):

	$arResult['CATEGORIES'] = getHighloadElements('categories', 'UF_XML_ID', 'ID');
	$arResult['SECTIONS']   = array();
	
	$raw = CIBlockElement::GetElementGroups($arResult['ID']);
	while($data = $raw->GetNext())
		if(!in_array($data['CODE'], array('all', 'sale')))
			$arResult['IBLOCK_SECTION_ID'] = $data['ID'];

	$rsPath = GetIBlockSectionPath($arResult['IBLOCK_ID'], $arResult['IBLOCK_SECTION_ID']);
	while($arPath = $rsPath->GetNext())
		$arResult['SECTIONS'][] = $arPath;

	$colorFilter['!PROPERTY_SECTION_'.$arResult['CATEGORIES'][$arResult['SECTIONS'][1]['XML_ID']]] = $arResult['SECTIONS'][2]['XML_ID'];

	if(count($arResult['PROPERTIES']['COLOR']['VALUE'])>1)
		$colorFilter['?PROPERTY_COLOR'] = implode($arResult['PROPERTIES']['COLOR']['VALUE'],' || ');
	elseif(count($arResult['PROPERTIES']['COLOR']['VALUE'])==1)
		$colorFilter['=PROPERTY_COLOR'] = $arResult['PROPERTIES']['COLOR']['VALUE'][0];

	$colorFilter['!PROPERTY_PICTURES'] = false;
	$colorFilter['!ID'] = $arResult['ID'];
	if($CITY['CLOSED'] == 'Y') $colorFilter['=PROPERTY_GENERAL'] = "Y";

	$obCache->EndDataCache(array('data' => $colorFilter));
	
endif;


if (isset($templateData['JS_OBJ']))
{
?><script type="text/javascript">
BX.ready(BX.defer(function(){
	if (!!window.<? echo $templateData['JS_OBJ']; ?>)
	{
		window.<? echo $templateData['JS_OBJ']; ?>.allowViewedCount(true);
	}
}));
</script><?
}
?>