<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetTitle("Регистрация");
$APPLICATION->SetPageProperty('body_class', "about textpage");
/*if (isset($_REQUEST["captcha_word"]) && isset($_REQUEST["captcha_code"])) {
	if(!$GLOBALS['APPLICATION']->CaptchaCheckCode($_REQUEST["captcha_word"], $_REQUEST["captcha_code"])){
		echo 'captcha_error';
		die();
	}
}*/
?>
<div class="container textpage__content">
	<h1 class="center xxl-margin-top">Регистрация оптового покупателя</h1>
	<div class="textpage__divider no-margin-bottom"></div>
	<?$APPLICATION->IncludeComponent("bitrix:main.register","italbags", Array(
            "USER_PROPERTY_NAME" => "", 
            "SEF_MODE"           => "Y", 
            "SHOW_FIELDS"        => Array("NAME", "LAST_NAME", "PERSONAL_PHONE"), 
            "REQUIRED_FIELDS"    => Array("NAME"), 
            "AUTH"               => "Y", 
            "USE_BACKURL"        => "Y", 
            "USE_CAPTCHA"        => "Y", 
            "SUCCESS_PAGE"       => "", 
            "SET_TITLE"          => "N", 
            "USER_PROPERTY"      => Array(), 
            "SEF_FOLDER"         => "/", 
            "VARIABLE_ALIASES"   => Array()
        )
    );?> 
</div>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>