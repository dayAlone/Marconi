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
	    			<p>
	    				Телефон для справок и приема заказов: <nobr>+7 495 972-32-65</nobr><br>
						E-mail: <a href="mailto:info@fmarconi.ru">info@fmarconi.ru</a> <br>
						Адрес: 125212, г. Москва, <nobr>ул. Адмирала Макарова, д. 8</nobr>
	    			</p>
	    		</div>
	    		<div class="col-md-5 col-sm-6">
	    			<div class="page__title">График работы</div>
	    			<p>
	    				Пятница с 10 до 19 ч. (без обеда)<br>
						Выходные дни: суббота, воскресенье, а также праздничные дни.
	    			</p>

	    		</div>
	    		<div class="col-md-2 col-xs-12 contacts__button">
	    			<a href="#feedback" data-toggle="modal" data-target="#feedback" class="product__big-button product__big-button--border full-width xxl-margin-bottom">Напишите нам</a>
	    			<p class="xxl-margin-top full-width visible-md visible-lg">ООО «Мегатрон»</p>
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