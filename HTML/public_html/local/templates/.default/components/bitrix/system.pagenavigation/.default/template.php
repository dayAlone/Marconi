<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
if($arResult["NavPageCount"]>1):
$strNavQueryString = ($arResult["NavQueryString"] != "" ? str_replace('&amp;short=Y', '', $arResult["NavQueryString"])."&amp;" : "");
?>
<div class="pages">
	<?
	$i = $arResult["nStartPage"];
	while($i <= $arResult["nEndPage"]):?>
		<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$i?>" class="pages__item <?=($i==$arResult["NavPageNomer"]?'pages__item--active':"")?>"><?=$i?></a>
	<?
	$i++;
	endwhile;?>
	<?if ($arResult["bShowAll"] && $arResult["NavPageCount"]<7):?>
		<?if ($arResult["NavShowAll"]):?>
			<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=0" rel="nofollow"><?=GetMessage("nav_paged")?></a>
		<?else:?>
			<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=1" rel="nofollow" class="pages__item pages__item--all <?=($i==$arResult["NavPageNomer"]?'pages__item--active':"")?>">Показать все</a>
		<?endif?>
	<?endif?>
	
</div>
<?endif;?>