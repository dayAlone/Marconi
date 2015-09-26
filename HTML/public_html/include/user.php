<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
switch ($_REQUEST['action']):
	case 'register':
		$APPLICATION->IncludeComponent("bitrix:main.register","ajax",Array(
                "USER_PROPERTY_NAME" => "",
                "SEF_MODE"           => "Y",
                "SHOW_FIELDS"        => Array("NAME", "LAST_NAME", 'PERSONAL_GENDER', 'PERSONAL_BIRTHDAY', 'PERSONAL_PHONE',
                								'WORK_PHONE', 'LOGIN', 'WORK_COMPANY', 'WORK_NOTES', 'PERSONAL_NOTES', 'ADMIN_NOTES', 'PERSONAL_MOBILE', 'WORK_DEPARTMENT', 'WORK_FAX'),
                "REQUIRED_FIELDS"    => Array("NAME", "LAST_NAME"),
                "AUTH"               => "Y",
                "USE_BACKURL"        => "Y",
                "USE_CAPTCHA"        => "Y",
                "SUCCESS_PAGE"       => "",
                "SET_TITLE"          => "Y",
                "USER_PROPERTY"      => Array(),
                "SEF_FOLDER"         => "/",
                "VARIABLE_ALIASES"   => Array()
        ));
		break;
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
