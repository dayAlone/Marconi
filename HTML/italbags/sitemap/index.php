<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty('body_class', "search");
$APPLICATION->SetTitle("Карта сайта");
?> 
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty('body_class', "about about--italbags textpage");
$APPLICATION->SetTitle('О компании');
?>
<div class="container textpage__content">
	<h1 class="center">о компании</h1>
	<div class="textpage__divider"></div>
	<?
	$APPLICATION->IncludeComponent(
		"bitrix:main.map", 
		".default", 
		array(
			"LEVEL"            => "4",
			"COL_NUM"          => "3",
			"SHOW_DESCRIPTION" => "Y",
			"SET_TITLE"        => "N",
			"CACHE_TIME"       => "36000",
			"CACHE_TYPE"       => "A"
		),
		false
	);?>	
</div>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>?>