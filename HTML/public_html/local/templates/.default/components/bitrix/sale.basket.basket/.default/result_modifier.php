<?
	$arIDs                = array();
	$arResult['SECTIONS'] = array();
  $arResult['BRANDS'] = getHighloadElements('brands', 'UF_XML_ID', 'UF_NAME');
  $arResult['SIZES']  = getHighloadElements('sizes', 'UF_XML_ID', 'UF_NAME');
	foreach($arResult['GRID']['ROWS'] as $item) {
		$arIDs = array_merge($arIDs, array_diff($item['CATALOG']['SECTION_ID'], $arIDs));
	}
	
	$arFilter = Array('ID'=> $arIDs);
  $raw = CIBlockSection::GetList(Array($by=>$order), $arFilter, true);
  while ( $section = $raw->Fetch()) {
  	$arResult['SECTIONS'][$section['ID']] = $section;
  }

  $arIDs = array();
  
  foreach($arResult['GRID']['ROWS'] as &$item) {
    $arIDs[$item['PRODUCT_ID']] = $item['ID'];
    $item['NAME'] = preg_replace("/\s\s/", "", str_replace(array($arResult['BRANDS'][$item['PROPERTY_BRAND_VALUE']], $item['PROPERTY_NOTE_SHORT_VALUE']), '', $item['NAME']));
  	foreach($item['CATALOG']['SECTION_ID'] as $s) {
  		$section = $arResult['SECTIONS'][$s];
  		if(in_array($section['CODE'], array('best-sellers', 'sale', 'sale30', 'latest', 'promotion'))):
  			$item['TYPE'] = array('CODE'=>$section['CODE'], 'NAME' => $section['NAME']);
  		endif;
  	}
  }

  if(SITE_ID == 's2'):
    $arResult['SETS'] = array();
    $rsSets = CCatalogProductSet::getList(
      array(),
      array(
        '@ITEM_ID' => array_keys($arIDs),
        //'=SET_ID' => 0
      ),
      false,
      false,
      array('ID', 'OWNER_ID', 'ITEM_ID', 'TYPE')
    );
    while ($arSet = $rsSets->Fetch())
    {
      if(!isset($arResult['SETS'][$arSet['OWNER_ID']]))
        $arResult['SETS'][$arSet['OWNER_ID']] = array('TYPE' => $arSet['TYPE'], 'ITEMS'=>array(), 'IMAGES'=>array());
      $set = &$arResult['SETS'][$arSet['OWNER_ID']];
      if($arSet['OWNER_ID'] != $arSet['ITEM_ID']) {
        if($arSet['TYPE'] == CCatalogProductSet::TYPE_GROUP):
          $set['ITEMS'][] = $arResult['GRID']['ROWS'][$arIDs[$arSet['ITEM_ID']]];
          unset($arResult['GRID']['ROWS'][$arIDs[$arSet['ITEM_ID']]]);
        endif;
        if($arSet['TYPE'] == CCatalogProductSet::TYPE_SET):
          $arResult['SETS']['LOCKED'][] = $arSet['ITEM_ID'];
        endif;
      }
    }

    //var_dump($arResult['SETS']);

  endif;

?>