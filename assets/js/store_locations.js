/**
 * Created by connormulhall on 25/05/2017.


initMap();
function initMap() {
    $.each(store_locations, function (k, i) {
        console.log(k);
        console.log(i);


        var myLatLng = {lat: parseFloat(i.lat), lng: parseFloat(i.lng)};

        var map = new google.maps.Map(document.getElementById(k), {
            zoom: 14,
            center: myLatLng
        });

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: i.address
        });

    });
}
 */