

const TRACCAR_API = (function () {
    const domain = "195.201.137.69:8082";
    const apiPrefix = "/api";
    const token = "MGBcpTvmZBBy1xOkmJtZKoHL1EIfWhqq";

    let setMarkers;
    let devices;
    let geofence;

    function getPath(protocol, path) {
        return protocol + "://" + domain + apiPrefix + path;
    }

    function call(options) {
        switch (options.type) {
            case "GET":
                $.ajax({
                    type: options.type,
                    url: options.url,
                    xhrFields: { withCredentials: true },
                    crossDomain: true,
                    success: options.success,
                    error: function(e) {
                        toastr.error(e.responseText, "Traccar API Error:");
                        options.error?.();
                    }
                });
                break;

            case "POST":
                $.ajax({
                    type: options.type,
                    url: options.url,
                    xhrFields: { withCredentials: true },
                    crossDomain: true,
                    contentType:"application/json; charset=utf-8",
                    dataType:"json",
                    data: options.data,
                    success: options.success,
                    error: function(e) {
                        toastr.error(e.responseText, "Traccar API Error:");
                        options.error?.();
                    }
                });

            default:
                break;
        }
    }

    function init(func1) {
        setMarkers = func1;

        startSession();
    }

    function startSession() {
        $.ajax({
            type: "POST",
            contentType: "application/x-www-form-urlencoded",
            url: "http://195.201.137.69:8082/api/session",
            xhrFields: { withCredentials: true },
            crossDomain: true,
            data: {
                "email": "admin",
                "password": "admin"
            },
            success: function(){
                getDevice();
            }
        });
    }

    function getDevice() {
        call({
            type: "GET",
            url: getPath('http', '/devices'),
            success: function(result){
                devices = result;
                // add device options to select form
                result.forEach(device => {
                    $('#device').append(`<option value="${device.id}">${device.name}</option>`);
                //     $('#selectedDevice').append(`<option value="${device.id}" selected>${device.name}</option>`);
                });
                
                socket();
            }
        });
    }

    function socket() {
        let ws = new ReconnectingWebSocket(getPath('ws', '/socket'));

        ws.onmessage = function(e) {
            let data = JSON.parse(e.data);
        
            if(data.positions !== undefined) {
                if (data.positions.length >= 1) {
                    setMarkers(data.positions);
                }
            }

            if(data.events !== undefined) {
                if (data.events.length >= 1) {
                    data.events.forEach(event => {
                        let device = getDeviceById(event.deviceId);
                        let toastrMsg = "Unknown Event";
                        switch (event.type) {
                            case "alarm":
                                toastrMsg = "Overspeed by " + device.name;
                                toastr.warning(toastrMsg);
                                break;
                            case "geofenceEnter":
                                toastrMsg = "GeoFence Entered by " + device.name;
                                toastr.success(toastrMsg);
                                break;
                            case "geofenceExit":
                                toastrMsg = "GeoFence Exited by " + device.name;
                                toastr.warning(toastrMsg);
                                break;
                            default:
                                break;
                        }
                    });
                }
            }
        };
    }

    function selectGeofence(options) {
        call({
            type: "GET",
            url: getPath('http', '/geofences/'+options.id),
            success: function(result){
                options.success?.(result);
            },
            error: function(result){
                toastr.warning("Geofence not found");
            }
        });
    }

    function createGeofence(options) {
        let data = JSON.stringify({
            "id": -1,
            "name": options.name,
            "description": "",
            "area": options.linestring,
            "calendarId": 0,
            "attributes": {
                "polylineDistance": options.distance * 1000
            }
        });
        
        if (options.linestring === "") {
            toastr.warning("Please select origin and destination");
        } else {
            $.ajax({
                type: "POST",
                url: getPath('http', '/geofences'),
                xhrFields: { withCredentials: true },
                crossDomain: true,
                contentType:"application/json; charset=utf-8",
                dataType:"json",
                data: data,
                success: function(result){
                    toastr.success("Geofence created");
                    if (options.deviceId !== null) {
                        linkDeviceToGeofence(result.id, options.deviceId);
                    }
                    options.success?.(result);
                },
                error: function(result) {
                    toastr.success("Error occured while creating Geofence");
                }
            });
        }
    }

    function updateGeofence(options) {
        let data = JSON.stringify({
            "id": options.id,
            "name": options.name,
            "description": "",
            "area": options.linestring,
            "calendarId": 0,
            "attributes": {
                "polylineDistance": options.distance * 1000
            }
        });

        if (options.linestring === "") {
            toastr.warning("Something went wrong while updating geofence", "Route not found:");
        } else {
            $.ajax({
                type: "PUT",
                url: getPath('http', '/geofences/' + options.id),
                xhrFields: { withCredentials: true },
                crossDomain: true,
                contentType:"application/json; charset=utf-8",
                dataType:"json",
                data: data,
                success: function(result){
                    toastr.success("Geofence updated");
                    if (options.deviceId !== null) {
                        linkDeviceToGeofence(result.id, options.deviceId);
                    }
                    options.success?.();
                },
                error: function(result) {
                    toastr.success("Error occured while creating Geofence");
                    options.error?.();
                }
            });
        }
    }

    function deleteGeofence(options) {
        $.ajax({
            type: "DELETE",
            url: getPath('http', '/geofences/' + options.id),
            xhrFields: { withCredentials: true },
            crossDomain: true,
            contentType:"application/json; charset=utf-8",
            dataType:"json",
            success: function(result){
                toastr.success("Geofence deleted");
                options.success?.();
            },
            error: function(result) {
                toastr.success("Error occured while deleting Geofence");
                options.error?.();
            }
        });
    }

    function linkDefaultGeofences(geofenceIds, deviceId, options) {
        let arr_len = geofenceIds.length;
        geofenceIds.forEach((geofenceId, index) => {
            if((arr_len - 1) == index){
                linkDeviceToGeofence(geofenceId, deviceId, options);
            } else {
                linkDeviceToGeofence(geofenceId, deviceId);
            }
        });
    }

    function linkDeviceToGeofence(geofenceId, deviceId, options) {
        let data = JSON.stringify({
            "deviceId": deviceId,
            "geofenceId": geofenceId
        });

        console.log(options);

        $.ajax({
            type: "POST",
            url: getPath('http', '/permissions'),
            xhrFields: { withCredentials: true },
            crossDomain: true,
            contentType:"application/json; charset=utf-8",
            dataType:"json",
            data: data,
            success: function(result){
                options?.success()
            }
        });
    }

    function getEvents(options) {
        call({
            type: "GET",
            // url: getPath('http', '/reports/events?from=2021-09-20T10:00:00Z&to=2021-10-04T18:30:00Z&deviceId=1'),
            url: getPath('http', '/reports/events?from='+options.from+'&to='+options.to+'&deviceId='+options.deviceId),
            success: function(result){
                options.success?.(result);
            },
            error: function(result){
                toastr.warning("Geofence not found");
            }
        });
    }


    function getDevices(){
        return devices;
    }

    function getDeviceById(deviceId) {
        index = devices.findIndex(function(device, index) {
            if(device.id === deviceId)
                return true;
        });
        return devices[index];
    }

    function createDevice(options) {
        let data = JSON.stringify({
            "id": -1,
            "name": options.name,
            "uniqueId": options.identifier,
            "phone": options.phone
        });

        if (options.name == "" || options.identifier == "" || options.phone == "") {
            toastr.error("Name, identifier and phone is required", "Validation error:");
        } else {
            $.ajax({
                type: "POST",
                url: getPath('http', '/devices'),
                xhrFields: { withCredentials: true },
                crossDomain: true,
                contentType:"application/json; charset=utf-8",
                dataType:"json",
                data: data,
                success: function(result){
                    toastr.success("Tracker Device Created");
                    
                    options.success?.(result);
                },
                error: function(result) {
                    toastr.success(result, "Error while creating tracker device:");
                    options.error?.(result);
                }
            });
        }
    }

    function updateDevice(options) {
        let data = JSON.stringify({
            "id": options.id,
            "name": options.name,
            "uniqueId": options.identifier,
            "phone": options.phone
        });

        if (options.name == "" || options.identifier == "" || options.phone == "") {
            toastr.error("Name, identifier and phone is required", "Validation error:");
        } else {
            $.ajax({
                type: "PUT",
                url: getPath('http', '/devices/' + options.id),
                xhrFields: { withCredentials: true },
                crossDomain: true,
                contentType:"application/json; charset=utf-8",
                dataType:"json",
                data: data,
                success: function(result){
                    toastr.success("Tracker Device updated");

                    options.success?.(result);
                },
                error: function(result) {
                    toastr.success("Error occured while creating Tracker Device");
                    options.error?.(result);
                }
            });
        }
    }

    return {
        init,
        startSession,
        getDeviceById,
        selectGeofence,
        createGeofence,
        updateGeofence,
        deleteGeofence,
        linkDeviceToGeofence,
        getDevices,
        getEvents,
        createDevice,
        updateDevice
    }
})();