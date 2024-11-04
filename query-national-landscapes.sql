
SELECT lg.name, nl.name FROM lookup_geometries lg, natural_landscapes nl WHERE ST_Intersects(lg.geometry, nl.boundary);
