<?
$arResult['BRANDS'] = getHighloadElements('brands', 'UF_XML_ID', 'UF_NAME');

$images   = array();
$sections = array();

$arResult['IMAGES']   = array();
$arResult['SECTIONS'] = array();

foreach ($arResult['ITEMS'] as &$item):

	$item['OLD_NAME'] = $item['NAME'];
	$brand = $arResult['BRANDS'][$item['PROPERTIES']['BRAND']['VALUE']];
	
	$rsPath = GetIBlockSectionPath($arResult['ID'], $item['IBLOCK_SECTION_ID']);
	$arPath = $rsPath->GetNext();
	
	if($arPath):
		$arResult['SECTIONS'][$item['IBLOCK_SECTION_ID']] = $arPath['CODE'];
		$item['DETAIL_PAGE_URL'] = "/catalog/".$arPath['CODE']."/".$item['CODE']."/";
	endif;

	$name = &$item['NAME'];
	$name = substr($name, strpos($name, $brand)+strlen($brand), strlen($name));
	
	if(count($item['PROPERTIES']['PICTURES']['VALUE'])>0)
		foreach ($item['PROPERTIES']['PICTURES']['VALUE'] as $img)
			$images[] = $img;
	
	$small = CFile::ResizeImageGet(CFile::GetFileArray($item['PREVIEW_PICTURE']['ID']), Array("width" => 400, "height" => 400), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), false, 75);
	$item['PREVIEW_PICTURE']['SRC'] = $small['src'];

endforeach;

$raw = CFile::GetList(array(), array('@ID'=>implode($images,',')));
while($img = $raw->Fetch())
	$arResult['IMAGES'][$img['ID']] = array('src'=>"/upload/".$img['SUBDIR']."/".$img['FILE_NAME'], 'h'=>$img['HEIGHT'], 'w'=>$img['WIDTH']);

foreach ($arResult['ITEMS'] as &$item)
	foreach ($item['PROPERTIES']['PICTURES']['VALUE'] as &$img)
		$img = array_merge($arResult['IMAGES'][$img], array('title'=>$item['OLD_NAME']));

$arResult['SIZES'] = getHighloadElements('sizes', 'UF_XML_ID', 'UF_NAME');
?>