<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty('body_class', "textpage");
$APPLICATION->SetTitle('Достижения');
?>
<div class="container textpage__content">
	<?
    	$APPLICATION->IncludeComponent("bitrix:news.list", "achievements", 
			array(
				"IBLOCK_ID"            => 9,
				"NEWS_COUNT"           => "100",
				"PARENT_SECTION"       => $_GLOBALS['currentCatalogSection'],
				"SORT_BY1"             => "ACTIVE_FROM",
				"SORT_ORDER1"          => "DESC",
				"DETAIL_URL"           => "/achievements/",
				"CACHE_TYPE"           => "A",
				'PROPERTY_CODE'        => array('TITLE', 'PICTURES'),
				"SET_TITLE"            => "N",
			),
			false
		);
	?>
</div>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>