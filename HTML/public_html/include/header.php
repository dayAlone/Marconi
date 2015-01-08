<!DOCTYPE html><html lang='ru'>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=980"> <?/*device-width, user-scalable=no, initial-scale=1, maximum-scale=1">*/?>
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <?
  $APPLICATION->SetAdditionalCSS("/layout/css/frontend.css", true);
  $APPLICATION->AddHeadScript('/layout/js/frontend.js');
  $APPLICATION->ShowViewContent('header');?>
  <title><?php 
    if($APPLICATION->GetCurDir() != '/') {
      $APPLICATION->ShowTitle();
      $rsSites = CSite::GetByID(SITE_ID);
      $arSite  = $rsSites->Fetch();
      echo ' | ' . $arSite['NAME'];
    }
    else echo $arSite['NAME'];
    ?></title>
  <?
    $APPLICATION->ShowHead();
    $APPLICATION->ShowViewContent('header');
  ?>
</head>
<body class="<?=$APPLICATION->AddBufferContent("body_class");?>">
<div class="wrap">
  <div id="panel"><?$APPLICATION->ShowPanel();?></div>
  <div class="toolbar">
    <div class="container">
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
          <div class="auth">
          <?/*
            <a href="#" class="auth__item">Авторизация</a>
            <span class="auth__divider">|</span>
            <a href="#" class="auth__item">Регистрация</a>
            */
          ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <header class="header">
    <div class="container">
    <?if($APPLICATION->GetCurDir() == '/'):?>
      <div class="row">
        <div class="col-xs-4"><span class="logo__line"></span></div>
        <div class="col-xs-4 center"><a href="/" class="logo"><?=svg('logo')?></a></div>
        <div class="col-xs-4 right"><span class="logo__line logo__line--right"></span></div>
      </div>
    <?else:?>
      <div class="row">
        <div class="col-xs-3 col-lg-2">
          <div class="contacts"><?=svg('phone')?>+7 926 468 86 86</a>
            <div class="contacts__text">Горячая линия</div>
          </div>
        </div>
        <div class="col-xs-1 col-lg-2"><span class="logo__line logo__line--left"></span></div>
        <div class="col-xs-4 center"><a href="/" class="logo"><?=svg('logo')?></a></div>
        <div class="col-xs-1 col-lg-2"><span class="logo__line logo__line--right"></span></div>
        <div class="col-xs-3 col-lg-2">
          <div class="header__links xs-margin-top">
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
            <?/*<br><a href="#" class="simmilar"><?=svg('simmilar')?>К сравнению: 5</a>*/?>
          </div>
        </div>
      </div>
    <?endif;?>
    </div>
  </header>