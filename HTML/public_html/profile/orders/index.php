<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
?>
<div class="page">
    <div class="container">
<?if(isset($_REQUEST['ID'])):?>
<?$APPLICATION->IncludeComponent("bitrix:sale.personal.order.detail","",Array(
        "PATH_TO_LIST" => "order_list.php",
        "PATH_TO_CANCEL" => "order_cancel.php",
        "PATH_TO_PAYMENT" => "payment.php",
        "ID" => $_REQUEST['ID'],
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "CACHE_GROUPS" => "Y",
        "SET_TITLE" => "Y",
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "PREVIEW_PICTURE_WIDTH" => "110",
        "PREVIEW_PICTURE_HEIGHT" => "110",
        "RESAMPLE_TYPE" => "1",
        "CUSTOM_SELECT_PROPS" => array(),
        "PROP_1" => Array(),
        "PROP_2" => Array()
    )
);?>
<?else:?>
<?$APPLICATION->IncludeComponent("bitrix:sale.personal.order.list","",Array(
        "STATUS_COLOR_N" => "green",
        "STATUS_COLOR_P" => "yellow",
        "STATUS_COLOR_F" => "gray",
        "STATUS_COLOR_PSEUDO_CANCELLED" => "red",
        "PATH_TO_DETAIL" => "/profile/orders/?ID=#ID#",
        "PATH_TO_COPY" => "basket.php",
        "PATH_TO_CANCEL" => "order_cancel.php?ID=#ID#",
        "PATH_TO_BASKET" => "basket.php",
        "ORDERS_PER_PAGE" => 20,
        "ID" => $ID,
        "SET_TITLE" => "Y",
        "SAVE_IN_SESSION" => "Y",
        "NAV_TEMPLATE" => "",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "CACHE_GROUPS" => "Y",
        "HISTORIC_STATUSES" => "F",
        "ACTIVE_DATE_FORMAT" => "d.m.Y"
    )
);?>
<?endif;?>
    </div>
</div>

<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>