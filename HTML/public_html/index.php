<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty("description", "Официальный сайт и интернет-магазин сумок и аксессуаров Francesco Marconi");
$APPLICATION->SetPageProperty("keywords", "francesco marconi, сумки francesco marconi, francesco marconi сайт, купить сумку francesco marconi, франческо маркони, купить франческо маркони");
$APPLICATION->SetTitle("Francesco Marconi");
$APPLICATION->SetPageProperty('body_class', "index  index--fmarconi");
$v = getHighloadElements('categories', 'UF_XML_ID', 'ID');
?>
<div class="background"></div>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>
