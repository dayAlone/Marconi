<?
$arResult['SIZES'] = array();
$raw = CUserFieldEnum::GetList(array(), array(
    "USER_FIELD_ID" => 37,
));
while($el = $raw->GetNext())
    $arResult['SIZES'][$el['ID']] = preg_split("/x/", $el['XML_ID']);
?>