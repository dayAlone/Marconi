<? $this->setFrameMode(true);?>
<div class="achievements">
<?foreach ($arResult['ITEMS'] as $key=>$item):
    if(intval($item['PROPERTIES']['FILE']['VALUE']) > 0):
      ?><div class="achievement achievement--with-links">
        <img src="<?=$item['PREVIEW_PICTURE']['SMALL']?>" alt="">
        <div class="achievement__footer">
          <div class="row">
            <div class="col-xs-6 left">
              <a href="#achievement" data-toggle="modal" data-image="<?=$item['PREVIEW_PICTURE']['SRC']?>" data-text='<?=htmlspecialcharsEx($item['NAME']."<br>".$item['~PREVIEW_TEXT'])?>'>Увеличить</a>
            </div>
            <div class="col-xs-6 right">
              <a href="<?=$item['PROPERTIES']['FILE']['VALUE']?>" target="_blank">Скачать</a>
            </div>
          </div>
        </div>
      </div><?
    else:
      ?><a href="#achievement" data-toggle="modal" class="achievement" data-image="<?=$item['PREVIEW_PICTURE']['SRC']?>" data-text='<?=htmlspecialcharsEx($item['NAME']."<br>".$item['~PREVIEW_TEXT'])?>'>
        <img src="<?=$item['PREVIEW_PICTURE']['SMALL']?>" alt="">
      </a><?
    endif;
  endforeach;?>
</div>
<?$this->SetViewTarget('footer');?>
  <div id="achievement" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade feedback">
    <div class="modal-dialog">
      <div class="modal-content"><a data-dismiss="modal" href="#" class="close"><?=svg('close')?></a>
        <div class="modal-text center">
          <img src="" alt="" width="70%">
          <div class="xxl-margin-top">
            <p class="no-margin-bottom"></p>  
          </div>
        </div>
      </div>
    </div>
  </div>
<?$this->EndViewTarget();?>