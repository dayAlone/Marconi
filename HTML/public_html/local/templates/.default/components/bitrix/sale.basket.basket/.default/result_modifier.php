<?
	$arIDs                = array();
	$arResult['SECTIONS'] = array();

	foreach($arResult['GRID']['ROWS'] as $item) {
		$arIDs = array_merge($arIDs, array_diff($item['CATALOG']['SECTION_ID'], $arIDs));
	}
	
	$arFilter = Array('ID'=> $arIDs);
  	$raw = CIBlockSection::GetList(Array($by=>$order), $arFilter, true);
  	while ( $section = $raw->Fetch()) {
  		$arResult['SECTIONS'][$section['ID']] = $section;
  	}
  	foreach($arResult['GRID']['ROWS'] as &$item) {
  		foreach($item['CATALOG']['SECTION_ID'] as $s) {
  			$section = $arResult['SECTIONS'][$s];
  			if(in_array($section['CODE'], array('best-sellers', 'sale', 'sale30', 'latest', 'promotion'))):
  				$item['TYPE'] = array('CODE'=>$section['CODE'], 'NAME' => $section['NAME']);
  			endif;
  		}
  	}
?>