<?php


require __DIR__ . '/vendor/autoload.php';

$bboxes = geoPHP::load(file_get_contents("aonb-bboxes.wkt", "wkt"));

$loadPolygons = true;

if ($loadPolygons) {
    $json = file_get_contents('Areas_of_Outstanding_Natural_Beauty_England_6441771052448718089.geojson');

    $parsed = json_decode($json);
    $nationalLandscapes = array();

    foreach ($parsed->features as $feature) {
        $name = $feature->properties->NAME;
        print("Loading $name". PHP_EOL);
        $geo = geoPHP::load(json_encode($feature),'json');
        $nationalLandscapes[$name] = $geo;
    }
}

$points = array();
$points['Newlands Corner'] = 'POINT(-0.5064713 51.2329694)'; // definitely in an AONB
$points['Buckingham Palace'] = 'POINT(-0.1419329173761422 51.50133722966322)'; // definitely not
$points['Sevenoaks Wildlife Reserve'] = 'POINT(0.18080 51.29719)'; // an edge case - an AONB surrounds it but it's exempted. !!! FIXME doesn't work
$points['Bramley'] = 'POINT(-0.55644 51.19387)'; // an edge case - it's within the bbox but not in the polygon

foreach ($points as $pointName => $wkt) {

    $point = geoPHP::load($wkt, 'wkt');

    if(! $point->intersects($bboxes) ) {
        print("bbox: $pointName is not in an AONB". PHP_EOL);
        continue;
    }
    print("bbox: $pointName might be in an AONB". PHP_EOL);
    
    if ($loadPolygons) {
        foreach($nationalLandscapes as $nationalLandscapeName => $nationalLandscape) {
            if ($point->intersects($nationalLandscape)) {
                print("$pointName is definitely in $nationalLandscapeName". PHP_EOL);
                break;
            }
        }
    }
}
