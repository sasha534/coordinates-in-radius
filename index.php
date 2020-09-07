<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Full Stack Developer practical test</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/components/dropdowns/">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <script src="../js/custom.js"></script>
    <link rel="stylesheet" href="../css/custom.css">
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
?>
<div class="container">
    <div class="row">
        <form id="my_form">
        <div>Settings</div>
        <div class="col-sm-4">
            <h3>Place:</h3>
            <select class="bootstrap-select">
                <?php
                foreach ($csv as $part){
                    echo "<option value=".$part['name']." name=".$part['name'].">".$part['name']."</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-sm-6">
            <h3>Range (km):</h3>
            <div id="slider">
                <div id="custom-handle" class="ui-slider-handle"></div>
            </div>

        </div>
        </form>
    </div>
    <div class="row">
        <div>Results:</div>
        <div class="col-sm-1">
            <ul class="list-group" id="result">
            </ul>
        </div>
    </div>
</div>
<div id="locality"></div>
<script>
    <?php
    echo "var posts =". json_encode($csv). ";";
    ?>
            function show()
            {
                $.ajax({
                    cache: false,
                    success: function(html){

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

                        var sliderValue = 1;

                        function returnSlideValue(ui) {
                            // console.log(ui.value);
                            return sliderValue = ui.value;
                        }

                        $( function() {
                            var handle = $( "#custom-handle" );
                            $( "#slider" ).slider({
                                max: 300,
                                create: function() {
                                    handle.text( $( this ).slider( "value" ) );
                                },
                                slide: function( event, ui ) {
                                    handle.text( ui.value );
                                    returnSlideValue(ui);
                                }
                            });
                        });


                            $("select.bootstrap-select").change(function () {

                                const name = $(this).children("option:selected").val();
                                const distance = sliderValue;
                                const hamburg = findLocationByName(name, posts);
                                const closePosts = findClosePosts(hamburg, distance, posts);

                                $( "#result" ).html($.each(closePosts , function( index, value ){ this.value}));
                                // $( "#result" ).html('11111111111111111111111111111111');
                                console.log(closePosts);

                            });
                    }
                });
            }

            $(document).ready(function(){
                show();
                setInterval('show()',1000);
            });
</script>
</body>
</html>
