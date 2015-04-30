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
  ?>
</head>
<body class="<?=$APPLICATION->AddBufferContent("body_class");?> <?=(strstr($APPLICATION->GetCurDir(), "/eng/")?"eng":"")?>">
<div class="wrap">
  <div id="panel"><?$APPLICATION->ShowPanel();?></div>
  <?if(SITE_ID == 's1'):
    require($_SERVER['DOCUMENT_ROOT'].'/include/header_fmarconi.php');
  else:
    require($_SERVER['DOCUMENT_ROOT'].'/include/header_italbags.php');
  endif;?>