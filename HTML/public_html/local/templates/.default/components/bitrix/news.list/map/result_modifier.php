<?
	$arResult['SECTIONS'] = array();
	$array    = Array('IBLOCK_ID'=>$arResult['ID'], 'ACTIVE'=>'Y', '=<DEPTH_LEVEL'=>1, 'CHECK_PERMISSIONS' => 'N');
	$raw      = CIBlockSection::GetList(Array("LEFT_MARGIN"=>"ASC"), $array, true, array('ID', 'NAME', 'XML_ID', 'DEPTH_LEVEL', 'IBLOCK_SECTION_ID'));

	while($section = $raw->GetNext()):
		$arResult['SECTIONS'][$section['ID']] = $section;
	endwhile;
?>