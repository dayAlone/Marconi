<?
  global $USER;
	$arIDs = array();
  $arSections = array();
	$arResult['SECTIONS'] = array();
  $arResult['BRANDS'] = getHighloadElements('brands', 'UF_XML_ID', 'UF_NAME');
  $arResult['SIZES']  = getHighloadElements('sizes', 'UF_XML_ID', 'UF_NAME');
	foreach($arResult['GRID']['ROWS'] as $item) {
		$arSections = array_merge($arSections, array_diff($item['CATALOG']['SECTION_ID'], $arSections));
	}

  foreach($arResult['GRID']['ROWS'] as &$item) {
    if(strlen($item['PROPERTY_BRAND_VALUE'])>0):
        $item['NAME'] = preg_replace("/\s\s/", "", str_replace(array($arResult['BRANDS'][$item['PROPERTY_BRAND_VALUE']], $item['PROPERTY_NOTE_SHORT_VALUE']), '', $item['NAME']));
    endif;
    $arIDs[$item['PRODUCT_ID']] = $item['ID'];
  }

  if(SITE_ID == 's2'):
    $arResult['SETS'] = array();
    $rsSets = CCatalogProductSet::getList(
      array('SET_ID'=>"DESC"),
      array(
        '@OWNER_ID' => array_keys($arIDs),
        //'=SET_ID' => 0
      ),
      false,
      false
    );
    while ($arSet = $rsSets->Fetch())
    {
      if(!isset($arResult['SETS'][$arSet['OWNER_ID']]))
        $arResult['SETS'][$arSet['OWNER_ID']] = array('TYPE' => $arSet['TYPE'], 'ITEMS'=>array(), 'IMAGES'=>array(), 'TOTAL'=>0);
      $set = &$arResult['SETS'][$arSet['OWNER_ID']];

      if($arSet['OWNER_ID'] != $arSet['ITEM_ID']) {
        if($arSet['TYPE'] == CCatalogProductSet::TYPE_GROUP):
          $id = $arIDs[$arSet['ITEM_ID']];
          if(intval($id) > 0):
            $row = $arResult['GRID']['ROWS'][$id];
            $set['ITEMS'][$id] = $row;
            $set['TOTAL'] += $row['QUANTITY'] * $row['PRICE'];
            unset($arResult['GRID']['ROWS'][$id]);
          endif;
        endif;
        if($arSet['TYPE'] == CCatalogProductSet::TYPE_SET):
          $id = $arIDs[$arSet['OWNER_ID']];
          $set['ITEMS'][$arSet['ITEM_ID']] = array('QUANTITY' => $arSet['QUANTITY']*$arResult['GRID']['ROWS'][$id]['QUANTITY']);
          $arResult['SETS']['LOCKED'][$arSet['ITEM_ID']] = $arSet['OWNER_ID'];
        endif;
      }
      elseif($arSet['TYPE'] == CCatalogProductSet::TYPE_GROUP) {
          $id = $arIDs[$arSet['ITEM_ID']];
          $row = $arResult['GRID']['ROWS'][$id];
          $arResult['DISCOUNT_PRICE_ALL'] -= $row['FULL_PRICE'] * $row['QUANTITY'];
      }
    }

    if(count($arResult['SETS']['LOCKED']) > 0):
        $arOffers = array();
        $raw = CIBlockElement::GetList(
            array("ID" => "DESC"),
            array("=ID" => array_keys($arResult['SETS']['LOCKED']), 'IBLOCK_CODE'=>'offers'),
            false,
            false,
            array('ID', 'PROPERTY_CML2_LINK', 'PROPERTY_SIZE')
        );

      while($row = $raw->Fetch()):
          $arOffers[$row['PROPERTY_CML2_LINK_VALUE']] = intval($row['ID']);
          $arResult['SETS']['LOCKED'][$row['PROPERTY_CML2_LINK_VALUE']] = $arResult['SETS']['LOCKED'][$row['ID']];
          unset($arResult['SETS']['LOCKED'][$row['ID']]);
      endwhile;

      $raw = CIBlockElement::GetList(
          array("ID" => "DESC"),
          array("=ID" => array_keys($arResult['SETS']['LOCKED'])),
          false,
          false,
          array('NAME', 'PROPERTY_BRAND', 'PROPERTY_NOTE_SHORT', 'PROPERTY_ARTNUMBER', 'PREVIEW_PICTURE', 'IBLOCK_ID', 'CODE')
      );
      while($row = $raw->Fetch()):
        if(strlen($row['PROPERTY_BRAND_VALUE'])>0):
            $row['NAME'] = preg_replace("/\s\s/", "", str_replace(array($arResult['BRANDS'][$row['PROPERTY_BRAND_VALUE']], $row['PROPERTY_NOTE_SHORT_VALUE']), '', $row['NAME']));
        endif;
        $row['PREVIEW_PICTURE_SRC'] = CFile::GetPath($row['PREVIEW_PICTURE']);
        $set = &$arResult['SETS'][$arResult['SETS']['LOCKED'][$row['ID']]];

        // Получаем разделы
        $s = CIBlockElement::GetElementGroups($row['ID'], true);
        $row['SECTIONS'] = array();
        while($el = $s->Fetch()):
          $row['SECTIONS'][] = intval($el['ID']);
        endwhile;
        if(count($row['SECTIONS']) > 0) $arSections = array_merge($arSections, array_diff($row['SECTIONS'], $arSections));

        if(!isset($set['ITEMS'][$row['ID']])):
            $ID = $arOffers[$row['ID']];
        else:
            $ID = $row['ID'];
        endif;
        $arPrice = CCatalogProduct::GetOptimalPrice($ID, 1, $USER->GetUserGroupArray());
        $set['ITEMS'][$ID] = array_merge($set['ITEMS'][$ID], $row, $arPrice);

      endwhile;
    endif;
  endif;

  $arFilter = Array('ID'=> $arSections);
  $raw = CIBlockSection::GetList(Array($by=>$order), $arFilter, true);
  while ( $section = $raw->Fetch()) {
    $arResult['SECTIONS'][$section['ID']] = $section;
  }
  $arSections = array('best-sellers', 'sales', 'sale', 'sale30', 'new', 'latest', 'promotion');

  $arResult['SHOW_TYPE'] = false;
  foreach($arResult['GRID']['ROWS'] as &$item) {
    foreach($item['CATALOG']['SECTION_ID'] as $s) {
      $section = $arResult['SECTIONS'][$s];
      if($section['CODE']=='promotion') $APPLICATION->SetPageProperty('showPromotionAlert', 'notVisible');
      if(in_array($section['CODE'], $arSections)):
        $item['TYPE'] = array('CODE'=>$section['CODE'], 'NAME' => $section['NAME']);
        if(!$arResult['SHOW_TYPE']) $arResult['SHOW_TYPE'] = true;
      endif;
    }
  }



  foreach ($arResult['SETS']['LOCKED'] as $key => $val) {

    if(!isset($arResult['SETS'][$val]['ITEMS'][$key])):

    else:
        $item = &$arResult['SETS'][$val]['ITEMS'][$key];
    endif;

    foreach($item['SECTIONS'] as $s) {
      $section = $arResult['SECTIONS'][$s];
      if(in_array($section['CODE'], $arSections)):
          $item['TYPE'] = array('CODE'=>$section['CODE'], 'NAME' => $section['NAME']);
          if(!$arResult['SHOW_TYPE']) $arResult['SHOW_TYPE'] = true;
      endif;
    }
  }
?>
