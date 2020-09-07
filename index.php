<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Full Stack Developer practical test</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>
<body>
<?php
    $file = 'places.csv';

    $csv = array_map('str_getcsv', file($file));

    array_walk($csv, function(&$a) use ($csv) {
        $a = array_combine($csv[0], $a);
        $coordinates = explode(',', $a['coordinates']);
        $a['latitude'] = $coordinates['0'];
        $a['longitude'] = $coordinates['1'];
    });
    array_shift($csv);
    echo "<script> const posts =". json_encode($csv). ";</script>";
?>

<script>

            function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
            const R = 6371; // Radius of the earth in km
            const dLat = deg2rad(lat2-lat1); // deg2rad below
            const dLon = deg2rad(lon2-lon1);
            const a =
            Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
            Math.sin(dLon/2) * Math.sin(dLon/2)
            ;
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            const d = R * c; // Distance in km
            return d;
            }

            function deg2rad(deg) {
            return deg * (Math.PI/180);
            }

            function findClosePosts(location, radius, posts) {
            return posts.filter((post) =>
            // find close points within the radius of the location, but exclude the location itself from results
            getDistanceFromLatLonInKm(location.latitude, location.longitude, post.latitude, post.longitude) <= radius && location !== post);
            }

            function findLocationByName(name, posts) {
            return posts.find((post) => post.name === name);
            }

            const hamburg = findLocationByName('Palermo', posts);
            const closePosts = findClosePosts(hamburg, 400, posts);
            console.log(closePosts);
</script>
</body>
</html>
