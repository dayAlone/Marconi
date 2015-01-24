<? $this->setFrameMode(true);?>
<?foreach ($arResult['ITEMS'] as $key=>$item):?>
<div class="news-item">
  <div class="news-item__date"><?=r_date($item['ACTIVE_FROM'])?></div><a href="#" class="news-item__title"><?=$item['NAME']?></a>
  <?if($item['PROPERTIES']['TITLE']['VALUE']):?>
    <div class="news-item__sub-title"><?=$item['PROPERTIES']['TITLE']['VALUE']?></div>
  <?endif;?>
  <div class="news-item__content">
    <?=$item['PREVIEW_TEXT']?>
    <?if(isset($item['PREVIEW_PICTURE']['SRC'])):?>
      <img src="<?=$item['PREVIEW_PICTURE']['SRC']?>">
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
<div class="center">
  <?=$arResult["NAV_STRING"]?>
</div>