           var gcd;
            mapboxgl.accessToken = 'pk.eyJ1Ijoia2VpdGhlcmljcGFyc29uczc3IiwiYSI6ImNsZ2syeG96NzA5MWwzam00aW05ejkwaHIifQ.t6aEapdsBtJZwzk5Xxw4Dg';
            const map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/keithericparsons77/clo3huwzf002b01qv4k8k436w',
                center: [-74.5, 40],
                zoom: 4,
                preserveDrawingBuffer: true
            });

            map.on('wheel', (e) => {
                const delta = e.originalEvent.deltaY;
                const zoomSpeed = 0.01; // You can adjust the zoom speed
                const zoom = map.getZoom() + (delta > 0 ? -zoomSpeed : zoomSpeed);
                const coords = map.unproject([e.originalEvent.x, e.originalEvent.y]);
                const mapCenter = map.getCenter();
                map.jumpTo({
                    center: mapCenter,
                    zoom: zoom,
                });
            });


            const zoomLevelDisplay = document.getElementById('zoom-level-display');

            map.on('zoom', () => {
                const zoom = map.getZoom().toFixed(2);
                zoomLevelDisplay.textContent = `Zoom Level: ${zoom}`;
            });




            document.getElementById('fly').addEventListener('click', () => {
                const latitude = parseFloat(document.getElementById('latitude').value);
                const longitude = parseFloat(document.getElementById('longitude').value);

                if (!isNaN(latitude) && !isNaN(longitude) && latitude >= -90 && latitude <= 90 && longitude >= -180 && longitude <= 180) {
                    map.flyTo({
                        center: [longitude, latitude],
                        essential: true 
                    });
                } else {
                    alert('Please enter valid latitude and longitude coordinates.');
                }
            });

            document.getElementById('location-input').addEventListener('input', function () {
                const query = this.value;
                const suggestionsContainer = document.getElementById('location-suggestions');

                suggestionsContainer.innerHTML = '';

                if (query) {
                    fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${query}.json?access_token=pk.eyJ1Ijoia2VpdGhlcmljcGFyc29uczc3IiwiYSI6ImNsZ2syeG96NzA5MWwzam00aW05ejkwaHIifQ.t6aEapdsBtJZwzk5Xxw4Dg&types=place,postcode`)
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.features) {
                                data.features.forEach((feature) => {
                                    const suggestionItem = document.createElement('div');
                                    suggestionItem.textContent = feature.place_name;
                                    suggestionItem.classList.add('suggestion-item');
                                    suggestionItem.addEventListener('click', function () {
                                        const coordinates = feature.center;
                                        document.getElementById('latitude').value = coordinates[1];
                                        document.getElementById('longitude').value = coordinates[0];
                                        suggestionsContainer.innerHTML = ''; 
                                    });
                                    suggestionsContainer.appendChild(suggestionItem);
                                });
                            }
                        });
                }
            });

            // Wait until the map has finished loading.
            map.on('load', () => {




                map.addSource('roads', {
                    type: 'vector',
                    url: 'mapbox://mapbox.mapbox-streets-v8'
                
                });
                map.addLayer({
                    'id': 'roads',
                    'type': 'line',
                    'source': 'roads',
                    'source-layer': 'road',
                    'layout': {
                        'visibility': 'visible',
                        'line-join': 'round',
                        'line-cap': 'round'
                    },
         
                },);




                map.moveLayer('roads', 'state-label');
                map.moveLayer('roads', 'settlement-major-label');
                map.moveLayer('roads', 'settlement-minor-label');
                map.moveLayer('roads', 'settlement-subdivision-label');
                map.moveLayer('roads', 'road-label-simple');
                map.setLayoutProperty('road-label-simple', 'visibility', 'none');
    
                map.getStyle().layers.forEach(function (layer) {
                    if (layer.source === 'composite' && layer['source-layer'] === 'road') {
                        console.log('Layer Name:', layer.id, 'ID:', layer.id);
                    }
                })
                



                function toggleLayer(layerId) {
                    const visibility = map.getLayoutProperty(layerId, 'visibility');
                    if (layerId === 'water') {
                        const visibility1 = map.getLayoutProperty('waterway', 'visibility');
                    }
                    if (layerId === 'roads') {
                        const visibility2 = map.getLayoutProperty('aeroway-line', 'visibility');

                        const visibility3 = map.getLayoutProperty('road-simple', 'visibility');

                    }
                    if (visibility === 'visible') {
                        map.setLayoutProperty(layerId, 'visibility', 'none');
                        if (layerId === 'water') {
                            map.setLayoutProperty('waterway', 'visibility', 'none');
                        }
                        if (layerId === 'roads') {
                            map.setLayoutProperty('aeroway-line', 'visibility', 'none');
                            map.setLayoutProperty('road-simple', 'visibility', 'none');

                        }
                    } else {
                        map.setLayoutProperty(layerId, 'visibility', 'visible');
                        if (layerId === 'water') {
                            map.setLayoutProperty('waterway', 'visibility', 'visible');
                        }
                        if (layerId === 'roads') {
                            map.setLayoutProperty('aeroway-line', 'visibility', 'visible');

                            map.setLayoutProperty('road-simple', 'visibility', 'visible');
                        }
                    }
                }

                const toggleableLayerIds = ['roads', 'water', 'land'];

                for (const id of toggleableLayerIds) {
                    if (document.getElementById(id)) {
                        continue;
                    }

                    const link = document.createElement('a');
                    link.id = id;
                    link.href = '#';
                    link.textContent = id;
                    link.className = 'active';

                    link.onclick = function (e) {
                        const clickedLayer = this.textContent;
                        e.preventDefault();
                        e.stopPropagation();
                        toggleLayer(clickedLayer);
                        this.classList.toggle('active');
                    };

                    const layers = document.getElementById('menu');
                    layers.appendChild(link);
                }

                const overlay = document.getElementById('preview-overlay');
                overlay.style.display = 'none';

                function findGCD(a, b) {
                    if (b === 0) {
                        return a;
                    } else {
                        return findGCD(b, a % b);
                    }
                }


                function makeWindow() {

                    const input_widthInches = parseFloat(document.getElementById('width-inches').value);
                    const input_heightInches = parseFloat(document.getElementById('height-inches').value);


                    gcd = 1;


                    var widthInches = input_widthInches / gcd;
                    var heightInches = input_heightInches / gcd;

                    if (input_widthInches < 15 || input_heightInches < 8 || document.getElementById('width-inches').value == document.getElementById('height-inches').value) {
                        widthInches = input_widthInches;
                        heightInches = input_heightInches;
                        gcd = 1;
                    }

                    document.getElementById('overlay-width').textContent = `Width: ${input_widthInches} inches`;
                    document.getElementById('overlay-height').textContent = `Height: ${input_heightInches} inches`;


                    if (!isNaN(widthInches) && !isNaN(heightInches) && widthInches > 0 && heightInches > 0) {
                        const dpi = 54; // Set the desired DPI value
                        const widthPixels = widthInches * dpi;
                        const heightPixels = heightInches * dpi;

                        const mapCenter = map.getCenter();
                        const halfWidth = widthPixels / 2;
                        const halfHeight = heightPixels / 2;

                        const mapContainer = document.getElementById('map-container');
                        const overlayContainer = document.getElementById('overlay-container');

                        overlay.style.display = 'block';
                        overlay.style.width = `${widthPixels}px`;
                        overlay.style.height = `${heightPixels}px`;

                        const overlayTop = `calc(50% - ${halfHeight}px)`;
                        const overlayLeft = `calc(50% - ${halfWidth}px)`;

                        overlayContainer.style.top = overlayTop;
                        overlayContainer.style.left = overlayLeft;
                        overlayContainer.style.width = `${widthPixels}px`;
                        overlayContainer.style.height = `${heightPixels}px`;

                    
                    } else {
                        alert('Please enter valid width and height in inches.');
                    }
                }


                makeWindow();

                document.getElementById('preview-button').addEventListener('click', () => {
                    const input_widthInches = parseFloat(document.getElementById('width-inches').value);
                    const input_heightInches = parseFloat(document.getElementById('height-inches').value);


                    gcd = findGCD(input_widthInches, input_heightInches);



                    var widthInches = input_widthInches 
                    var heightInches = input_heightInches                


                    if (input_widthInches < 15 || input_heightInches < 8 || document.getElementById('width-inches').value == document.getElementById('height-inches').value) {
                        widthInches = input_widthInches;
                        heightInches = input_heightInches;
                        gcd = 1;
                    }

                    document.getElementById('overlay-width').textContent = `Width: ${input_widthInches} inches`;
                    document.getElementById('overlay-height').textContent = `Height: ${input_heightInches} inches`;


                    if (!isNaN(widthInches) && !isNaN(heightInches) && widthInches > 0 && heightInches > 0) {
                        var dpi = 43;
                        
                        const widthPixels = widthInches * dpi;
                        const heightPixels = heightInches * dpi;

                        const mapCenter = map.getCenter();
                        const halfWidth = widthPixels / 2;
                        const halfHeight = heightPixels / 2;

                        const mapContainer = document.getElementById('map-container');
                        const overlayContainer = document.getElementById('overlay-container');

                        overlay.style.display = 'block';
                        overlay.style.width = `${widthPixels}px`;
                        overlay.style.height = `${heightPixels}px`;

                        const overlayTop = `calc(50% - ${halfHeight}px)`;
                        const overlayLeft = `calc(50% - ${halfWidth}px)`;

                        overlayContainer.style.top = overlayTop;
                        overlayContainer.style.left = overlayLeft;
                        overlayContainer.style.width = `${widthPixels}px`;
                        overlayContainer.style.height = `${heightPixels}px`;


                    } else {
                        alert('Please enter valid width and height in inches.');
                    }
                });

                document.getElementById('dpi-slider').addEventListener('input', () => {
                    const dpiValue =43;
                  
                    document.getElementById('dpi-value').textContent = dpiValue;

                    document.getElementById('preview-button').click();
                });



            });

            map.addControl(new MapboxExportControl({
                PageSize: Size.A3,
                PageOrientation: PageOrientation.Portrait,
                Format: Format.PNG,
                DPI: DPI[96],
                Crosshair: true,
                PrintableArea: true,
            }), 'top-right');

            map.addControl(new mapboxgl.ScaleControl());

            map.addControl(new mapboxgl.NavigationControl());

            function updateMarkerSize() {
                if (sizeState == 'big') {
                    customMarkerElement.style.width = '500px';
                    customMarkerElement.style.height = '500px';
                } else {
                    customMarkerElement.style.width = '30px';
                    customMarkerElement.style.height = '30px';
                }
            }





            function createCustomMarker(iconClass) {
                let sizeState = 'big';
                const customMarkerElement = document.createElement('div');
                customMarkerElement.className = 'custom-marker'; 

                const iconElement = document.createElement('i');
                iconElement.className = iconClass; 

                customMarkerElement.appendChild(iconElement);

                customMarkerElement.style.color = 'white';
                if (iconClass == "fa fa-heart"){
                    customMarkerElement.style.color = 'red';
                }



                iconElement.addEventListener('click', () => {

                });

                function updateMarkerSize() {
                    if (sizeState === 'small') {
                        customMarkerElement.style.width = '50px';
                        customMarkerElement.style.height = '50px';
                    } else {
                        customMarkerElement.style.width = '25px'; 
                        customMarkerElement.style.height = '25px'; 
                    }
                }

                return new mapboxgl.Marker({
                    element: customMarkerElement,
                    draggable: true
                });
            }


            const mapCenter = map.getCenter();
            const marker = createCustomMarker('fa fa-heart').setLngLat([mapCenter.lng, mapCenter.lat]);
            
            const houseMarker = createCustomMarker('fa fa-home').setLngLat([mapCenter.lng, mapCenter.lat]);
            const starMarker = createCustomMarker('fa fa-star').setLngLat([mapCenter.lng, mapCenter.lat]);

            document.getElementById('marker-toggle').addEventListener('click', () => toggleMarkerVisibility(marker));
            document.getElementById('house-toggle').addEventListener('click', () => toggleMarkerVisibility(houseMarker));
            document.getElementById('star-toggle').addEventListener('click', () => toggleMarkerVisibility(starMarker));

            let markerAdded = false; 

            function toggleMarkerVisibility(marker) {
                const mapCenter = map.getCenter();
                marker.setLngLat([mapCenter.lng, mapCenter.lat]);
                if (markerAdded) {
                    marker.remove();
                    markerAdded = false;
                } else {
                    marker.addTo(map);
                    markerAdded = true;
                }
            }


            function createCustomMarker1(iconClass, title, font) {
                const customMarkerElement = document.createElement('div');
                customMarkerElement.className = 'custom-marker'; 


                customMarkerElement.style.color = 'white';
       
                var selectedFont = document.getElementById('font-select').value;



                let font1 = font
                if (title) {
                    const titleElement = document.createElement('p');
                    titleElement.textContent = title;
                    titleElement.style.fontFamily = selectedFont;
                    titleElement.style.fontSize = ((font1 * 96) / gcd) + 'px';
               
                    customMarkerElement.appendChild(titleElement);
                    const deleteButton = document.createElement('button');
                    deleteButton.textContent = 'Delete';
                    deleteButton.style.display = 'none'; // Initially hidden
                    deleteButton.addEventListener('click', () => {
                        text_marker.remove();
                        const addedTitles = document.getElementById('added-titles');
                        addedTitles.removeChild(customMarkerElement);
                    });




                    let font = 12; 

                    const toggleButton = document.createElement('button');
                    toggleButton.textContent = 'Toggle Font Size';
                    toggleButton.style.display = 'none';
                    toggleButton.addEventListener('click', () => {
                        font = (font === 48 / gcd) ? 96 / gcd : 48 / gcd;

                        titleElement.style.fontSize = font + 'px';
                    });



                    const fontSizeSlider = document.createElement('input');
                    fontSizeSlider.type = 'range';
                    fontSizeSlider.min = '10'; 
                    fontSizeSlider.max = '50'; 
                    fontSizeSlider.value = font; 
                    fontSizeSlider.style.display = 'none'; 

                    fontSizeSlider.addEventListener('input', () => {
                        const newFontSize = fontSizeSlider.value;
                        titleElement.style.fontSize = newFontSize + 'px';

                    });

                    titleElement.addEventListener('click', () => {
                        if (deleteButton.style.display === 'none') {
                            deleteButton.style.display = 'block';
 
                            toggleButton.style.display = 'block';
                        } else {
                            deleteButton.style.display = 'none';
                  
                            toggleButton.style.display = 'none';
                        }
                    });


                    customMarkerElement.appendChild(toggleButton);
                    customMarkerElement.appendChild(deleteButton);

                }
                return new mapboxgl.Marker({
                    element: customMarkerElement,
                    draggable: true
                });
            }



            let text_marker;

            document.getElementById('add-title').addEventListener('click', () => {
                const title = document.getElementById('title-input').value;
                const font = document.getElementById('title-font').value;
                if (title) {
                    console.log(title);
                    const mapCenter = map.getCenter();

                    text_marker = createCustomMarker1('fas fa-heart', title, font).setLngLat([mapCenter.lng, mapCenter.lat]).addTo(map);
                }
            });



    $('#downloadLink').click(function () {
        const overlayContainer = document.getElementById('overlay-container');
        const overlayWidthPixels = overlayContainer.offsetWidth;
        const overlayHeightPixels = overlayContainer.offsetHeight;

        const mapCanvas = map.getCanvas(); 

        const dpi = 26; 

        if (overlayWidthPixels > 0 && overlayHeightPixels > 0) {
            const captureCanvas = document.createElement('canvas');
            captureCanvas.width = overlayWidthPixels;
            captureCanvas.height = overlayHeightPixels;
            const ctx = captureCanvas.getContext('2d');
            

            const overlayRect = overlayContainer.getBoundingClientRect();
            const mapRect = mapCanvas.getBoundingClientRect();

            const mapX = overlayRect.left - mapRect.left;
            const mapY = overlayRect.top - mapRect.top;

            ctx.drawImage(
                mapCanvas,
                mapX, mapY, overlayWidthPixels, overlayHeightPixels, 
                0, 0, overlayWidthPixels, overlayHeightPixels  
            );

            const customMarkers = document.querySelectorAll('.custom-marker');
            customMarkers.forEach(marker => {
                const markerRect = marker.getBoundingClientRect();
                const markerX = markerRect.left - mapRect.left - mapX;
                const markerY = markerRect.top - mapRect.top - mapY;

                const textMarker = marker.querySelector('p');
                if (textMarker) {
                    ctx.font = window.getComputedStyle(textMarker).getPropertyValue('font');
                    ctx.fillStyle = window.getComputedStyle(textMarker).getPropertyValue('color');
                    ctx.fillText(textMarker.textContent, markerX, markerY);
                }
                else{
            

                    const iconMarker = marker.querySelector('i');

                    const iconHTML = iconMarker.outerHTML.trim();

                    const img = new Image();

                    img.src = `data:image/svg+xml,${encodeURIComponent(iconHTML)}`;

                    console.log("Image source:", img.src);

                    console.log("image:");
                    img.onload = function() {
                        console.log("image:1");
                        ctx.drawImage(img, markerX, markerY);
                    };
                img.onerror = function(error) {
                    console.error("Error loading image:", error);
                };
            
                }
            });

            const dataURL = captureCanvas.toDataURL('image/png', 1.0);
            const input_widthInches = parseFloat(document.getElementById('width-inches').value);
            const input_heightInches = parseFloat(document.getElementById('height-inches').value);
            fetch('/save-image', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({
                    image: dataURL,
                    width:input_widthInches,
                    height:input_heightInches
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Image saved successfully', data);
            })
            .catch(error => {
                console.error('Error saving image:', error);
            });
        } else {
            alert('Overlay container has invalid dimensions.');
        }
    });


            $(".toggle_menu").on("click", function (event) {
                $("#menu").toggleClass("active");
            });



