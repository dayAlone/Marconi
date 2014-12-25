<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty('body_class', "lookbook");
$APPLICATION->SetTitle('Lookbook');
?>
<div class="page">
    <div class="container">
        <?
        	$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "lookbook", array(
			    "IBLOCK_TYPE"  => "content",
			    "IBLOCK_ID"    => 5,
			    "TOP_DEPTH"    => "2",
			    "CACHE_TYPE"   => "A",
			    "CACHE_TIME"   => "36000",
			    "SECTION_USER_FIELDS" => array('UF_SIZE'),
			),
			false
			);
			$APPLICATION->IncludeComponent("bitrix:news.list", "news", 
				array(
					"IBLOCK_ID"      => 5,
					"NEWS_COUNT"     => "15",
					"PARENT_SECTION" => $_GLOBALS['currentCatalogSection'],
					"SORT_BY1"       => "ACTIVE_FROM",
					"SORT_ORDER1"    => "DESC",
					"DETAIL_URL"     => "/news/",
					"CACHE_TYPE"     => "A",
					'PROPERTY_CODE'  => array('TITLE'),
					"SET_TITLE"      => "N"
				),
				false
			);
		?>
	</div>
</div>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>