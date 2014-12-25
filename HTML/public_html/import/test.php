<?
	$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/..");
	$path = iconv('windows-1251', 'UTF-8', file_get_contents($_SERVER['DOCUMENT_ROOT']."/import/upload/products.xml"));
	
	$dom  = new DOMDocument('1.0', 'utf-8');
	$dom->loadXML($path);
	$path = "";
	$element = array('products', 'product');
	$xpath    = new DOMXPath($dom);
	$elements = $dom->getElementsByTagName($element[0])->item(0);
	$count    = $xpath->evaluate("count(".$element[1].")", $elements); 

	$start = 1;
	if(intval($offset)>0)
		$start = $offset;
	$end   = 2226;
	
	$items = $xpath->evaluate($element[1]."[position() >= $start and not(position() > $end)]", $products);

	$errors = array('category'=>array(), 'type'=>array(), 'brand'=>array(), 'color'=>array(), 'material'=>array());

	foreach ($items as $item) {
		$raw = $item->getElementsByTagName('property');
		$props = array();
		foreach ($raw as $prop) 
		{
			$id    = $prop->getAttribute('id');
			$value = $prop->getAttribute('value');
			$props[$id] = $value;
		}
		foreach ($errors as $key => &$el) 
		{
			if(!isset($props[$key]))
				$el[] = $item->getElementsByTagName('name')->item(0)->nodeValue;
		}
	}
	$title = array('section'=>"Без раздела(section)", 'category'=>"Без категории(category)", 'type'=>"Без типа(type)", 'brand'=>"Без бренда(brand)", 'color'=>"Без цвета", 'material'=>"Без материала");
	$text = "";
	foreach ($errors as $key => &$el) 
	{
		$text .= "-------------------\n\r\n\r".$title[$key].":\n\r\n\r".implode($el, "\n\r")."\n\r";
	}
	file_put_contents($_SERVER['DOCUMENT_ROOT']."/import/log.txt", $text);
?>