<?
	require_once ($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include.php");
	CModule::IncludeModule("catalog");
	CModule::IncludeModule("sale");
	global $APPLICATION, $USER;
	
	$action = $_GET['a'];
	$result = 'fail';
	switch ($action):
		case 'add':
				$props = array();
				$count = 1;
				if($_REQUEST['size'])
	    			$props['size'] = array("NAME"=>'Размер', "CODE"=>'SIZE', "VALUE"=>$_REQUEST['size']);
	    		if($_REQUEST['artnumber'])
	    			$props['artnumber'] = array("NAME"=>'Артикул', "CODE"=>'ARTNUMBER', "VALUE"=>$_REQUEST['artnumber']);
				if(intval($_REQUEST['count']) > 0)
	    			$count = intval($_REQUEST['count']);

				$id = intval($_GET['id']);
				$result = Add2BasketByProductID($id, $count, false, $props);
				if(intval($result)>0)
					$result = 'success';
			break;
		case 'check':
			if(CCatalogDiscountCoupon::IsExistCoupon($_REQUEST['code'])):
				CCatalogDiscountCoupon::SetCoupon($_REQUEST['code']);
				if($USER->IsAuthorized()):
					$ID = $USER->GetID();
					$arGroups = CUser::GetUserGroup($ID);
					if(!in_array(VIP,$arGroups)):
						$arGroups[] = VIP;
						CUser::SetUserGroup($ID, $arGroups);
						$u = new CUser;
						$u->Update($ID, array('UF_VIP' => $_REQUEST['code']));
					endif;
				else:
					$_SESSION['COUPON'] = $_REQUEST['code'];
				endif;
				$result = 'success';
				endif;
			break;
		case 'update':
				$id    = intval($_GET['id']);
				$count = intval($_GET['count']);
				if( $id > 0 && $count > 0):
			        $arFields = array("QUANTITY" => $count);
			        if(CSaleBasket::Update($id, $arFields))
			            $result = 'success';
			    endif;
			break;
		case 'delete':
				$id    = intval($_GET['id']);
				if( $id > 0):
			        if(CSaleBasket::Delete($id))
			            $result = 'success';
			    endif;
			break;
	endswitch;
	if($action != 'add' && $result == 'success'):
		ob_start();
			$basket = $APPLICATION->IncludeComponent("bitrix:sale.basket.basket","json", Array(
					"OFFERS_PROPS"                  => array("COLOR_REF"),
					"PATH_TO_ORDER"                 => "/personal/order.php",
					"HIDE_COUPON"                   => "N",
					"COLUMNS_LIST"                  => Array("NAME", "PROPERTY_ARTNUMBER", "PROPERTY_BRAND", "PRICE", "DISCOUNT", "QUANTITY", "DELETE"),
					"PRICE_VAT_SHOW_VALUE"          => "Y",
					"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
					"USE_PREPAYMENT"                => "N",
					"QUANTITY_FLOAT"                => "N",
					"SET_TITLE"                     => "N",
					"ACTION_VARIABLE"               => "action"
			    )
			);
			$data = ob_get_contents();
		ob_end_clean();
		echo $data;
	else:
		echo $result;
		endif;

?>