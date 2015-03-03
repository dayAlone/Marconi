<? $this->setFrameMode(true);?>
<?if(isset($arResult['SECTION']['PATH'][0]['NAME'])):?>
<h1 class="lookbook__title"><span><?=$arResult['SECTION']['PATH'][0]['NAME']?></span></h1>
<?endif;?>
<div style="background-image: url(<?=$arResult['ITEMS'][count($arResult['ITEMS'])-1]['PREVIEW_PICTURE']['SRC']?>)" data-direction="&lt;" class="lookbook__slider-preview lookbook__slider-preview--prev"><?=svg('slider-arrow-left')?></div>
<div style="background-image: url(<?=$arResult['ITEMS'][1]['PREVIEW_PICTURE']['SRC']?>)" data-direction="&gt;" class="lookbook__slider-preview lookbook__slider-preview--next"><?=svg('slider-arrow-right')?></div>
<div data-loop="true" data-width="120%" data-keyboard="true" data-nav="false" data-arrows="false" data-click="false" data-transition="crossfade" class="lookbook__slider">
<?foreach ($arResult['ITEMS'] as $key=>$item):
    ?>
      <div class="lookbook__slider-item">
        <?if(strlen($item['PROPERTIES']['VIDEO']['VALUE'])>0):
            preg_match("#([\/|\?|&]vi?[\/|=]|youtu\.be\/|embed\/)(\w+)#", $item['PROPERTIES']['VIDEO']['VALUE'], $matches);
        ?>
        <div class="lookbook__slider-video" data-id="<?=end($matches)?>">
            <div id="video-1"></div>
        </div>
        <?endif;?>
        <div style="background-image: url(<?=$item['PREVIEW_PICTURE']['SRC']?>), url(<?=$item['PREVIEW_PICTURE']['SMALL']?>)" class="lookbook__picture <?=($item['PREVIEW_PICTURE']['HEIGHT'] > $item['PREVIEW_PICTURE']['WIDTH'] || ($item['PREVIEW_PICTURE']['HEIGHT']/$item['PREVIEW_PICTURE']['WIDTH']) > 0.7 ?"lookbook__picture--contain":"")?>" data-height="<?=$item['PREVIEW_PICTURE']['HEIGHT']?>"  data-width="<?=$item['PREVIEW_PICTURE']['WIDTH']?>"></div>
        <?if(count(array_filter($item['PROPERTIES']['PRODUCTS']['VALUE']))>0):?>
          <div class="lookbook__divider"><span>на этом фото</span></div>
          <div class="catalog catalog--full-width catalog--without-images">
          <?
            foreach ($item['PROPERTIES']['PRODUCTS']['VALUE'] as $value)
              if (isset($arResult['PRODUCTS'][$value]))
                echo "<div class='slider-item'>".$arResult['PRODUCTS'][$value]."</div>";
          ?>
          </div>
        <?endif;?>
      </div>
<?endforeach;?>
</div>
<script>
  $(function(){
    initLookbook()
  })
</script>