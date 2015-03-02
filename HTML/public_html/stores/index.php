<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
if(!isset($_REQUEST['short'])):
	$APPLICATION->SetPageProperty('body_class', "stores");
	$APPLICATION->SetPageProperty('section', array('IBLOCK'=>6, 'CODE'=>'stores', 'CHECK'=>true));
	require($_SERVER['DOCUMENT_ROOT'].'/include/section.php');
	$APPLICATION->SetTitle('Магазины');
	?>
	<div class="page">
	    <div class="container">
	    	<div class="row">
	    		<div class="col-md-3 col-sm-4">
	    			 <?
	    			 	$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "cities", array(
						    "IBLOCK_TYPE"  => "content",
						    "IBLOCK_ID"    => 6,
						    "TOP_DEPTH"    => "1",
						    "CACHE_TYPE"   => "A",
						    "CACHE_TIME"   => "36000",
						    "SECTION_USER_FIELDS" => array('UF_SIZE'),
						    "CACHE_NOTES"  => $_GLOBALS['currentCatalogSection']
						),
						false
					);
			        ?>
	    		</div>
	    		<div class="col-sm-8 col-md-9 right">
	    			<span class="stores__title">
	    				<nobr><img src="/layout/images/store-3.png" alt=""> <a href="#stores" data-toggle="modal" data-target="#stores" >Фирменные магазины с самовывозом</a></nobr>
	    			</span>
	    			<span class="stores__title">
	    				<nobr><img src="/layout/images/store-2.png" alt=""> фирменные магазины</nobr>
	    			</span>
	    			<span class="stores__title">
	    				<nobr><img src="/layout/images/store-1.png" alt=""> Места продаж</nobr>
	    			</span>
	    		</div>
	    	</div>
		</div>
	</div>
	<div class="stores__modal">
	    <a href="#" class="stores__close">
	    	<?=svg('close')?>
	    </a>
	    <div class="stores__content">
	    	
	    </div>
	</div>
	<?
endif;
?>
<div id="stores" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
  <div class="modal-dialog stores__dialog">
    <div class="modal-content"><a data-dismiss="modal" href="#" class="close"><?=svg('close')?></a>
    <div class="page__title page__title--no-border center">Магазины с самовывозом</div>
    <?
    	global $arFilter;
    	$arFilter = array('!PROPERTY_STORE' => false);

    	$APPLICATION->IncludeComponent("bitrix:news.list", "available", 
			array(
				"IBLOCK_ID"     => 6,
				"NEWS_COUNT"    => "9999999",
				"FILTER_NAME"   => "arFilter",
				"SORT_BY1"      => "SECTION_ID",
				"SORT_ORDER1"   => "ASC",
				"SORT_BY2"      => "PROPERTY_TYPE",
				"SORT_ORDER2"   => "DESC",
				"DETAIL_URL"    => "/catalog/",
				"CACHE_TYPE"    => "A",
				'PROPERTY_CODE' => array('ADDRESS'),
				'OFFERS'        => $offers,
				"SET_TITLE"     => "N"
			),
			$component
		);
	?>
    </div>
  </div>
</div>
<?
$APPLICATION->IncludeComponent("bitrix:news.list", "map", 
	array(
		"IBLOCK_ID"     => 6,
		"NEWS_COUNT"    => "9999999",
		"CACHE_NOTES"   => $_REQUEST['ELEMENT_CODE'].(isset($_REQUEST['short'])?"_short":""),
		"SORT_BY1"      => "IBLOCK_SECTION_ID",
		"SORT_ORDER1"   => "ASC",
		"DETAIL_URL"    => "/stores/",
		"CACHE_TYPE"    => "A",
		'PROPERTY_CODE' => array('COORDS', 'TYPE'),
		"SET_TITLE"     => "N"
	),
	false
);
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>