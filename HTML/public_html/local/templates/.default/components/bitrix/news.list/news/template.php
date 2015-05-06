<? $this->setFrameMode(true);?>
<?foreach ($arResult['ITEMS'] as $key=>$item):?>
<div class="news-item">
  <div class="news-item__date"><?=r_date($item['ACTIVE_FROM'])?></div><a href="#" class="news-item__title"><?=$item['NAME']?></a>
  <? if($arParams['DISPLAY_PREVIEW_TEXT'] == "Y"):?>
    <?if($item['PROPERTIES']['TITLE']['VALUE']):?>
      <div class="news-item__sub-title"><?=$item['PROPERTIES']['TITLE']['VALUE']?></div>
    <?endif;?>
  <?endif;?>
  <div class="news-item__content">
    <?=$item['PREVIEW_TEXT']?>
    <? if($arParams['DISPLAY_PICTURE'] == "Y"):?>
      <?if(isset($item['PREVIEW_PICTURE']['SRC'])):?>
        <img src="<?=$item['PREVIEW_PICTURE']['SRC']?>">
      <?endif;?>
    <?endif;?>
    <?if(count($item['PROPERTIES']['PICTURES']['VALUE'])>0):
      foreach ($item['PROPERTIES']['PICTURES']['VALUE'] as $pic):
      ?>
      <img src="<?=CFile::GetPath($pic)?>">
      <?
      endforeach;
    endif;?>
  </div>
</div>
<?endforeach;?>
<? if($arParams['DISPLAY_BOTTOM_PAGER'] == "Y"):?>
<div class="center">
  <?=$arResult["NAV_STRING"]?>
</div>
<?endif;?>
<? if($arParams['DISPLAY_PREVIEW_TEXT'] == "Y"):?>
<script>
  initNews()
</script>
<?endif;?>