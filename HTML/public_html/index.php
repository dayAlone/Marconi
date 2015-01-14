<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty('body_class', "index");
$v = getHighloadElements('categories', 'UF_XML_ID', 'ID');
?>
<div class="background"></div>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>