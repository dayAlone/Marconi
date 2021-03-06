<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty('body_class', "news news--list");
$IBLOCK = 28;
require($_SERVER['DOCUMENT_ROOT'].'/include/section.php');
$APPLICATION->SetTitle('Вопросы и ответы');
?>
<div class="page">
    <div class="container">
      <div class="row">
      	<div class="col-md-2 xl-margin-bottom">
      		<a href="#review" data-toggle="modal" data-target="#review" class="product__big-button product__big-button--border full-width">Оставить отзыв</a>
      	</div>
        <div class="col-md-8">
        <?
        	$APPLICATION->IncludeComponent("bitrix:news.list", "feedback", 
				array(
					"IBLOCK_ID"            => $IBLOCK,
					"NEWS_COUNT"           => "15",
					"PARENT_SECTION"       => $_GLOBALS['currentCatalogSection'],
					"SORT_BY1"             => "SORT",
					"SORT_ORDER1"          => "ASC",
					"DETAIL_URL"           => "/faq/",
					"CACHE_TYPE"           => "A",
					'PROPERTY_CODE'        => array('TITLE', 'PICTURES'),
					'FIELD_CODE'           => array('DETAIL_TEXT'),
					"SET_TITLE"            => "N",
					"DISPLAY_BOTTOM_PAGER" => "Y"
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