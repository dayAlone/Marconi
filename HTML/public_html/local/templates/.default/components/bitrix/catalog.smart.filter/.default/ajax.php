<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$APPLICATION->RestartBuffer();
require_once($_SERVER['DOCUMENT_ROOT'].'/include/fix_filter.php');
global $arrFilter;
$arResult["ELEMENT_COUNT"] = CIBlockElement::GetList(array(), $arrFilter, array(), false);
unset($arResult["COMBO"]);
echo json_encode($arResult, JSON_UNESCAPED_UNICODE);
?>