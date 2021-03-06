<div class="toolbar">
    <div class="container toolbar__desktop">
      <div class="row">
        <div class="col-lg-2 visible-lg">
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
          <div class="toolbar__divider"></div>
          <span class="lang">
            <a href="/" class="lang__item <?=(strstr($APPLICATION->GetCurDir(), "/eng/")?"":"lang__item--active")?>">RU</a>
            <a href="/eng/" class="lang__item <?=(!strstr($APPLICATION->GetCurDir(), "/eng/")?"":"lang__item--active")?>">EN</a>
          </span>
        </div>
        <div class="col-xs-10 col-lg-8">
          <?php
              $APPLICATION->IncludeComponent("bitrix:menu", "toolbar",
              array(
                  "ALLOW_MULTI_SELECT" => "Y",
                  "MENU_CACHE_TYPE"    => "A",
                  "ROOT_MENU_TYPE"     => "toolbar",
                  "MAX_LEVEL"          => "1",
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
                <a class="auth__item" href="#register" data-toggle="modal" data-target="#register">Регистрация</a>
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
                <a class="auth__item" href="#register" data-toggle="modal" data-target="#register">Регистрация</a>
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
                <a class="auth__item" href="#register" data-toggle="modal" data-target="#register">Регистрация</a>
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
                <a class="auth__item" href="#register" data-toggle="modal" data-target="#register">Регистрация</a>
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
          <a href="tel:<?=preg_replace('/[^\dx+]/i', '', $CITY['PHONE'])?>" title=""><?=svg('phone')?></a><span class="toolbar__divider"></span><span class="search__frame"><a href="#" class="search"><?=svg('seach')?></a><span class="toolbar__divider"></span></span><a <?=($GLOBALS['USER']->IsAuthorized()?'href="/profile/"':'href="#login" data-toggle="modal"')?> title=""><?=svg('profile')?></a><span class="toolbar__divider"></span><a href="/basket/" title=""><?=svg('cart')?></a>
        </div>
      </div>
    </div>
  </div>
  <header class="header">
    <div class="container header__mobile center">
      <a href="/" class="logo"><?=svg('logo')?></a>
    </div>
    <div class="container header__desktop">
    <?if($APPLICATION->GetCurDir() == '/' || strstr($APPLICATION->GetCurDir(), "/eng/")):?>
      <div class="row">
        <div class="col-xs-4"><span class="logo__line"></span></div>
        <div class="col-xs-4 center"><a href="/" class="logo"><?=svg('logo')?></a></div>
        <div class="col-xs-4 right"><span class="logo__line logo__line--right"></span></div>
      </div>
    <?else:?>
      <div class="row">
        <div class="col-xs-3 col-md-2">
          <div class="contacts">
              <?
                $frame = new \Bitrix\Main\Page\FrameHelper("phone-number");
                $frame->begin();?>
                <a href="tel:<?=preg_replace('/[^\dx+]/i', '', $CITY['PHONE'])?>" class="contacts__number"><?=svg('phone')?><?=$CITY['PHONE']?></a>
                <?$frame->beginStub();?>
                <a href="tel:+74959723265" class="contacts__number"><?=svg('phone')?>+7 495 972-32-65</a>

                <?$frame->end();?>

          </div>
          <div class="city">
            <?
            $frame = new \Bitrix\Main\Page\FrameHelper("city-select");
            $frame->begin();?>
            <a href="#" class="city__trigger"><span><?=$CITY['NAME']?></span> <?=svg('arrow')?></a>
            <div class="city__dropdown">
                <div class="city__message">
                  Попробуем угадать, ваш город — <br>
                  <strong class="city__value"><?=$CITY['NAME']?>?</strong><br>
                  <a href="#" class="city__button city__button--true">Да</a><a href="#" class="city__button city__button--false">Выбрать другой город</a><br>
                  <div class="city__description">От выбранного города зависит наличие товара <br>и способы доставки</div>
                </div>
                <div class="city__select">
                  <?
                    $APPLICATION->IncludeComponent(
                      "bitrix:sale.location.selector.search",
                      ".default",
                      array(
                        "ID"                     => "",
                        "CODE"                   => "",
                        //"CACHE_NOTE"             => $arResult["BUYER_STORE"],
                        "INPUT_NAME"             => "place",
                        "PROVIDE_LINK_BY"        => "id",
                        "SEARCH_BY_PRIMARY"      => "Y",
                        "EXCLUDE_SUBTREE"        => "",
                        "FILTER_BY_SITE"         => "Y",
                        "SHOW_DEFAULT_LOCATIONS" => "Y",
                        "CACHE_TYPE"             => "A",
                        "CACHE_TIME"             => "36000000"
                      ),
                      false
                    );
                    ?>
                </div>
            </div>
            <?$frame->beginStub();?>
            <a href="#" class="city__trigger"><span>Москва</span> <?=svg('arrow')?></a>
            <?$frame->end();?>
          </div>
        </div>
        <div class="col-xs-1 col-md-2"><span class="logo__line logo__line--left"></span></div>
        <div class="col-xs-4 center"><a href="/" class="logo"><?=svg('logo')?></a></div>
        <div class="col-xs-1 col-md-2"><span class="logo__line logo__line--right"></span></div>
        <div class="col-xs-3 col-md-2">
          <div class="header__links">
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
            <?
            $frame = new \Bitrix\Main\Page\FrameHelper("compare");
            $frame->begin();
              if(isset($_COOKIE['simmilar'])&&count(json_decode($_COOKIE['simmilar']))>0):?>
                <a href="/catalog/compare.php" class="simmilar"><?=svg('simmilar')?><span class="simmilar__text">К сравнению: <?=count(json_decode($_COOKIE['simmilar']))?></span></a>
              <?else:?>
                <a href="#" class="simmilar"><?=svg('simmilar')?><span class="simmilar__text">Товары не выбраны</span></a>
              <?endif;
            $frame->beginStub();
              ?><a href="#" class="simmilar"><?=svg('simmilar')?><span class="simmilar__text">Товары не выбраны</span></a><?
            $frame->end();
            ?>

          </div>
        </div>
      </div>
    <?endif;?>
    </div>
  </header>
