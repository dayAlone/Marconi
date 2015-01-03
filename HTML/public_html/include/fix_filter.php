<?
global $arrFilter;
$elements = array();

foreach ($arrFilter as $key => &$item)
	if(in_array(str_replace('=PROPERTY_', '', $key), getFilterProperties()))
		$elements[$key] = $item;

if(count($elements) > 1):
	foreach ($elements as $key => $elem)
		unset($arrFilter[$key]);
	$arrFilter[] = array_merge(array("LOGIC" => "OR"), $elements);
endif;
var_dump($arrFilter);
?>