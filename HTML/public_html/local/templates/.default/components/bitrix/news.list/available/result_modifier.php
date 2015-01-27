<?
	if(isset($arParams['CACHE_NOTES'])):
		$arResult['COUNTS'] = array();
		$raw = CCatalogStoreProduct::GetList(array(), array('PRODUCT_ID' => ($arParams['OFFERS']?array_keys($arParams['OFFERS']):$arParams['CACHE_NOTES'])), false, false);
		while ($item = $raw->Fetch()):
			if(!isset($arResult['COUNTS'][$item['STORE_ID']]))
				$arResult['COUNTS'][$item['STORE_ID']] = array();
			if($item['AMOUNT']>0)
				$arResult['COUNTS'][$item['STORE_ID']][$item['PRODUCT_ID']] = $item['AMOUNT'];
		endwhile;
	endif;
?>