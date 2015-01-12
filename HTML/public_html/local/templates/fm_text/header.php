<?if(!isset($_REQUEST['short'])):?>
<?
require($_SERVER['DOCUMENT_ROOT'].'/include/header.php');
$APPLICATION->SetPageProperty('body_class', "textpage");
?>
<?endif;?>
<div class="page">
    <div class="container">
      <div class="row">
        <div class="col-md-3">
        	<?php
	          $APPLICATION->IncludeComponent("bitrix:menu", "side", 
	          array(
	              "ALLOW_MULTI_SELECT" => "Y",
	              "MENU_CACHE_TYPE"    => "A",
	              "ROOT_MENU_TYPE"     => "catalog_footer",
	              "CLASS"              => "footer",
	              "MAX_LEVEL"          => "1",
	              ),
	          false);
	      ?>
        </div>
        <div class="col-md-9 textpage__content">
        	<div class="page__title xl-margin-bottom"><?=$APPLICATION->ShowTitle();?></div>
