<? $this->setFrameMode(true);
$item = $arResult;
?>

<div class="news-item news-item--detail">
  <div class="news-item__date"><?=r_date($item['ACTIVE_FROM'])?></div>
  <a href="<?=($arParams['DETAIL']=="Y"?$item['DETAIL_PAGE_URL']:"#")?>" class="news-item__title"><?=$item['NAME']?></a>
  <? if($arParams['DISPLAY_PREVIEW_TEXT'] == "Y"):?>
    <?if($item['PROPERTIES']['TITLE']['VALUE']):?>
      <div class="news-item__sub-title"><?=$item['PROPERTIES']['TITLE']['VALUE']?></div>
    <?endif;?>
  <?endif;?>
  <div class="news-item__content news-item__content--open">
    <?=$item['PREVIEW_TEXT']?>
    <div class="center">
      <?if(isset($item['PREVIEW_PICTURE']['SRC'])):?>
        <img src="<?=$item['PREVIEW_PICTURE']['SRC']?>">
      <?endif;?>
      <?if(count($item['PROPERTIES']['PICTURES']['VALUE'])>0):
        foreach ($item['PROPERTIES']['PICTURES']['VALUE'] as $k => $pic):
        ?>
        <img src="<?=CFile::GetPath($pic)?>">
        <?
        if(isset($item['PROPERTIES']['PICTURES']['VALUE']['DESCRIPTION'][$k])):?>
        <p><?=$item['PROPERTIES']['PICTURES']['VALUE']['DESCRIPTION'][$k]?></p>
        <?
        endforeach;
      endif;?>
    </div>
  </div>
</div>
<div class="center">
  <a href="/news/" class="news-item__back"><?=svg('arrow')?> вернуться к новостям</a>
</div>