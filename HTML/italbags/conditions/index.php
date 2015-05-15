<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty('body_class', "about");
$APPLICATION->SetTitle('Условия работы');
?>
<div class="container textpage__content">
	<h1 class="center">Условия работы</h1>
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
							Чтобы заказывать товары через сайт пройдите <a href="">регистрацию</a>.
						</p>
					</div>
					<div id="new-2" class="condition-theme__content-item">
						<p>
							Для активации вашего аккаунта позвоните по нашему <a href="/contacts/">контактному телефону</a>.
						</p>
					</div>
					<div id="new-3" class="condition-theme__content-item">
						<p>
							Как только вы активировали аккаунт, можете приступать к <a href="/catalog/">покупкам на сайте</a>.
						</p>
					</div>
					<div id="new-4" class="condition-theme__content-item">
						<p>
							Когда мы получаем Ваш заказ ,оформленный на сайте, его собирают на нашем складе и только после того как заказ собран, Вам выставляется счет, который нужно оплатить в течении трех банковских дней, считая со следующего дня после выставления. 
						</p>
						<p>
							<strong>ВНИМАНИЕ!!!</strong> После того как Вы получили счет, Ваш заказ ,фактически, собран и упакован на нашем складе! Поэтому заказ можно корректировать только в сторону увеличения, в этом случае счёт высылается повторно.
						</p>
					</div>
					<div id="new-5" class="condition-theme__content-item">
						<p>
							Чтобы заказывать товары через сайт пройдите <a href="">регистрацию</a>.
						</p>
					</div>
					<div id="new-6" class="condition-theme__content-item">
						<p>
							Для активации вашего аккаунта позвоните по нашему <a href="/contacts/">контактному телефону</a>.
						</p>
					</div>
				</div>
				
			</div>
			<div id="exist" class="condition-theme condition-theme--short">
				<div class="condition-theme__nav">
					<a href="#current-1" class="condition-theme__nav-item condition-theme__nav-item--active">1. авторизация</a>
					<a href="#current-2" class="condition-theme__nav-item">2. оформление заказа</a>
					<a href="#current-3" class="condition-theme__nav-item">3. оплата и сборка заказа</a>
					<a href="#current-4" class="condition-theme__nav-item">4. получение заказа</a>
					<div class="condition-theme__arrow"></div>
				</div>
				<div class="condition-theme__content">
					<div id="current-1" class="condition-theme__content-item condition-theme__content-item--active">
						<p>
							Чтобы заказывать товары через сайт пройдите <a href="">регистрацию</a>.
						</p>
					</div>
					<div id="current-2" class="condition-theme__content-item">
						<p>
							Для активации вашего аккаунта позвоните по нашему <a href="/contacts/">контактному телефону</a>.
						</p>
					</div>
					<div id="current-3" class="condition-theme__content-item">
						<p>
							Как только вы активировали аккаунт, можете приступать к <a href="/catalog/">покупкам на сайте</a>.
						</p>
					</div>
					<div id="current-4" class="condition-theme__content-item">
						<p>
							Когда мы получаем Ваш заказ ,оформленный на сайте, его собирают на нашем складе и только после того как заказ собран, Вам выставляется счет, который нужно оплатить в течении трех банковских дней, считая со следующего дня после выставления. 
						</p>
						<p>
							<strong>ВНИМАНИЕ!!!</strong> После того как Вы получили счет, Ваш заказ ,фактически, собран и упакован на нашем складе! Поэтому заказ можно корректировать только в сторону увеличения, в этом случае счёт высылается повторно.
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