<?
global $arrFilter, $CITY;
$elements = array();

foreach ($arrFilter as $key => &$item)
	if(in_array(str_replace('=PROPERTY_', '', $key), getFilterProperties()))
		$elements[$key] = $item;

if(count($elements) > 1):
	foreach ($elements as $key => $elem)
		unset($arrFilter[$key]);
	$arrFilter[] = array_merge(array("LOGIC" => "OR"), $elements);
endif;
$arrFilter['!PROPERTY_MIN_PRICE'] = false;

if($CITY['CLOSED'] == 'Y' || SITE_ID != 's1') $arrFilter['=PROPERTY_GENERAL'] = "Y";

if(SITE_ID == 's1') $arrFilter['=CATALOG_TYPE'] = 1;

if($arResult['VARIABLES']['SECTION_CODE'] != 'coming'):
	$arrFilter[] = array(
        "LOGIC" => "OR",
        array("=PROPERTY_COMING" => false),
        array("=PROPERTY_COMING" => "Y", "=PROPERTY_RETAIL" => "Y"),
        array("=PROPERTY_COMING" => "Y", "=PROPERTY_GENERAL" => "Y")
	);
endif;

if($arResult['VARIABLES']['SECTION_CODE'] != 'promotion'):
	$arrFilter['=PROPERTY_PROMOTION'] = false;
endif;
?>