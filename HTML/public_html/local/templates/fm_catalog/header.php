<?if(!isset($_REQUEST['short'])):?>
<?
require($_SERVER['DOCUMENT_ROOT'].'/include/header.php');
?>
<div class="catalog__nav">
    <div class="container">
      <div class="row">
        <div class="col-xs-2">
        	<?
          if(!preg_match("/\/catalog\/(.*?)\/(.*?)\/|\/basket\//", $APPLICATION->GetCurDir(), $matches)):
          $APPLICATION->IncludeComponent(
    				"bitrix:highloadblock.list", 
    				"brands", 
    				array(
    					"DETAIL_URL" => "detail.php?BLOCK_ID=#BLOCK_ID#&ROW_ID=#ID#",
    					"BLOCK_ID" => "3",
    				),
    				false
    			);
          else:
            $frame = new \Bitrix\Main\Page\FrameHelper("back");
            $frame->begin();
              $ref   = parse_url($_SERVER['HTTP_REFERER']);
              parse_str($ref['query'], $query);
              $link  = "/catalog/";
              if(isset($matches[1]))
                $link = "/catalog/".$matches[1]."/";
              $symbol = "?";
              if($link==$ref['path']&&isset($query['PAGEN_1'])){
                $link .= "?PAGEN_1=".$query['PAGEN_1'];
                $symbol = "&";
              }
              if(isset($_SESSION['Filter'][$matches[1]]))
                $link .= $symbol.$_SESSION['Filter'][$matches[1]];
              
              if($link=="/catalog/")
                $link = $_SERVER['HTTP_REFERER'];
              ?>
              <a class="catalog__back" href="<?=$link?>">
                Вернуться в каталог
              </a>
            <?$frame->beginStub();?>
              <a class="catalog__back" href="/catalog/">
                Вернуться в каталог
              </a>
            <?$frame->end();?>
          <?

          endif;?>
        </div>
        <div class="col-xs-8">
        	<?php
            if(!preg_match("/\/catalog\/(.*?)\/(.*?)\//", $APPLICATION->GetCurDir(), $matches)):
              $APPLICATION->IncludeComponent("bitrix:menu", "catalog", 
              array(
                  "ALLOW_MULTI_SELECT" => "Y",
                  "MENU_CACHE_TYPE"    => "A",
                  "ROOT_MENU_TYPE"     => "catalog_toolbar",
                  "MAX_LEVEL"          => "1",
                  "CLASS"              => "sub",
                  ),
              false);
            else:
              $APPLICATION->ShowViewContent('toolbar');
            endif;
          ?>
        </div>
        <div class="col-xs-2">
          <div class="search">
            <form action="/catalog/">
              <input type="text" name="q" value="<?=$_REQUEST['q']?>" placeholder="Введите название товара" class="search__input">
              <button class="search__button" type="submit"><?=svg('search')?>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
<div class="page">
    <div class="container">
<?endif;?>