<?php

include_once("phayes-geoPHP-6855624/geoPHP.inc");

$naturalLandscapes = geoPHP::load(file_get_contents('AONBs.geojson'),'json');

$points = array(
    'Newlands Corner' => 'POINT(-0.5064713 51.2329694)',
    'Buckingham Palace' => 'POINT(-0.1419329173761422 51.50133722966322)',
);

print_r($naturalLandscapes);

foreach ($points as $name => $wkt) {
    
    $point = geoPHP::load($wkt, 'wkt');

    if ($point->intersects($naturalLandscapes)) {
        print("$name is in an AONB". PHP_EOL);
    } else {
        print("$name is not in an AONB". PHP_EOL);
    }
}
