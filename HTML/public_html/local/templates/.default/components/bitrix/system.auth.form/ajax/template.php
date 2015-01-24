<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'] && $arResult['ERROR_MESSAGE']['TYPE'] != 'OK'):
	echo "error";
elseif(strlen($arResult["USER_LOGIN"])>0 || $arResult['ERROR_MESSAGE']['TYPE'] == 'OK'):
	echo "success";
else:
	echo "error";
endif;
?>
