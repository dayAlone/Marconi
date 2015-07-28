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
					<br class="hidden-xs"><br class="hidden-xs">
	    		</div>
	    		<div class="col-md-2 col-xs-12 contacts__button xxl-margin-bottom">
	    			<a href="#feedback" data-toggle="modal" data-target="#feedback" class="product__big-button product__big-button--border full-width xxl-margin-bottom">Напишите нам</a>
	    		</div>
				<div class="col-md-5 col-sm-6 clearfix s-margin-top">
					<div class="page__title">
						<div class="row">
							<div class="col-sm-8">Реквизиты</div>
							<div class="col-sm-4 sm-right"><a href="#requisits" class="toggle">Развернуть</a></div>
						</div>
					</div>
					<div class="hidden" id="requisits">
						<div class="row">
							<div class="col-sm-6">Наименование предприятия</div>
							<div class="col-sm-6">ООО «Мегатрон»</div>
						</div>
						<div class="row xs-margin-top">
							<div class="col-sm-6">ИНН / КПП</div>
							<div class="col-sm-6">7723576871 / 772301001</div>
						</div>
						<div class="row xs-margin-top">
							<div class="col-sm-6">ОГРН</div>
						<div class="col-sm-6">1067746756494</div>
						</div>
							<div class="row xs-margin-top">
							<div class="col-sm-6">ОКПО</div>
						<div class="col-sm-6">96044634</div>
						</div>
							<div class="row xs-margin-top">
							<div class="col-sm-6">ОКВЭД</div>
							<div class="col-sm-6">52.43 - Розничная торговля кожаными изделиями</div>
						</div>
						<div class="row xs-margin-top">
							<div class="col-sm-6">Юридический адреc</div>
							<div class="col-sm-6">109469, г. Москва, Перервинский б-р, д. 27, корп. 1, кв. 15</div>
						</div>
						<div class="row xs-margin-top">
							<div class="col-sm-6">Расчетный счет</div>
							<div class="col-sm-6">40702810638050020496</div>
						</div>
						<div class="row xs-margin-top">
							<div class="col-sm-6">Корреспондентский счет</div>
							<div class="col-sm-6">30101810400000000225</div>
						</div>
						<div class="row xs-margin-top">
							<div class="col-sm-6">БИК</div>
							<div class="col-sm-6">044525225</div>
						</div>
						<div class="row xs-margin-top">
							<div class="col-sm-6">Банк</div>
							<div class="col-sm-6">ОАО "СБЕРБАНК РОССИИ" <br >г. Москва</div>
						</div>
						<div class="row xs-margin-top">
							<div class="col-sm-6">Генеральный директор</div>
							<div class="col-sm-6">Шипов Андрей Игоревич</div>
						</div>
					</div>

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