<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty("description", "Официальный сайт и интернет-магазин сумок и аксессуаров Francesco Marconi");
$APPLICATION->SetPageProperty("keywords", "francesco marconi, сумки francesco marconi, francesco marconi сайт, купить сумку francesco marconi, франческо маркони, купить франческо маркони");
$APPLICATION->SetTitle("Francesco Marconi");
$APPLICATION->SetPageProperty('body_class', "index index--italbags");
$v = getHighloadElements('categories', 'UF_XML_ID', 'ID');
?>
<div class="container">
	<a href="/" class="index__logo">
		<?=svg('italbags')?>
	</a>
	<div class="index__block">
		<span>
			оптовые поставки <br>женских и мужских <br>сумок и аксессуаров
		</span>
		<h1>francesco marconi <br>gilda tonelli </h1>
	</div>
	<div class="news news--index">
	<?
		$APPLICATION->IncludeComponent("bitrix:news.list", "news", 
			array(
				"IBLOCK_ID"            => 19,
				"NEWS_COUNT"           => "1",
				"SORT_BY1"             => "ACTIVE_FROM",
				"SORT_ORDER1"          => "DESC",
				"DETAIL_URL"           => "/news/#ELEMENT_CODE#/",
				"CACHE_TYPE"           => "A",
				'PROPERTY_CODE'        => array('TITLE', 'PICTURES'),
				"SET_TITLE"            => "N",
				"DISPLAY_PREVIEW_TEXT" => "N",
				"DISPLAY_BOTTOM_PAGER" => "N",
				"DETAIL"               => "Y",
				"CLASS"                =>"news--index"
			),
			false
		);
	?>
	</div>
</div>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>