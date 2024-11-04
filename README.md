# National Landscape (AONB) boundary matching

## Libraries
To install GeoJSON use composer and `composer require phayes/geophp`

## Input file
The input is taken as a GeoJSON file from [DEFRA](https://naturalengland-defra.opendata.arcgis.com/datasets/6f2ad07d91304ad79cdecd52489d5046_0/explore).

To get at the different area labels we need to parse the JSON then iterate over each feature. It's pretty slow because the full 70MB JSON file needs to be JSON decoded in memory. If you import that whole GeoJSON directly using the GeoPHP library then the name labels are lost so you can't identify specific National Landscapes.

## MySQL GeoSpatial Lookups
To get everything working:
 - Download the DEFRA GeoJSON file of AONBs mentioned above
 - Execute the contents of `create-spatial-tables.sql` to create the tables and some example geometries
 - Run `get-mysql-inserts.php` to turn the GeoJSON file into a set of MySQL inserts and pipe these inserts to MySQL
 - You now have all of the National Landscapes and some example data loaded (points and lines)
 - Run the test query in `query-national-landscapes.sql` to get a list of all geometries that are in a National Landscape

## PHP GeoSpatial Lookups
I had a play with doing this stuff in PHP instead of MySQL. It's really slow though because all 70MB of the National Landscapes have to be read into memory each time including a full `json_decode()` in PHP.

I managed to speed this up by creating an "index" of bounding boxes for each polygon. It sped things up a lot but it didn't make sense to continue with this approach because MySQL or other system with proper spatial indexing will be faster and less work.

## Bugs
There's an edge-case that I can't find a solution for. If a National Landscape area has a hole in the middle of it like the middle of a ring doughnut then there will be a false match of points that are in the hole. The **Sevenoaks Nature Reserve** point in the example dataset is a good example.

This kind of hole or void seems to be called a "perforated polygon".

## TODO
 - [x] Use composer to bring in dependencies
 - [x] Document details of GeoJSON input file
 - [x] Match specific AONBs
 - [x] Use bounding boxes to simplify lookups
 - [x] Speed up matching using MySQL or indexes
