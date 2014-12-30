<?
    function years_sort($a, $b)
    {
        return ($a['NAME'] >= $b['NAME']) ? -1 : 1;
    }
    uasort($arResult['SECTIONS'], "years_sort");
?>