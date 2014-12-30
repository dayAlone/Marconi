<?
if(isset($_REQUEST["ajax"]) && $_REQUEST["ajax"] === "y")
	$_CHECK = &$_REQUEST;
elseif(isset($_REQUEST["del_filter"]))
	$_CHECK = array();
elseif(isset($_GET["set_filter"]))
	$_CHECK = &$_GET;
elseif($arParams["SAVE_IN_SESSION"] && isset($_SESSION[$FILTER_NAME][$this->SECTION_ID]))
	$_CHECK = $_SESSION[$FILTER_NAME][$this->SECTION_ID];
else
	$_CHECK = array();

$allCHECKED = array();
foreach($arResult["ITEMS"] as $PID => $arItem)
{
	foreach($arItem["VALUES"] as $key => $ar)
	{
		if(
			isset($_CHECK[$ar["CONTROL_NAME"]])
			|| (
				isset($_CHECK[$ar["CONTROL_NAME_ALT"]])
				&& $_CHECK[$ar["CONTROL_NAME_ALT"]] == $ar["HTML_VALUE_ALT"]
			)
		)
		{
			if($arItem["PROPERTY_TYPE"] == "N" || isset($arItem["PRICE"]))
			{
				$arResult["ITEMS"][$PID]["VALUES"][$key]["HTML_VALUE"] = htmlspecialcharsbx($_CHECK[$ar["CONTROL_NAME"]]);
			}
			elseif($_CHECK[$ar["CONTROL_NAME"]] == $ar["HTML_VALUE"])
			{
				$arResult["ITEMS"][$PID]["VALUES"][$key]["CHECKED"] = true;
				$allCHECKED[$PID][$ar["VALUE"]] = true;
			}
			elseif($_CHECK[$ar["CONTROL_NAME_ALT"]] == $ar["HTML_VALUE_ALT"])
			{
				$arResult["ITEMS"][$PID]["VALUES"][$key]["CHECKED"] = true;
				$allCHECKED[$PID][$ar["VALUE"]] = true;
			}
		}
	}
}

$index = array();
foreach ($arResult["COMBO"] as $id => $combination)
	foreach ($combination as $PID => $value)
		$index[$PID][$value][] = &$arResult["COMBO"][$id];

$totalCheck = false;
foreach ($allCHECKED as $key => $value)
	if(in_array($key, getFilterProperties()))
		$totalCheck = true;

foreach ($arResult["ITEMS"] as $PID => &$arItem)
{
	if ($arItem["PROPERTY_TYPE"] != "N" && !isset($arItem["PRICE"]))
	{
		//All except current one
		$checked = $allCHECKED;
		unset($checked[$PID]);

		foreach ($arItem["VALUES"] as $key => &$arValue)
		{
			$found = false;
			if (isset($index[$PID][$arValue["VALUE"]]))
			{
				//Check if there are any combinations exists
				foreach ($index[$PID][$arValue["VALUE"]] as $id => $combination)
				{
					//Check if combination fits into the filter
					$isOk = true;
					foreach ($checked as $cPID => $values)
					{
						if(!in_array($cPID, getFilterProperties()))
						{
							if (!isset($values[$combination[$cPID]]))
							{
								$isOk = false;
								break;
							}
						}
					}

					if($isOk && $totalCheck)
					{
						$enter = false;
						foreach ($checked as $cPID => $values)
							if (isset($values[$combination[$cPID]]) && in_array($cPID, getFilterProperties()))
								$enter = true;
						if(!$enter)
							$isOk = false;
					}
					if ($isOk)
					{
						$found = true;
						break;
					}
				}
			}
			if (!$found)
				$arValue["DISABLED"] = true;
			else
				unset($arValue["DISABLED"]);
		}
		unset($arValue);
	}
}
unset($arItem);
?>