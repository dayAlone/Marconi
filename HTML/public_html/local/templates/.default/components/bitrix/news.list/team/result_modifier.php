<?
foreach ($arResult['ITEMS'] as $key=>&$item):
	$small = CFile::ResizeImageGet(CFile::GetFileArray($item['PREVIEW_PICTURE']['ID']), Array("width" => 400, "height" => 400), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), false, 75);
	$item['PREVIEW_PICTURE']['SMALL'] = $small['src'];
endforeach;