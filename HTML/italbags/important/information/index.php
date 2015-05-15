<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty('body_class', "news news--list");
$IBLOCK = 26;
$APPLICATION->SetPageProperty('section', array('IBLOCK'=>$IBLOCK, 'CODE'=>'news', "NOEMPTY"=>false, 'SORT'=>array('ID'=>'DESC')));
require($_SERVER['DOCUMENT_ROOT'].'/include/section.php');
$APPLICATION->SetTitle('Полезная информация');
?>
<div class="page">
    <div class="container">
    
      <div class="row">
        <div class="col-md-2">
        <?
        	$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "years", array(
			    "IBLOCK_TYPE"  => "content_italbags",
			    "IBLOCK_ID"    => $IBLOCK,
			    "TOP_DEPTH"    => "2",
			    "CACHE_TYPE"   => "A",
			    "CACHE_TIME"   => "36000",
			    "CACHE_NOTES"  => $_GLOBALS['currentCatalogSection']
			),
			false
			);
		?>        </div>
        <div class="col-md-8">
        <?if(!isset($_REQUEST['ELEMENT_CODE'])):
        	$APPLICATION->IncludeComponent("bitrix:news.list", "news", 
				array(
					"IBLOCK_ID"            => $IBLOCK,
					"NEWS_COUNT"           => "15",

					"SORT_BY1"             => "ACTIVE_FROM",
					"SORT_ORDER1"          => "DESC",
					"DETAIL_URL"           => "/news/#ELEMENT_CODE#/",
					"CACHE_TYPE"           => "A",
					'PROPERTY_CODE'        => array('TITLE', 'PICTURES'),
					"SET_TITLE"            => "N",
					"DISPLAY_PREVIEW_TEXT" => "Y",
					"DISPLAY_BOTTOM_PAGER" => "Y",
					"DISPLAY_PICTURE"      => "Y",
					"ARROW"                => "Y"
				),
				false
			);
		else:
			$APPLICATION->IncludeComponent("bitrix:news.detail","news",Array(
		      "IBLOCK_ID"     => $IBLOCK,
		      "ELEMENT_CODE"  => $_REQUEST['ELEMENT_CODE'],
		      "CHECK_DATES"   => "N",
		      "IBLOCK_TYPE"   => "content_italbags",
		      "SET_TITLE"     => "Y",
		      "CACHE_TYPE"    => "A",
		      "PROPERTY_CODE" => array("GALLERY"),
		    
		    ));
		endif;?>
		</div>
	  </div>
	
	
	</div>
</div>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>