<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
	$this->setFrameMode(true);
	$item = &$arResult;
	$props = &$arResult['PROPERTIES'];
?>
<div class="row">
<div class="col-xs-6">
  <div class="picture">
    <div class="row">
      <div class="<?=(count($item['IMAGES'])>1?"col-xs-9 col-lg-10":"col-xs-12")?>">
      	<? if(count($item['IMAGES'])>0 || isset($item['PREVIEW_PICTURE']['SRC'])):
      		$array = array_values($item['IMAGES']);
      	?>
        	<div style="background-image:url(<?=(isset($array[0]['middle'])?$array[0]['middle']:$item['PREVIEW_PICTURE']['SRC'])?>)" class="picture__big"></div>
        	<a data-pictures='<?=(count($item['IMAGES'])>0?json_encode($item['IMAGES']):json_encode(array('src'=>$item['PREVIEW_PICTURE']['SRC'], 'w'=> $item['PREVIEW_PICTURE']['WIDTH'], 'h'=>$item['PREVIEW_PICTURE']['HEIGHT'])))?>' class="picture__zoom"><?=svg('zoom')?></a>
        <? endif;?>
      </div>
      <? if(count($item['IMAGES'])>1):?>
      <div class="col-xs-3 col-lg-2">
      <? foreach ($item['IMAGES'] as $key => $image): ?>
      	<a style="background-image:url(<?=$image['small']?>)" href="<?=$image['middle']?>" class="picture__small <?=($key==0?"picture__small--active":"")?>"></a>
      <? endforeach; ?>

      </div>
      <? endif;?>
    </div>
  </div>
</div>
<div class="col-xs-6">
  <div class="product__description">
    <div class="row">
      <div class="col-lg-6">
        <h1 class="product__title"><?=$item['NAME']?></h1>
        <?
        	global $arFilter;
        	$arFilter = array('PROPERTY_ARTNUMBER' => $props['ARTNUMBER']['VALUE']);
        	$APPLICATION->IncludeComponent("bitrix:news.list", "colors", 
				array(
					"IBLOCK_ID"     => 1,
					"NEWS_COUNT"    => "9999999",
					"CACHE_NOTES"   => $item['ID'],
					"FILTER_NAME"   => "arFilter",
					"SORT_BY1"      => "ID",
					"SORT_ORDER1"   => "ASC",
					"DETAIL_URL"    => "/catalog/",
					"CACHE_TYPE"    => "A",
					'PROPERTY_CODE' => array(),
					"SET_TITLE"     => "N"
				),
				$component
			);
        ?>
      </div>
      <div class="col-lg-6">
        <div class="props">
        <? if(strlen($props['ARTNUMBER']['VALUE'])>0):?>
          <div class="props__item">
            <div class="props__name">артикул</div>
            <div class="props__value"><?=$props['ARTNUMBER']['VALUE']?></div>
          </div>
        <? endif; ?>
        <? if(strlen($props['BRAND']['VALUE'])>0):?>
          <div class="props__item">
            <div class="props__name">бренд</div>
            <div class="props__value"><?=$arResult['BRANDS'][$props['BRAND']['VALUE']]?></div>
          </div>
        <? endif; ?>
        <? 
        	if(count($props['COLOR']['VALUE'])>0):
        		$colors = "";
        		foreach ($props['COLOR']['VALUE'] as $key=>$color)
        			$colors .= ($key!=0?" / ":"").$arResult['COLORS'][$color];

        ?>
          <div class="props__item">
            <div class="props__name">цвет</div>
            <div class="props__value"><?=$colors?></div>
          </div>
        <? endif;?>
        <? if(count($props['MATERIAL']['VALUE'])>0):
        		$materials = "";
        		foreach ($props['MATERIAL']['VALUE'] as $key=>$color)
        			$materials .= ($key!=0?" / ":"").$arResult['MATERIALS'][$color];
       	?>
          <div class="props__item">
            <div class="props__name">материал</div>
            <div class="props__value"><?=$materials?></div>
          </div>
        <? endif;?>
        <? if(strlen($props['SIZE']['VALUE'])>0):?>
          <div class="props__item">
            <div class="props__name">размер</div>
            <div class="props__value"><?=$props['SIZE']['VALUE']?></div>
          </div>
        <? endif; ?>
          <div class="props__item props__item--price">
            <div class="props__name">цена</div>
            <div class="props__value">
            <? if(isset($item['MIN_PRICE']['VALUE'])): ?>
		      <?=number_format($item['MIN_PRICE']['VALUE'], 0, '.', ' ')?> ₷
		    <? else: ?>
		      <small>Товара нет в наличии</small>
		    <? endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6 center-lg">
      	<a href="#" class="product__big-button product__big-button--width">В корзину</a>
      	<? /*<a href="#" class="product__big-button">сравнить</a> */?>
      </div>
      <div class="col-lg-6"><a href="#" class="product__big-button product__big-button--border">наличие в магазинах</a>
        <div class="social-likes social-likes_notext"><div class="facebook"></div><div class="twitter"></div><div class="vkontakte"></div><div class="odnoklassniki"></div></div>
      </div>
    </div>
    <div class="tabs">
      <div class="tabs__title"><a href="#description" class="tabs__trigger tabs__trigger--active">описание</a><a href="#payment" class="tabs__trigger">оплата</a><a href="#delivery" class="tabs__trigger">доставка</a><a href="#refund" class="tabs__trigger">возврат</a><a href="#feedback" class="tabs__trigger">обратная связь</a></div>
      <div id="description" class="tabs__content tabs__content--active">
        <p>Земля традиций и инноваций, фермеров и ремесленников, земля, где искусство встречается с технологиями, где хорошая кухня сочетается с гостеприимством местных жителей. Именно в Абруццо трое молодых братьев — Франческо, Назарено и Абрамо Тонелли, в небольшой мастерской объединив свои навыки и знания, основывают небольшую семейную фабрику в честь своих родителей.</p>
      </div>
      <div id="payment" class="tabs__content">
        <p>Земля традиций и инноваций, фермеров и ремесленников, земля, где искусство встречается с технологиями, где хорошая кухня сочетается с гостеприимством местных жителей. Именно в Абруццо трое молодых братьев — Франческо, Назарено и Абрамо Тонелли, в небольшой мастерской объединив свои навыки и знания, основывают небольшую семейную фабрику в честь своих родителей.</p>
      </div>
      <div id="delivery" class="tabs__content">
        <p>Земля традиций и инноваций, фермеров и ремесленников,</p>
      </div>
      <div id="refund" class="tabs__content">
        <p>Земля традиций и инноваций, фермеров и ремесленников, земля, где искусство встречается</p>
      </div>
      <div id="feedback" class="tabs__content">
        <p>Земля традиций и инноваций, фермеров и ремесленников, земля, где искусство встречается с технологиями, где </p>
      </div>
    </div>
  </div>
</div>
</div>