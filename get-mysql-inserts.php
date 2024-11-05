<?php

require __DIR__ . '/vendor/autoload.php';

$json = file_get_contents('Areas_of_Outstanding_Natural_Beauty_England_6441771052448718089.geojson');

$parsed = json_decode($json);

// each feature is one AONB
foreach ($parsed->features as $feature) {
    $name = $feature->properties->NAME;
    print("-- $name". PHP_EOL);

    // take the decoded JSON for this AONB and then convert it to JSON > GeoPHP Object > WKT
    printf('INSERT INTO national_landscapes (name, boundary) VALUES("%s", ST_GeomFromText("%s", 4326));'. PHP_EOL,
        $name,
        geoPHP::load(json_encode($feature),'json')->out("wkt")
    );
}

