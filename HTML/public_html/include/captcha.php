<?
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC","Y");
define("NOT_CHECK_PERMISSIONS", true);

$HTTP_ACCEPT_ENCODING = "";
$_SERVER["HTTP_ACCEPT_ENCODING"] = "";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/captcha.php");
$cpt = new CCaptcha();
if($_REQUEST['captcha_sid']) {
$cpt->SetImageSize(150,35);
	
	$cpt->SetLinesNumber(0);
	$cpt->SetEllipsesNumber(0);
	$cpt->SetTextColor(array(array(0,0), array(0,0), array(0,0)));
	$cpt->SetTextWriting($cpt->angleFrom, $cpt->angleTo, 5, 20, 26, 18);
	$cpt->SetBorderColorRGB("ffffff");


	if (isset($_GET["captcha_sid"]))
	{
		if ($cpt->InitCode($_GET["captcha_sid"]))
			$cpt->Output();
		else
			$cpt->OutputError();
	}
	elseif (isset($_GET["captcha_code"]))
	{
		$captchaPass = COption::GetOptionString("main", "captcha_password", "");

		if ($cpt->InitCodeCrypt($_GET["captcha_code"], $captchaPass))
			$cpt->Output();
		else
			$cpt->OutputError();
	}
	else
	{
		$cpt->OutputError();
	}
}
else {
    $cpt->SetCodeLength(4);
    $cpt->SetCode();
    $code=$cpt->GetSID();
    echo $code;
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>