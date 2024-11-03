<?php

include_once("phayes-geoPHP-6855624/geoPHP.inc");

$json = file_get_contents('AONBs.geojson');
$geo = geoPHP::load($json,'json');
$bboxes = array();

foreach($geo->getComponents() as $component) {
    $a = $component->envelope()->asArray();

    $points = array();
    foreach($a[0] as list($x,$y)) {
        $points[] = "$x $y";
    }
    $bboxes[] = sprintf("((%s))", implode(", ", $points));
}

file_put_contents("aonb-bboxes.wkt",
    sprintf("SRID=4326;MULTIPOLYGON (%s)", implode(", ", $bboxes))
);

