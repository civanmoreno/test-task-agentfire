jQuery(document).ready(function($) {
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
        var mark_title= $('#namemark').val();
        var lng = $('#mark_lng').val()
        var lat = $('#mark_lat').val();
        var tag = $('#tag').val();
        // Ajax to save mark.
        $.ajax({
            url: "/wp-json/api/v1/createMark",
            type: "POST",
            data: {
                'title': mark_title,
                'post_lng': lng,
                'post_lat': lat,
                'post_tag': tag
            },
            success: function(response) {
                if (response.status === 200 && response.post_id != null){

                    var marker = new mapboxgl.Marker().setLngLat([lng,lat]).addTo(map);
                    $('input[type="checkbox"][name="options"]').on('change', function() {
                        marker.remove()
                    });
                    $('.alert-success').css('display', 'block');
                    //marker.remove()
                }
            },
            error: function(error) {
                console.error(error);
            }
        });
    });
    // Function Get By Tag.
    function getByTag(tag){
        $.ajax({
            url: "/wp-json/api/v1/marks-by-tag/" + tag,
            type: "GET",
            success: function(result) {
                var listItems = '';
                $.each(result, function(value, index){
                    var marker = new mapboxgl.Marker().setLngLat([index['post_lng'], index['post_lat']]).addTo(map);
                    marker.getElement().addEventListener('mouseenter', function() {
                        // Create a popup with the marker's information
                        var popup = new mapboxgl.Popup()
                            .setLngLat(marker.getLngLat())
                            .setHTML('<div class="pop-up-info"><h3>' + index['title'] + '</h3><p>' + index['created'] + '</p><p>Tags: '+index['tags']+'</p></div>')
                            .addTo(map);
                        marker.getElement().addEventListener('mouseleave', function() {
                            popup.remove();
                        });
                    });


                    listItems += '<li><input type="checkbox"><label>' + index['title']+ '</label></li>';
                    // Add an event listener to the marker

                    $('input[type="checkbox"][name="options"]').on('change', function() {
                        if ($(this).prop('checked') == false) {
                            marker.remove()
                        }
                        marker.remove()
                    });

                });
            },
            error: function(xhr, status, error) {
                console.log("An error occurred: " + error);
            }
        });
    }

    // On Click Tag Checkbox.
    $('input[type="checkbox"][name="options"]').on('change', function() {

        if ($(this).prop('checked') == true){
            $('input[type="checkbox"][name="options"]').not(this).prop('checked', false)
            var value = $(this).val();
            getByTag(value);
        }
    });
});