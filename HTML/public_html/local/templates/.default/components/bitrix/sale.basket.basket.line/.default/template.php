<? $this->setFrameMode(true);?>
<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$cartStyle = 'bx_cart_block';
$cartId = $cartStyle.$component->getNextNumber();
$arParams['cartId'] = $cartId;
?>

<script>
	var <?=$cartId?> = new BitrixSmallCart;
</script>
<script>
	<?=$cartId?>.siteId       = '<?=SITE_ID?>';
	<?=$cartId?>.cartId       = '<?=$cartId?>';
	<?=$cartId?>.ajaxPath     = '<?=$componentPath?>/ajax.php';
	<?=$cartId?>.templateName = '<?=$templateName?>';
	<?=$cartId?>.arParams     =  <?=CUtil::PhpToJSObject ($arParams)?>;
	<?=$cartId?>.closeMessage = '<?=GetMessage('TSB1_COLLAPSE')?>';
	<?=$cartId?>.openMessage  = '<?=GetMessage('TSB1_EXPAND')?>';
	<?=$cartId?>.activate();
</script>
<div id="<?=$cartId?>">
<?require(realpath(dirname(__FILE__)).'/ajax_template.php');?>
</div>