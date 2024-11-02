<?php

include_once("phayes-geoPHP-6855624/geoPHP.inc");

$json = file_get_contents('AONBs.geojson');

$parsed = json_decode($json);
$naturalLandscapes = array();
$bboxes = false;

foreach ($parsed->features as $feature) {
    $name = $feature->properties->NAME;
    print("Loading $name". PHP_EOL);
    $geo = geoPHP::load(json_encode($feature),'json');
    $naturalLandscapes[$name] = $geo;

    if ($bboxes === false) {
        $bboxes = $geo->envelope();
    } else {
        $bboxes = $bboxes->union($geo);
    }
}

$points = array(
    'Newlands Corner' => 'POINT(-0.5064713 51.2329694)',
    'Buckingham Palace' => 'POINT(-0.1419329173761422 51.50133722966322)',
);

foreach ($points as $pointName => $wkt) {

    print("Looking up $pointName". PHP_EOL);
    $point = geoPHP::load($wkt, 'wkt');

    if(! $point->intersects($bboxes) ) {
        print("$pointName is not in an AONB". PHP_EOL);
        continue;
    }

    foreach($naturalLandscapes as $naturalLandscapeName => $naturalLandscape) {
        if ($point->intersects($naturalLandscape)) {
            print("$pointName is in $naturalLandscapeName". PHP_EOL);
            break;
        }
    }
}
