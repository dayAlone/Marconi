<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty('body_class', "news");
$APPLICATION->SetPageProperty('section', array('IBLOCK'=>4, 'CODE'=>'news', "NOEMPTY"=>true));
require($_SERVER['DOCUMENT_ROOT'].'/include/section.php');
$APPLICATION->SetTitle('Новости');
?>
<div class="page">
    <div class="container">
      <div class="row">
        <div class="col-md-2">
        <?
        	$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "years", array(
			    "IBLOCK_TYPE"  => "content",
			    "IBLOCK_ID"    => 4,
			    "TOP_DEPTH"    => "2",
			    "CACHE_TYPE"   => "A",
			    "CACHE_TIME"   => "36000",
			    "CACHE_NOTES"  => $_GLOBALS['currentCatalogSection']
			),
			false
			);
		?>        </div>
        <div class="col-md-8">
        <?
			$APPLICATION->IncludeComponent("bitrix:news.list", "news", 
				array(
					"IBLOCK_ID"      => 4,
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
	</div>
</div>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>