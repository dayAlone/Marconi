<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetTitle("Регистрация");
$APPLICATION->SetPageProperty('body_class', "about textpage");
?>
<div class="container textpage__content">
	<h1 class="center xxl-margin-top">Регистрация оптового покупателя</h1>
	<div class="textpage__divider no-margin-bottom"></div>
	<form data-parsley-validate class="signup">
		<div class="row">
			<div class="col-sm-6 col-md-4">
				<label for="REGISTER[LAST_NAME]" class="signup__label">Фамилия *</label>
				<input type="text" name="REGISTER[LAST_NAME]" id="REGISTER[LAST_NAME]" required class="signup__input">
			</div>
			<div class="col-sm-6 col-md-4">
				<label for="REGISTER[NAME]" class="signup__label">Имя *</label>
				<input type="text" name="REGISTER[NAME]" id="REGISTER[NAME]" required class="signup__input">
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="row">
					<div class="col-xs-4">
						<label class="signup__label">пол *</label>
						<input type="radio" name="REGISTER[PERSONAL_GENDER]" id="gender-m" value="M" class="hidden" checked>
						<label for="gender-m" class="signup__radio"><?=svg('man')?></label>
						<input type="radio" name="REGISTER[PERSONAL_GENDER]" id="gender-f" value="F" class="hidden">
						<label for="gender-f" class="signup__radio"><?=svg('woman')?></label>
					</div>
					<div class="col-xs-8">
						<label for="REGISTER[PERSONAL_BIRTHDAY]" class="signup__label">дата рождения *</label>
						<input type="text" name="REGISTER[PERSONAL_BIRTHDAY]" id="REGISTER[PERSONAL_BIRTHDAY]" required class="signup__input">		
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<label for="REGISTER[PERSONAL_PHONE]" class="signup__label">Мобильный телефон *</label>
				<input type="text" name="REGISTER[PERSONAL_PHONE]" id="REGISTER[PERSONAL_PHONE]" required class="signup__input">
			</div>
			<div class="col-sm-6 col-md-4">
				<label for="REGISTER[WORK_PHONE]" class="signup__label">Рабочий телефон *</label>
				<input type="text" name="REGISTER[WORK_PHONE]" id="REGISTER[WORK_PHONE]" required class="signup__input">
			</div>
			<div class="col-sm-6 col-md-4">
				<label for="REGISTER[LOGIN]" class="signup__label">E-mail *</label>
				<input type="email" name="REGISTER[LOGIN]" id="REGISTER[LOGIN]" required class="signup__input">
			</div>
			<div class="col-sm-6 signup__height">
				<label for="REGISTER[WORK_COMPANY]" class="signup__label">Название организации или ИП *</label>
				<input type="email" name="REGISTER[WORK_COMPANY]" id="REGISTER[WORK_COMPANY]" required class="signup__input">
				<div class="m-margin-top">
					<input type="checkbox" required id="agree" name="agree" value="1" class="signup__checkbox">
					<label for="agree" class="signup__notice">Обязуюсь сообщать компании об открытии новых и закрытии существующих магазинов, где реализуется товар торговой марки Francesco Marconi во избежание спорных вопросов.</label>
				</div>
			</div>
			<div class="col-sm-6">
				<label for="REGISTER[WORK_NOTES]" class="signup__label">Торговые точки, количество. Название ТЦ и его адрес *</label>
				<textarea name="REGISTER[WORK_NOTES]" id="REGISTER[WORK_NOTES]" required class="signup__input"></textarea>
			</div>
			<div class="col-sm-6">
				<label for="REGISTER[PERSONAL_NOTES]" class="signup__label">примечания</label>
				<textarea name="REGISTER[PERSONAL_NOTES]" id="REGISTER[PERSONAL_NOTES]" class="signup__input"></textarea>
			</div>
			<div class="col-sm-6">
				<label for="REGISTER[ADMIN_NOTES]" class="signup__label">откуда вы узнали о нашей компании?</label>
				<textarea name="REGISTER[ADMIN_NOTES]" id="REGISTER[ADMIN_NOTES]" class="signup__input"></textarea>
			</div>
			<div class="col-md-6">
				<label for="REGISTER[PERSONAL_MOBILE]" class="signup__label">Номер для What`s App рассылки</label>
				<div class="row">
					<div class="col-xs-8">
						<input type="text" name="REGISTER[PERSONAL_MOBILE]" id="REGISTER[PERSONAL_MOBILE]" class="signup__input">		
					</div>
					<div class="col-xs-4">
						<input type="checkbox" id="email" class="signup__checkbox">
						<label for="email" class="signup__notice">Получать новости по электронной почте</label>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<div class="col-xs-5">
						<label for="REGISTER[CAPTCHA_WORD]" class="signup__label">введите защитный код</label>
						<div class="signup__captcha"></div>
					</div>
					<div class="col-xs-2">
						<a href="#" class="signup__refresh"><?=svg('refresh')?></a>
					</div>
					<div class="col-xs-5">
						<label for="REGISTER[CAPTCHA_WORD]" class="signup__label">в это поле *</label>
						<input type="text" name="REGISTER[CAPTCHA_WORD]" id="REGISTER[CAPTCHA_WORD]" required class="signup__input">		
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-7"></div>
			<div class="col-sm-5 right">
				<div class="row">
					<div class="col-md-6 col-md-offset-6">
						<input type="submit" value="регистрация" class="signup__submit">
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>