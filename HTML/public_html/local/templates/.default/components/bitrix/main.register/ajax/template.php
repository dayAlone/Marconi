<?
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
<?
	if($USER->IsAuthorized()):
		echo 'success';
	else:
		foreach ($arResult["ERRORS"] as $key => $value):
			if(intval($key)>0):
				$code = false;
				switch (strip_tags($value)) {
					case "Пользователь с таким e-mail (ak@radia.ru) уже существует.":
						$code = "EMAIL";
						break;
					case "Неверно введено слово с картинки":
						$code = "captcha_word";
						break;
				}
				if(strlen($code)>0):
					unset($arResult["ERRORS"][$key]);
					$arResult["ERRORS"][$code] = $value;
				endif;
			endif;
		endforeach;
		if (count($arResult["ERRORS"]) > 0):
			echo json_encode($arResult["ERRORS"], JSON_UNESCAPED_UNICODE);
	endif;
endif?>