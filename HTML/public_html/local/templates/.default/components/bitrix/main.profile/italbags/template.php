<?
/**
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
?>

<div class="bx-auth-profile row">
	<div class="col-sm-6 col-md-12">
		<div class="page__title">Профиль</div>
		<?ShowError($arResult["strProfileError"]);?>

		<?
		if ($arResult['DATA_SAVED'] == 'Y' && !isset($_REQUEST['NEW_PASSWORD']))
			ShowNote(GetMessage('PROFILE_DATA_SAVED'));
		?>
		<form data-parsley-validate method="post" name="form1" class="profile" action="<?=$arResult["FORM_TARGET"]?>" enctype="multipart/form-data">

		<?=$arResult["BX_SESSION_CHECK"]?>
		<input type="hidden" name="lang" value="<?=LANG?>" />
		<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
		<input type="hidden" name="EMAIL" maxlength="50" value="<?=$arResult["arUser"]["EMAIL"]?>" />
		<? 
		$arDisabled = array("NAME", "LAST_NAME", "PERSONAL_GENDER", "WORK_COMPANY");
		foreach (array("NAME", "LAST_NAME", "PERSONAL_GENDER", "USER_COMPANY", "DIVIDER", "LOGIN", "PERSONAL_PHONE", "UF_SMS", "WORK_PHONE", "PERSONAL_MOBILE") as $item):
			switch ($item):
				case 'DIVIDER':
					?><div class="m-margin-top xxl-margin-bottom"><p>Для того, чтобы изменить имя, фамилию, пол и наименование организации, необходимо отправить запрос администратору сайта <a href="mailto:admin@italbags.ru">admin@italbags.ru</a> со всеми необходимыми данными.</p></div><?
					break;
				case "PERSONAL_GENDER":?>
					<div class="row">
						<div class="col-sm-4"><label class="profile__label" for="#<?=$item?>"><span><?=GetMessage($item)?></span></label></div>
						<div class="col-sm-8 profile__gender">
							<input type="radio"  <?=(in_array($item, $arDisabled)?"disabled":"")?> id="<?=$item?>-1" value="M" <?=($arResult["arUser"][$item]=="M"?"checked":"")?> name="<?=$item?>">
							<label for="<?=$item?>-1" class=" <?=(in_array($item, $arDisabled)?"disabled":"")?>">Мужской</label>
							<input type="radio" <?=(in_array($item, $arDisabled)?"disabled":"")?> id="<?=$item?>-2" value="F" <?=($arResult["arUser"][$item]=="F"?"checked":"")?> name="<?=$item?>">
							<label for="<?=$item?>-2" class=" <?=(in_array($item, $arDisabled)?"disabled":"")?>">Женский</label>
						</div>
					</div>
				<?
				break;
				case "UF_SMS":
				?>
					<div class="row">
						<div class="col-md-8 col-md-offset-4">
						<?if($arResult['USER_PROPERTIES']['DATA'][$item]['SETTINGS']['CHECKBOX'] == ""):?>
							<input type="hidden" name="<?=$item?>" value="0"/>
							<nobr><input type="checkbox" id="<?=$item?>-1" name="<?=$item?>" maxlength="50" value="1" <?=($arResult["arUser"][$item]==1?"checked":"")?>/>
							<label class="profile__label profile__label--consedered" for="<?=$item?>-1"><?=$arResult['USER_PROPERTIES']['DATA'][$item]['EDIT_FORM_LABEL']?></label></nobr>
						<?endif;?>
						</div>
					</div>
				<?
				break;
				default:
				?>
				<div class="row">
					<div class="col-sm-4">
					<label class="profile__label" for="#<?=$item?>"><span><?=GetMessage($item)?></span></label>
					<?if($item == 'LOGIN'):?>
						<div class="s-line-height">
							<p>Является логином для входа на сайт</p>
						</div>
					<?endif;?>
					</div>
					<div class="col-sm-8">
						<input type="text" <?=(in_array($item, $arDisabled)?"disabled":"")?> <?=(in_array($item, array("NAME", "LOGIN"))?"required":"")?>  id="<?=$item?>" name="<?=$item?>" maxlength="50" value="<?=$arResult["arUser"][$item]?>" />
						<?if($item == 'LOGIN'):
							CModule::IncludeModule("subscribe");
							$aSubscr = CSubscription::GetUserSubscription();
							
							?>
							<div class="s-margin-top">
							<nobr>
								<input type="checkbox" id="maillist" <?=($aSubscr['ID'] > 0?"checked":"")?> name="maillist" maxlength="50" value="1" />
								<label class="profile__label profile__label--consedered" for="maillist">Подписаться на рассылку новостей</label>
							</nobr>
							</div>
						<?endif;?>
					</div>
				</div>
				<?
				break;
			endswitch;
		endforeach;
		?>
		<div class="row">
			<div class="col-md-offset-4 col-md-8">
				<input type="submit" name="save" class="product__big-button product__big-button--border" value="Сохранить">
			</div>
		</div>
		</form>
	</div>
	<div class="col-sm-6 col-md-12 password-change">
		<div class="page__title page__title--full-width"><small>Изменение пароля</small></div>
		<?
		if ($arResult['DATA_SAVED'] == 'Y' && isset($_REQUEST['NEW_PASSWORD']))
			ShowNote(GetMessage('PROFILE_DATA_SAVED'));
		?>
		<form data-parsley-validate method="post" name="form1" class="profile" action="<?=$arResult["FORM_TARGET"]?>" enctype="multipart/form-data">
			<?=$arResult["BX_SESSION_CHECK"]?>
			<input type="hidden" name="lang" value="<?=LANG?>" />
			<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
			<input type="hidden" name="EMAIL" maxlength="50" value="<?=$arResult["arUser"]["EMAIL"]?>" />
			<input type="hidden" name="LOGIN" maxlength="50" value="<?=$arResult["arUser"]["LOGIN"]?>" />
			<? foreach (array("NEW_PASSWORD", "NEW_PASSWORD_CONFIRM") as $item):?>
				<div class="row">
					<div class="col-md-4"><label class="profile__label" for="#<?=$item?>"><?=GetMessage($item)?></label></div>
					<div class="col-md-8">
						<input type="password" data-parsley-mincheck="6" required <?=($item=="NEW_PASSWORD_CONFIRM"?'data-parsley-equalto="#password"':"id='password'")?>  id="<?=$item?>" name="<?=$item?>" maxlength="50" value="<?=$arResult["arUser"][$item]?>" />
					</div>
				</div>
			<? endforeach;?>
			<div class="row xxl-margin-bottom">
				<div class="col-md-offset-4 col-md-8">
					<input type="submit" name="save" class="xxl-margin-bottom product__big-button product__big-button--border" value="Изменить пароль">
				</div>
			</div>
		</form>
	</div>
</div>