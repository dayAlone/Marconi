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

$this->facet = new \Bitrix\Iblock\PropertyIndex\Facet($arResult['SECTION']['IBLOCK_ID']);
$this->facet->setPrices($arResult["PRICES"]);
$this->facet->setSectionId($arResult['SECTION']['ID']);

$facetIndex = array();
$except     = getFilterProperties();

foreach($arResult["ITEMS"] as $PID => $arItem):
	foreach($arItem["VALUES"] as $key => $ar):
		if ($arResult["FACET_FILTER"] && isset($ar["FACET_VALUE"]))
			$facetIndex[$PID][$ar["FACET_VALUE"]] = &$arResult["ITEMS"][$PID]["VALUES"][$key];
		if(
			isset($_CHECK[$ar["CONTROL_NAME"]])
			|| (
				isset($_CHECK[$ar["CONTROL_NAME_ALT"]])
				&& $_CHECK[$ar["CONTROL_NAME_ALT"]] == $ar["HTML_VALUE_ALT"]
			)
		):
			if($_CHECK[$ar["CONTROL_NAME"]] == $ar["HTML_VALUE"] && !isset($arItem["PRICE"])):
				if ($arResult["FACET_FILTER"] && !in_array($PID, $except)):
					$this->facet->addDictionaryPropertyFilter($PID, "=", $ar["FACET_VALUE"]);
				endif;
			endif;
		endif;

	endforeach;
endforeach;

$res = $this->facet->query($arResult["FACET_FILTER"]);
while ($row = $res->fetch()):
	$facetId = $row["FACET_ID"];
	
	if (\Bitrix\Iblock\PropertyIndex\Storage::isPropertyId($facetId)):
		$pp = \Bitrix\Iblock\PropertyIndex\Storage::facetIdToPropertyId($facetId);
		if ($arResult["ITEMS"][$pp]["PROPERTY_TYPE"] != "N"):
			if (isset($facetIndex[$pp][$row["VALUE"]])):
					unset($facetIndex[$pp][$row["VALUE"]]["DISABLED"]);
				endif;
		endif;
	endif;
endwhile;
?>