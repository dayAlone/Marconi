<?
$frame = $this->createFrame()->begin();
$_SESSION['ELEMENS'] = array();
foreach($arResult['CATEGORIES']['READY'] as $item)
	$_SESSION['ELEMENS'][] = $item['PRODUCT_ID'];
if($arResult['NUM_PRODUCTS']>0):?>
	<a href="/basket/" class="cart"><?=svg('cart')?>В корзине: <span class="cart__num"><?=$arResult['NUM_PRODUCTS']?></span> <?=$arResult['PRODUCT(S)']?></a>
<? else: ?>
	<span class="cart"><?=svg('cart')?> Корзина пуста</span>
<? endif;
$frame->beginStub();
?>
	<span class="cart"><?=svg('cart')?> Корзина пуста</span>
<?
$frame->end();
?>
