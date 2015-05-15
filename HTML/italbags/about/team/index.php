<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty('body_class', "textpage");
$APPLICATION->SetTitle('Команда');
?>
<div class="container textpage__content">
	<?
    	$APPLICATION->IncludeComponent("bitrix:news.list", "team", 
			array(
				"IBLOCK_ID"            => 22,
				"NEWS_COUNT"           => "100",
				"SORT_BY1"             => "SORT",
				"SORT_ORDER1"          => "ASC",
				"DETAIL_URL"           => "/achievements/",
				"CACHE_TYPE"           => "A",
				'PROPERTY_CODE'        => array('POSITION', 'PHONE', 'EMAIL'),
				"SET_TITLE"            => "N",
			),
			false
		);
	?>
</div>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>