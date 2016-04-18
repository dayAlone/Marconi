<!DOCTYPE html><html lang='ru'>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <?
  $APPLICATION->SetAdditionalCSS("/layout/css/frontend.css", true);
  $APPLICATION->AddHeadScript('/layout/js/frontend.js');
  global $CITY;
  ?>
  <title><?php
    $rsSites = CSite::GetByID(SITE_ID);
    $arSite  = $rsSites->Fetch();
    if($APPLICATION->GetCurDir() != '/' && $APPLICATION->GetCurDir() != "/eng/") {
      $APPLICATION->ShowTitle();

      echo ' | ' . $arSite['NAME'];
    }
    else echo $arSite['NAME'];
    ?></title>
  <?
    $APPLICATION->ShowHead();
    $APPLICATION->ShowViewContent('header');
    if (SITE_ID == 's2') {?>
        <style>
            @font-face {
              font-family: 'Proxima';
              src: url("/layout/css/../fonts/ProximaNova-Bold.ttf");
              src: local('â˜º'), url("/layout/css/../fonts/ProximaNova-Bold.ttf") format('truetype');
              font-weight: bold;
            }
            @font-face {
              font-family: 'Proxima';
              src: url("/layout/css/../fonts/ProximaNova-Regular.ttf");
              src: local('â˜º'), url("/layout/css/../fonts/ProximaNova-Regular.ttf") format('truetype');
              font-weight: normal;
            }
            @font-face {
              font-family: 'Proxima';
              src: url("/layout/css/../fonts/ProximaNova-Light.ttf");
              src: local('â˜º'), url("/layout/css/../fonts/ProximaNova-Light.ttf") format('truetype');
              font-weight: 300;
            }
            @font-face {
              font-family: 'Rubl';
              src: url("/layout/css/../fonts/Rubl-Bold.ttf");
              src: local('â˜º'), url("/layout/css/../fonts/Rubl-Bold.ttf") format('truetype');
              font-weight: bold;
            }
            @font-face {
              font-family: 'Rubl';
              src: url("/layout/css/../fonts/Rubl-Regular.ttf");
              src: local('â˜º'), url("/layout/css/../fonts/Rubl-Regular.ttf") format('truetype');
              font-weight: normal;
            }
        </style>
    <? } 
  ?>
</head>
<body class="<?=$APPLICATION->AddBufferContent("body_class");?> <?=(strstr($APPLICATION->GetCurDir(), "/eng/")?"eng":"")?> <?=SITE_ID?>">
<div class="wrap">
  <div id="panel"><?$APPLICATION->ShowPanel();?></div>
  <?
  if(SITE_ID == 's1'):
    require($_SERVER['DOCUMENT_ROOT'].'/include/header_fmarconi.php');
  else:
    require($_SERVER['DOCUMENT_ROOT'].'/include/header_italbags.php');
  endif;

  ?>
