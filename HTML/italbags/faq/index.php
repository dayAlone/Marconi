<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty('body_class', "news news--list");
$IBLOCK = 8;
require($_SERVER['DOCUMENT_ROOT'].'/include/section.php');
$APPLICATION->SetTitle('Вопросы и ответы');
?>
<div class="page">
    <div class="container">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
        <?
        	$APPLICATION->IncludeComponent("bitrix:news.list", "news", 
				array(
					"IBLOCK_ID"            => $IBLOCK,
					"NEWS_COUNT"           => "15",
					"PARENT_SECTION"       => $_GLOBALS['currentCatalogSection'],
					"SORT_BY1"             => "ACTIVE_FROM",
					"SORT_ORDER1"          => "DESC",
					"DETAIL_URL"           => "/faq/",
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
		?>
		</div>
	  </div>
	
	
	</div>
</div>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>