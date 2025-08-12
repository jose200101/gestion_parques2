function initMap() {
    const defaultLocation = { lat: 40.4168, lng: -3.7038 }; // Madrid
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 13,
        center: defaultLocation,
    });

    let marker;

    map.addListener("click", (mapsMouseEvent) => {
        if (marker) {
            marker.setMap(null);
        }

        marker = new google.maps.Marker({
            position: mapsMouseEvent.latLng,
            map,
        });

        const infoWindow = new google.maps.InfoWindow({
            content: JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2),
        });
        infoWindow.open(map, marker);
        
        console.log("Coordenadas del clic:", mapsMouseEvent.latLng.toJSON());
    });
}