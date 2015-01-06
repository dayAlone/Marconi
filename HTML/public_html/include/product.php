<? 
	global $items;
	foreach ($arResult['ITEMS'] as $key => $item):
?>
<div class="col-xs-4 col-lg-3">
	<div class="product">
	  <div class="product__content">
	  	<a href="<?=$item['DETAIL_PAGE_URL']?>" class="product__picture-frame">
	    	<div data-bg="<?=(isset($item['PREVIEW_PICTURE']['SRC'])?$item['PREVIEW_PICTURE']['SRC']:"/layout/images/no-image.jpg")?>" class="product__picture <?=(!$item['PREVIEW_PICTURE']['SRC']?"product__picture--no":"")?>"></div>
	    </a>
	    <div class="product__brand"><?=$arResult['BRANDS'][$item['PROPERTIES']['BRAND']['VALUE']]?></div>
	    <div class="product__name"><?=$item['NAME']?></div>
	    <div class="product__price">
	    <? if(isset($item['MIN_PRICE']['VALUE'])): ?>
	      <?=number_format($item['MIN_PRICE']['VALUE'], 0, '.', ' ')?> ₷
	    <? else: ?>
	      <small>Товара нет в наличии</small>
	    <? endif; ?>
	    </div>
	  </div>
	  <div class="product__hidden">
	    <div class="product__frame"></div>
	    <?if(count($item['PROPERTIES']['PICTURES']['VALUE'])>0&&isset($item['PREVIEW_PICTURE']['SRC'])):?>
	      <a href="#" class="product__icon product__icon--zoom" data-pictures='<?=json_encode($item['PROPERTIES']['PICTURES']['VALUE'])?>'><?=svg('zoom')?></a>
	    <?endif;?>
	    <?if(isset($item['MIN_PRICE']['VALUE'])):?>
	    <a href="#" class="product__icon product__icon--cart <?=(count($item['OFFERS'])>0?"product__icon--trigger":"")?>"><?=svg('cart')?></a>
	    <?endif;?>
	    <a href="<?=$item['DETAIL_PAGE_URL']?>" class="product__button product__button--center product__button--more">Подробнее</a>
	    <?/*<a href="" class="product__button product__button--simmilar">Сравнить</a>*/
	    if(count($item['OFFERS'])>0):
	      ?>
	      <div class="product__sizes">
	        <div class="product__brand">Выберите размер</div>
	          <? foreach($item['OFFERS'] as $k=>$size):?>
	            <a href="#" class="product__size <?=($k==0?"product__size--active":"")?>" data-id="<?=$size['ID']?>"> <?=$arResult['SIZES'][$size['DISPLAY_PROPERTIES']['SIZE']['VALUE']]?> </a>
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
<? endforeach; ?>