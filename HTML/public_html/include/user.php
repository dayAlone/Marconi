<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
switch ($_REQUEST['action']):
	case 'login':
		$APPLICATION->IncludeComponent("bitrix:system.auth.form", "ajax", 
			Array(
				"REGISTER_URL"        => "register.php",
				"FORGOT_PASSWORD_URL" => "",
				"PROFILE_URL"         => "/profile/",
				"SHOW_ERRORS"         => "Y" 
			)
		);
		break;
endswitch;
?>