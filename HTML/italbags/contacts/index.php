<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
if(!isset($_REQUEST['short'])):
	$APPLICATION->SetPageProperty('body_class', "contacts");
	$APPLICATION->SetTitle("Контакты");
	?>	<div class="page">
	    <div class="container">
	    	<div class="row xl-margin-top">
	    		<div class="col-md-5 col-sm-6">
	    			<div class="page__title">Контактная информация</div>
	    			<p class="l-line-height">
	    				<strong>Телефон/факс</strong>: (495) 787-2264<br>
						<strong>Бесплатный телефон</strong>: 8-800-555-9045<br>
						<strong>Электронная почта</strong>: <a href="mailto:info@italbags.ru">info@italbags.ru</a> <br>
						<strong>По вопросам заказов через интернет</strong>: <a href="mailto:zakaz@italbags.ru">zakaz@italbags.ru</a> <br>
						<strong>Адрес</strong>: 125212 г.Москва ул. Адмирала Макарова д.8,<br> ООО «Сэлтон»
	    			</p>
	    		</div>
	    		<div class="col-md-5 col-sm-6">
	    			<div class="page__title">График работы</div>
	    			<p>
	    				Пятница с 10 до 18 ч. (без обеда)<br>
						Выходные дни: суббота, воскресенье, а также праздничные дни.
	    			</p>
	    			<div class="row">
	    				<div class="col-md-6">
	    					<a href="#feedback" data-toggle="modal" data-target="#feedback" class="product__big-button product__big-button--border full-width xl-margin-top">Напишите нам</a>
	    				</div>
	    			</div>

	    		</div>
	    		<div class="col-md-2 col-xs-12 right">
	    			<img src="/layout/images/qr.jpg" class="max-width">
	    		</div>
	    	</div>
		</div>
	</div>
	<?
endif;
?>
<div id="contactsMap"></div>
<?

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>
