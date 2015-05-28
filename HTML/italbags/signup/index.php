<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetTitle("Регистрация");
$APPLICATION->SetPageProperty('body_class', "about textpage");
?>
<div class="container textpage__content">
	<h1 class="center xxl-margin-top">Регистрация оптового покупателя</h1>
	<div class="textpage__divider no-margin-bottom"></div>
	<div class="register">
		<div class="register__success hidden xxl-padding-top center">
			<p class="highlight">
				Ваш профиль зарегистрирован, но не активирован. Активация профиля происходит после предоставления пакета документов, а также проверки всех данных. Ознакомиться с необходимым списком документов, необходимых для начала работы, вы можете в разделе “<a href="/conditions/info/">Условия работы</a>”. После активации профиля вы получите уведомление на e-mail, который указали при регистрации.
			</p>
			<p class="highlight">
				По всем возникающим вопросам обращайтесь по телефонам: (495) 787-2264 или 8 800 555 9045 (бесплатная линия для вызовов по России),  а также по электронной почте <a href="mailto:info@italbags.ru">info@italbags.ru</a>
			</p>
		</div>
		<div class="register__form">
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
    </div>
</div>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>