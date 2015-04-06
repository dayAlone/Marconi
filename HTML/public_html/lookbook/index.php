<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty('body_class', "lookbook");
$APPLICATION->SetPageProperty('section', array('IBLOCK'=>5, 'CODE'=>'lookbook'));
require($_SERVER['DOCUMENT_ROOT'].'/include/section.php');
$APPLICATION->SetTitle('Lookbook');
?>
<div class="page">
    <div class="container">
        <?
        	if(!isset($_REQUEST['ELEMENT_CODE'])):
	        	$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "lookbook", array(
					"IBLOCK_TYPE"         => "content",
					"IBLOCK_ID"           => 5,
					"TOP_DEPTH"           => "2",
					"CACHE_TYPE"          => "A",
					"CACHE_TIME"          => "36000",
					"SECTION_URL"         => "/lookbook/",

					"SECTION_USER_FIELDS" => array('UF_SIZE'),
				),
				false
				);
	       	else:
				$APPLICATION->IncludeComponent("bitrix:news.list", "lookbook", 
					array(
						"IBLOCK_ID"      => 5,
						"NEWS_COUNT"     => "9999999",
						"PARENT_SECTION" => $_GLOBALS['currentCatalogSection'],
						"SORT_BY1"       => "SORT",
						"SORT_ORDER1"    => "ASC",
						"DETAIL_URL"     => "/lookbook/",
						"CACHE_TYPE"     => "A",
						'PROPERTY_CODE'  => array('PRODUCTS','VIDEO'),
						"SET_TITLE"      => "N"
					),
					false
				);
			endif;
		?>
	</div>
</div>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>