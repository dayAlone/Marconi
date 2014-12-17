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
    $APPLICATION->ShowTitle();
    if($APPLICATION->GetCurDir()!='/') {
      $rsSites = CSite::GetByID(SITE_ID);
      $arSite  = $rsSites->Fetch();
      echo ' | ' . $arSite['NAME'];
    }
    ?></title>
  <?
    $APPLICATION->ShowHead();
  ?>
</head>
<body class="<?=$APPLICATION->AddBufferContent("body_class");?>" style="max-width:1100px;margin:0 auto;">
<div class="wrap">
  <div id="panel"><?$APPLICATION->ShowPanel();?></div>