<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty('body_class', "about about--fmarconi about--menu");
$APPLICATION->SetTitle('Франчайзинг');
    $APPLICATION->IncludeComponent("bitrix:news.list", "about", 
    array(
	    "IBLOCK_ID"           => 23,
	    "NEWS_COUNT"          => "99999",
	    "SORT_BY1"            => "SORT",
	    "SORT_ORDER1"         => "ASC",
	    "DETAIL_URL"          => "/about/",
	    "CACHE_TYPE"          => "A",
	    "DISPLAY_PANEL"       => "N",
	    "SET_TITLE"           => "N",
	    "PROPERTY_CODE"       => array('DARK')
       ),
       false
    );
?>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>