<?php

require __DIR__ . '/vendor/autoload.php';

$json = file_get_contents('Areas_of_Outstanding_Natural_Beauty_England_6441771052448718089.geojson');

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

