<?
$frame = $this->createFrame()->begin();
$frame->setBrowserStorage(true);

	if($arResult['NUM_PRODUCTS']>0):
		$count = 0;
		$_SESSION['ELEMENS'] = array();
		$arIDS = array();
		foreach ($arResult['CATEGORIES']['READY'] as $key => $item) {
			$arIDS[$item['PRODUCT_ID']] = $key;
			$count += $item['QUANTITY'];
			$_SESSION['ELEMENS'][] = $item['PRODUCT_ID'];
		}
		if(SITE_ID == 's2'):
			$rsSets = CCatalogProductSet::getList(
					array('SET_ID' => 'DESC'),
					array(
						'@ITEM_ID' => array_keys($arIDS),
						//'=SET_ID' => 0
					),
					false,
					false,
					array('ID', 'OWNER_ID', 'ITEM_ID', 'TYPE', 'QUANTITY', 'SET_ID')
				);
			$sets = array();
			while ($arItem = $rsSets->Fetch())
			{
				if($arItem['SET_ID'] == 0):
					if($arItem['TYPE'] == CCatalogProductSet::TYPE_GROUP):
						$count -= $arResult['CATEGORIES']['READY'][$arIDS[$arItem['ITEM_ID']]]['QUANTITY'];
					else:
						$count += $sets[$arItem['ITEM_ID']] * $arResult['CATEGORIES']['READY'][$arIDS[$arItem['ITEM_ID']]]['QUANTITY'];
					endif;
				elseif($arItem['TYPE'] != CCatalogProductSet::TYPE_GROUP):

					if(!isset($sets[$arItem['OWNER_ID']])) $sets[$arItem['OWNER_ID']] = 0;
					$sets[$arItem['OWNER_ID']]++;
				endif;
			}
		endif;

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
