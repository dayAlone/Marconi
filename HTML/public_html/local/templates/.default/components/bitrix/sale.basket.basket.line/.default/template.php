<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<? if($arResult['NUM_PRODUCTS']>0):?>
<a href="#" class="cart"><?=svg('cart')?>В корзине: <span class="cart__num"><?=$arResult['NUM_PRODUCTS']?></span> <?=$arResult['PRODUCT(S)']?></a>
<? else: ?>
	<span class="cart"><?=svg('cart')?> Корзина пуста</span>
<? endif;?>