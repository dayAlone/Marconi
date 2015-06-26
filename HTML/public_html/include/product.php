<?
/*if (!function_exists('getProduct')):
	function getProduct($item, $arResult, $arParams)
	{*/
?>
<div class="col-xs-6 col-sm-4 col-lg-3">
	<div class="product <?=(!$arParams['SHOW_PRICE']?"product--without-price":"")?>" data-id="<?=$item["ID"]?>" data-artnumber="<?=$item['PROPERTIES']['ARTNUMBER']['VALUE']?>">
	  <div class="product__content">
	  	<a href="<?=$item['DETAIL_PAGE_URL']?>" class="product__picture-frame">
	    	<div data-bg="<?=(isset($item['PREVIEW_PICTURE']['SRC'])?$item['PREVIEW_PICTURE']['SRC']:"/layout/images/no-image.jpg")?>" class="product__picture <?=(!$item['PREVIEW_PICTURE']['SRC']?"product__picture--no":"")?>"></div>
	    </a>
	    <div class="product__content-text">
		    
		    <? 
		    if(strlen($item['PROPERTIES']['BRAND']['VALUE'])>0):?>
		    	<div class="product__type">
		    	<?=str_replace($arResult['BRANDS'][$item['PROPERTIES']['BRAND']['VALUE']], '</div><div class="product__brand">'.$arResult['BRANDS'][$item['PROPERTIES']['BRAND']['VALUE']].'</div><div class="product__name">', $item['NAME'])?>
		    	</div>
		    <? else: ?>
				<div class="product__brand"><?=$item['NAME']?></div>
		    <?endif;?>
		    <?if(strlen($item['PROPERTIES']['ARTNUMBER']['VALUE'])>0):?>
		    	<div class="product__artnumber">Арт. <?=$item['PROPERTIES']['ARTNUMBER']['VALUE']?></div>
		    <?endif;?>
		    <?if($arParams['SHOW_PRICE']):?>
			    <div class="product__price">
			    <? if(isset($item['PRICE'])&&intval($item['PRICE'])!=0): ?>
			    	<? if($item['PROPERTIES']['SALE']['VALUE'] == "77ebb502-85d4-11e4-82e4-0025908101de" && SITE_ID != 's1'):?>
						<?=number_format($item['PRICE']*.7, 0, '.', ' ')?> ₷
						<del><?=number_format($item['PRICE'], 0, '.', ' ')?> ₷</del>
					<?else:?>
			      		<?=number_format($item['PRICE'], 0, '.', ' ')?> ₷
			      	<?endif;?>
			    <? else: ?>
			      <small>Товара нет в наличии</small>
			    <? endif; ?>
			    </div>
			<? endif;?>
		</div>
		<? if(strlen($item['PROPERTIES']['SALE']['VALUE']) > 0):
			if($item['PROPERTIES']['SALE']['VALUE']=="77ebb501-85d4-11e4-82e4-0025908101de"): ?>
	    	<div class="product__sale <?=(SITE_ID == 's1'?"":"product__sale--big")?>">
	    		<span><?=(SITE_ID == 's1'?"Уникальная<br>цена":"SALE")?></span>
	    	</div>
	    	<? 	
	    	elseif(SITE_ID != 's1'):?>
			<div class="product__sale">
				<span>Скидка<br>30%</span>
			</div>
	    	<?endif;
	    elseif(strlen($item['PROPERTIES']['NEW']['VALUE']) > 0 && SITE_ID != 's1'):?>
	    	<div class="product__sale">
				<span>NEW</span>
			</div>
	    <? 
	    elseif(strlen($item['PROPERTIES']['TRADELINE']['VALUE'])>0): ?>
	    	<div class="product__tradeline"><?=$arResult['TRADELINES'][$item['PROPERTIES']['TRADELINE']['VALUE']]?></div>
	    <? endif; ?>
	  </div>
	  <div class="product__hidden">
	    <div class="product__frame"></div>
	    
	    <?if(count($item['PROPERTIES']['PICTURES']['VALUE'])>0&&isset($item['PREVIEW_PICTURE']['SRC'])):?>
	      <a href="#" class="product__icon product__icon--zoom" data-pictures='<?=json_encode($item['PROPERTIES']['PICTURES']['VALUE'])?>'><?=svg('zoom')?></a>
	    <?endif;?>
	    
	    <?if(isset($item['PRICE']) && $arParams['SHOW_PRICE']):?>
	    <a href="#" class="product__icon product__icon--cart <?=(count($item['OFFERS'])>0?"product__icon--trigger":"")?>" data-id="<?=$item['ID']?>" data-artnumber="<?=$item['PROPERTIES']['ARTNUMBER']['VALUE']?>"><?=svg('cart')?></a>
	    <?endif;?>
	    <?
	    if($arParams['HIDE_MORE'] != "Y"):?>
	    	<a href="<?=$item['DETAIL_PAGE_URL']?>" class="product__button product__button--more">Подробнее</a>
	    <?endif;?>
	    <?if($arParams['HIDE_SIMMILAR'] != "Y"):?>
	    	<a href="#" data-id="<?=$item['ID']?>" class="product__button product__button--simmilar"><?=($arParams['COMPARE_TEXT']?$arParams['COMPARE_TEXT']:"Сравнить")?></a>
	    <?endif;?>
	    <?if($arParams['SHOW_COUNT'] == "Y" && $arParams['SHOW_PRICE']):?>
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
	          <? foreach($item['OFFERS'] as $k=>$size):?>
	            <a href="#" class="product__size <?=($k==0?"product__size--active":"")?>" data-id="<?=$size['ID']?>" data-size="<?=$arResult['SIZES'][$size['DISPLAY_PROPERTIES']['SIZE']['VALUE']]?>"> <?=$arResult['SIZES'][$size['DISPLAY_PROPERTIES']['SIZE']['VALUE']]?> </a>
	          <? endforeach; ?>
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