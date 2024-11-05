
SELECT lg.name reference, nl.name national_landscape FROM lookup_geometries lg, national_landscapes nl WHERE ST_Intersects(lg.geometry, nl.boundary);
