<?
ini_set('xdebug.var_display_max_depth', 5);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

define("BX_COMPOSITE_DEBUG", false);
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");

AddEventHandler("main", "OnEndBufferContent", "OnEndBufferContentHandler");
	function OnEndBufferContentHandler(&$content)
	{
		$content = str_replace("₷", "<span class='rubl'>&#x20bd;</span>", $content);
	}

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

function svg($value='')
{
	$path = $_SERVER["DOCUMENT_ROOT"]."/layout/images/svg/".$value.".svg";
	return file_get_contents($path);
}
function body_class()
{
	global $APPLICATION;
	if($APPLICATION->GetPageProperty('body_class')) {
		return $APPLICATION->GetPageProperty('body_class');
	}
}
function page_class()
{
	global $APPLICATION;
	if($APPLICATION->GetPageProperty('page_class')) {
		return $APPLICATION->GetPageProperty('page_class');
	}
}
function page_title()
{
	global $APPLICATION;
	if($APPLICATION->GetPageProperty('page_title')) {
		return $APPLICATION->GetPageProperty('page_title');
	}
	else
		return $APPLICATION->GetTitle();
}
function content_class()
{
	global $APPLICATION;
	if(!$APPLICATION->GetPageProperty('hide_right')) {
		return "col-xs-6 col-lg-8";
	}
	else
		return "col-xs-9 col-lg-10";
}


function IBlockElementsMenu($IBLOCK_ID)
{
	$obCache       = new CPHPCache();
	$cacheLifetime = 86400; 
	$cacheID       = 'IBlockElementsMenu_'.$IBLOCK_ID; 
	$cachePath     = '/'.$cacheID;

	if( $obCache->InitCache($cacheLifetime, $cacheID, $cachePath) )
	{
	   $vars = $obCache->GetVars();
	   return $vars['NAV'];
	}
	elseif( $obCache->StartDataCache()  )
	{
		CModule::IncludeModule("iblock");
		
		$arNav    = array();
		$arSort   = array("NAME" => "DESC");
		$arFilter = array("IBLOCK_ID" => $IBLOCK_ID, 'ACTIVE'=>'Y');
		$rs       = CIBlockElement::GetList($arSort, $arFilter, false, false);
		//$rs->SetUrlTemplates("/catalog/#SECTION_CODE#/#ELEMENT_CODE#.php");

		while ($item = $rs->GetNext()):
			$arNav[] = Array(
				$item['NAME'], 
				$item['DETAIL_PAGE_URL'], 
				Array(), 
				Array(), 
				"" 
			);
		endwhile;

		$obCache->EndDataCache(array('NAV' => $arNav));

		return $arNav;
	}
}
function r_date($date = '') {

	$date = strtotime($date);

	$treplace = array (
		"Январь"   => "января",
		"Февраль"  => "февраля",
		"Март"     => "марта",
		"Апрель"   => "апреля",
		"Май"      => "мая",
		"Июнь"     => "июня",
		"Июль"     => "июля",
		"Август"   => "августа",
		"Сентябрь" => "сентября",
		"Октябрь"  => "октября",
		"Ноябрь"   => "ноября",
		"Декабрь"  => "декабря",
		"January"   => "января",
		"February"  => "февраля",
		"March"     => "марта",
		"April"   => "апреля",
		"May"      => "мая",
		"June"     => "июня",
		"July"     => "июля",
		"August"   => "августа",
		"September" => "сентября",
		"October"  => "октября",
		"November"   => "ноября",
		"December"  => "декабря",
		"*"        => "",
		"th"       => "",
		"st"       => "",
		"nd"       => "",
		"rd"       => ""
	);
   	return strtr(date('d F Y', $date), $treplace);
}
class CatalogStore
{
   function GetIBlockPropertyDescription()
   {
		return array(
			"PROPERTY_TYPE"        =>"S",
			"USER_TYPE"            =>"CatalogStore",
			"DESCRIPTION"          =>"Склад",
			"GetPropertyFieldHtml" =>array("CatalogStore", "GetPropertyFieldHtml"), 
		);
   }
   function GetPropertyFieldHtml($arProperty, $arUserField, $arHtmlControl)
   {
   		static $str;
        $str = '<select name="'.$arHtmlControl["VALUE"].'">';
        $str .= "<option value=''>Выберите склад</option>";
        $raw = CCatalogStore::GetList(array('ID'=>'ASC'), array('ACTIVE' => 'Y'));
        while ($item = $raw->Fetch())
            $str .= "<option value='".$item['ID']."' ".($item['ID']==$arUserField['VALUE']?"selected":"").">".$item['TITLE']."</option>";
        
        $str .= "</select>";
        return $str;
   }
}
AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("CatalogStore", "GetIBlockPropertyDescription"));


function getHighloadBlocks()
{
	$obCache   = new CPHPCache();
	$cacheLife = 86400; 
	$cacheID   = 'getHighloadBlocks'; 
	$cachePath = '/'.$cacheID;

	if( $obCache->InitCache($cacheLife, $cacheID, $cachePath) ):

		$vars = $obCache->GetVars();
		$data = $vars['data'];
	
	elseif( $obCache->StartDataCache() ):

		$data    = array();
		$dbHblock = HL\HighloadBlockTable::getList();
	    while ($ib = $dbHblock->Fetch())
	    	$data[$ib['TABLE_NAME']] = (int)$ib['ID'];
		
		$obCache->EndDataCache(array('data' => $data));
	endif;
	return $data;
}

function getFilterProperties()
{
	$obCache   = new CPHPCache();
	$cacheLife = 86400; 
	$cacheID   = 'getFilterProperties'; 
	$cachePath = '/'.$cacheID;
	
	if( $obCache->InitCache($cacheLife, $cacheID, $cachePath) ):

		$vars = $obCache->GetVars();
		$data = $vars['data'];
	
	elseif( $obCache->StartDataCache() ):

		CModule::IncludeModule("iblock");
		$data = array();
		$res = CIBlockProperty::GetList(Array("sort"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>1));
		
		while($s = $res->Fetch())
    		if( preg_match("/SECTION_(.*)/", $s['CODE']) )
    			$data[] = $s['ID'];
		$obCache->EndDataCache(array('data' => $data));
	
	endif;
	return $data;
}


function getHighloadElements($name, $key, $value)
{
	$iblocks   = getHighloadBlocks();
	$id        = $iblocks[$name];
	$obCache   = new CPHPCache();
	$cacheLife = 86400; 
	$cacheID   = 'getHighloadElements_site_'.$key.$value.$id;
	$cachePath = '/'.$cacheID;

	if( $obCache->InitCache($cacheLife, $cacheID, $cachePath) ):

		$vars = $obCache->GetVars();
		$data = $vars['data'];

	elseif( $obCache->StartDataCache() ):
		
		$hlblock = HL\HighloadBlockTable::getById($id)->fetch();
		$entity  = HL\HighloadBlockTable::compileEntity($hlblock);
		$class   = $entity->getDataClass();

		$rsData = $class::getList(array(
			"select" => array("*"),
			"order"  => array("ID" => "ASC")
		));

		$data = array();

		while($arData = $rsData->Fetch())
			$data[$arData[$key]] = $arData[$value];
		
		$obCache->EndDataCache(array('data' => $data));
		
	endif;
	return $data;
}

function getFilterStringValues($id, $section, $values)
{
	$current = COption::GetOptionString("main","getFilterStringValues_".$id."_".$value);
	if(count($values)>0):
		$string = "";
		foreach ($values as $val)
			$string .= $val['CONTROL_ID']."=Y&";
		if(md5($current) != md5($string)):
			COption::SetOptionString("main","getFilterStringValues_".$id."_".$value,$string);
		endif;
	elseif(strlen($current)>0):
		return $current;
	endif;
}
?>
