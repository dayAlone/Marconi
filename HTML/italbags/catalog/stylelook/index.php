<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty('body_class', "lookbook catalog--italbags");
$APPLICATION->SetTitle('Stylelook');
$APPLICATION->IncludeComponent("bitrix:news.list", "lookbook", 
	array(
		"IBLOCK_ID"      => 5,
		"NEWS_COUNT"     => "99999909",
		"SORT_BY1"       => "SORT",
		"SORT_ORDER1"    => "ASC",
		"DETAIL_URL"     => "/catalog/stylelook/",
		"CACHE_TYPE"     => "A",
		'PROPERTY_CODE'  => array('PRODUCTS','VIDEO'),
		"SET_TITLE"      => "N"
	),
	false
);
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>