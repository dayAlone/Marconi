<?
    function years_sort($a, $b)
    {
        return ($a['NAME'] >= $b['NAME']) ? -1 : 1;
    }
    usort($arResult['SECTIONS'], "years_sort");
?>
