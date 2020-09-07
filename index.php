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
//        var_dump($a);
    });
    array_shift($csv); # remove column header

    $csv = json_encode($csv);

    echo '<script>
        const posts ='.$csv.';</script>';



?>

</body>
</html>
