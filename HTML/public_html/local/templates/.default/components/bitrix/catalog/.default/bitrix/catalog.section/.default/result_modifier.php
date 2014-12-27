<?

$arResult['BRANDS'] = getHighloadElements('brands', 'UF_XML_ID', 'UF_NAME');

$images = array();
$arResult['IMAGES'] = array();
foreach ($arResult['ITEMS'] as &$item):
	$brand = $arResult['BRANDS'][$item['PROPERTIES']['BRAND']['VALUE']];
	$name = &$item['NAME'];
	$name = substr($name, strpos($name, $brand)+strlen($brand), strlen($name));
	if(count($item['PROPERTIES']['PICTURES']['VALUE'])>0)
		foreach ($item['PROPERTIES']['PICTURES']['VALUE'] as $img)
			$images[] = $img;
	$small = CFile::ResizeImageGet($item['PREVIEW_PICTURE'], Array("width" => 400, "height" => 400), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), false, 75);
	$item['PREVIEW_PICTURE']['SRC'] = $small['src'];
endforeach;
$raw = CFile::GetList(array(), array('ID'=>$images));
while($img = $raw->Fetch())
	$arResult['IMAGES'][$img['ID']] = "/upload/".$img['SUBDIR']."/".$img['FILE_NAME'];

foreach ($arResult['ITEMS'] as &$item)
	foreach ($item['PROPERTIES']['PICTURES']['VALUE'] as &$img)
		$img = $arResult['IMAGES'][$img];
?>