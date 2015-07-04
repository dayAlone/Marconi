<? $this->setFrameMode(true);?>
<?foreach ($arResult['ITEMS'] as $key=>$item):?>
<div class="news-item">
  <div class="news-item__title news-item__title--small"><?=$item['NAME']?></div>
  
  <?if($item['PROPERTIES']['TITLE']['VALUE']):?>
    <div class="news-item__sub-title"><?=$item['PROPERTIES']['TITLE']['VALUE']?></div>
  <?endif;?>
  
  <div class="news-item__content news-item__content--open <?=(strlen($item['PREVIEW_PICTURE']['SRC'])>0?"news-item__content--arrow":"")?>">
    <?=$item['~PREVIEW_TEXT']?>
  

    <?if(strlen($item['PREVIEW_PICTURE']['SRC'])>0):?>
    <div class="news-item__divider"></div>
    <div class="center">
      <a href="#" data-pictures='<?=json_encode($item['IMAGES'])?>' class="news-item__link">Cмотреть оригинал отзыва</a>
    </div>
    <?endif;?>
    
    
    
  </div>
</div>
<?endforeach;?>

<? if($arParams['DISPLAY_BOTTOM_PAGER'] == "Y"):?>
<div class="center xl-margin-bottom">
  <?=$arResult["NAV_STRING"]?>
</div>
<?endif;?>

<script>
  initNews()
</script>