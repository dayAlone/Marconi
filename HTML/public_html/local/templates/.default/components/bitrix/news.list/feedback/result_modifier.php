<?
	foreach ($arResult['ITEMS'] as $key=>&$item):
		$middle = CFile::ResizeImageGet(CFile::GetFileArray($item['PREVIEW_PICTURE']['ID']), Array("width" => 800, "height" => 800), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), true, 75);
		$item['IMAGES'] = array( 0 => array('src'=>$middle['src'], 'h'=>$middle['width'], 'w'=>$middle['height']));
	endforeach;
?>