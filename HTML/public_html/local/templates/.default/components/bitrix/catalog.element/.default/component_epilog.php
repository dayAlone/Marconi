<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->SetPageProperty('body_class', "product");
/** @var array $templateData */
/** @var @global CMain $APPLICATION */
use Bitrix\Main\Loader;
global $APPLICATION;

global $colorFilter;
$arResult['CATEGORIES'] = getHighloadElements('categories', 'UF_XML_ID', 'ID');
$arResult['SECTIONS']   = array();
$rsPath = GetIBlockSectionPath($arResult['IBLOCK_ID'], $arResult['IBLOCK_SECTION_ID']);
while($arPath = $rsPath->GetNext())
	$arResult['SECTIONS'][] = $arPath;

$colorFilter['!SECTION_'.$arResult['CATEGORIES'][$arResult['SECTIONS'][1]['XML_ID']]] = $section['XML_ID'];
if(count($arResult['PROPERTIES']['COLOR']['VALUE'])>1)
	$colorFilter['?PROPERTY_COLOR'] = implode($arResult['PROPERTIES']['COLOR']['VALUE'],' || ');
elseif(count($arResult['PROPERTIES']['COLOR']['VALUE'])==1)
	$colorFilter['=PROPERTY_COLOR'] = $arResult['PROPERTIES']['COLOR']['VALUE'][0];

$colorFilter['!PROPERTY_PICTURES'] = false;
$colorFilter['!ID'] = $arResult['ID'];

if (isset($templateData['TEMPLATE_THEME']))
{
	$APPLICATION->SetAdditionalCSS($templateData['TEMPLATE_THEME']);
}
if (isset($templateData['TEMPLATE_LIBRARY']) && !empty($templateData['TEMPLATE_LIBRARY']))
{
	$loadCurrency = false;
	if (!empty($templateData['CURRENCIES']))
		$loadCurrency = Loader::includeModule('currency');
	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);
	if ($loadCurrency)
	{
	?>
	<script type="text/javascript">
		BX.Currency.setCurrencies(<? echo $templateData['CURRENCIES']; ?>);
	</script>
<?
	}
}
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