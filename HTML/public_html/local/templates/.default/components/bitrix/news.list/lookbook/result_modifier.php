<?
foreach ($arResult['ITEMS'] as &$item):
	if(isset($item['PREVIEW_PICTURE']['ID'])):
		$small = CFile::ResizeImageGet(CFile::GetFileArray($item['PREVIEW_PICTURE']['ID']), Array("width" => 570, "height" => 570), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), false, 75);
		$medium = CFile::ResizeImageGet(CFile::GetFileArray($item['PREVIEW_PICTURE']['ID']), Array("width" => 1140, "height" => 1140), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), false, 75);
		$item['PREVIEW_PICTURE']['SRC'] = $medium['src'];
		$item['PREVIEW_PICTURE']['SMALL'] = $small['src'];
	endif;
	endforeach;

?>