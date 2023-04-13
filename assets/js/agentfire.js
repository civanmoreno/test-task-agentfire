jQuery(document).ready(function($) {
    // Get Access Token.
    $.ajax({
        url: "/wp-json/api/v1/getAccessToken",
        type: "GET",
        success: function(result) {

            // Start Map.
            mapboxgl.accessToken = result.token;
            var map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v11',
                center: [-96, 37.8],
                zoom: 1
            });

            // Get Longitude And Latitude.
            map.on('click', function(e) {
                console.log(e);
                var lngLat = e.lngLat;
                var lng = lngLat.lng;
                var lat = lngLat.lat;
                $('#mark_lng').val(lng);
                $('#mark_lat').val(lat);
            });

            $('form').submit(function(e) {
                e.preventDefault();

                // Ajax to save mark.
                $.ajax({});

                // Create new mark.
                var mark_value = $('#namemark').val();
                var lng = $('#mark_lng').val()
                var lat = $('#mark_lat').val();
                new mapboxgl.Marker().setLngLat([lng,lat]).addTo(map);
                var newItem = '<li><input type="checkbox"><label for="">'+ mark_value +'</label></li>';
                $( "#tags-ul" ).append(newItem);
            });

            // Get points and render.
            $.ajax({
                url: "/wp-json/api/v1/getPoints",
                type: "GET",
                success: function(result) {
                    var listItems = '';
                    $.each(result.points, function(value, index){
                        var marker = new mapboxgl.Marker().setLngLat([index[0], index[1]]).addTo(map);
                        listItems += '<li><input type="checkbox"><label for="' + index[2] + '">' + index[2] + '</label></li>';
                    });
                    $('#tags-ul').html(listItems);
                },
                error: function(xhr, status, error) {
                    console.log("An error occurred: " + error);
                }
            });

        },
        error: function(xhr, status, error) {
            console.log("An error occurred: " + error);
        }
    });




});