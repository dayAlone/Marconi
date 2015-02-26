<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
if(count($arResult['SECTIONS'])>0):
?>
<div class="row enter">
	<?foreach($arResult['SECTIONS'] as $item):?>
	<div class="col-xs-12 col-md-<?=$arResult['SIZES'][$item['UF_SIZE']][1]*3?>">
		<a style="background-image: url(<?=$item['PICTURE']['SRC']?>)" class="lookbook__promo <?=($arResult['SIZES'][$item['UF_SIZE']][0]==2?"lookbook__promo--height":"")?>" href="/lookbook/<?=$item['CODE']?>/">
	    	<div class="lookbook__promo-title"><span><?=$item['NAME']?></span></div>
	    </a>
	</div>
	<?endforeach;?>
</div>
<?endif;?>