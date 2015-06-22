<? $this->setFrameMode(true);?>
<div data-width="100%" data-height="100%" data-keyboard="true" data-nav="false" data-arrows="false" data-transition="crossfade" data-click="false" class="about__slider">
  <?foreach ($arResult['ITEMS'] as $key=>$item):?>
  <div style="background-image: url(<?=$item['PREVIEW_PICTURE']['SRC']?>), url(<?=$item['PREVIEW_PICTURE']['SMALL']?>)" class="about__slider-item" data-dark="<?=($item['PROPERTIES']['DARK']['VALUE_XML_ID']=='Y'?"Y":"N")?>">
    <div class="about__slider-item-content">
    <?if($item['PROPERTIES']['HIDE']['VALUE_XML_ID'] != 'Y'):?>
      <h2 class="about__slider-title"> <span><?=$item['NAME']?></span>
        <div class="about__slider-title-after"></div>
        <div class="about__slider-title-before"></div>
      </h2>
    <?endif;?>
      <?=str_replace(array("<h4>", "</h4>"), array("<h4 class='about__slider-sub-title'><span>", "</span><div class=\"about__slider-sub-title-after\"></div>
        <div class=\"about__slider-sub-title-before\"></div></h4>"), $item['~PREVIEW_TEXT'])?>
      <?if(count($arResult['ITEMS'])-1 != $key):?>
        <div data-direction=">" class="about__slider-arrow about__slider-arrow--next"><?=svg('slider-arrow-right')?></div>
      <?endif;?>
      <?if($key!=0):?>
        <div data-direction="<" class="about__slider-arrow about__slider-arrow--prev"><?=svg('slider-arrow-left')?></div>
      <?endif;?>
    </div>
  </div>
  <?endforeach;?>
</div>