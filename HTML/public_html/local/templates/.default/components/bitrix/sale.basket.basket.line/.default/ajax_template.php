<?
$frame = $this->createFrame()->begin();
$frame->setBrowserStorage(true);
$_SESSION['ELEMENS'] = array();
foreach($arResult['CATEGORIES']['READY'] as $item)
	$_SESSION['ELEMENS'][] = $item['PRODUCT_ID'];
if($arResult['NUM_PRODUCTS']>0):
	$count = 0;
	foreach ($arResult['CATEGORIES']['READY'] as $item)
		$count += $item['QUANTITY'];
	?>
	<a href="/basket/" class="cart"><?=svg('cart')?>В корзине: <span class="cart__num"><?=$count?></span> товар<?=BasketNumberWordEndings($count)?></a>
<? else: ?>
	<span class="cart"><?=svg('cart')?> Корзина пуста</span>
<? endif;
$frame->beginStub();
?>
	<span class="cart"><?=svg('cart')?> Корзина пуста</span>
<?
$frame->end();
?>
