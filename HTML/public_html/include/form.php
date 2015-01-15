<div id="feedback" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
  <div class="modal-dialog feedback__dialog">
    <div class="modal-content"><a data-dismiss="modal" href="#" class="close"><?=svg('close')?></a>
    <div class="feedback__success">
      <h1 class="center">Ваше сообщение успешно отправлено. </h1>
      <p class="center">В ближайшее время представители нашей компании свяжутся с вами. Благодарим за обращение.</p>
    </div>
    <form class="feedback__form visible" data-parsley-validate>
      <input type="hidden" name="group_id" value="5">
      <label>представьтесь, пожалуйста</label>
      <input name="name" type="text" required>
      <label>Ваш e-mail</label>
      <input name="email" type="email" required>
      <label>телефон для связи с вами</label>
      <input name="phone" type="text" data-parsley-pattern="/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}/" data-parsley-trigger="change">
      <label>ваше сообщение</label>
      <textarea required name="message"></textarea>
      <div class="row">
        <div class="col-xs-5">
          <label class="left">введите данный код</label>
          <?
            include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/captcha.php");
            $cpt = new CCaptcha();
            $cpt->SetCodeLength(4);
            $cpt->SetCode();
            $code=$cpt->GetSID();
          ?>
          <div class="captcha" style="background-image:url(/include/captcha.php?captcha_sid=<?=$code?>)"></div>
        </div>
        <div class="col-xs-2 no-padding center">
          
          <input type="hidden" name="captcha_code" value="<?=$code?>">
          <a href="#" class="captcha_refresh">
            <?=svg('refresh')?>
          </a>
        </div>
        <div class="col-xs-5">
          <label class="right">в это поле</label>
          <input name="captcha_word" type="text" required>
        </div>
      </div>
      <div class="center">
        <input type="submit" class="product__big-button product__big-button--border m-margin-top" value="Отправить">
      </div>
    </form>

    </div>
  </div>
</div>