<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$items = array();
foreach($arResult['GRID']['ROWS'] as $item):
		$items[] = array(
			'id' => $item['ID'], 
			'price' => $item['PRICE'],
			'price_text' => number_format($item['FULL_PRICE'], 0, ' ', ' ').' <span class="rubl">₽</span>',
			'discount' => $item['DISCOUNT_PRICE'],
			'percent' => round($item["DISCOUNT_PRICE_PERCENT_FORMATED"])."%");
	endforeach;
$data = array(
	'result' => 'success',
	'items'  => $items
);
echo json_encode($data);
?>