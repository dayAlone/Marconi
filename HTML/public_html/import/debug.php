<?
	$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/..");
	require_once ($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include.php");
	CModule::IncludeModule("iblock");
	set_time_limit(0);
	@ignore_user_abort(true);
	define("NO_KEEP_STATISTIC", true);
	define("NOT_CHECK_PERMISSIONS", true); 
	$arFilter = array('PREVIEW_PICTURE'=>false, '!PROPERTY_PICTURES'=>false);
	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
	while($el = $res->Fetch()):
		var_dump($el['ID']);
	endwhile;
?>