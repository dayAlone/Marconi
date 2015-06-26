<?

ini_set('xdebug.var_display_max_depth', 5);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);
define("VIP", 16);
define("BX_COMPOSITE_DEBUG", false);
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");


AddEventHandler("main", "OnOrderNewSendEmail", "OnBeforeMailSendHandler");
AddEventHandler("main", "OnBeforeEventSend", "OnBeforeMailSendHandler");

function findCityByLocation($ID)
{
	CModule::IncludeModule("iblock");
	$filter = Array('IBLOCK_ID' => 6, 'ACTIVE'=>'Y', 'UF_LOCATION'=>$ID);
	$raw = CIBlockSection::GetList(Array('NAME'=>'ASC'), $filter, false, array('ID', 'NAME', 'UF_PHONE', 'UF_CLOSED', 'UF_EMAIL'));
	$item = $raw->Fetch();
	return $item;
}
function isUserAccept($groups) {
	global $USER;
	if(!isset($groups))
		$groups = array(1,9,5);
	foreach ($groups as $value) {
		if(in_array($value, $USER->GetUserGroupArray()))
			return true;
	}
	return false;
}
function getOrderProps($ID) {
	CModule::IncludeModule("sale");
	CModule::IncludeModule("iblock");
	$db_vals = CSaleOrderPropsValue::GetList(
	    array("ORDER_PROPS_ID" => "ASC"),
	    array(
	            "ORDER_ID" => $ID
	        )
	);
	$orderProps = array();
	while ($prop = $db_vals->Fetch()) {
		switch ($prop['CODE']) {
			case 'address':
				$val = CSaleLocation::GetByID($prop['VALUE']);
				if($val) {
					$orderProps[$prop['CODE']] = $val['CITY_NAME_ORIG'].", ".$val['REGION_NAME_ORIG'].", ".$val['COUNTRY_NAME_ORIG'];
					if($item = findCityByLocation($prop['VALUE'])) {
						$orderProps['EMAIL'] = $item['UF_EMAIL'];
					}
				}
				break;
			default:
				$orderProps[$prop['CODE']] = $prop['VALUE'];
				break;
		}
	}
	return $orderProps;
}

function getOrderDelivery($ID, $props) {
	CModule::IncludeModule("sale");
	CModule::IncludeModule("catalog");
	$order = CSaleOrder::GetByID($ID);
	$delivery = CSaleDelivery::GetByID($order['DELIVERY_ID']);
	if(!$delivery) {
		$order['DELIVERY_ID'] = preg_split("/:/", $order['DELIVERY_ID']);
		$delivery = CSaleDeliveryHandler::GetBySID($order['DELIVERY_ID'][0])->Fetch();
	}
	if(isset($order['STORE_ID'])):
		$dbList = CCatalogStore::GetList(
			array("SORT" => "DESC", "ID" => "DESC"),
			array("ACTIVE" => "Y", "ID" => $order["STORE_ID"]),
			false,
			false,
			array("ID", "TITLE", "ADDRESS", "DESCRIPTION", "IMAGE_ID", "PHONE", "SCHEDULE", "LOCATION_ID", "GPS_N", "GPS_S")
		);
		if ($arList = $dbList->Fetch()):
			$delivery['ADDRESS'] = $arList["TITLE"];
		endif;
	endif;
	$str = "<strong>Способ доставки</strong>: ".$delivery['NAME'];
	switch ($delivery['ID']) {
		case 2:
			if(isset($delivery['ADDRESS'])):
				$str .= " (".$delivery['ADDRESS'].")";
			elseif($props['pickup']):
				$str .= " <strong>Адрес</strong>: ".trim(preg_replace('/\s+/', ' ', $props['pickup']));
				endif;
			break;
		default:
			$subStr = '';
			$desc = array('street'=>'ул.', 'house'=>'дом', 'corpus'=>'корпус', 'building'=>'стр.', 'flat'=>'кв./оф.', 'stage'=>'этаж');
			foreach (array('street', 'house', 'corpus', 'building', 'flat', 'stage') as $value) {
				if(strlen($props[$value]) > 0) $subStr .=  (strlen($subStr)>0?", ":"") .$desc[$value] ." " . $props[$value];
			}
			if(strlen($subStr) > 0) $str .= " <strong>Адрес</strong>: ".$subStr;
			
			$subStr = '';
			foreach (array('date', 'time') as $value) {
				if(strlen($props[$value]) > 0) $subStr .=  (strlen($subStr)>0?", ":""). $props[$value];
			}
			if(strlen($subStr) > 0) $str .= " <strong>Пожелания к доставке</strong>: ".$subStr;
			break;
	}
	return $str;
}
function OnBeforeMailSendHandler(&$arFields) {
	global $USER;
	CModule::IncludeModule("sale");
	CModule::IncludeModule("iblock");
	$dbBasketItems = CSaleBasket::GetList(array("NAME" => "ASC","ID" => "ASC"),array("ORDER_ID" => $arFields['ORDER_ID']), false, false);
	$orderProps    = getOrderProps($arFields['ORDER_ID']);
	$delivery      = getOrderDelivery($arFields['ORDER_ID'], $orderProps);
	$arItems       = array();
	$rsUser        = CUser::GetByID($USER->GetID());
	$arUser        = $rsUser->Fetch();
	$str = '<table width="100%" cellpadding="10" cellspacing="0" style="text-align:center;font-size:14px;border-collapse:collapse;border:1px solid #c2c4c6;"><thead>
		<tr style="font-size:12px;">
			<th></th>
			<th style="text-align:left">Название</th>
			<th>Артикул</th>
			<th>Цена</th>
			<th>Количество</th>
			<th>Сумма</th>
		</tr>
		</thead>
		<tbody>';
	
	while ($arItem = $dbBasketItems->Fetch()) {
		$res = CIBlockElement::GetByID($arItem['PRODUCT_ID']);
		if($ar_res = $res->GetNextElement()){

			$fields = $ar_res->GetFields(); 
			$small = CFile::ResizeImageGet(CFile::GetFileArray($fields['PREVIEW_PICTURE']), Array("width" => 150, "height" => 150), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array("name" => "sharpen", "precision" => 15), false, 75);
			$arProps = $ar_res->GetProperties();
			$arItems[] = array_merge($arItem, $fields, $arProps);
		}
		$str .= '<tr>
				<td style="border:1px solid #c2c4c6;border-collapse:collapse;">
					'.($small?'<img src="http://'.$_SERVER['SERVER_NAME'].'/'.$small['src'].'" width="40" alt="">':'').'
				</td>
				<td style="text-align:left;border:1px solid #c2c4c6;border-collapse:collapse;">'.$arItem['NAME'].'</td>
				<td style="border:1px solid #c2c4c6;border-collapse:collapse;">'.$arProps['ARTNUMBER']['VALUE'].'</td>
				<td style="border:1px solid #c2c4c6;border-collapse:collapse;">
					<nobr>'.number_format($arItem['PRICE'], 0, '.', ' ').' руб.</nobr>
					'.(intval($arItem['DISCOUNT_PRICE'])>0?"<br><nobr><small><strike>".number_format($arItem['PRICE']+$arItem['DISCOUNT_PRICE'], 0, '.', ' ')." руб.</strike></small></nobr>":"").'
				</td>
				<td style="border:1px solid #c2c4c6;border-collapse:collapse;">'.intval($arItem['QUANTITY']).'</td>
				<td style="border:1px solid #c2c4c6;border-collapse:collapse;"><nobr>'.number_format($arItem['PRICE']*intval($arItem['QUANTITY']), 0, '.', ' ').' руб.</nobr></td></tr>';
	}
	$orderProps['NAME'] = $USER->GetFullName();
	if(strlen($orderProps['NAME']) == 0)
		$orderProps['NAME'] = ($orderProps['NAME']?$orderProps['NAME']:$orderProps['FIRST_NAME'])." ".$orderProps['LAST_NAME'];
	
	if(strlen($orderProps['email']) == 0)
		$orderProps['email'] = $USER->GetLogin();
	if(strlen($orderProps['phone']) == 0):
		$orderProps['phone'] = (strlen($arUser['WORK_PHONE'])>0?$arUser['WORK_PHONE']:$arUser['PERSONAL_PHONE']);
	endif;
	$str .= '</tbody>
		<tfooter>
			<td colspan="2" style="font-size:12px;text-align:left;"><strong>Заказчик</strong>: '.$orderProps['NAME'].'
			'.(strlen($delivery)>0?"<br><br>".$delivery:"").'
			<td colspan="4" style="text-align: right;font-size:12px"><strong>Телефон</strong>: '.$orderProps['phone'].', <br><strong>Эл. почта</strong>: '.$orderProps['email'].'</td>
		</tfooter>
	</table>';
	$arFields['ORDER_LIST'] = $str;
	
	AddMessage2Log(var_export($arUser, true), '1');
	//die();
	$orderData = array(
		'ID'      => $arResult['ORDER_ID'],
		'DATE'    => date('d.m.Y'),
		'TIME'    => date('H:i:s'),
		'NAME'    => $orderProps['NAME'],
		/*'COMPANY' => ,
		'LOGIN'   => ,
		'PHONE'   => ,
		'EMAIL'   => ,
		'ADDRESS' => ,
		'COUNT'   => ,
		'TOTAL'   => ,*/
		'LIST'    => array()
	);
	foreach ($arItems as $key => $item) {
		$orderData['LIST'][] = array(
			'key'       => $key+1,
			'artnumber' => (strlen($item['ARTNUMBER']['VALUE']) > 0 ? str_replace($item['NOTE_SHORT']['VALUE'], '', $item['NAME']) : $item['NAME']),
			'quantity'  => intval($item['QUANTITY']),
			'price'     => $item['PRICE'],
			'total'     => $item['PRICE']*intval($item['QUANTITY'])
		);
	}

	if(SITE_ID == 's2'):
		$arFields['SALE_EMAIL'] = "zakaz@italbags.ru";
		$arFields['BCC'] = "zakaz@italbags.ru";
		$arFields['SITE_NAME'] = 'Новый стиль студио';
	elseif($orderProps['EMAIL']):
		$arFields['BCC'] .= ", ".$orderProps['EMAIL'];
	endif;

	return $arFields;
}

AddEventHandler("main", "OnAfterUserAdd", "OnAfterUsedAddHandler");
AddEventHandler("main", "OnBeforeUserRegister", "OnBeforeUserAddHandler");
AddEventHandler("main", "OnBeforeUserAdd", "OnBeforeUserAddHandler");

AddEventHandler("main", "OnBeforeUserUpdate", "OnBeforeUserUpdateHandler");

function OnBeforeUserUpdateHandler(&$arFields)
{
	global $USER;
	if(!$USER->IsAdmin())
	{
		if(!isset($arFields["EMAIL"])) $arFields["EMAIL"] = $arFields["LOGIN"];
		else $arFields["LOGIN"] = $arFields["EMAIL"];
	}
	
	if($_SERVER['SCRIPT_NAME'] == '/profile/index.php' && SITE_ID == 's2' && !isset($_REQUEST['NEW_PASSWORD"'])):
		CModule::IncludeModule("subscribe");
		$aSubscr = CSubscription::GetUserSubscription();
		if($_REQUEST['maillist'] == 1 && $aSubscr['ID'] == 0):
			$data = Array(
				"USER_ID"      => $arFields['ID'],
				"FORMAT"       => "html",
				"EMAIL"        => $arFields["EMAIL"],
				"ACTIVE"       => "Y",
				"SEND_CONFIRM" => "N",
				"CONFIRMED"    => "Y",
				"RUB_ID"       => 1
			);
			$subscr = new CSubscription;
			$subscr->Add($data);
		elseif(!isset($_REQUEST['maillist']) && $aSubscr['ID'] > 0):
			CSubscription::Delete($aSubscr['ID']);
		endif;
	endif;
}
function OnBeforeUserAddHandler(&$arFields)
{
	if(!$GLOBALS['USER']->IsAdmin()):
		if(SITE_ID == 's1'):
			if(isset($_REQUEST['ORDER_PROP_1'])):
				$arFields["NAME"] = $_REQUEST['ORDER_PROP_1'];
				$arFields["LAST_NAME"] = "";
			endif;
			if(isset($_REQUEST['ORDER_PROP_14'])):
				$arFields["LAST_NAME"] = $_REQUEST['ORDER_PROP_14'];
			endif;
			if(isset($_REQUEST['ORDER_PROP_3'])):
				$arFields["PERSONAL_PHONE"] = $_REQUEST['ORDER_PROP_3'];
			endif;
			
		else:
			if(isset($_REQUEST['ORDER_PROP_16'])):
				$arFields["NAME"] = $_REQUEST['ORDER_PROP_16'];
				$arFields["LAST_NAME"] = "";
			endif;
			if(isset($_REQUEST['ORDER_PROP_18'])):
				$arFields["LAST_NAME"] = $_REQUEST['ORDER_PROP_18'];
			endif;
			if(isset($arFields['PERSONAL_BIRTHDAY']))
				$arFields['PERSONAL_BIRTHDAY'] = date('d.m.Y', strtotime($arFields['PERSONAL_BIRTHDAY']));
			$arFields["ACTIVE"] = "N";
		endif;
	endif;
	if(!isset($arFields["EMAIL"])) $arFields["EMAIL"] = $arFields["LOGIN"];
	else $arFields["LOGIN"] = $arFields["EMAIL"];
}
function checkUserCoupon(&$arFields)
{
	if(intval($arFields['ID'])>0):
		$coupon   = $_SESSION['COUPON'];
		if(strlen($coupon)>0):
			$ID       = $arFields['ID'];
			$arGroups = CUser::GetUserGroup($ID);
			
			if(!in_array(VIP,$arGroups)):
				$arGroups[] = VIP;
				CUser::SetUserGroup($ID, $arGroups);
				$u = new CUser;
				$u->Update($ID, array('UF_VIP' => $coupon));
			endif;
		endif;
	endif;
}
function OnAfterUsedAddHandler(&$arFields)
{
	if(SITE_ID == 's2'):
		if($_REQUEST['maillist'] == 1):
			CModule::IncludeModule("subscribe");
			$data = Array(
				"USER_ID"      => $arFields['ID'],
				"FORMAT"       => "html",
				"EMAIL"        => $arFields["EMAIL"],
				"ACTIVE"       => "Y",
				"SEND_CONFIRM" => "N",
				"CONFIRMED"    => "Y",
				"RUB_ID"       => 1
			);
			$subscr = new CSubscription;
			$subscr->Add($data);
		endif;
		$rsUsers = CUser::GetList(
			($by = 'name'),
			($order = 'asc'),
			array(
				'GROUPS_ID' => array(15)
			)
		);
		$data = array(
			"USER_ID"   => $arFields['ID'],
			"LOGIN"     => $arFields['LOGIN'],
			"NAME"      => $arFields['NAME'],
			"LAST_NAME" => $arFields['LAST_NAME']
		);
		while($ar_user = $rsUsers->GetNext()) {
			$data = array_merge($data, array('EMAIL_TO'=>$ar_user['EMAIL']));
			CEvent::Send("ITALBAGS_NEW_USER", SITE_ID, $data, "N", 68);
		}
	else:
		checkUserCoupon($arFields);
	endif;
}

AddEventHandler('sale', 'OnOrderAdd', Array('CSaleGuestHandlers', 'OnOrderUpdateHandler'));
AddEventHandler('sale', 'OnSaleComponentOrderOneStepProcess', Array('CSaleGuestHandlers', 'OnSaleComponentOrderOneStepProcessHandler'));
AddEventHandler('sale', 'OnSaleComponentOrderOneStepComplete', Array('CSaleGuestHandlers', 'OnSaleComponentOrderOneStepCompleteHandler'));
AddEventHandler('sale', 'OnSaleComponentOrderOneStepFinal', Array('CSaleGuestHandlers', 'OnSaleComponentOrderOneStepFinalHandler'));

class CSaleGuestHandlers {

	private static $bGuestOrder = false;
	public static function OnSaleComponentOrderOneStepProcessHandler($arResult, $arUserResult, $arParams) {
		if ($_REQUEST['delete_user']=="Y" && empty($arResult['ERROR']) && $arUserResult['CONFIRM_ORDER']=='Y' && !$GLOBALS['USER']->IsAuthorized()) {
			if ($arUser = CUser::GetList($by='id', $order='asc', array('LOGIN' => 'system'))->Fetch()) {
				if (!in_array(1, CUser::GetUserGroup($arUser['ID']))) {
					$GLOBALS['USER']->Authorize($arUser['ID']);
					self::$bGuestOrder = true;
				}
			}
		}
	}
	
	public static function OnSaleComponentOrderOneStepCompleteHandler($ID, $arOrder, $arParams) {
		if (self::$bGuestOrder && $GLOBALS['USER']->IsAuthorized() && $_REQUEST['delete_user']=="Y") {
			$_SESSION['SAVED_UID'] = $GLOBALS['USER']->GetID();
			$GLOBALS['USER']->Logout();
		}
	}
	
	public static function OnSaleComponentOrderOneStepFinalHandler($ID, $arOrder, $arParams) {
		if ((!$GLOBALS['USER']->IsAuthorized() && $_SESSION['SAVED_UID']!=$arOrder['USER_ID']) ||
			($GLOBALS['USER']->IsAuthorized() && $GLOBALS['USER']->GetID()!=$arOrder['USER_ID'])
		) {
			$arOrder = array();
		}
	}
	
}

AddEventHandler("main", "OnEndBufferContent", "OnEndBufferContentHandler", 101);
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
	global $APPLICATION;

	if(strstr($APPLICATION->GetCurDir(), "/eng/"))
		$str = date('d F Y', $date);
	else
   		$str = strtr(date('d F Y', $date), $treplace);
   	return $str;
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


class CSectionLocation
{
   function GetUserTypeDescription()
   {
   		return array(
			"USER_TYPE_ID" => "section_location",
			"CLASS_NAME"   => "CSectionLocation",
			"DESCRIPTION"  => "Привязка местоположения",
			"BASE_TYPE"    => "int",
        );
   }
   function GetDBColumnType($arUserField) {
      switch(strtolower($GLOBALS['DB']->type)) {
         case 'mysql':
            return 'int(18)';
         break;
         case 'oracle':
            return 'number(18)';
         break;
         case 'mssql':
            return "int";
         break;
      }
   }
   function GetEditFormHTML($arUserField, $arHtmlControl) {
   		global $APPLICATION;
   		ob_start();
	   		$APPLICATION->IncludeComponent(
				"bitrix:sale.location.selector.search", 
				".default", 
				array(
					"ID"                     => $arHtmlControl['VALUE'],
					"CODE"                   => "",
					"INPUT_NAME"             => $arHtmlControl['NAME'],
					"PROVIDE_LINK_BY"        => "id",
					"SEARCH_BY_PRIMARY"      => "Y",
					"EXCLUDE_SUBTREE"        => "",
					"FILTER_BY_SITE"         => "Y",
					"SHOW_DEFAULT_LOCATIONS" => "Y",
					"CACHE_TYPE"             => "A",
					"CACHE_TIME"             => "36000000"
				),
				false
			);
			$str = ob_get_contents();
		ob_end_clean();
		return $str;
   }

}
AddEventHandler("main", "OnUserTypeBuildList", array("CSectionLocation", "GetUserTypeDescription"), 5000);


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
		CModule::IncludeModule("highloadblock");
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
		CModule::IncludeModule("highloadblock");
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

/*use Bitrix\Main;
use Bitrix\Main\Loader;*/

function findCity($name = false, $setCookie = true)
{
	global $APPLICATION, $CITY;
	
	if($name) {
		CModule::IncludeModule("iblock");
		$filter = Array('IBLOCK_ID' => 6, 'ACTIVE'=>'Y', 'NAME'=>$name);
		$raw = CIBlockSection::GetList(Array('NAME'=>'ASC'), $filter, false, array('NAME', 'UF_PHONE', 'UF_CLOSED'));
		$item = $raw->Fetch();
		if($item && isset($item['UF_PHONE'])) { $phone = $item['UF_PHONE']; }
	}
	else
		$name = "Москва";
	
	if(!isset($phone))
		$phone = COption::GetOptionString("grain.customsettings","phone");
	
	$value = array('NAME'=>$name, 'PHONE'=> $phone);

	if(isset($item) && $item['UF_CLOSED'])
		$value['CLOSED'] = "Y";

	$CITY = $value;
	$APPLICATION->set_cookie("CITY", json_encode($value, JSON_UNESCAPED_UNICODE), time()+60*60*24);

}
if(!strstr($_SERVER['SCRIPT_NAME'], 'bitrix/admin') && !defined("NO_IP")):
	global $CITY;
	$CITY = json_decode($APPLICATION->get_cookie("CITY"), true);
	if(strlen($_COOKIE['city']) > 1) { findCity($_COOKIE['city']); }
	if(!is_array($CITY) && CModule::IncludeModule("altasib.geoip")) {
		$arData = ALX_GeoIP::GetAddr();
		if(isset($_SESSION['GEOIP']['city']))
			findCity($_SESSION['GEOIP']['city']);
		else
			findCity();
	}
	
endif;


?>
