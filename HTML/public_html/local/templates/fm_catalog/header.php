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
            ?>
          <a class="catalog__back" href="<?=(isset($matches[1])?"/catalog/".$matches[1]."/":"/catalog/")?>">
            Вернуться в каталог
          </a>
          <?endif;
			/*
          <div class="brand-select">
            <div class="dropdown">
            	<a href="#" class="dropdown__trigger"><span class="dropdown__text">Все бренды</span><svg width="8" height="7" viewBox="0 0 8 7" xmlns="http://www.w3.org/2000/svg"><g id="Page-1" fill="none" fill-rule="evenodd"><path d="M0 0h7.956L3.978 6.89 0 0" id="arrow" fill="#21242C"/></g></svg></a><span class="dropdown__frame"><a href="#" style="display:none" class="dropdown__item">Все бренды</a><a href="#" class="dropdown__item">Francesco Marconi</a><a href="#" class="dropdown__item">Gilda Tonelli</a><a href="#" class="dropdown__item">Uomo Tonelli</a><a href="#" class="dropdown__item">Thelebre</a></span>
              <select class="dropdown__select">
                <option value="Francesco Marconi">Francesco Marconi</option>
                <option value="Gilda Tonelli">Gilda Tonelli</option>
                <option value="Uomo Tonelli">Uomo Tonelli</option>
                <option value="Thelebre">Thelebre</option>
              </select>
            </div>
          </div>*/?>
        </div>
        <div class="col-xs-8">
        	<?php
              $APPLICATION->IncludeComponent("bitrix:menu", "catalog", 
              array(
                  "ALLOW_MULTI_SELECT" => "Y",
                  "MENU_CACHE_TYPE"    => "A",
                  "ROOT_MENU_TYPE"     => "catalog_toolbar",
                  "MAX_LEVEL"          => "1",
                  "CLASS"              => "sub",
                  ),
              false);
          ?>
        </div>
        <div class="col-xs-2">
          <div class="search">
            <form action="/catalog/">
              <input type="text" name="q" value="<?=$_REQUEST['q']?>" placeholder="Введите название товара" class="search__input">
              <button class="search__button" type="submit"><svg width="14" height="14" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg"><g id="Page-1" fill="none" fill-rule="evenodd"><path d="M13.855 11.735L10.77 8.61c.503-.875.765-1.85.765-2.832C11.535 2.593 8.945 0 5.767 0S0 2.593 0 5.778c0 3.186 2.59 5.778 5.767 5.778 1.015 0 2.018-.28 2.913-.814l3.07 3.113c.068.07.166.112.265.112.098 0 .196-.04.265-.112l1.575-1.593c.145-.15.145-.382 0-.527zM5.767 2.25c1.942 0 3.52 1.583 3.52 3.528 0 1.946-1.578 3.527-3.52 3.527s-3.52-1.58-3.52-3.527c0-1.945 1.578-3.527 3.52-3.527z" id="search" fill="#000"/></g></svg>
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