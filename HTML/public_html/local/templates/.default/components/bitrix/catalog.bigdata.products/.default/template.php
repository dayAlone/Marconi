<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$frame = $this->createFrame()->begin("");

$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME']
);

if (isset($arResult['REQUEST_ITEMS']))
{
	CJSCore::Init(array('ajax'));

	$injectId = 'bigdata_recommeded_products_'.rand();

	// component parameters
	$signer = new \Bitrix\Main\Security\Sign\Signer;
	$signedParameters = $signer->sign(
		base64_encode(serialize($arResult['_ORIGINAL_PARAMS'])),
		'bx.bd.products.recommendation'
	);
	$signedTemplate = $signer->sign($arResult['RCM_TEMPLATE'], 'bx.bd.products.recommendation');

	?>

	<span id="<?=$injectId?>" class="bigdata_recommended_products_container"></span>

	<script type="application/javascript">

		BX.cookie_prefix = '<?=CUtil::JSEscape(COption::GetOptionString("main", "cookie_name", "BITRIX_SM"))?>';
		BX.cookie_domain = '<?=$APPLICATION->GetCookieDomain()?>';
		BX.current_server_time = '<?=time()?>';

		BX.ready(function(){

			var params = <?=CUtil::PhpToJSObject($arResult['RCM_PARAMS'])?>;
			var url = 'https://analytics.bitrix.info/crecoms/v1_0/recoms.php';
			var data = BX.ajax.prepareData(params);

			if (data)
			{
				url += (url.indexOf('?') !== -1 ? "&" : "?") + data;
				data = '';
			}

			var onready = function(response) {

				if (!response.items)
				{
					response.items = [];
				}
				BX.ajax({
					url: '/bitrix/components/bitrix/catalog.bigdata.products/ajax.php?'+BX.ajax.prepareData({'AJAX_ITEMS': response.items, 'RID': response.id}),
					method: 'POST',
					data: {'parameters':'<?=CUtil::JSEscape($signedParameters)?>', 'template': '<?=CUtil::JSEscape($signedTemplate)?>', 'rcm': 'yes'},
					dataType: 'html',
					processData: false,
					start: true,
					onsuccess: function (html) {
						var ob = BX.processHTML(html);

						// inject
						BX('<?=$injectId?>').innerHTML = ob.HTML;
						BX.ajax.processScripts(ob.SCRIPT);

						// set detail view event - set up recommendation id
						var detailLinks = BX.findChildren(BX('<?=$injectId?>'), {'className':'bx_rcm_view_link'}, true);
						if (detailLinks)
						{
							for (i in detailLinks)
							{
								BX.bind(detailLinks[i], 'click', function(e){
									e.preventDefault();

									window.JCCatalogBigdataProducts.prototype.RememberRecommendation(
										BX(this),
										BX(this).getAttribute('data-product-id')
									);
								});
							}
						}
					}
				});
			};

			BX.ajax({
				'method': 'GET',
				'dataType': 'json',
				'url': url,
				'timeout': 3,
				'onsuccess': onready,
				'onfailure': onready
			});
		});
	</script>

	<?
	$frame->end();
	return;
}


if (!empty($arResult['ITEMS']))
{

	?>
	<input type="hidden" name="bigdata_recommendation_id" value="<?=htmlspecialcharsbx($arResult['RID'])?>">
	<div data-title="francesco marconi рекомендует" class="catalog__divider catalog__divider--title"></div>
	<div class="catalog catalog--full-width catalog--one-line">
        <div class="row">
	<?
	foreach ($arResult['ITEMS'] as $key => $arItem)
	{
		$item = $arItem;
		require($_SERVER['DOCUMENT_ROOT'].'/include/product.php');

		$strMainID = $this->GetEditAreaId($arItem['ID'] . $key);

		$arItemIDs = array(
			'ID' => $strMainID,
			'PICT' => $strMainID . '_pict',
			'SECOND_PICT' => $strMainID . '_secondpict',
			'MAIN_PROPS' => $strMainID . '_main_props',

			'QUANTITY' => $strMainID . '_quantity',
			'QUANTITY_DOWN' => $strMainID . '_quant_down',
			'QUANTITY_UP' => $strMainID . '_quant_up',
			'QUANTITY_MEASURE' => $strMainID . '_quant_measure',
			'BUY_LINK' => $strMainID . '_buy_link',
			'SUBSCRIBE_LINK' => $strMainID . '_subscribe',

			'PRICE' => $strMainID . '_price',
			'DSC_PERC' => $strMainID . '_dsc_perc',
			'SECOND_DSC_PERC' => $strMainID . '_second_dsc_perc',

			'PROP_DIV' => $strMainID . '_sku_tree',
			'PROP' => $strMainID . '_prop_',
			'DISPLAY_PROP_DIV' => $strMainID . '_sku_prop',
			'BASKET_PROP_DIV' => $strMainID . '_basket_prop'
		);

		$strObName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

		$strTitle = (
		isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) && '' != isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"])
			? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]
			: $arItem['NAME']
		);
		$showImgClass = $arParams['SHOW_IMAGE'] != "Y" ? "no-imgs" : "";

		?>
		<?
		if (!isset($arItem['OFFERS']) || empty($arItem['OFFERS'])) // Simple Product
		{
		
			$arJSParams = array(
				'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
				'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
				'SHOW_ADD_BASKET_BTN' => false,
				'SHOW_BUY_BTN' => true,
				'SHOW_ABSENT' => true,
				'PRODUCT' => array(
					'ID' => $arItem['ID'],
					'NAME' => $arItem['~NAME'],
					'PICT' => ('Y' == $arItem['SECOND_PICT'] ? $arItem['PREVIEW_PICTURE_SECOND'] : $arItem['PREVIEW_PICTURE']),
					'CAN_BUY' => $arItem["CAN_BUY"],
					'SUBSCRIPTION' => ('Y' == $arItem['CATALOG_SUBSCRIPTION']),
					'CHECK_QUANTITY' => $arItem['CHECK_QUANTITY'],
					'MAX_QUANTITY' => $arItem['CATALOG_QUANTITY'],
					'STEP_QUANTITY' => $arItem['CATALOG_MEASURE_RATIO'],
					'QUANTITY_FLOAT' => is_double($arItem['CATALOG_MEASURE_RATIO']),
					'ADD_URL' => $arItem['~ADD_URL'],
					'SUBSCRIBE_URL' => $arItem['~SUBSCRIBE_URL']
				),
				'BASKET' => array(
					'ADD_PROPS' => ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET']),
					'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
					'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
					'EMPTY_PROPS' => $emptyProductProperties
				),
				'VISUAL' => array(
					'ID' => $arItemIDs['ID'],
					'PICT_ID' => ('Y' == $arItem['SECOND_PICT'] ? $arItemIDs['SECOND_PICT'] : $arItemIDs['PICT']),
					'QUANTITY_ID' => $arItemIDs['QUANTITY'],
					'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
					'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
					'PRICE_ID' => $arItemIDs['PRICE'],
					'BUY_ID' => $arItemIDs['BUY_LINK'],
					'BASKET_PROP_DIV' => $arItemIDs['BASKET_PROP_DIV']
				),
				'LAST_ELEMENT' => $arItem['LAST_ELEMENT']
			);
			?>
				<script type="text/javascript">
					var <? echo $strObName; ?> = new JCCatalogBigdataProducts(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
				</script><?
		}
		else // Wth Sku
		{
			$arJSParams = array(
				'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
				'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
				'SHOW_ADD_BASKET_BTN' => false,
				'SHOW_BUY_BTN' => true,
				'SHOW_ABSENT' => true,
				'SHOW_SKU_PROPS' => $arItem['OFFERS_PROPS_DISPLAY'],
				'SECOND_PICT' => ($arParams['SHOW_IMAGE'] == "Y" ? $arItem['SECOND_PICT'] : false),
				'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
				'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
				'DEFAULT_PICTURE' => array(
					'PICTURE' => $arItem['PRODUCT_PREVIEW'],
					'PICTURE_SECOND' => $arItem['PRODUCT_PREVIEW_SECOND']
				),
				'VISUAL' => array(
					'ID' => $arItemIDs['ID'],
					'PICT_ID' => $arItemIDs['PICT'],
					'SECOND_PICT_ID' => $arItemIDs['SECOND_PICT'],
					'QUANTITY_ID' => $arItemIDs['QUANTITY'],
					'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
					'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
					'QUANTITY_MEASURE' => $arItemIDs['QUANTITY_MEASURE'],
					'PRICE_ID' => $arItemIDs['PRICE'],
					'TREE_ID' => $arItemIDs['PROP_DIV'],
					'TREE_ITEM_ID' => $arItemIDs['PROP'],
					'BUY_ID' => $arItemIDs['BUY_LINK'],
					'ADD_BASKET_ID' => $arItemIDs['ADD_BASKET_ID'],
					'DSC_PERC' => $arItemIDs['DSC_PERC'],
					'SECOND_DSC_PERC' => $arItemIDs['SECOND_DSC_PERC'],
					'DISPLAY_PROP_DIV' => $arItemIDs['DISPLAY_PROP_DIV'],
				),
				'BASKET' => array(
					'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
					'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE']
				),
				'PRODUCT' => array(
					'ID' => $arItem['ID'],
					'NAME' => $arItem['~NAME']
				),
				'OFFERS' => $arItem['JS_OFFERS'],
				'OFFER_SELECTED' => $arItem['OFFERS_SELECTED'],
				'TREE_PROPS' => $arSkuProps,
				'LAST_ELEMENT' => $arItem['LAST_ELEMENT']
			);
			?>
				<script type="text/javascript">
					var <? echo $strObName; ?> = new JCCatalogBigdataProducts(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
				</script>
			<?
		}
	}
	?>
	</div></div>
<?
}

$frame->end();?>