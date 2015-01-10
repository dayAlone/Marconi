<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
if(!isset($_REQUEST['short'])):
$APPLICATION->SetPageProperty('body_class', "basket");
$APPLICATION->SetTitle('Корзина');
?>
	<?
	$APPLICATION->IncludeComponent("bitrix:sale.basket.basket","",Array(
	        "OFFERS_PROPS" => array("COLOR_REF"),
	        "PATH_TO_ORDER" => "/personal/order.php",
	        "HIDE_COUPON" => "N",
	        "COLUMNS_LIST" => Array("NAME", "PROPERTY_ARTNUMBER", "PROPERTY_BRAND", "PRICE", "DISCOUNT", "QUANTITY", "DELETE"),
	        "PRICE_VAT_SHOW_VALUE" => "Y",
	        "COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
	        "USE_PREPAYMENT" => "N",
	        "QUANTITY_FLOAT" => "N",
	        "SET_TITLE" => "Y",
	        "ACTION_VARIABLE" => "action"
	    )
	);
	endif;
	$APPLICATION->IncludeComponent(
	"bitrix:sale.order.ajax", 
	".default", 
	array(
		"ALLOW_NEW_PROFILE" => "Y",
		"SHOW_PAYMENT_SERVICES_NAMES" => "Y",
		"SHOW_STORES_IMAGES" => "N",
		"PATH_TO_BASKET" => "/basket/",
		"PATH_TO_PERSONAL" => "index.php",
		"PATH_TO_PAYMENT" => "payment.php",
		"PATH_TO_AUTH" => "/auth/",
		"PAY_FROM_ACCOUNT" => "N",
		"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
		"COUNT_DELIVERY_TAX" => "N",
		"ALLOW_AUTO_REGISTER" => "Y",
		"SEND_NEW_USER_NOTIFY" => "N",
		"DELIVERY_NO_AJAX" => "Y",
		"DELIVERY_NO_SESSION" => "Y",
		"TEMPLATE_LOCATION" => "popup",
		"DELIVERY_TO_PAYSYSTEM" => "d2p",
		"SET_TITLE" => "N",
		"USE_PREPAYMENT" => "N",
		"DISABLE_BASKET_REDIRECT" => "Y",
		"PRODUCT_COLUMNS" => array(
			0 => "DISCOUNT_PRICE_PERCENT_FORMATED",
			1 => "WEIGHT_FORMATED",
		),
		"PROP_1" => array(
		),
		"PROP_2" => ""
	),
	false
);
	if(!isset($_REQUEST['short'])):
	?>
<? require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php'); 
endif;
?>