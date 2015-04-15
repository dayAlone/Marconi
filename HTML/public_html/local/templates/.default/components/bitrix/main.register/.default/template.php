<?
$this->setFrameMode(true);
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
?>
<?if($USER->IsAuthorized()):?>
<p><?echo GetMessage("MAIN_REGISTER_AUTH")?></p>
<?else:?>
<?
if (count($arResult["ERRORS"]) > 0):
	foreach ($arResult["ERRORS"] as $key => $error)
		if (intval($key) == 0 && $key !== 0) 
			$arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;", $error);

	ShowError(implode("<br />", $arResult["ERRORS"]));

elseif($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):
?>
<p><?echo GetMessage("REGISTER_EMAIL_WILL_BE_SENT")?></p>
<?endif?>

<form method="post" data-parsley-validate action="<?=POST_FORM_ACTION_URI?>" name="regform" class="register__form" enctype="multipart/form-data"  data-action="/include/user.php?action=register">
<?if($arResult["BACKURL"] <> ''):?><input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" /><?endif;?>

<?
$arResult["SHOW_FIELDS"] = array_merge($arParams['SHOW_FIELDS'], array_diff($arResult["SHOW_FIELDS"], $arParams['SHOW_FIELDS']));

foreach ($arResult["SHOW_FIELDS"] as $FIELD):?>
	<?if($FIELD!="EMAIL"):?><label><?=GetMessage("REGISTER_FIELD_".$FIELD)?></label><?endif;?>
	<?
	switch ($FIELD)
	{
		case "PASSWORD":
			?><input size="30" type="password" data-parsley-mincheck="6" data-parsley-mincheck-message="Введите не менее 6 символов" id="reg-password" <?=($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"?"required":"")?> name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" class="bx-auth-input" /><?
			break;
		case "CONFIRM_PASSWORD":
			?><input size="30" type="password" data-parsley-mincheck="6" data-parsley-mincheck-message="Введите не менее 6 символов" data-parsley-equalto-message="Пароли не совпадают" data-parsley-equalto="#reg-password" <?=($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"?"required":"")?> name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" /><?
			break;
		case "EMAIL":
			?><input size="30" type="hidden" <?=($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"?"required":"")?> name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" /><?
			break;
		default:
			if ($FIELD == "PERSONAL_BIRTHDAY"):?><small><?=$arResult["DATE_FORMAT"]?></small><br /><?endif;
			?><input size="30" type="<?=($FIELD=='LOGIN'?"email":"text")?>" <?=($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"?"required":"")?> name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" /><?
				if ($FIELD == "PERSONAL_BIRTHDAY")
					$APPLICATION->IncludeComponent(
						'bitrix:main.calendar',
						'',
						array(
							'SHOW_INPUT' => 'N',
							'FORM_NAME' => 'regform',
							'INPUT_NAME' => 'REGISTER[PERSONAL_BIRTHDAY]',
							'SHOW_TIME' => 'N'
						),
						null,
						array("HIDE_ICONS"=>"Y")
					);
				?><?
	}?>
<?endforeach?>
<?// ********************* User properties ***************************************************?>
<?if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>
	<tr><td colspan="2"><?=strlen(trim($arParams["USER_PROPERTY_NAME"])) > 0 ? $arParams["USER_PROPERTY_NAME"] : GetMessage("USER_TYPE_EDIT_TAB")?></td></tr>
	<?foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField):?>
	<tr><td><?=$arUserField["EDIT_FORM_LABEL"]?>:<?if ($arUserField["MANDATORY"]=="Y"):?><span class="starrequired">*</span><?endif;?></td><td>
			<?$APPLICATION->IncludeComponent(
				"bitrix:system.field.edit",
				$arUserField["USER_TYPE"]["USER_TYPE_ID"],
				array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField, "form_name" => "regform"), null, array("HIDE_ICONS"=>"Y"));?></td></tr>
	<?endforeach;?>
<?endif;?>
<?// ******************** /User properties ***************************************************?>
<div class="row xs-margin-top">
	<div class="col-xs-5">
	  <label class="left">введите данный код</label>
	  <?
		
      ?>
	  <input type="hidden" name="captcha_sid" value="<?=$code?>" />
	  <div class="captcha" style="background-image:url(/include/captcha.php?captcha_sid=<?=$code?>)"></div>
	</div>
	<div class="col-xs-2 no-padding center">
	  
	  <input type="hidden" name="captcha_code" value="<?=$code?>">
	  <a href="#" class="captcha_refresh">
	    <?=svg('refresh')?>
	  </a>
	</div>
	<div class="col-xs-5">
	  <label class="right">в это поле</label>
	  <input name="captcha_word" type="text" required>
	</div>
</div>
<div class="center">
	<input type="submit" class="product__big-button product__big-button--border m-margin-top" name="register_submit_button" value="<?=GetMessage("AUTH_REGISTER")?>" />
</div>
</form>
<?endif?>