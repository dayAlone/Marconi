<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty('body_class', "cabinet");
$APPLICATION->SetTitle('Личный кабинет');
?>
<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-xs-5">
                <?$APPLICATION->IncludeComponent(
                	"bitrix:main.profile", 
                	".default", 
                	array(
                		"USER_PROPERTY_NAME" => "",
                		"SET_TITLE" => "N",
                		"AJAX_MODE" => "N",
                		"USER_PROPERTY" => array(
                		),
                		"SEND_INFO" => "N",
                		"CHECK_RIGHTS" => "Y",
                		"AJAX_OPTION_JUMP" => "N",
                		"AJAX_OPTION_STYLE" => "N",
                		"AJAX_OPTION_HISTORY" => "N",
                		"AJAX_OPTION_ADDITIONAL" => ""
                	),
                	false
                );?> 
            </div>
            <div class="col-xs-7">
                <div class="page__title page__title--full-width">История заказов</div>
                <?
                $_REQUEST['show_all'] = "Y";
                $APPLICATION->IncludeComponent("bitrix:sale.personal.order.list","",Array(
                    "STATUS_COLOR_N"                => "green",
                    "STATUS_COLOR_P"                => "yellow",
                    "STATUS_COLOR_F"                => "gray",
                    "STATUS_COLOR_PSEUDO_CANCELLED" => "red",
                    "PATH_TO_DETAIL"                => "/profile/?ID=#ID#",
                    "PATH_TO_COPY"                  => "basket.php",
                    "PATH_TO_CANCEL"                => "/profile/?ID=#ID#&action=cancel",
                    "PATH_TO_BASKET"                => "basket.php",
                    "ORDERS_PER_PAGE"               => 10,
                    "ID"                            => $ID,
                    "SET_TITLE"                     => "Y",
                    "SAVE_IN_SESSION"               => "Y",
                    "NAV_TEMPLATE"                  => "",
                    "CACHE_TYPE"                    => "A",
                    "CACHE_TIME"                    => "3600",
                    "CACHE_GROUPS"                  => "Y",
                    "HISTORIC_STATUSES"             => "F",
                    "ACTIVE_DATE_FORMAT"            => "d.m.Y"
                )
            );?>
            </div>
        </div>
    </div>
</div>

<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>