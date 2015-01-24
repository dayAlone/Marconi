<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<?if($arResult["FORM_TYPE"] == "login"):?>
<form data-parsley-validate method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>" data-action="/include/user.php?action=login">
	<?if($arResult["BACKURL"] <> ''):?>
		<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
	<?endif?>
	<?foreach ($arResult["POST"] as $key => $value):?>
		<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
	<?endforeach?>
	<input type="hidden" name="AUTH_FORM" value="Y" />
	<input type="hidden" name="TYPE" value="AUTH" />
	<input type="hidden" name="USER_REMEMBER" value="Y" />
    <label>Ваш e-mail</label>
    <input type="text" name="USER_LOGIN" class="<?=($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR']?"parsley-error":"")?>" maxlength="50" value="<?=$arResult["USER_LOGIN"]?>" size="17" required/>
    <label>Пароль</label>
    <input type="password" name="USER_PASSWORD" class="<?=($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR']?"parsley-error":"")?>" maxlength="50" size="17" required/>
    <div class="center">
      <input type="submit" class="product__big-button product__big-button--border m-margin-top" value="Войти">
       | <a class="lost" href="#forget" data-dismiss="modal" data-toggle="modal" data-target="#forget" data-dismiss="modal">Забыли пароль?</a>
    </div>
</form>
<?endif?>