<?php

require __DIR__ . '/vendor/autoload.php';

$json = file_get_contents('Areas_of_Outstanding_Natural_Beauty_England_6441771052448718089.geojson');
$geo = geoPHP::load($json,'json');
$bboxes = array();

foreach($geo->getComponents() as $component) {
    $a = $component->envelope()->asArray();

    $points = array();
    foreach($a[0] as list($x,$y)) {
        $points[] = "$x $y";
    }
    $polygon = sprintf("SRID=4326;POLYGON((%s))", implode(", ", $points));
    $bboxes[] = geoPHP::load($polygon, "wkt");
}

// initialise a combined polygon
$combined = $bboxes[0];

// combine all of the polygons
$i = 0;
foreach($bboxes as $box) {
    $combined = $combined->union($box);
    if (++$i % 100 == 0) {
        printf("Processed %d/%d boxes". PHP_EOL, $i, count($bboxes));
    }
}



file_put_contents("aonb-bboxes.wkt", $combined->out("wkt"));
