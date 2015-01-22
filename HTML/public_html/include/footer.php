</div>
<div class="scroll-fix"></div>
<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col-xs-8 col-sm-3 col-sm-4 col-md-3">
        <div class="copyright">© <?=date('Y')?> ООО «Мегатрон» </div>
      </div>
      <div class="col-sm-3 col-md-2">
        <div class="contacts"><span>Москва,  ул. адмирала макарова, 8 <br></span><a href="mailto:info@fmarconi.ru" class="contacts_link">info@fmarconi.ru</a></div>
      </div>
      <div class="col-sm-2">
        <div class="map"><a href="#">карта сайта</a></div>
      </div>
      <div class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
        <?php
              $APPLICATION->IncludeComponent("bitrix:menu", "social", 
              array(
                  "ALLOW_MULTI_SELECT" => "Y",
                  "MENU_CACHE_TYPE"    => "A",
                  "ROOT_MENU_TYPE"     => "social",
                  "MAX_LEVEL"          => "1",
                  ),
              false);
          ?>
      </div>
      <div class="col-md-3 col-lg-3 visible-md-block visible-lg-block"><a href="http://radia.ru" target="_blank" class="radia"><?=svg('radia')?>
          <div class="radia__content">разработка сайта <br>radia interactive</div></a>
        <div id="bx-composite-banner"></div>
      </div>

    </div>
  </div>
</footer>

<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="pswp__bg"></div>

    <div class="pswp__scroll-wrap">

        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">

                <div class="pswp__counter"></div>

                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                <button class="pswp__button pswp__button--share" title="Share"></button>

                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>


                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                      <div class="pswp__preloader__cut">
                        <div class="pswp__preloader__donut"></div>
                      </div>
                    </div>
                </div>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div> 
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>

            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>

            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>

        </div>

    </div>

</div>

<?
require_once($_SERVER['DOCUMENT_ROOT'].'/include/form.php');
?>
<?if(!$GLOBALS['USER']->GetID()):?>
  <?if($_REQUEST['login']=="yes"):?>
  <script>
    $(function(){
      $('#login').modal()
    })
  </script>
  <?endif;?>
  <div id="login" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog feedback__dialog">
      <div class="modal-content"><a data-dismiss="modal" href="#" class="close"><?=svg('close')?></a>
        <?
          $APPLICATION->IncludeComponent("bitrix:system.auth.form", "", 
          Array(
            "REGISTER_URL"        => "register.php",
            "FORGOT_PASSWORD_URL" => "",
            "PROFILE_URL"         => "/profile/",
            "SHOW_ERRORS"         => "Y" 
          )
        );
        ?>
      </div>
    </div>
  </div>
  <div id="forget" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade forget">
    <div class="modal-dialog feedback__dialog">
      <div class="modal-content"><a data-dismiss="modal" href="#" class="close"><?=svg('close')?></a>
        <div class="forget__success hidden m-margin-top center">
          <p><big>Ссылка для востановления пароля<br> отправлена на вашу почту.</big></p>
        </div>
        <?$APPLICATION->IncludeComponent(
        "bitrix:system.auth.forgotpasswd",
        ".default",
        Array()
        );?>
      </div>
    </div>
  </div>
  <div id="register" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade register">
    <div class="modal-dialog feedback__dialog">
      <div class="modal-content"><a data-dismiss="modal" href="#" class="close"><?=svg('close')?></a>
        <div class="register__success hidden m-margin-top center">
          <p><big>Вы успешно зарегистрированы и авторизованы на сайте.</big></p>
        </div>
        <?$APPLICATION->IncludeComponent("bitrix:main.register","",Array(
                "USER_PROPERTY_NAME" => "", 
                "SEF_MODE"           => "Y", 
                "SHOW_FIELDS"        => Array("NAME", "LAST_NAME", "PERSONAL_PHONE"), 
                "REQUIRED_FIELDS"    => Array("NAME", "LAST_NAME"), 
                "AUTH"               => "Y", 
                "USE_BACKURL"        => "Y", 
                "USE_CAPTCHA"        => "Y", 
                "SUCCESS_PAGE"       => "", 
                "SET_TITLE"          => "Y", 
                "USER_PROPERTY"      => Array(), 
                "SEF_FOLDER"         => "/", 
                "VARIABLE_ALIASES"   => Array()
            )
        );?> 
      </div>
    </div>
  </div>
<?endif;?>
<?$APPLICATION->ShowViewContent('footer');?>
</body>
</html>