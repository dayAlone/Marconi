</div>
<div class="scroll-fix"></div>
<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col-xs-7 col-sm-3 col-md-2">
        <div class="copyright">© <?=date('Y')?>
        <?if(SITE_ID == 's1'):?>
          <?=(strstr($APPLICATION->GetCurDir(), "/eng/")?"Megatron LLC":"ООО «Мегатрон»")?>
        <?else:?>
          ООО «Сэлтон»
        <?endif;?>
        </div>
      </div>
      <div class="col-sm-4 col-md-3">
        <div class="contacts"><span><?=(strstr($APPLICATION->GetCurDir(), "/eng/")?"Moscow, Admiral Makarov Street 8, str. 1":"Москва,  ул. адмирала макарова, 8")?> <br></span>
        <a href="mailto:info@<?=(SITE_ID=='s1'?"fmarconi.ru":"italbags.ru")?>" class="contacts_link">info@<?=(SITE_ID=='s1'?"fmarconi.ru":"italbags.ru")?></a></div>
      </div>
      <div class="col-sm-2 col-md-1">
        <div class="map"><?=(strstr($APPLICATION->GetCurDir(), "/eng/")?"":'<a href="/sitemap/"><nobr>карта сайта</nobr></a>')?></div>
      </div>
      <div class="col-xs-5 col-sm-3 col-md-3 xs-right md-left lg-center">
        <nobr>
        <?php
            if(SITE_ID == 's1'):
              $APPLICATION->IncludeComponent("bitrix:menu", "social",
              array(
                  "ALLOW_MULTI_SELECT" => "Y",
                  "MENU_CACHE_TYPE"    => "A",
                  "ROOT_MENU_TYPE"     => "social",
                  "MAX_LEVEL"          => "1",
                  ),
              false);
            endif;
          ?>
        </nobr>
      </div>
      <div class="col-md-3 col-lg-3 visible-md-block visible-lg-block"><a href="http://radia.ru" target="_blank" class="radia"><?=svg('radia')?>
          <div class="radia__content"><?=(strstr($APPLICATION->GetCurDir(), "/eng/")?"developed by":"разработка сайта")?> <br>radia interactive</div></a>
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
$APPLICATION->ShowViewContent('footer');
?>
<?if($_REQUEST['change_password']=="yes"):?>
  <script>
    $(function(){
      $('#change').modal()
    })
  </script>
<?endif;?>
<div id="change" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade forget">
  <div class="modal-dialog feedback__dialog">
    <div class="modal-content"><a data-dismiss="modal" href="#" class="close"><?=svg('close')?></a>
      <div class="hidden m-margin-top center change__success">
        <p><big>Вы успешно ищмени пароль. <a class="auth__item" href="#login" data-toggle="modal" data-target="#login">Авторизация</a></big></p>
      </div>
      <?$APPLICATION->IncludeComponent(
      "bitrix:system.auth.changepasswd",
      ".default",
      Array()
      );?>
    </div>
  </div>
</div>
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

  <? if(SITE_ID == 's1'):?>
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
                "REQUIRED_FIELDS"    => Array("NAME"),
                "AUTH"               => "Y",
                "USE_BACKURL"        => "Y",
                "USE_CAPTCHA"        => "Y",
                "SUCCESS_PAGE"       => "",
                "SET_TITLE"          => "N",
                "USER_PROPERTY"      => Array(),
                "SEF_FOLDER"         => "/",
                "VARIABLE_ALIASES"   => Array()
            )
        );?>
      </div>
    </div>
  </div>
  <?endif;?>
<?endif;?>
<? if(SITE_ID == 's2'):?>
<div id="SV" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content"><a data-dismiss="modal" href="#" class="close"><?=svg('close')?></a>
        <object type="application/x-shockwave-flash" data="/layout/sv.swf" id="flash" style="margin:0;width:100%;height:580px;">

        <param name="movie" value="/layout/sv.swf" />
        <param name="wmode" value="transparent" />
        <param name="quality" value="high" />

        </object>
    </div>
  </div>
</div>
<?endif;?>
<div id="Nav" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade overlay">
  <div class="modal-dialog">
    <a data-dismiss="modal" href="#" class="close"><?=svg('close')?></a>
    <?php
        $APPLICATION->IncludeComponent("bitrix:menu", "toolbar",
        array(
            "ALLOW_MULTI_SELECT" => "Y",
              "MENU_CACHE_TYPE"    => "A",
              "ROOT_MENU_TYPE"     => "toolbar",
              "CHILD_MENU_TYPE"    => "header",
              "MAX_LEVEL"          => "2"
            ),
        false);
    ?>
  </div>
</div>

<? if (SITE_ID == 's1'): ?>
<div id="Lottery" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
  <div class="modal-dialog feedback__dialog">
    <div class="modal-content">
        <a data-dismiss="modal" href="#" class="close"><?=svg('close')?></a>
        <h3 class="modal__title">Новогодняя акция</h3>
        <p>Новогодняя лотерея от Francesco Marconi стала доброй традицией. Каждый год с 10 декабря по 10 января все Покупатели становятся её участниками.</p>
        <p>Соглашаясь на участие, Вы получаете шанс стать обладателем главного приза. Основное преимущество нашей лотереи, это то, что призы Вы выбираете сами!</p>
        <p>Победитель получает возможность, с 20 января по 20 февраля 2016г., выбрать себе подарок в нашем магазине, на ту сумму, на которую он соврешил покупку, сделав заказ в период с 10 декабря 2015 года, по 10 января 2016 года.</p>
        <p>Ждем Ваших заказов!</p>
        <p>От Вас хорошее настроение и согласие сфотографироваться в момент вручения подарков.</p>
        <p style='line-height: 1; color: rgba(0, 0, 0, .5)'><small>Принимая участие в лотереи, Вы даете свое согласие на обработку своих персональных данных, а именно совершение действий, предусмотренных п. 3 ч. 1 ст. 3 Федерального закона от 27.07.2006 N 152-ФЗ "О персональных данных", и подтверждаете, что, давая такое согласие, Вы действуете свободно, своей волей и в своем интересе.</small></p>
    </div>
  </div>
</div>
<? endif; ?>

<?
if(SITE_ID=='s2' && $_REQUEST['login']=="yes"):
?>
<div class="hello hidden">
  <div class="hello__frame">
    <strong>Здравствуйте, <?=$USER->GetFirstName()?></strong><br>
    <?if(strlen(COption::GetOptionString("grain.customsettings","popup_text"))>0):?>
    <?=html_entity_decode(COption::GetOptionString("grain.customsettings","popup_text"))?><br>
    <?endif;?>
    <a href="#" class="hello__button">Спасибо</a>
  </div>
</div>
<?endif;?>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
var yaParams = {/*Здесь параметры визита*/};
</script>

<script type="text/javascript">
(function (d, w, c) {
(w[c] = w[c] || []).push(function() {
try {
w.yaCounter28060548 = new Ya.Metrika({id:<?=(SITE_ID=='s1'?'28060548':'32173039')?>,
webvisor:true,
clickmap:true,
trackLinks:true,
accurateTrackBounce:true,params:window.yaParams||{ }});
} catch(e) { }
});

var n = d.getElementsByTagName("script")[0],
s = d.createElement("script"),
f = function () { n.parentNode.insertBefore(s, n); };
s.type = "text/javascript";
s.async = true;
s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

if (w.opera == "[object Opera]") {
d.addEventListener("DOMContentLoaded", f, false);
} else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/28060548" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<?if(SITE_ID=='s1'):?>
<!-- {literal} -->
<script type='text/javascript'>
    window['liv'+'e'+'T'+'e'+'x'] = true,
    window['liv'+'e'+'TexI'+'D'] = 97237,
    window['live'+'Tex_o'+'bjec'+'t'] = true;
    (function() {
        var t = document['create'+'Elem'+'e'+'nt']('script');
        t.type ='text/javascript';
        t.async = true;
        t.src = '//'+'cs15.liv'+'ete'+'x.ru'+'/js/cl'+'ien'+'t.js';
        var c = document['getElemen'+'tsB'+'yTagNam'+'e']('script')[0];
        if ( c ) c['pa'+'r'+'ent'+'No'+'de']['inser'+'tB'+'efor'+'e'](t, c);
        else document['docu'+'ment'+'Ele'+'ment']['f'+'irstC'+'hild']['a'+'ppe'+'ndChi'+'ld'](t);
    })();
</script>
<!-- {/literal} -->
<?endif;?>
<?$APPLICATION->ShowViewContent('footer');?>
</body>
</html>
