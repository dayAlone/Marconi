<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#^/news/([\w-_]+)/.*#",
		"RULE" => "&ELEMENT_CODE=\$1&\$2",
		"ID" => "",
		"PATH" => "/news/index.php",
	),
	array(
		"CONDITION" => "#^/lookbook/([\w-_]+)/.*#",
		"RULE" => "&ELEMENT_CODE=\$1&\$2",
		"ID" => "",
		"PATH" => "/lookbook/index.php",
	),
);

?>