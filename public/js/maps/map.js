

class Map{

    /*** Initialize the map with default parameters and a map element! */
    constructor(mapElem, mapOptions=undefined, mapRestriction=undefined)
    {
        console.log('Creating New Map Object!');

        this.mapElem = mapElem;
        this.place = this.markedPlace = this.addressTitle = "Sanepa, Lalitpur";

        this.defaultLocation  = {lat: 27.683772,lng: 85.309353};
        this.location = this.defaultLocation;

        if(mapOptions != undefined)
            this.mapOptions = mapOptions;
        else {
            this.mapOptions={center:this.location, zoom:12};
        }
        try {
            this.map = new google.maps.Map(this.mapElem, this.mapOptions);
        }
        catch(e) {
            console.log(e, "from map contructor")
        }

        
        this.geocoder = new google.maps.Geocoder();

        this.markerLocation = this.defaultLocation;
        this.marker = new google.maps.Marker({
            position: this.markerLocation, //lat and lng
            map: this.map,
            animation: google.maps.Animation.BOUNCE,
            draggable: false,
            title: this.addressTitle,
            //icon: shipMarkerIcon,
        });

        this.infoWindow = new google.maps.InfoWindow({
            content: 'Location: '+this.addressTitle,
            size: new google.maps.Size(50, 50),
            disableAutoPan: true,
            map:this.map
        });

        this.marker.addListener("click", (e) => {
            console.log('infoWindow', this.infoWindow);
            this.infoWindow.open({
                anchor: this.marker,
                shouldFocus: false,
            });
        });

    }

   /******  BASIC FUNCTIONS: BEGINS *****/

    /**** Changes the center of the map to given position! ******/
    panTo(latLng)
    {
        console.log('Changing Map Center to: ',latLng);
        if(latLng == undefined) return;
        this.location = {lat:parseFloat(latLng.lat), lng:parseFloat(latLng.lng)}
        if(this.map)
        {
            this.map.panTo(this.location);
            //this.map.setZoom(16);
        }
    }

    /**** Set center of the map to the default Position! */
    panToDefault()
    {
        this.location = this.defaultLocation;
        this.map.panTo(this.location);
        this.map.setZoom(16);
    }

    /***** Change the marker's position in the map! */
    setMarkerPosition(latLng=undefined, addressTitle=undefined)
    {
        if(latLng == undefined)
            return;
        this.markerLocation = {lat:parseFloat(latLng.lat), lng:parseFloat(latLng.lng)};
        this.marker.setPosition(this.markerLocation);     
        console.log('Changed marker position to: '+this.marker.getPosition().lat()+", "+ this.marker.getPosition().lng());
    }

    updateMapTitle(addressTitle=undefined)
    {
        if(addressTitle!=undefined)
            this.addressTitle = addressTitle;
        this.marker.setTitle(this.addressTitle);
    }

    updateInfoWindowContent(addressTitle=undefined)
    {
        if(addressTitle!=undefined)
            this.addressTitle = addressTitle;
        this.infoWindow.setContent('<strong> Location: ' + this.addressTitle + '</strong>');
    }
   /******  BASIC FUNCTIONS: ENDS *****/


 
   /******  ADVANCED FUNCTIONS: BEGINS *****/

    /*** Initializes elements for updating after finishing callback functions like geocoders ****/
    initializeElements(latElem=undefined, lngElem=undefined, inputAddressElem=undefined, inputAddressOptionalElem=undefined, provinceElem=undefined, districtElem=undefined, countryElem=undefined, cityElem=undefined, postalCodeElem=undefined, subLocalityElem=undefined, routeElem=undefined)
    {
        this.inputAddressElem = inputAddressElem;
        this.inputAddressOptionalElem = inputAddressOptionalElem;
        this.latElem = latElem;
        this.lngElem = lngElem;
        this.provinceElem = provinceElem;
        this.districtElem = districtElem;
        this.countryElem = countryElem;
        this.cityElem = cityElem;
        this.postalCodeElem = postalCodeElem;
        this.subLocalityElem = subLocalityElem;
        this.routeElem = routeElem;
    }

    /**** Adds a listener for when the marker is dragged */
    addMarkerDragListener(inputAddressElem=undefined)
    {
        var parent = this;
        google.maps.event.addListener(this.marker, 'dragend', (function(evt) {
            //Check if the dragged location is within bounds (i.e. Nepal) and update fields accordingly and
            //reset marker if it crosses the country bounds
            console.log('Marker Position when dragged: ',parent.marker.getPosition());
            parent.getLocationFromLatLng({ lat:parent.marker.getPosition().lat(), lng:this.marker.getPosition().lng() }  );
        }).bind(parent));
    }

    /**** Adds an autocomplete listener to provided element */
    addAutoCompleteListener(inputAddressElem=undefined)
    //addAutoCompleteListener()
    {
        // console.log('Adding Autocomplete listener to: ');
        // console.log(inputAddressElem);
        // if(inputAddressElem == undefined) return;
        // console.log('Still Adding ');
        if(inputAddressElem != undefined)
            this.inputAddressElem = inputAddressElem;
        this.autocomplete = new google.maps.places.Autocomplete(this.inputAddressElem, {
            componentRestrictions: {
                "country": ['NP']
            }, //Limiting the place suggestion autocomplete to Nepal only
            fields: ['geometry',
            'name'], //Fetch only Geometry (lat and lng) and name of the selected place from autocomplete
            //  types: ['establishment']                        //Supports business/establishment places
        });
      

        var placeChanged = function(){
            var place = parent.autocomplete.getPlace();
            if (!place.geometry || !place.geometry.location) {
                // User entered the name of a Place that was not suggested and
                // pressed the Enter key, or the Place Details request failed.
                window.alert("No details available for input: '" + place.name + "'");
                return;
            }
            console.log("Selected Place: ", place); //Gets the selected place in input field
            parent.getLocationFromLatLng({lat:place.geometry.location.lat(), lng:place.geometry.location.lng() }, this.inputAddressElem.value);
        }

        //Triggers when the place is changed in the input field
        var parent = this;
        google.maps.event.addListener(this.autocomplete,  'place_changed', 
            placeChanged.bind(parent)
        );

        

        // this.inputAddressElem.addEventListener('change', function(){
        //     console.log('Address Input Changed!', this.location);
        // });
        

    }

    /***** Uses reverse geocoding for deducing a location details from given latitude and longitude */
    //getLocationFromLatLng(lat, lng, geocoder, map, marker, infoWindow, shipOrBill) {
    getLocationFromLatLng(latLng=undefined, addressTitle=undefined) {
        this.tempAddressTitle = addressTitle;
        console.log('Getting Location from latlng: ', latLng);
        console.log('Marker Position in Geocoder: ',this.marker.getPosition());

        this.latLng = undefined;
        //If triggered by dragging the marker
        if(latLng == undefined)
        {
            this.latLng = this.marker.getPosition();
        }
        else{   //Triggered from places autocomplete selection
            this.latLng = {
                lat: parseFloat(latLng.lat),
                lng: parseFloat(latLng.lng),
            };
        }
        
        this.geocoder.geocode({
            location: this.latLng
        })
        .then((response)  => {
            console.log('Reverse Geocoder Response: ', response);
            var addressJSON  = this.getFormattedAddress(response);
            if(addressJSON.country.toLocaleLowerCase().trim() != "nepal")
            {
                //Alert user about out of bounds
                Swal.fire({
                    icon: 'error',
                    title: 'Error...',
                    text: 'Address out of bounds!',
                    footer: 'Please select an address within Nepal!'
                });
                console.log('Marker out of bounds! Select an address within Nepal!');
                var latLng = this.location;
                //Reset the marker to previous position
                this.marker.setPosition(latLng); //Revert position of marker to previous position
            }
            else {
                //Update fields for new address
                //var selectedAddress = addressJSON.formatted_address;
                console.log('Updating address to dragged marker!');
                this.latElem.value= this.latLng.lat;
                this.lngElem.value= this.latLng.lng;
                // this.districtElem.value = addressJSON.district;
                // this.provinceElem.value = addressJSON.province;
                // this.cityElem.value = addressJSON.city;
                // this.subLocalityElem.value = addressJSON.sub_locality;
                // this.routeElem.value = addressJSON.route;
                // this.postalCodeElem.value = addressJSON.postal_code;
                // this.countryElem.value = addressJSON.country;

                var selectedAddress =  addressJSON.formatted_address;
                if( this.tempAddressTitle != undefined) selectedAddress = this.tempAddressTitle;
                this.inputAddressElem.value = selectedAddress;
                // this.inputAddressOptionalElem.value = selectedAddress;
                this.addressTitle = selectedAddress;
                
                this.location = this.latLng;
                this.setMarkerPosition(this.location);
                this.panTo(this.location);
                this.updateInfoWindowContent();
                this.updateMapTitle();
            }
        })
        .catch((e) => {
            //Geocoder fails due to some reason 
            Swal.fire({
                icon: 'error',
                title: 'Error...',
                text: "Geocoder failed due to: " + e,
            });
        }); 
    }



    /*** Breaks the response of reverse geocoder into country, province, district, formatted and optionally city(locality), sub_locality and route!  **/
    getFormattedAddress(response)
    {   
        
        console.log('Geocoder Response:',response);

       // var country = province = district = formatted_address = city = sub_locality = route = postal_code = '';
        var addressJSON = {
            country:"",province:"",district:"",formatted_address:"",city:"",sub_locality:"",route:"",postal_code:""
        };
        var parent = this;
        $.each(response.results, (function(index, result){
            
            //Formatted Address 
            addressJSON.formatted_address = result.formatted_address;

            //If any of country, province or district is empty or not in proper english
            if(addressJSON.province == "" || addressJSON.country == "" || addressJSON.district == "" || !parent.isEnglish(addressJSON.province) || !parent.isEnglish(addressJSON.district) || !parent.isEnglish(addressJSON.country) || addressJSON.locality == "" || addressJSON.sub_locality == "" || addressJSON.route == "" || addressJSON.postal_code == "" ||  addressJSON.route.toLowerCase().includes("unnamed road") || (addressJSON.country.toLocaleLowerCase().trim() != "nepal") )
            {
                $.each(result.address_components, function(index, component){
                    if(component.types.includes('administrative_area_level_1') && ( addressJSON.province=="" || !parent.isEnglish(addressJSON.province) )  )//&& component.types[1] == 'political' )
                        addressJSON.province = component.long_name;
                    if(component.types.includes('administrative_area_level_2') && ( addressJSON.district==""  || !parent.isEnglish(addressJSON.district) )   ) // && component.types[1] == 'political')
                        addressJSON.district = component.long_name;
                    if(component.types.includes('country') && (addressJSON.country==""  || !parent.isEnglish(addressJSON.country) )    ) // && component.types[1] == 'political' )
                        addressJSON.country = component.long_name;
                    if(component.types.includes('locality') &&  (addressJSON.city=="" || !parent.isEnglish(addressJSON.city)  )  ) 
                        addressJSON.city = component.long_name;
                    if(component.types.includes('sublocality') &&  ( addressJSON.sub_locality == ""  || !parent.isEnglish(addressJSON.sub_locality)   )   ) 
                        addressJSON.sub_locality = component.long_name;
                    if(component.types.includes('route') &&  (addressJSON.route == "" || addressJSON.route.toLowerCase().includes("unnamed road") || !parent.isEnglish(addressJSON.route)  )) 
                        addressJSON.route = component.long_name;
                    if(component.types.includes('postal_code') &&  ( addressJSON.postal_code == "" || !parent.isEnglish(addressJSON.postal_code) ) ) 
                        addressJSON.postal_code = component.long_name;
                });
            }

            //Verify Country Nepal 
            if(addressJSON.country != "" && parent.isEnglish(addressJSON.country) && addressJSON.country.toLocaleLowerCase().trim() != "nepal")
            {  
                addressJSON.country = "";
                return false;
            }

            //formatted_address = result.formatted_address;
            console.log('Generating Intermediate Formatted Address: ', addressJSON);
            //Check if the above method failed to fetch any of the above fields country, province, district or formattedAddress
            //If it did, loop through another element of the results array
            if( !addressJSON.formatted_address.toLowerCase().includes("unnamed road") && !addressJSON.formatted_address.toLowerCase().includes("+") && addressJSON.province != "" && addressJSON.district != "" && addressJSON.country != "" && parent.isEnglish(addressJSON.province) && parent.isEnglish(addressJSON.district) && parent.isEnglish(addressJSON.country) ) 
            {
                if(addressJSON.route.toLowerCase().includes("unnamed road"))
                    addressJSON.route = "";
                console.log('Generating Final Formatted Address: ', addressJSON);
                return false;
            }
        }).bind(parent)  );
        return addressJSON;
    }
  
    /*** Determine allowed characters for the address deduced by the geocoder! **/
    isEnglish(str){
        var english = /^[A-Za-z0-9._ ]*$/;
        console.log("Check IsEnglish for "+str+", result: "+english.test(str.toLowerCase()) );
        return ((english.test(str.toLowerCase())) ?  true :  false);
    }
    test(){return 'TESTING@@';}

    /******  ADVANCED FUNCTIONS: ENDS *****/




}

