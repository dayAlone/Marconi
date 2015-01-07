<?
	require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/bitrix/catalog.element/templates/.default/result_modifier.php');
	$arResult['BRANDS']    = getHighloadElements('brands', 'UF_XML_ID', 'UF_NAME');
	$arResult['COLORS']    = getHighloadElements('colors', 'UF_XML_ID', 'UF_NAME');
	$arResult['MATERIALS'] = getHighloadElements('materials', 'UF_XML_ID', 'UF_NAME');
	$arResult['SIZES']     = getHighloadElements('sizes', 'UF_XML_ID', 'UF_NAME');
	$arResult['IMAGES']    = array();
	$raw = CFile::GetList(array(), array('ID'=>implode($arResult['PROPERTIES']['PICTURES']['VALUE'])));
	while($img = $raw->Fetch()):
		$small = CFile::ResizeImageGet(CFile::GetFileArray($img['ID']), Array("width" => 200, "height" => 200), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), false, 75);
		$middle = CFile::ResizeImageGet(CFile::GetFileArray($img['ID']), Array("width" => 800, "height" => 800), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), false, 75);
		$arResult['IMAGES'][] = array('small'=>$small['src'], 'middle'=>$middle['src'], 'src'=>"/upload/".$img['SUBDIR']."/".$img['FILE_NAME'], 'h'=>$img['HEIGHT'], 'w'=>$img['WIDTH']);
	endwhile;

	$small = CFile::ResizeImageGet(CFile::GetFileArray($arResult['PREVIEW_PICTURE']['ID']), Array("width" => 800, "height" => 800), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), false, 75);
	$arResult['PREVIEW_PICTURE']['SMALL'] = $small['src'];
	
	$APPLICATION->SetPageProperty('description', strip_tags($arResult["PREVIEW_TEXT"]));
	if(isset($arResult['PREVIEW_PICTURE']['SRC'])):
		$this->SetViewTarget('header');
			?><link rel="image_src" href="<?=$arResult['PREVIEW_PICTURE']['SRC']?>" /><?
		$this->EndViewTarget();
	endif;
?>