<?
/*if (!function_exists('getProduct')):
	function getProduct($item, $arResult, $arParams)
	{*/
$props = &$item['PROPERTIES'];
?>
<div class="col-xs-6 col-sm-4 col-lg-3">
	<div data-in="<?=$item['IN_COMPLECT']?>" class="product <?=(!$arParams['SHOW_PRICE']?"product--without-price":"")?>" data-id="<?=$item["ID"]?>" data-artnumber="<?=$props['ARTNUMBER']['VALUE']?>">
	  <div class="product__content">
	  	<a href="<?=$item['DETAIL_PAGE_URL']?>" class="product__picture-frame">
	    	<div data-bg="<?=(isset($item['PREVIEW_PICTURE']['SRC'])?$item['PREVIEW_PICTURE']['SRC']:"/layout/images/no-image.jpg")?>" class="product__picture <?=(!$item['PREVIEW_PICTURE']['SRC']?"product__picture--no":"")?>"></div>
	    </a>
	    <div class="product__content-text">
		    <?
		    if(strlen($props['BRAND']['VALUE'])>0):
		    	if(strstr($item['NAME'], $arResult['BRANDS'][$props['BRAND']['VALUE']])):?>
			    	<div class="product__type">
			    	<?=str_replace($arResult['BRANDS'][$props['BRAND']['VALUE']], '</div><div class="product__brand">'.$arResult['BRANDS'][$props['BRAND']['VALUE']].'</div><div class="product__name">'.(SITE_ID=="s2"?$props['ARTNUMBER']['VALUE']." ":""), $item['NAME'])?>
			    	</div>
			    <? else: ?>
			    	<div class="product__type"><?=$item['NAME']?></div>
			    	<div class="product__brand"><?=$arResult['BRANDS'][$props['BRAND']['VALUE']]?></div>
		    	<?endif;?>
		    <? else: ?>
				<div class="product__brand"><?=$item['NAME']?></div>
		    <?endif;?>
		    <?if(strlen($props['ARTNUMBER']['VALUE'])>0 && SITE_ID == 's1'):?>
		    	<div class="product__artnumber">Арт. <?=$props['ARTNUMBER']['VALUE']?></div>
		    <?endif;?>
		    <?if($arParams['SHOW_PRICE']):?>
			    <div class="product__price">
			    <? if(isset($item['PRICE']) && intval($item['PRICE'])!=0): ?>
			    	<? if($props['SALE']['VALUE'] == "77ebb502-85d4-11e4-82e4-0025908101de" && ((SITE_ID == 's2' && $props['DAY']['VALUE'] != 'Y') || SITE_ID == 's1')):?>
						<?=number_format($item['PRICE']*.7, 0, '.', ' ')?> <span class='rubl'>₽</span>
						<del><?=number_format($item['PRICE'], 0, '.', ' ')?> <span class='rubl'>₽</span></del>
					<?elseif(strlen($props['SALE_TEXT']['VALUE']) > 0 && SITE_ID == 's1'):
						?>
						<?=number_format($item['PRICE']*$item['MIN_PRICE']['DISCOUNT_DIFF_PERCENT']/100, 0, '.', ' ')?> <span class='rubl'>₽</span>
						<del><?=number_format($item['PRICE'], 0, '.', ' ')?> <span class='rubl'>₽</span></del>
					<?else:?>
			      		<?=number_format($item['PRICE'], 0, '.', ' ')?> <span class='rubl'>₽</span>
			      	<?endif;?>
			    <? else: ?>
			      <small>Нет в наличии</small>
			    <? endif; ?>
			    </div>
			<? endif;?>
		</div>
		<?
		if (SITE_ID == 's2' && $props['DAY']['VALUE'] != 'Y') {
			if(strlen($props['SALE']['VALUE']) > 0):
				?>
				<div class="product__sale product__sale--sale<?=($props['SALE']['VALUE'] == "77ebb501-85d4-11e4-82e4-0025908101de" ? '' : "-30")?>">
					<span>
						<?=($props['SALE']['VALUE'] == "77ebb501-85d4-11e4-82e4-0025908101de" ? (SITE_ID=='s1'?"Уникальная цена":"SALE") : "Скидка<br>30%")?>
					</span>
				</div>
			<?
		    elseif(strlen($props['TRADELINE']['VALUE'])>0): ?>
		    	<div class="product__tradeline"><?=$arResult['TRADELINES'][$props['TRADELINE']['VALUE']]?></div>
		    	<?
		    elseif(strlen($props['NEW']['VALUE']) > 0):?>
		    	<div class="product__sale product__sale--new">
					<span>NEW</span>
				</div>

		    <? endif;
		}?>
		<?
		if (SITE_ID == 's1') {
			if(strlen($props['SALE']['VALUE']) > 0 || strlen($props['SALE_TEXT']['VALUE']) > 0):
				?>
				<div class="product__sale product__sale--sale<?=($props['SALE']['VALUE'] == "77ebb501-85d4-11e4-82e4-0025908101de" ? '' : "-30")?>">
					<span>
						<?=(
							$props['SALE']['VALUE'] == "77ebb501-85d4-11e4-82e4-0025908101de"
							? "Уникальная цена" : (strlen($props['SALE_TEXT']['VALUE']) > 0 ? html_entity_decode($props['SALE_TEXT']['VALUE']) : "Скидка<br>30%"))?>
					</span>
				</div>
		    <?
		    elseif(strlen($props['TRADELINE']['VALUE'])>0): ?>
		    	<div class="product__tradeline"><?=$arResult['TRADELINES'][$props['TRADELINE']['VALUE']]?></div>
		    <? endif;
		}?>
	    <? if(SITE_ID != 's1'):
			if ($props['SHOWCASE']['VALUE'] == 'Y') {?>
	    		<div class="product__showcase">Витринный экземпляр</div>
			<?} else if ($props['DAY']['VALUE'] == 'Y') {?>
				<div class="product__showcase">Товар дня</div>
			<?}
		endif; ?>
	  </div>
	  <div class="product__hidden">
	    <div class="product__frame"></div>

	    <?if(count($props['PICTURES']['VALUE'])>0&&isset($item['PREVIEW_PICTURE']['SRC'])):?>
	      <a href="#" class="product__icon product__icon--zoom" data-pictures='<?=json_encode($props['PICTURES']['VALUE'])?>'><?=svg('zoom')?></a>
	    <?endif;?>

	    <?
		if(isset($item['PRICE']) && $arParams['SHOW_PRICE'] && !($item['IN_COMPLECT'] && SITE_ID != 's1')):?>
	    	<?if(SITE_ID == 's2' && $arResult['SETS'][$item['ID']]['TYPE'] == CCatalogProductSet::TYPE_GROUP):
				$set = $arResult['SETS'][$item['ID']];
				$data = array();
				foreach ($set['ITEMS'] as $val) {
					$tmp = array('id' => $val['ITEM_ID'], 'artnumber'=> $val['PROPERTY_ARTNUMBER_VALUE'], 'quantity'=>$val['QUANTITY']);
					if(strlen($val['PROPERTY_SIZE_VALUE']) > 0)
						$tmp['size'] = $val['PROPERTY_SIZE_VALUE'];
					$data[] = $tmp;
				}
			?>
			<?endif;?>
			<a href="#"
				<?=(count($data)>0?"data-request='".json_encode($data)."'":"")?>
				class="product__icon product__icon--cart <?=(count($item['OFFERS'])>0?"product__icon--trigger":"")?>"
				data-id="<?=$item['ID']?>"
				data-artnumber="<?=$props['ARTNUMBER']['VALUE']?>"
				<?=($props['SHOWCASE']['VALUE'] == 'Y' ? "data-showcase='true'":"")?>
				><?=svg('cart')?></a>

	    <?endif;?>
	    <?
	    if($arParams['HIDE_MORE'] != "Y"):?>
	    	<a href="<?=$item['DETAIL_PAGE_URL']?>" class="product__button product__button--more">Подробнее</a>
	    <?endif;?>
	    <?if($arParams['HIDE_SIMMILAR'] != "Y"):?>
	    	<a href="#" data-id="<?=$item['ID']?>" class="product__button product__button--simmilar"><?=($arParams['COMPARE_TEXT']?$arParams['COMPARE_TEXT']:"Сравнить")?></a>
	    <?endif;?>
	    <?
		if($arParams['SHOW_COUNT'] == "Y" && $props['SHOWCASE']['VALUE'] != "Y" && $arParams['SHOW_PRICE'] && !($item['IN_COMPLECT'] && SITE_ID != 's1')):?>
	    	<div class="product__counter">
	    		<a href="#" class="product__counter-trigger product__counter-trigger--minus">-</a>
	    		<input type="text" class="product__counter-input" value="1">
	    		<a href="#" class="product__counter-trigger product__counter-trigger--plus">+</a>
	    	</div>
	    <?endif;?>
	    <?if(count($item['OFFERS'])>0):
			  ?>
		      <div class="product__sizes">
		        <div class="product__brand">Выберите размер</div>
		          <?
				foreach($item['OFFERS'] as $k=>$size):
					if(!in_array($size['ID'], $arResult['SETS']['LOCKED_OFFERS'])):?>
		            <a href="#" class="product__size <?=($k==0?"product__size--active":"")?>" data-id="<?=$size['ID']?>" data-size="<?=$arResult['SIZES'][$size['DISPLAY_PROPERTIES']['SIZE']['VALUE']]?>"> <?=$arResult['SIZES'][$size['DISPLAY_PROPERTIES']['SIZE']['VALUE']]?> </a>
		          <?endif;
				endforeach; ?>
		        <a href="#" class="product__button product__button--cancel">Отмена</a>
		        <a href="#" class="product__button product__button--buy">В корзину</a>
		      </div>
		      <?
	  endif;
	    ?>

	  </div>
	</div>
</div>
<?
/*	}
endif;*/
?>
