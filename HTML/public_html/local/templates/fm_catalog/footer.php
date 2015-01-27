<?if(!isset($_REQUEST['short'])):?>
	</div>
</div>
<div class="catalog__card hidden">
	<img src="/layout/images/card.png" alt=""><br>
	<span class="catalog__card-text">Только в нашем интернет-магазине скидка на все товары 10% по вашей новой дисконтной карте, которую мы вам дарим.<br></span>
	<a href="#" class="catalog__card-button">Отлично! Спасибо!</a>
</div>
<div class="catalog__card-frame hidden"></div>
<?
require($_SERVER['DOCUMENT_ROOT'].'/include/footer.php');
?>
<?endif;?>