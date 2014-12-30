<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
if(!isset($arParams['HIDE_TOOLBAR'])):
?>
<div class="catalog__toolbar">
  <div class="row">
    <div class="col-xs-4">сортировать по: <span class="dropdown"><a href="#" class="dropdown__trigger"><span class="dropdown__text">Не выбрано</span><?=svg('arrow')?></a><span class="dropdown__frame"><a href="#" style="display:none" class="dropdown__item">Не выбрано</a><a href="#" class="dropdown__item">Цене</a><a href="#" class="dropdown__item">Наличию</a></span>
        <select class="dropdown__select">
          <option value="Francesco Marconi">Цене</option>
          <option value="Gilda Tonelli">Наличию</option>
        </select></span></div>
    <div class="col-xs-8 right">
      <?=$arResult["NAV_STRING"]?>
    </div>
  </div>
</div>
<h1 class="catalog__title"><?=$arResult['NAME']?></h1>
<div class="row catalog__frame">
<?
endif;
if (!empty($arResult['ITEMS']))
{
	foreach ($arResult['ITEMS'] as $item):
    ?>
			<div class="col-xs-4 col-lg-3">
        <div class="product">
          <div class="product__content">
          	<a href="<?=$item['DETAIL_PAGE_URL']?>" class="product__picture-frame">
            	<div data-bg="<?=(isset($item['PREVIEW_PICTURE']['SRC'])?$item['PREVIEW_PICTURE']['SRC']:"/layout/images/no-image.jpg")?>" class="product__picture <?=(!$item['PREVIEW_PICTURE']['SRC']?"product__picture--no":"")?>"></div>
            </a>
            <div class="product__brand"><?=$arResult['BRANDS'][$item['PROPERTIES']['BRAND']['VALUE']]?></div>
            <div class="product__name"><?=$item['NAME']?></div>
            <div class="product__price"><?=number_format($item['MIN_PRICE']['VALUE'], 0, '.', ' ')?> ₷</div>
          </div>
          <div class="product__hidden">
            <div class="product__frame"></div>
            <?if(count($item['PROPERTIES']['PICTURES']['VALUE'])>0&&isset($item['PREVIEW_PICTURE']['SRC'])):?>
              <a href="#" class="product__icon product__icon--zoom" data-pictures='<?=json_encode($item['PROPERTIES']['PICTURES']['VALUE'])?>'><?=svg('zoom')?></a>
            <?endif;?>
            <a href="#" class="product__icon product__icon--cart <?=(count($item['OFFERS'])>0?"product__icon--trigger":"")?>"><?=svg('cart')?></a>
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
		<?
	endforeach;
}
else
{
  ?>
    <div class="col-xs-4 col-xs-offset-4">
      <div class="xxl-margin-top xxl-margin-bottom xxl-padding-bottom product product--empty">
          <div class="product__content">
            <div class="product__picture-frame">
              <div data-bg="/layout/images/no-image.jpg" class="product__picture product__picture--no"></div>
            </div>
            <div class="product__brand">К сожалению, товары не найдены.</div>
            <div class="product__name">Выберите другие параметры для поиска</div>
          </div>
      </div>
    </div>
  <?
}
if(!isset($arParams['HIDE_TOOLBAR'])):
?>
</div>
<div class="catalog__footer">
  <div class="row">
    <div class="col-xs-4">
      <?php
          $APPLICATION->IncludeComponent("bitrix:menu", "catalog", 
          array(
              "ALLOW_MULTI_SELECT" => "Y",
              "MENU_CACHE_TYPE"    => "A",
              "ROOT_MENU_TYPE"     => "catalog_footer",
              "CLASS"              => "footer",
              "MAX_LEVEL"          => "1",
              ),
          false);
      ?>
    </div>
    <div class="col-xs-8 right">
      <?=$arResult["NAV_STRING"]?>
    </div>
  </div>
</div>
<?endif;?>