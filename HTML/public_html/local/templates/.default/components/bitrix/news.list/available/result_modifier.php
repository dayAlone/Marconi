<?
	$arResult['COUNTS'] = array();
	$raw = CCatalogStoreProduct::GetList(array(), array('PRODUCT_ID' => ($arParams['OFFERS']?array_keys($arParams['OFFERS']):$arParams['CACHE_NOTES'])), false, false);
	while ($item = $raw->Fetch()):
		if(!isset($arResult['COUNTS'][$item['STORE_ID']]))
			$arResult['COUNTS'][$item['STORE_ID']] = array();
		$arResult['COUNTS'][$item['STORE_ID']][$item['PRODUCT_ID']] = $item['AMOUNT'];
	endwhile;
?>