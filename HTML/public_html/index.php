<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty('body_class', "index");
AddMessage2Log("Страница кейсов ", "test");
die();
?>
<div class="background"></div>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>