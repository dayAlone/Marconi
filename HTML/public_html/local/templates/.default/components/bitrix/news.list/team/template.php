<? $this->setFrameMode(true);?>
<div class="team">
  <div class="team__arrow team__arrow--prev"><?=svg('slider-arrow-left')?></div>
  <div class="team__arrow team__arrow--next"><?=svg('slider-arrow-right')?></div>
<?foreach ($arResult['ITEMS'] as $key=>$item):?>
  <div id="el-<?=$key?>" class="team__item <?=($key==0?"team__item--active":"")?>" style="background-image: url(<?=$item['PREVIEW_PICTURE']['SRC']?>)">
    <div class="team__content">
    <?if(strlen($item['PROPERTIES']['POSITION']['VALUE'])>0):?>
      <div class="team__position"><?=$item['PROPERTIES']['POSITION']['VALUE']?></div>
    <?endif;?>
      <div class="team__name"><?=$item['NAME']?></div>
      <div class="team__divider"></div>
      <div class="team__info">
        <?if(strlen($item['PROPERTIES']['PHONE']['VALUE'])>0):?>
          Тел./факс: <?=$item['PROPERTIES']['PHONE']['VALUE']?><br>
        <?endif;?>
        <?if(strlen($item['PROPERTIES']['EMAIL']['VALUE'])>0):?>
          Электронная почта: <a href="mailto:<?=$item['PROPERTIES']['EMAIL']['VALUE']?>"><?=$item['PROPERTIES']['EMAIL']['VALUE']?></a><br>
        <?endif;?>
      </div>
    </div>
  </div>
  <?endforeach;?>
  <div class="team__slider">
  <?foreach ($arResult['ITEMS'] as $key=>$item):?>
    <div class="team__preview-frame">
      <a href="#el-<?=$key?>" class="team__preview" style="background-image: url(<?=$item['PREVIEW_PICTURE']['SRC']?>)">
        <div class="team__preview-text"><span><?=$item['NAME']?></span></div>
      </a>
    </div>
  <?endforeach;?>
  </div>
</div>
<script>
  $(function () {
    initTeam('.team')
  })
</script>