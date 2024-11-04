<?php

include_once("phayes-geoPHP-6855624/geoPHP.inc");
$bboxes = geoPHP::load(file_get_contents("aonb-bboxes.wkt", "wkt"));


$json = file_get_contents('AONBs.geojson');

$parsed = json_decode($json);
$naturalLandscapes = array();

foreach ($parsed->features as $feature) {
    $name = $feature->properties->NAME;
    print("-- Loading $name". PHP_EOL);

    $geo = geoPHP::load(json_encode($feature),'json');

    printf('INSERT INTO natural_landscapes (name, boundary) VALUES("%s", ST_GeomFromText("%s", 4326));'. PHP_EOL,
        $name,
        $geo->out("wkt")
    );
}


