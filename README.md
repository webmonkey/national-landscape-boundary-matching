# National Landscape (AONB) boundary matching

## Libraries
To install GeoJSON use composer and `composer require phayes/geophp`

## Input file
The input is taken as a GeoJSON file from [DEFRA](https://naturalengland-defra.opendata.arcgis.com/datasets/6f2ad07d91304ad79cdecd52489d5046_0/explore).

To get at the different area labels we need to parse the JSON then iterate over each feature. It's pretty slow because the full 70MB JSON file needs to be JSON decoded in memory. If you import that whole GeoJSON directly using the GeoPHP library then the name labels are lost so you can't identify specific National Landscapes.

## TODO
 - [x] Use composer to bring in dependencies
 - [x] Document details of GeoJSON input file
 - [x] Match specific AONBs
 - [x] Use bounding boxes to simplify lookups
 - [x] Speed up matching using MySQL or indexes
