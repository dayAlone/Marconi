<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty('body_class', "lookbook");
$APPLICATION->SetTitle('Stylelook');
?>
<div class="page">
    <div class="container">
        <?
        	
			$APPLICATION->IncludeComponent("bitrix:news.list", "lookbook", 
				array(
					"IBLOCK_ID"      => 7,
					"NEWS_COUNT"     => "99999909",
					"SORT_BY1"       => "SORT",
					"SORT_ORDER1"    => "ASC",
					"DETAIL_URL"     => "/lookbook/",
					"CACHE_TYPE"     => "A",
					'PROPERTY_CODE'  => array('PRODUCTS','VIDEO'),
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