<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty('body_class', "news news--list");
$APPLICATION->SetPageProperty('section', array('IBLOCK'=>4, 'CODE'=>'news', "NOEMPTY"=>false, 'SORT'=>array('ID'=>'DESC')));
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
            if(!isset($_REQUEST['ELEMENT_CODE'])||intval($_GLOBALS['currentCatalogSection'])>0):
    			$APPLICATION->IncludeComponent("bitrix:news.list", "news",
    				array(
    					"IBLOCK_ID"      => 4,
    					"NEWS_COUNT"     => "15",
    					"CACHE_NOTES"    => "fmarconi",
    					"PARENT_SECTION" => $_GLOBALS['currentCatalogSection'],
    					"SORT_BY1"       => "ACTIVE_FROM",
    					"SORT_ORDER1"    => "DESC",
    					"DETAIL_URL"     => "/news/#ELEMENT_CODE#/",
    					"CACHE_TYPE"     => "A",
    					'PROPERTY_CODE'  => array('TITLE', 'PICTURES'),
    					"SET_TITLE"      => "N",
    					"DISPLAY_PREVIEW_TEXT" => "Y",
    					"DISPLAY_BOTTOM_PAGER" => "Y",
    					"DISPLAY_PICTURE" => "Y",
                        "DETAIL" => "Y"
    				),
    				false
    			);
            else:
    			$APPLICATION->IncludeComponent("bitrix:news.detail","news",Array(
    		      "IBLOCK_ID"     => 4,
    		      "ELEMENT_CODE"  => $_REQUEST['ELEMENT_CODE'],
    		      "CHECK_DATES"   => "N",
    		      "IBLOCK_TYPE"   => "content",
    		      "SET_TITLE"     => "Y",
    		      "CACHE_TYPE"    => "A",
    		      "PROPERTY_CODE" => array("GALLERY"),

    		    ));
    		endif;
		?>
		</div>
	  </div>
	</div>
</div>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>
