<div class="toolbar">
  <div class="container toolbar__desktop">
    <div class="row">
      <div class="visible-lg col-lg-2">
        <a href="tel:<?=preg_replace('/[^\dx+]/i', '', $CITY['PHONE'])?>" class="toolbar__number"><?=svg('phone')?><?=$CITY['PHONE']?></a>
      </div>
      <div class="col-xs-10 col-lg-8">
        <?php
            $APPLICATION->IncludeComponent("bitrix:menu", "toolbar", 
            array(
                "ALLOW_MULTI_SELECT" => "Y",
                "MENU_CACHE_TYPE"    => "A",
                "ROOT_MENU_TYPE"     => "toolbar",
                "CHILD_MENU_TYPE"    => "header", 
                "MAX_LEVEL"          => "2",
                "CLASS"              => "center"
                ),
            false);
        ?>
      </div>
      <div class="col-xs-2">
        <?
          if(!strstr($APPLICATION->GetCurDir(), "/eng/")):
          $frame = new \Bitrix\Main\Page\FrameHelper("login");
          $frame->begin();?>
          <div class="auth <?=($GLOBALS['USER']->IsAuthorized()?"auth--active":"")?>">
            <div class="auth__login">
              <a class="auth__item" href="#login" data-toggle="modal" data-target="#login">Авторизация</a>
              <span class="auth__divider">|</span>
              <a class="auth__item" href="/signup/">Регистрация</a>
            </div>
            <div class="auth__profile">
              <a class="auth__item" href="/profile/">Личный кабинет</a>
              <span class="auth__divider">|</span>
              <a class="auth__item" href="/?logout=yes">Выход</a>
            </div>
          </div>
          <?$frame->beginStub();?>
          <div class="auth">
            <div class="auth__login">
              <a class="auth__item" href="#login" data-toggle="modal" data-target="#login">Авторизация</a>
              <span class="auth__divider">|</span>
              <a class="auth__item" href="/signup/">Регистрация</a>
            </div>
          </div>
          <?$frame->end();
          endif;?>
      </div>
    </div>
  </div>
  <div class="container toolbar__tablet">
      <div class="col-xs-8">
        <a href="#Nav" data-toggle="modal" data-target="#Nav" class="toolbar__nav-trigger"><?=svg('nav')?> Меню</a>
      </div>
      <div class="col-xs-4 right">
        <?
          $frame = new \Bitrix\Main\Page\FrameHelper("login-tablet");
          $frame->begin();?>
          <div class="auth <?=($GLOBALS['USER']->IsAuthorized()?"auth--active":"")?>">
            <div class="auth__login">
              <a class="auth__item" href="#login" data-toggle="modal" data-target="#login">Авторизация</a>
              <span class="auth__divider">|</span>
              <a class="auth__item" href="/signup/">Регистрация</a>
            </div>
            <div class="auth__profile">
              <a class="auth__item" href="/profile/">Личный кабинет</a>
              <span class="auth__divider">|</span>
              <a class="auth__item" href="/?logout=yes">Выход</a>
            </div>
          </div>
          <?$frame->beginStub();?>
          <div class="auth">
            <div class="auth__login">
              <a class="auth__item" href="#login" data-toggle="modal" data-target="#login">Авторизация</a>
              <span class="auth__divider">|</span>
              <a class="auth__item" href="/signup/">Регистрация</a>
            </div>
          </div>
          <?$frame->end();?>
      </div>
  </div>
  <div class="container toolbar__mobile">
    <div class="row">
      <div class="col-xs-3">
        <a href="#Nav" data-toggle="modal" data-target="#Nav" class="toolbar__nav-trigger"><?=svg('nav')?> Меню</a>
      </div>
      <div class="col-xs-9 right">
        <a href="tel:<?=preg_replace('/[^\dx+]/i', '', $CITY['PHONE'])?>" title=""><?=svg('phone')?></a><span class="toolbar__divider"></span><span class="search__frame"><a href="#" class="search"><?=svg('seach')?></a><span class="toolbar__divider"></span></span><a <?=($GLOBALS['USER']->IsAuthorized()?'href="/profile/"':'href="#login" data-toggle="modal"')?> title=""><?=svg('profile')?></a><?if(isUserAccept()):?><span class="toolbar__divider"></span><a href="/basket/" title=""><?=svg('cart')?></a><?endif;?>
      </div>
    </div>
  </div>
</div>
<?if($APPLICATION->GetCurDir() != '/'):?>
<header class="header header--italbags">
  <div class="container header__mobile center">
    <a href="/" class="logo"><?=svg('italbags')?></a>
  </div>
  <div class="container header__desktop">
    <div class="row">
      <div class="col-xs-5 col-md-5"><span class="logo__line logo__line--short logo__line--left"></span></div>
      <div class="col-xs-2 center"><a href="/" class="logo"><?=svg('italbags')?></a></div>
      <?if(isUserAccept()):?>
      <div class="col-xs-2 col-md-3"><span class="logo__line logo__line--right"></span></div>
      <div class="col-xs-3 col-md-2">
        <div class="header__links header__links--center">
          <?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line","",Array(
            "PATH_TO_BASKET"      => "/basket/",
            "PATH_TO_PERSONAL"    => "/personal/",
            "SHOW_PERSONAL_LINK"  => "Y",
            "SHOW_NUM_PRODUCTS"   => "Y",
            "SHOW_TOTAL_PRICE"    => "Y",
            "SHOW_EMPTY_VALUES"   => "Y",
            "SHOW_PRODUCTS"       => "Y",
            "POSITION_FIXED"      => "Y",
            "POSITION_HORIZONTAL" => "right",
            "POSITION_VERTICAL"   => "top",
            "PATH_TO_ORDER"       => SITE_DIR."personal/order/",
            "SHOW_DELAY"          => "N",
            "SHOW_NOTAVAIL"       => "Y",
            "SHOW_SUBSCRIBE"      => "Y",
            "SHOW_IMAGE"          => "Y",
            "SHOW_PRICE"          => "Y",
            "SHOW_SUMMARY"        => "Y"
        ));?>
        </div>
      </div>
      <?else:?>
      <div class="col-xs-5 col-md-5"><span class="logo__line logo__line--short logo__line--right"></span></div>
      <?endif;?>
    </div>
  </div>
  <?php
    $APPLICATION->IncludeComponent("bitrix:menu", "header", 
      array(
          "ALLOW_MULTI_SELECT" => "Y",
          "MENU_CACHE_TYPE"    => "A",
          "ROOT_MENU_TYPE"     => "header",
          "MAX_LEVEL"          => "1",
          "CLASS"              => "header__nav center"
          ),
      false);
  ?>
</header>
<?endif;?>
