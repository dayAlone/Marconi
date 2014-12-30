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
	    		<div class="col-xs-3">
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
	    		<div class="col-xs-9 right">
	    			<span class="stores__title">
	    				<img src="/layout/images/store-3.png" alt=""> Фирменные магазины с самовывозом
	    			</span>
	    			<span class="stores__title">
	    				<img src="/layout/images/store-2.png" alt=""> фирменные магазины
	    			</span>
	    			<span class="stores__title">
	    				<img src="/layout/images/store-1.png" alt=""> Места продаж
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
?><?
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