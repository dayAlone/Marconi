<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty('body_class', "basket");
$APPLICATION->SetTitle('Корзина');
?>
<div class="page">
    <div class="container">
	<?
	$APPLICATION->IncludeComponent("bitrix:sale.basket.basket","",Array(
	        "OFFERS_PROPS" => array("COLOR_REF"),
	        "PATH_TO_ORDER" => "/personal/order.php",
	        "HIDE_COUPON" => "N",
	        "COLUMNS_LIST" => Array("NAME", "PROPERTY_ARTNUMBER", "PRICE", "DISCOUNT", "QUANTITY", "DELETE"),
	        "PRICE_VAT_SHOW_VALUE" => "Y",
	        "COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
	        "USE_PREPAYMENT" => "N",
	        "QUANTITY_FLOAT" => "N",
	        "SET_TITLE" => "Y",
	        "ACTION_VARIABLE" => "action"
	    )
	);?>
	</div>
</div>
<? require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php'); ?>