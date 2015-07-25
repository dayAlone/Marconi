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
			if(intval($key) == $key):
				$code = false;
				$value = strip_tags(html_entity_decode($value));
				switch ($value) {
					case "Неверно введено слово с картинки":
						$code = "captcha_word";
						break;
				}
				if(strstr($value,'e-mail'))
					$code = "LOGIN";
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
