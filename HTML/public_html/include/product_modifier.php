<?
$arResult['BRANDS'] = getHighloadElements('brands', 'UF_XML_ID', 'UF_NAME');

$images   = array();
$sections = array();
$arResult['IMAGES']   = array();
$arResult['SECTIONS'] = array();

foreach ($arResult['ITEMS'] as &$item):
	$brand = $arResult['BRANDS'][$item['PROPERTIES']['BRAND']['VALUE']];
	
	$raw = CIBlockElement::GetElementGroups($arResult['ID']);
	while($data = $raw->GetNext())
		if(!in_array($data['CODE'], array('all', 'sale')))
			$arResult['IBLOCK_SECTION_ID'] = $data['ID'];

	$rsPath = GetIBlockSectionPath($arResult['ID'], $item['IBLOCK_SECTION_ID']);
	$arPath = $rsPath->GetNext();
	
	if($arPath):
		$arResult['SECTIONS'][$item['IBLOCK_SECTION_ID']] = $arPath['CODE'];
		$item['DETAIL_PAGE_URL'] = "/catalog/".$arPath['CODE']."/".$item['CODE']."/";
	endif;
	
	if(count($item['PROPERTIES']['PICTURES']['VALUE'])>0)
		foreach ($item['PROPERTIES']['PICTURES']['VALUE'] as $img)
			$images[] = $img;
	
	$small = CFile::ResizeImageGet(CFile::GetFileArray($item['PREVIEW_PICTURE']['ID']), Array("width" => 400, "height" => 400), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), false, 75);
	$item['PREVIEW_PICTURE']['SRC'] = $small['src'];

endforeach;

$raw = CFile::GetList(array(), array('@ID'=>implode($images,',')));
while($img = $raw->Fetch()):
	$small = CFile::ResizeImageGet(CFile::GetFileArray($img['ID']), Array("width" => 400, "height" => 400), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), false, 75);
	$arResult['IMAGES'][$img['ID']] = array('small'=> $small['src'], 'src'=>"/upload/".$img['SUBDIR']."/".$img['FILE_NAME'], 'h'=>$img['HEIGHT'], 'w'=>$img['WIDTH']);
endwhile;

foreach ($arResult['ITEMS'] as &$item)
	foreach ($item['PROPERTIES']['PICTURES']['VALUE'] as &$img)
		$img = array_merge($arResult['IMAGES'][$img], array('title'=>$item['OLD_NAME']));

$arResult['SIZES'] = getHighloadElements('sizes', 'UF_XML_ID', 'UF_NAME');
?>