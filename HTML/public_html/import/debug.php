<?
	$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/..");
	require_once ($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include.php");
	CModule::IncludeModule("iblock");
	set_time_limit(0);
	@ignore_user_abort(true);
	define("NO_KEEP_STATISTIC", true);
	define("NOT_CHECK_PERMISSIONS", true); 
	$arFilter = array('!PROPERTY_PICTURES'=>false, 'PREVIEW_PICTURE'=>false);
	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, array('ID', 'PROPERTY_PICTURES'));
	while($el = $res->Fetch()):
		$raw = new CIBlockElement;
		$raw->Update($el['ID'], array("PREVIEW_PICTURE" =>CFile::GetFileArray($el['PROPERTY_PICTURES_VALUE'][0])));
	endwhile;
?>