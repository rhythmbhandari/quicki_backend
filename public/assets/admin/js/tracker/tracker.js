

const TRACKER = (function () {
    let map;
    let markers = []; // format [{deviceId: int, marker: google.maps.Marker(), polyline: google.maps.Polyline(), follow: bool},...]
    let eventMarkers = [];
    let selectedDeviceIds;
    let lastPositions;
    let getLiveLocation=null;

    const markerIcons = {
        primary: {
            url: ICONS.primary,
            anchor: new google.maps.Point(20, 20),
        },
        garageRequest: {
            url: ICONS.garage_request,
            anchor: new google.maps.Point(20, 40),
            scaledSize: new google.maps.Size(40, 40)
        },
        garage: {
            url: ICONS.garage,
            anchor: new google.maps.Point(20, 40),
            scaledSize: new google.maps.Size(40, 40)
        }
    };

    const autocompleteOptions = {
        // bounds: defaultBounds,
        componentRestrictions: { country: "np" },
        fields: ["address_components", "geometry", "icon", "name"],
        strictBounds: false,
        // types: ["establishment"],
    };

    let autocompleteOrigin;
    let autocompleteDestination;
    let originInput;
    let destinationInput;

    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer();

    let linestring = "";

    let extendMarkerListener;
    
    function init(mapdom, options, devices, origin, destination) {
        map = new google.maps.Map(mapdom, options);

        directionsRenderer.setMap(map);

        if(origin !== undefined){
            originInput = origin;
            destinationInput = destination;

            autocompleteOrigin = new google.maps.places.Autocomplete(origin, autocompleteOptions);
            autocompleteDestination = new google.maps.places.Autocomplete(destination, autocompleteOptions);
    
            setAutocomplete();
        }

        selectedDeviceIds = devices;

        TRACCAR_API.init(setMarkers);
    }

    function setAutocomplete() {
        bindInputToMap(autocompleteOrigin);
        bindInputToMap(autocompleteDestination);
    }

    function bindInputToMap(autocomplete) {
        autocomplete.bindTo("bounds", map);

        const markerFrom = new google.maps.Marker({
            map
        });

        autocomplete.addListener("place_changed", () => {
            markerFrom.setVisible(false);
            const place = autocomplete.getPlace();

            if (!place.geometry || !place.geometry.location) {
                window.alert("No details available for input: '" + place.name + "'");
                return;
            }

            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }

            // console.log(place.geometry.location.lat(), lng);

            markerFrom.setPosition(place.geometry.location);
            markerFrom.setVisible(true);
            place.formatted_address;
            calculateAndDisplayRoute();
        });
    }

    function calculateAndDisplayRoute() {
        directionsService.route({
            origin: {
                query: originInput.value,
            }, 
            destination: {
                query: destinationInput.value,
            }, 
            travelMode: google.maps.TravelMode.DRIVING,
        })
        .then((response, status) => {
            directionsRenderer.setDirections(response);
            // response.request.origin.split(", ")[0];
            // response.request.destination.split(", ")[0];

            linestring = toLinestring(response.routes[0].overview_polyline);
        }).catch((e) => console.log("Directions request failed due to " + status));
    }

    function displayWaypointRoute(options) {
        directionsService.route({
            origin: options.origin,
            destination: options.destination,
            waypoints: options.waypoints,
            optimizeWaypoints: false,
            travelMode: google.maps.TravelMode.DRIVING,
        })
        .then((response) => {
            console.log(response);
            directionsRenderer.setDirections(response);

            linestring = toLinestring(response.routes[0].overview_polyline);
        })
        .catch((e) =>console.log("Directions request failed due to " + e));
    }

    function createOtherMarker(position, type) {
        let googleMapPosition = new google.maps.LatLng(position.latitude, position.longitude);
        let marker = new google.maps.Marker({
            position: googleMapPosition,
            map: map,
            icon: markerIcons[type],
            clickable: true
        });

        eventMarkers.push(marker);

        // google.maps.event.addListener(marker, 'click', (function(map, marker, position) {
        //     return function() {
        //         let device = TRACCAR_API.getDeviceById(position.deviceId);
        //         const infowindow = new google.maps.InfoWindow({
        //             content: device.name,
        //         });

        //         infowindow.open({
        //             anchor: marker,
        //             map
        //         });
        //     }
        // })(map, marker, position));
    }

    function deleteAllEventMarkers(){
        eventMarkers.forEach(marker => {
            marker.setMap(null);
        });
        eventMarkers = [];
    }

    function toLinestring(str) {
        let index = 0,
            lat = 0,
            lng = 0,
            line_string = "LINESTRING (",
            shift = 0,
            result = 0,
            byte = null,
            latitude_change,
            longitude_change,
            factor = Math.pow(10, 5)
            i = 0;

        while (index < str.length) {
            byte = null;
            shift = 0;
            result = 0;

            do {
                byte = str.charCodeAt(index++) - 63;
                result |= (byte & 0x1f) << shift;
                shift += 5;
            } while (byte >= 0x20);

            latitude_change = ((result & 1) ? ~(result >> 1) : (result >> 1));
            shift = result = 0;

            do {
                byte = str.charCodeAt(index++) - 63;
                result |= (byte & 0x1f) << shift;
                shift += 5;
            } while (byte >= 0x20);

            longitude_change = ((result & 1) ? ~(result >> 1) : (result >> 1));

            lat += latitude_change;
            lng += longitude_change;

            if (i % 2 === 0) {
                line_string = line_string.concat("" + (lat / factor) + " " + (lng / factor) + ", ");
            }
            
            i = i + 1; 
        }

        return line_string.slice(0, -2) + ")";
    }

    function setMarkers(positions) {
        // if (markers.length === 0) {
        //     createMarkers(positions);
        //     return;
        // }
        lastPositions = positions;

        positions.forEach(function(position) {
            let i = markers.findIndex(function(marker, index) {
                if(marker.deviceId === position.deviceId)
                    return true;
            });

            if (getLiveLocation != null && getLiveLocation.deviceId == position.deviceId) {
                getLiveLocation.match(position);
            }
            
            let j = -1;
            if (selectedDeviceIds === "All") {
                j = 0;
            } else {
                j = selectedDeviceIds.findIndex((e) => e === position.deviceId);
            }

            if (i <= -1){
                if (j > -1){
                    createMarker(position);
                }
            } else {
                if (j > -1){
                    moveMarker(markers[i], position);
                } else {
                    deleteMarker(markers[i]);
                }
            }
        });
    }

    function getLocation(deviceId, match){
        getLiveLocation = {deviceId, match};
    }

    function createMarker(position) {
        let googleMapPosition = new google.maps.LatLng(position.latitude, position.longitude);
        let marker = new google.maps.Marker({
            position: googleMapPosition,
            map: map,
            icon: markerIcons.primary,
            clickable: true,
            markerID: position.deviceId,
        });

        google.maps.event.addListener(marker, 'click', (function(map, marker, position) {
            return function() {
                let device = TRACCAR_API.getDeviceById(position.deviceId);
                const infowindow = new google.maps.InfoWindow({
                    content: device.name,
                });

                infowindow.open({
                    anchor: marker,
                    map,
                    // shouldFocus: false,
                });

                // getMarkerDetails();
                map.panTo(marker.getPosition());
                // map.setZoom(15);
            }
        })(map, marker, position));

        let polyline = new google.maps.Polyline({
            path: [googleMapPosition],
            geodesic : true,
            strokeColor: '#FF0000',
            strokeOpacity: 1.0,
            strokeWeight: 5,
            editable: false,
            map:map
        });

        markers.push({
            deviceId: position.deviceId,
            marker,
            polyline,
            follow: false
        });
    }

    function deleteMarker(marker) {
        marker.marker.setMap(null);
        marker.polyline.setMap(null);
    }

    function moveMarker(marker, position) {
        if(marker.marker.map === null) {
            setMarkerVisibility(marker, map);
        }

        let googleMapPosition = new google.maps.LatLng(position.latitude, position.longitude);

        marker.polyline.getPath().push(googleMapPosition);
        if(marker.polyline.getPath().length > 10 && position.attributes.motion){
            marker.polyline.getPath().removeAt(0);
        }

        marker.marker.setPosition(googleMapPosition);
        if (marker.follow) {
            map.panTo(googleMapPosition);
        }
    }

    function setMarkerVisibility(marker, map) {
        marker.marker.setMap(map);
        marker.polyline.setMap(map);
    }

    // function getMarkerDetails(marker, position) {
    //     console.log(marker);
    //     console.log(position);
    // }

    // function getMarkerIndexByDeviceId(deviceId) {
    //     return markers.findIndex(function(marker, index) {
    //         if(deviceId === position.deviceId)
    //             return true;
    //     });
    // }

    // function setMarkerFollow(deviceId) {
    //     index = getMarkerIndexByDeviceId(deviceId);
    //     markers[index].follow = false;
    // }

    function createSelectedGeofence(options) {
        options.linestring = linestring;
        TRACCAR_API.createGeofence(options);
    }

    function updateSelectedGeofence(options) {
        options.linestring = linestring;
        TRACCAR_API.updateGeofence(options);
    }

    function setSelectedDeviceIds(deviceIds) {
        selectedDeviceIds = deviceIds;

        markers.forEach(marker => {
            let j = selectedDeviceIds.findIndex((e) => e === marker.deviceId);

            setMarkerVisibility(marker, (j > -1) ? map : null);
        });
    }

    function createExtendMarkerListener(options) {
        extendMarkerListener = google.maps.event.addListener(map, 'click', function(e) {
            options?.addToExtendMarkerList(e.latLng);
        });
    }

    function removeExtendMarkerListener() {
        google.maps.event.removeListener(extendMarkerListener);
    }

    return {
        init,
        createSelectedGeofence,
        updateSelectedGeofence,
        setSelectedDeviceIds,
        getLocation,
        toLinestring,
        displayWaypointRoute,
        createOtherMarker,
        deleteAllEventMarkers,
        createExtendMarkerListener,
        removeExtendMarkerListener
    }
})();