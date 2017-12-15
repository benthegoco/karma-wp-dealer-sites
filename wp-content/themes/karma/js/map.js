var g_is_map_initialized = false;
if(!mapZoom){
    mapZoom = 14; //default zoom
}

// Helper functions
var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};

function initMaps()
{
    if (!g_is_map_initialized) {
        g_is_map_initialized = true;
        initMap(0);
    }
}

function initMap(index)
{
    // Styles a map in night mode.
    function InfoBox(opts)
    {
        google.maps.OverlayView.call(this);
        this.latlng_ = opts.latlng;
        this.map_ = opts.map;
        this.html_content_ = opts.html_content;
        this.offsetVertical_ = -250;
        this.offsetHorizontal_ = -180;
        this.height_ = 165;
        this.width_ = 350;

        var me = this;
        this.boundsChangedListener_ =
            google.maps.event.addListener(this.map_, "bounds_changed", function() {
                return me.panMap.apply(me);
            });

        // Once the properties of this OverlayView are initialized, set its map so
        // that we can display it.  This will trigger calls to panes_changed and
        // draw.
        this.setMap(this.map_);
    }

    /* InfoBox extends GOverlay class from the Google Maps API
     */
    InfoBox.prototype = Object.create(google.maps.OverlayView.prototype);

    /* Creates the DIV representing this InfoBox
     */
    InfoBox.prototype.remove = function()
    {
        if (this.div_) {
            this.div_.parentNode.removeChild(this.div_);
            this.div_ = null;
        }
    };

    /* Redraw the Bar based on the current projection and zoom level
     */
    InfoBox.prototype.draw = function() {
        // Creates the element if it doesn't exist already.
        this.createElement();
        if (!this.div_) {
            return;
        }

        // Calculate the DIV coordinates of two opposite corners of our bounds to
        // get the size and position of our Bar
        var pixPosition = this.getProjection().fromLatLngToDivPixel(this.latlng_);
        if (!pixPosition) {
            return;
        }

        // Now position our DIV based on the DIV coordinates of our bounds
        this.div_.style.width = this.width_ + "px";
        this.div_.style.left = (pixPosition.x + this.offsetHorizontal_) + "px";
        this.div_.style.height = this.height_ + "px";
        this.div_.style.top = (pixPosition.y + this.offsetVertical_) + "px";
        this.div_.style.display = 'block';
    };

    /* Creates the DIV representing this InfoBox in the floatPane.  If the panes
    * object, retrieved by calling getPanes, is null, remove the element from the
    * DOM.  If the div exists, but its parent is not the floatPane, move the div
    * to the new pane.
    * Called from within draw.  Alternatively, this can be called specifically on
    * a panes_changed event.
    */
    InfoBox.prototype.createElement = function() {
        var panes = this.getPanes();
        var div = this.div_;
        if (!div) {
            // This does not handle changing panes.  You can set the map to be null and
            // then reset the map to move the div.
            div = this.div_ = document.createElement("div");
            div.style.border = "0px none";
            div.style.position = "absolute";
            div.style.width = this.width_ + "px";
            div.style.height = this.height_ + "px";
            var contentDiv = document.createElement("div");
            contentDiv.className +="iw-box-wrapper";
            contentDiv.innerHTML = this.html_content_;

            var topDiv = document.createElement("div");
            topDiv.className +="btnClose";
            topDiv.style.textAlign = "right";
            var closeImg = document.createElement("img");
            closeImg.style.width = "12x";
            closeImg.style.height = "12px";
            closeImg.style.cursor = "pointer";
            closeImg.src = siteURL+"wp-content/themes/karma/images/iconClose.png";
            topDiv.appendChild(closeImg);

            function removeInfoBox(ib) {
                return function() {
                    ib.setMap(null);
                };
            }

            google.maps.event.addDomListener(closeImg, 'click', removeInfoBox(this));

            div.appendChild(topDiv);
            div.appendChild(contentDiv);
            div.style.display = 'none';
            panes.floatPane.appendChild(div);
            this.panMap();
        }
        else if (div.parentNode != panes.floatPane) {
            // The panes have changed.  Move the div.
            div.parentNode.removeChild(div);
            panes.floatPane.appendChild(div);
        }
        else {
            // The panes have not changed, so no need to create or move the div.
        }
    }

    /* Pan the map to fit the InfoBox.
    */
    InfoBox.prototype.panMap = function() {
        // if we go beyond map, pan map
        var map = this.map_;
        var bounds = map.getBounds();
        if (!bounds) {
            return;
        }

        // The position of the infowindow
        var position = this.latlng_;

        // The dimension of the infowindow
        var iwWidth = this.width_;
        var iwHeight = this.height_;

        // The offset position of the infowindow
        var iwOffsetX = this.offsetHorizontal_;
        var iwOffsetY = this.offsetVertical_;

        // Padding on the infowindow
        var padX = 40;
        var padY = 40;

        // The degrees per pixel
        var mapDiv = map.getDiv();
        var mapWidth = mapDiv.offsetWidth;
        var mapHeight = mapDiv.offsetHeight;
        var boundsSpan = bounds.toSpan();
        var longSpan = boundsSpan.lng();
        var latSpan = boundsSpan.lat();
        var degPixelX = longSpan / mapWidth;
        var degPixelY = latSpan / mapHeight;

        // The bounds of the map
        var mapWestLng = bounds.getSouthWest().lng();
        var mapEastLng = bounds.getNorthEast().lng();
        var mapNorthLat = bounds.getNorthEast().lat();
        var mapSouthLat = bounds.getSouthWest().lat();

        // The bounds of the infowindow
        var iwWestLng = position.lng() + (iwOffsetX - padX) * degPixelX;
        var iwEastLng = position.lng() + (iwOffsetX + iwWidth + padX) * degPixelX;
        var iwNorthLat = position.lat() - (iwOffsetY - padY) * degPixelY;
        var iwSouthLat = position.lat() - (iwOffsetY + iwHeight + padY) * degPixelY;

        // calculate center shift
        var shiftLng =
            (iwWestLng < mapWestLng ? mapWestLng - iwWestLng : 0) +
            (iwEastLng > mapEastLng ? mapEastLng - iwEastLng : 0);
        var shiftLat =
            (iwNorthLat > mapNorthLat ? mapNorthLat - iwNorthLat : 0) +
            (iwSouthLat < mapSouthLat ? mapSouthLat - iwSouthLat : 0);

        // The center of the map
        var center = map.getCenter();

        // The new map center
        var centerX = center.lng() - shiftLng;
        var centerY = center.lat() - shiftLat;

        // center the map to the new shifted center
        map.setCenter(new google.maps.LatLng(centerY, centerX));

        // Remove the listener after panning is complete.
        google.maps.event.removeListener(this.boundsChangedListener_);
        this.boundsChangedListener_ = null;
    };

    var draggVal = true;
    if(isMobile.any()) {
        draggVal = false
    }

    var mapContainer = document.getElementsByName('map')[index];
    var map = new google.maps.Map(mapContainer, {
            draggable: draggVal,
            scrollwheel: false,
            disableDoubleClickZoom: true,
            zoom: mapZoom,
            styles: [{
                "elementType": "geometry",
                "stylers": [{
                    "color": "#f5f5f5"
                }]
            }, {
                "elementType": "labels.icon",
                "stylers": [{
                    "visibility": "off"
                }]
            }, {
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#616161"
                }]
            }, {
                "elementType": "labels.text.stroke",
                "stylers": [{
                    "color": "#f5f5f5"
                }]
            }, {
                "featureType": "administrative.land_parcel",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#bdbdbd"
                }]
            }, {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#eeeeee"
                }]
            }, {
                "featureType": "poi",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#757575"
                }]
            }, {
                "featureType": "poi.business",
                "stylers": [{
                    "visibility": "off"
                }]
            }, {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#e5e5e5"
                }]
            }, {
                "featureType": "poi.park",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#9e9e9e"
                }]
            }, {
                "featureType": "road",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#ffffff"
                }]
            }, {
                "featureType": "road",
                "elementType": "labels.icon",
                "stylers": [{
                    "visibility": "off"
                }]
            }, {
                "featureType": "road.arterial",
                "elementType": "labels",
                "stylers": [{
                    "visibility": "off"
                }]
            }, {
                "featureType": "road.arterial",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#757575"
                }]
            }, {
                "featureType": "road.highway",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#dadada"
                }]
            }, {
                "featureType": "road.highway",
                "elementType": "labels",
                "stylers": [{
                    "visibility": "off"
                }]
            }, {
                "featureType": "road.highway",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#616161"
                }]
            }, {
                "featureType": "road.local",
                "stylers": [{
                    "visibility": "off"
                }]
            }, {
                "featureType": "road.local",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#9e9e9e"
                }]
            }, {
                "featureType": "transit",
                "stylers": [{
                    "visibility": "off"
                }]
            }, {
                "featureType": "transit.line",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#e5e5e5"
                }]
            }, {
                "featureType": "transit.station",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#eeeeee"
                }]
            }, {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#c9c9c9"
                }]
            }, {
                "featureType": "water",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#9e9e9e"
                }]
            }]
        }
    );

    var geocoder = new google.maps.Geocoder();

    for (var i=0; i<3; ++i) {
        addMarker(InfoBox, map, geocoder, i);
    }
}

function addMarker(InfoBox, map, geocoder, index)
{
    geocoder.geocode({
            'address': address[index]
        },
        function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                var marker = new google.maps.Marker({
                        map: map,
                        draggable: false,
                        animation: google.maps.Animation.DROP,
                        position: results[0].geometry.location,
                        icon: siteURL+'wp-content/themes/karma/images/map-marker-icon.png'
                    }
                );
                map.setCenter(results[0].geometry.location);
                google.maps.event.addListener(marker, "click", function(e) {
                    new InfoBox({
                            latlng: marker.getPosition(),
                            map: map,
                            html_content: contentString[index]
                        }
                    );
                });
                google.maps.event.trigger(marker, "click")
            }
        }
    );
}

