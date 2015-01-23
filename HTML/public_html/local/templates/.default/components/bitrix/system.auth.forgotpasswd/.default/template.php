<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<form data-parsley-validate name="bform" class="forget__form" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>"  data-action="/include/user.php?action=login">
<? if (strlen($arResult["BACKURL"]) > 0) { ?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<? } ?>
	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="SEND_PWD">
	<label>Ваш e-mail</label>
	<input type="text" name="USER_LOGIN" required maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" />
	<div class="center">
		<input type="submit" name="send_account_info" class="product__big-button product__big-button--border m-margin-top" value="Восстановить пароль" />
	</div>	
</form>
