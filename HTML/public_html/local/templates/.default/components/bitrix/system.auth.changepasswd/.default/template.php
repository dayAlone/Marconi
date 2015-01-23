<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<?
ShowMessage($arParams["~AUTH_RESULT"]);
?>
<form data-parsley-validate method="post" action="<?=$arResult["AUTH_FORM"]?>" name="bform"  data-action="/include/user.php?action=login" class="change__form">
	<?if (strlen($arResult["BACKURL"]) > 0): ?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
	<? endif ?>
	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="CHANGE_PWD">
	<label><?=GetMessage("AUTH_LOGIN")?></label>
	<input required type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" class="bx-auth-input" />

	<input required type="hidden" name="USER_CHECKWORD" maxlength="50" value="<?=$arResult["USER_CHECKWORD"]?>" class="bx-auth-input" />
	
	<label><?=GetMessage("AUTH_NEW_PASSWORD_REQ")?></label>
	<input required type="password" id="password" name="USER_PASSWORD" maxlength="50" value="<?=$arResult["USER_PASSWORD"]?>" class="bx-auth-input" />
	
	<label><?=GetMessage("AUTH_NEW_PASSWORD_CONFIRM")?></label>
	<input required type="password"  data-parsley-equalto="#password" name="USER_CONFIRM_PASSWORD" maxlength="50" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" class="bx-auth-input"  />
	
	<div class="center">
		<input type="submit" name="change_pwd" class="product__big-button product__big-button--border m-margin-top" value="<?=GetMessage("AUTH_CHANGE")?>" />	
	</div>
	

</form>
