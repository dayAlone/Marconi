<?
	require_once ($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include.php");
	CModule::IncludeModule("catalog");
	global $APPLICATION;
	
	$action = $_GET['action'];
	
	switch ($action):
		case 'add':
				$props = array();
				if($_REQUEST['size'])
	    			$props['size'] = array("NAME"=>'Размер', "CODE"=>'SIZE', "VALUE"=>$_REQUEST['size']);
				$id = intval($_GET['id']);
				$result = Add2BasketByProductID($id, 1, false, $props);
				if(intval($result)>0)
					echo 'success';

			break;
	endswitch;
?>