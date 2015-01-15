<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
if(!isset($arParams['HIDE_TOOLBAR'])):
?>
<div class="catalog__toolbar">
  <div class="row">
    <?
      switch ($_REQUEST['sort_param']):
        case 'PROPERTY_MIN_PRICE':
            switch ($_REQUEST['sort_value']):
              case 'ASC':
                $text = 'Возрастанию цены';
                $active ="PROPERTY_MIN_PRICE_ASC";
              break;
              case 'DESC':
                $text = 'Убыванию цены';
                $active ="PROPERTY_MIN_PRICE_DESC";
              break;
            endswitch;
          break;
        default:
          $active ="SORT";
        break;
      endswitch;
    ?>
    <div class="col-xs-4">сортировать по: <span class="dropdown" data-param="<?=(!isset($_REQUEST['sort_param'])?$_REQUEST['sort_param']:'')?>" data-value="<?=(!isset($_REQUEST['sort_value'])?$_REQUEST['sort_value']:'')?>">
      <a href="#" class="dropdown__trigger"><span class="dropdown__text"><?=($active=="SORT"?'Не выбрано':'')?><?=($text?$text:'')?></span><?=svg('arrow')?></a>
      <span class="dropdown__frame">
          <a href="#" style="<?=($active=="SORT"?"display:none":'')?>" class="dropdown__item" data-param="SORT" data-value="ASC">Не выбрано</a>
          <a href="#" style="<?=($active=="PROPERTY_MIN_PRICE_ASC"?"display:none":'')?>" class="dropdown__item" data-param="PROPERTY_MIN_PRICE" data-value="ASC">Возрастанию цены</a>
          <a href="#" style="<?=($active=="PROPERTY_MIN_PRICE_DESC"?"display:none":'')?>" class="dropdown__item" data-param="PROPERTY_MIN_PRICE" data-value="DESC">Убыванию цены</a>
        </span>
        <select class="dropdown__select">
          <option data-param="PROPERTY_MIN_PRICE" data-value="ASC">Возрастанию цены</option>
          <option data-param="PROPERTY_MIN_PRICE" data-value="DESC">Убыванию цены</option>
        </select></span></div>
    <div class="col-xs-3 center">
      показывать по:
      <? foreach (array(40, 80, 120) as $count):?>
        <a href="#" class="catalog__per-page <?=($arParams['PAGE_ELEMENT_COUNT']==$count?"catalog__per-page--active":"")?>"><?=$count?></a>
      <? endforeach; ?>
    </div>
    <div class="col-xs-5 right">
      <?=$arResult["NAV_STRING"]?>
    </div>
  </div>
</div>
<h1 class="catalog__title"><?=$arResult['NAME']?></h1>
<div class="row catalog__frame <?=$arParams['CLASS']?>">
<?
endif;
if (!empty($arResult['ITEMS']))
{
  $strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
  $strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
  $arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));
  foreach ($arResult['ITEMS'] as $key => $item)
    require($_SERVER['DOCUMENT_ROOT'].'/include/product.php');
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