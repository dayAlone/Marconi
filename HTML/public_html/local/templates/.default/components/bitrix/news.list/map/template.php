<? $this->setFrameMode(true);?>
<?
  $items = array();
  foreach ($arResult['ITEMS'] as $key=>$item):
    if(count(preg_split("/,/", $item['PROPERTIES']['COORDS']['VALUE']))>0 && isset($item['PROPERTIES']['TYPE']['VALUE_XML_ID']))
      $items[$item['ID']] = array('code'=>$item['CODE'], 'coords' => preg_split("/,/", $item['PROPERTIES']['COORDS']['VALUE']), 'type'=>$item['PROPERTIES']['TYPE']['VALUE_XML_ID']);
    if($item['CODE'] == $_REQUEST['ELEMENT_CODE'])
      $active = $item;
  endforeach;
if(!isset($_REQUEST['short'])):
?>
<script>
  var items = '<?=json_encode($items)?>';
  <?if(isset($active)):
    $item = $active;
  ?>
  var currentStore = '<?=json_encode(array('code'=>$item['CODE'], 'coords' => preg_split("/,/", $item['PROPERTIES']['COORDS']['VALUE']), 'type'=>$item['PROPERTIES']['TYPE']['VALUE_XML_ID']))?>';
  <?endif;?>
</script>
<div id="map"></div>
<?
elseif(isset($active)):
  $item = $active;
  ?>
<div class="stores__city"><?=$arResult['SECTIONS'][$item['IBLOCK_SECTION_ID']]['NAME']?></div>
<div class="row">
  <div class="col-xs-<?=(isset($item['PREVIEW_PICTURE']['SRC'])?"7":"12")?>">
  <?if(strlen($item['PROPERTIES']['ADDRESS']['VALUE']['TEXT'])>0):?>
    <div class="stores__address">
      <?=html_entity_decode($item['PROPERTIES']['ADDRESS']['VALUE']['TEXT'])?>
    </div>
  <?endif;?>
  <?if(strlen($item['PREVIEW_TEXT'])>0):?>
    <div class="stores__description">
      <?=$item['~PREVIEW_TEXT']?>
    </div>
  <?endif;?>
  <?if(strlen($item['PROPERTIES']['METRO']['VALUE'])>0):?>
    <div class="stores__metro">
      <?=svg('metro')?> <?=$item['PROPERTIES']['METRO']['VALUE']?>
    </div>
  <?endif;?>
  </div>
  <?if(isset($item['PREVIEW_PICTURE']['SRC'])):?>
  <div class="col-xs-5">
    <img src="<?=$item['PREVIEW_PICTURE']['SRC']?>" alt="">
  </div>
  <?endif;?>
</div>
<script>
  initStores()
</script>
<?endif;?>