<?
	foreach ($arResult['ITEMS'] as $key=>&$item):
		$middle = CFile::ResizeImageGet(CFile::GetFileArray($item['PREVIEW_PICTURE']['ID']), Array("width" => 800, "height" => 800), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), false, 75);
		$item['IMAGES'] = array( 0 => array('small'=>$small['src'], 'middle'=>$middle['src'], 'src'=>"/upload/".$img['SUBDIR']."/".$img['FILE_NAME'], 'h'=>$img['HEIGHT'], 'w'=>$img['WIDTH']));
	endforeach;
?>