<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty('body_class', "about");
$APPLICATION->SetTitle('Оптовая закупка');
?>
<div class="container textpage__content">
	<h1 class="center">Оптовая закупка</h1>
	<div class="textpage__divider"></div>
	<div class="center">
		<p class="highlight">
			Чтобы узнать подробности сотрудничества, а также ознакомиться с порядком оформления заказов, пожалуйста, выберите предпочтительный для вас вариант ниже
		</p>
	</div>
	<div class="condition">
		<a href="#" class="condition__trigger">Заказ на сайте</a>
		<div class="condition__content">
			<div class="center">
				<a href="#exist" class="s-margin-right condition__theme-trigger ">Вы уже являетесь нашим клиентом</a>
				<a href="#new" class="s-margin-left condition__theme-trigger">Вы новый клиент нашей компании</a>
			</div>
			<div id="new" class="condition-theme">
				<div class="condition-theme__nav">
					<a href="#new-1" class="condition-theme__nav-item condition-theme__nav-item--active">1. регистрация</a>
					<a href="#new-2" class="condition-theme__nav-item">2. активация</a>
					<a href="#new-3" class="condition-theme__nav-item">3. авторизация</a>
					<a href="#new-4" class="condition-theme__nav-item">4. оформление заказа</a>
					<a href="#new-5" class="condition-theme__nav-item">5. оплата и сборка заказа</a>
					<a href="#new-6" class="condition-theme__nav-item">6. получение заказа</a>
					<div class="condition-theme__arrow"></div>
				</div>
				<div class="condition-theme__content">
					<div id="new-1" class="condition-theme__content-item condition-theme__content-item--active">
						<p>
							Пожалуйста, <a href="/signup/">зарегистрируйтесь</a> на нашем сайте, заполнив все необходимые поля
						</p>
					</div>
					<div id="new-2" class="condition-theme__content-item">
						<p>
							Для активации аккаунта позвоните по телефону 8-495-787-22-64, доб.2405 или отправьте письмо на <a href="mailto:admin@italbags.ru">admin@italbags.ru</a>.
						</p>
					</div>
					<div id="new-3" class="condition-theme__content-item">
						<p>
							Ваш аккаунт активирован. Авторизуйтесь под своим логином и паролем.
						</p>
						<p>Перейдите в раздел «<a href="/catalog/">Каталог</a>».</p>
					</div>
					<div id="new-4" class="condition-theme__content-item">
						<p>
							Выберите понравившуюся модель сумки, проставьте в карточке товара количество, добавьте в «Корзину». Таким образом сформируйте заказ по всем выбранным позициям, добавляя каждую в «корзину». 
						</p>
						<p>
							После отборки зайдите в «<a href="/basket/">Корзину</a>». Проведите необходимые корректировки, проверьте количество. В «примечании к заказу» укажите всю уточняющую информацию по нему. Нажмите кнопку «ОТПРАВИТЬ ЗАКАЗ».
						</p>
					</div>
					<div id="new-5" class="condition-theme__content-item">
						<p>
							После получения нами заказа на электронную почту, его резервируют и физически собирают на складе. Его можно упаковать в пакеты или картонные коробки, если Вы забираете сами или мы доставляем по г. Москве(для доставки по Москве в заказе должно быть не менее 40 сумок). При отправке через транспортную компанию, мы упаковываем товар в скочеванные коробки.
						</p>
						<p>
							Для большей надежности сохранности груза мы можем упаковать коробку в мешок под пломбу (стоимость услуги=50 руб.).Только после набора товара Вам выставляется счет на оплату по e-mail. Оплату необходимо произвести в течение трех дней, начиная со следующего дня после выставления счета.
						</p>
						<p>
							Оплатить Вы можете следующими способами:<br>
							- наличными в кассе у нас в компании<br>
							- банковской картой в кассе <br>
							- безналичным перечислением с Вашего р/счета<br>
							- б/н перечислением с пластиковой карты на наш р/с
						</p>
						<p>
							Внимание! При безналичной форме оплаты в «наименовании/поле назначения платежа» необходимо указать «Оплата по счету № … от … за кожгалантерею на сумму …руб., в том числе НДС… руб. <br>Оплата должна производиться от имени того юридического лица, на кого выписан счет.
						</p>
					</div>
					<div id="new-6" class="condition-theme__content-item">
						<p>
							После поступления оплаты за заказ Вы можете забрать товар с нашего склада, либо мы доставим его до транспортной компании, с которой Вы сотрудничаете, совершенно бесплатно по городу Москве мы доставляем заказы от 40 сумок.
						</p>
					</div>
				</div>
				
			</div>
			<div id="exist" class="condition-theme condition-theme--short">
				<div class="condition-theme__nav">
					<a href="#current-1" class="condition-theme__nav-item condition-theme__nav-item--active">1. авторизация</a>
					<a href="#current-2" class="condition-theme__nav-item">2. оформление заказа</a>
					<a href="#current-3" class="condition-theme__nav-item">3. Получение счета и оплата</a>
					<a href="#current-4" class="condition-theme__nav-item">4. Доставка заказа</a>
					<div class="condition-theme__arrow"></div>
				</div>
				<div class="condition-theme__content">
					<div id="current-1" class="condition-theme__content-item condition-theme__content-item--active">
						<p>
							Авторизуйтесь под своим логином и паролем. Перейдите в раздел «<a href="/catalog/">Каталог</a>».
						</p>
					</div>
					<div id="current-2" class="condition-theme__content-item">
						<p>
							Сформируйте заказ по выбранным позициям, укажите количество каждой цветомодели, добавьте в «<a href="/basket/">Корзину</a>».
						</p>
						<p>
							После отборки зайдите в «<a href="/basket/">Корзину</a>», проведите необходимые корректировки, проверьте количество. В «примечании к заказу» укажите всю уточняющую информацию по нему.
						</p>
					</div>
					<div id="current-3" class="condition-theme__content-item">
						<p>
							После получения нами заказа, его собирают и упаковывают на складе (в пакеты, коробки, мешок под пломбу). После сборки Вам выставляется счет на электронную почту. После получения счета заказ можно корректировать только в сторону увеличения. Оплату необходимо произвести в течение трех банковских дней, начиная со следующего дня после выставления счета.
						</p>
						<p>
							Оплата должна осуществляться строго от того же юридического лица, на какое выписан счет. В поле назначения платежа  указать: «Оплата по счету №… от… на сумму ….руб. за кожгалантерею, в том числе НДС… руб.»
						</p>
					</div>
					<div id="current-4" class="condition-theme__content-item">
						<p>
							После поступления оплаты, Вы можете забрать свой заказ с нашего склада.
						</p>
						<p>
							Мы можем отправить Ваш заказ через транспортную компанию, с которой Вы работаете.
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="condition">
		<a href="#" class="condition__trigger">Заказ в офисе компании</a>
		<div class="condition__content">
			<div class="center">
				<p class="highlight">Вам необходимо приехать к нам в <a href="/contacts/">офис</a> с пакетом документов</p>
			</div>
			<div class="row">
				<div class="col-xs-6">
					<h3>Для юридических лиц</h3>
					<ul class="small">
						<li>свидетельство о государственной регистрации, копия</li>
						<li>свидетельство о постановке на учет в налоговой инспекции, копия</li>
						<li>реквизиты организации</li>
						<li>печать или доверенность</li>
						<li>договор аренды (адреса торговых точек), копия</li>
						<li>ОКПО</li>
						<li>ОКВЭД (ОКДП)</li>
					</ul>
				</div>
				<div class="col-xs-6">
					<h3>Для индивидуальных предпринимателей</h3>
					<ul class="small">
						<li>свидетельство о государственной регистрации, копия</li>
						<li>свидетельство о постановке на учет в налоговой инспекции, копия</li>
						<li>реквизиты </li>
						<li>печать или <a href="#">доверенность</a></li>
						<li>договор аренды (адреса торговых точек), копия</li>
						<li>ОКПО</li>
						<li>ОКВЭД (ОКДП)</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(function(){
			delay(initConditions(), 300)
		})
	</script>
</div>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>