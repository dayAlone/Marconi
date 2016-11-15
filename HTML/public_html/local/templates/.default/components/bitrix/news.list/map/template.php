<? $this->setFrameMode(true);?>
<div class="stores__list">
  <div class="container">
    <?
      $items = array();
      $section = false;
      $i=0;
      $current = false;
      foreach ($arResult['ITEMS'] as $key=>$item):
          if ($_REQUEST['ELEMENT_CODE'] === $item['CODE']) $current = $item;
      endforeach;
      ?><div style="<?=($current ? 'display: none' : '')"><?
          foreach ($arResult['ITEMS'] as $key=>$item):
            if(count(preg_split("/,/", $item['PROPERTIES']['COORDS']['VALUE']))>0 && isset($item['PROPERTIES']['TYPE']['VALUE_XML_ID']))
              $items[$item['ID']] = array('code'=>$item['CODE'], 'coords' => preg_split("/,/", $item['PROPERTIES']['COORDS']['VALUE']), 'type'=>$item['PROPERTIES']['TYPE']['VALUE_XML_ID']);
            if($item['CODE'] == $_REQUEST['ELEMENT_CODE'])
              $active = $item;
            if($section != $item['IBLOCK_SECTION_ID']):?>
              <?if($i!=0):?></section>
              <?endif;?>
              <section data-id="<?=$item['IBLOCK_SECTION_ID']?>" key="<?=$i?>" class="<?=($item['IBLOCK_SECTION_ID']==47?"active":"")?>">
              <?
              $section = $item['IBLOCK_SECTION_ID'];
            endif;
            ?>
            <div class="stores__list-item"><?
                if(strlen($item['PROPERTIES']['ADDRESS']['VALUE']['TEXT'])>0):?>
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
                <?if(intval($item['PROPERTIES']['TYPE']['VALUE_XML_ID'])>0):?>
                  <div class="stores__metro">
                    <img width="17" src="/layout/images/store-<?=$item['PROPERTIES']['TYPE']['VALUE_XML_ID']?>.png" alt="">
                    <?switch ($item['PROPERTIES']['TYPE']['VALUE_XML_ID']):
                      case '1':
                      ?>Места продаж<?
                      break;
                      case '2':
                      ?>Фирменные магазины<?
                      break;
                      case '3':
                      ?>Фирменные магазины с самовывозом<?
                      break;
                    endswitch;?>
                  </div>
                <? endif; ?>
            </div>
          <?
            $i++;
            endforeach;
     ?></div><?
      if (isset($_REQUEST['ELEMENT_CODE'])):

        if ($current) {
            $item = $current;
            ?>
            <div class="stores__list-item"><?
                if(strlen($item['PROPERTIES']['ADDRESS']['VALUE']['TEXT'])>0):?>
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
                <?if(intval($item['PROPERTIES']['TYPE']['VALUE_XML_ID'])>0):?>
                  <div class="stores__metro">
                    <img width="17" src="/layout/images/store-<?=$item['PROPERTIES']['TYPE']['VALUE_XML_ID']?>.png" alt="">
                    <?switch ($item['PROPERTIES']['TYPE']['VALUE_XML_ID']):
                      case '1':
                      ?>Места продаж<?
                      break;
                      case '2':
                      ?>Фирменные магазины<?
                      break;
                      case '3':
                      ?>Фирменные магазины с самовывозом<?
                      break;
                    endswitch;?>
                  </div>
                <? endif; ?>
            </div>
            <div class="contacts">
                <p>
                    <a href='/stores/'>К полному списку магазинов</a>
                </p>
            </div>
            <?
        }
      endif;
    ?>
  </div>
</div>
<?
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
<?endif;
if(!isset($_REQUEST['short'])):
  ?>
<script>
  initStores()
</script>
<?endif;?>
