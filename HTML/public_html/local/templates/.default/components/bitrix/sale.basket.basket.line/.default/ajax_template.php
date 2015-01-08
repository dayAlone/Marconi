<?if($arResult['NUM_PRODUCTS']>0):?>
	<a href="/basket/" class="cart"><?=svg('cart')?>В корзине: <span class="cart__num"><?=$arResult['NUM_PRODUCTS']?></span> <?=$arResult['PRODUCT(S)']?></a>
<? else: ?>
	<span class="cart"><?=svg('cart')?> Корзина пуста</span>
<? endif;?>