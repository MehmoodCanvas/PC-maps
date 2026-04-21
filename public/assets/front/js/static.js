var gcd;
mapboxgl.accessToken = 'pk.eyJ1Ijoia2VpdGhlcmljcGFyc29uczc3IiwiYSI6ImNsZ2syeG96NzA5MWwzam00aW05ejkwaHIifQ.t6aEapdsBtJZwzk5Xxw4Dg';
const map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/keithericparsons77/clo3huwzf002b01qv4k8k436w',
    center: [-74.5, 40],
    zoom: 4,
    preserveDrawingBuffer: true,
    pitch: 0,
    maxPitch: 0,
    minPitch: 0
});
map.setPitch(0);
map.setPitch = function (pitch) {
    console.log('Map tilt disabled - rotation still allowed');
    return map;
};
const originalEaseTo = map.easeTo.bind(map);
const originalFlyTo = map.flyTo.bind(map);

map.easeTo = function (options) {
    if (options && options.pitch !== undefined) {
        console.log('Removed pitch from easeTo, keeping other options');
        delete options.pitch;
    }
    return originalEaseTo(options);
};

map.flyTo = function (options) {
    if (options && options.pitch !== undefined) {
        console.log('Removed pitch from flyTo, keeping other options');
        delete options.pitch;
    }
    return originalFlyTo(options);
};
document.getElementById('north-direction').addEventListener('click', () => {
    map.resetNorth();
});

map.on('wheel', (e) => {
    const delta = e.originalEvent.deltaY;
    const zoomSpeed = 0.01;
    const zoom = map.getZoom() + (delta > 0 ? -zoomSpeed : zoomSpeed);
    const coords = map.unproject([e.originalEvent.x, e.originalEvent.y]);
    const mapCenter = map.getCenter();
    map.jumpTo({
        center: mapCenter,
        zoom: zoom,
    });
    const zoomLevelDisplay = document.getElementById('zoom_count');
    zoomLevelDisplay.innerHTML = zoom.toFixed(2)
});

map.on('wheel', (e) => {
    e.preventDefault();

    const delta = e.originalEvent.deltaY > 0 ? -0.5 : 0.5;
    const zoom = map.getZoom() + delta;

    const coords = map.unproject([e.originalEvent.offsetX, e.originalEvent.offsetY]);

    map.easeTo({
        center: coords,
        zoom: zoom,
        duration: x``
    });
});
map.off('wheel');
map.on('wheel', (e) => {
    e.preventDefault();
    const delta = e.originalEvent.deltaY > 0 ? -0.5 : 0.5;
    const zoom = map.getZoom() + delta;

    const coords = map.unproject([e.originalEvent.offsetX, e.originalEvent.offsetY]);

    map.easeTo({
        center: coords,
        zoom: zoom,
        duration: 300
    });

    document.getElementById('zoom_count').innerHTML = zoom.toFixed(2);
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

        // Hide ferry routes
        if (layer.id.includes('ferry')) {
            map.setLayoutProperty(layer.id, 'visibility', 'none');
        }

        // Adjust street visibility from 11.00 to 10.60
        if (layer.id.includes('road-street') || layer.id.includes('road-minor') || layer.id.includes('road-label')) {
            map.setLayerZoomRange(layer.id, 10.60, 24);
        }
    });




    const overlay = document.getElementById('preview-overlay');
    overlay.style.display = 'none';

    function findGCD(a, b) {
        if (b === 0) {
            return a;
        } else {
            return findGCD(b, a % b);
        }
    }

    let overlayLocked = false;

    function getResponsiveDPI(wInches, hInches) {
        const mapCanvas = map.getCanvas();
        const padding = 60;
        const maxWidth = mapCanvas.offsetWidth - padding;
        const maxHeight = mapCanvas.offsetHeight - padding;

        const dpiW = maxWidth / wInches;
        const dpiH = maxHeight / hInches;

        return Math.min(dpiW, dpiH, 45);
    }

    function makeWindow() {
        window.gcd = 1; // Default to 1 for text scaling compatibility
        const input_widthInches = parseFloat(document.getElementById('width-inches').value);
        const input_heightInches = parseFloat(document.getElementById('height-inches').value);

        if (!isNaN(input_widthInches) && !isNaN(input_heightInches) && input_widthInches > 0 && input_heightInches > 0) {
            const dpi = getResponsiveDPI(input_widthInches, input_heightInches);
            const widthPixels = input_widthInches * dpi;
            const heightPixels = input_heightInches * dpi;

            const halfWidth = widthPixels / 2;
            const halfHeight = heightPixels / 2;

            const overlayContainer = document.getElementById('overlay-container');

            overlay.style.display = 'block';
            overlay.style.width = widthPixels + 'px';
            overlay.style.height = heightPixels + 'px';

            const overlayTop = 'calc(50% - ' + halfHeight + 'px)';
            const overlayLeft = 'calc(50% - ' + halfWidth + 'px)';

            overlayContainer.style.top = overlayTop;
            overlayContainer.style.left = overlayLeft;
            overlayContainer.style.width = widthPixels + 'px';
            overlayContainer.style.height = heightPixels + 'px';

            document.getElementById('overlay-width').textContent = 'Width: ' + input_widthInches + ' inches';
            document.getElementById('overlay-height').textContent = 'Height: ' + input_heightInches + ' inches';
        } else {
            alert('Please enter valid width and height in inches.');
        }
    }

    makeWindow();

    document.getElementById('preview-button').addEventListener('click', () => {
        makeWindow();
    });

    document.getElementById('dpi-slider').addEventListener('input', () => {
        makeWindow();
    });
});

// map.addControl(new MapboxExportControl({
//     PageSize: Size.A3,
//     PageOrientation: PageOrientation.Portrait,
//     Format: Format.PNG,
//     DPI: DPI[96],
//     Crosshair: true,
//     PrintableArea: true,
// }), 'top-right');

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
    customMarkerElement.addEventListener('dblclick', (e) => {
        e.stopPropagation();
    });
    const iconElement = document.createElement('i');
    iconElement.className = iconClass;

    customMarkerElement.appendChild(iconElement);

    customMarkerElement.style.color = 'white';
    if (iconClass == "fa fa-heart") {
        customMarkerElement.style.color = 'red';
    }



    iconElement.addEventListener('click', () => {

    });

    function updateMarkerSize() {
        if (sizeState === 'small') {
            customMarkerElement.style.width = '50px';
            customMarkerElement.style.height = '50px';
        } else {
            customMarkerElement.style.width = '35px';
            customMarkerElement.style.height = '35px';
        }
    }

    return new mapboxgl.Marker({
        element: customMarkerElement,
        draggable: true
    });
}


// Track all icon markers for download/export
const iconMarkers = [];

// Compass marker (single instance, toggle behavior)
const compassimage = '/public/assets/front/images/compass.png';
const CompassMaker = new mapboxgl.Marker({
    element: document.createElement('img'),
    draggable: true
}).setLngLat([map.getCenter().lng, map.getCenter().lat]);
CompassMaker.getElement().src = compassimage;
CompassMaker.getElement().style.width = '120px';
CompassMaker.getElement().style.height = '120px';
CompassMaker.getElement().style.objectFit = 'contain';
CompassMaker.getElement().classList.add('custom-marker');
CompassMaker.getElement().onerror = function () { this.style.display = 'none'; console.error('Compass image not found at: ' + compassimage); };

/**
 * Add a new icon marker at the current map center each time the button is clicked.
 * Right-click (contextmenu) on the marker to remove it.
 */
function addIconMarker(iconClass) {
    const center = map.getCenter();
    const newMarker = createCustomMarker(iconClass).setLngLat([center.lng, center.lat]);
    newMarker.addTo(map);
    iconMarkers.push(newMarker);

    // Right-click to delete this marker
    const el = newMarker.getElement();
    el.addEventListener('contextmenu', function (e) {
        e.preventDefault();
        e.stopPropagation();
        newMarker.remove();
        const idx = iconMarkers.indexOf(newMarker);
        if (idx > -1) iconMarkers.splice(idx, 1);
    });
}

// Each click adds a NEW marker to the map
document.getElementById('marker-toggle').addEventListener('click', () => addIconMarker('fa fa-heart'));
document.getElementById('house-toggle').addEventListener('click', () => addIconMarker('fa fa-home'));
document.getElementById('star-toggle').addEventListener('click', () => addIconMarker('fa fa-star'));

// Compass stays as a single toggle
document.getElementById('compasss-add').addEventListener('click', () => {
    const center = map.getCenter();
    CompassMaker.setLngLat([center.lng, center.lat]);
    if (CompassMaker._isVisible) {
        CompassMaker.remove();
        CompassMaker._isVisible = false;
    } else {
        CompassMaker.addTo(map);
        CompassMaker._isVisible = true;
    }
});

// ===== LOGO UPLOAD & PLACEHOLDER =====
let uploadedLogoFilename = null;
let logoPlaceholderMarker = null;

// Handle file input change
document.getElementById('logo-file-input').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;

    // Validate size (5MB)
    if (file.size > 5 * 1024 * 1024) {
        alert('Logo file must be under 5MB.');
        this.value = '';
        return;
    }

    // Show local preview immediately
    const reader = new FileReader();
    reader.onload = function (e) {
        document.getElementById('logo-preview-thumb').src = e.target.result;
        document.getElementById('logo-preview-name').textContent = file.name;
        document.getElementById('logo-dropzone').style.display = 'none';
        document.getElementById('logo-preview-area').style.display = 'block';
    };
    reader.readAsDataURL(file);

    // Upload to server
    const formData = new FormData();
    formData.append('logo', file);

    fetch('/upload-logo', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                uploadedLogoFilename = data.filename;
                addLogoPlaceholder(data.url);
            } else {
                alert('Upload failed: ' + (data.message || 'Unknown error'));
                resetLogoUI();
            }
        })
        .catch(err => {
            console.error('Logo upload error:', err);
            alert('Failed to upload logo. Please try again.');
            resetLogoUI();
        });
});

// Remove logo button
document.getElementById('logo-remove-btn').addEventListener('click', function () {
    removeLogoPlaceholder();
    resetLogoUI();
});

function resetLogoUI() {
    uploadedLogoFilename = null;
    document.getElementById('logo-file-input').value = '';
    document.getElementById('logo-dropzone').style.display = 'flex';
    document.getElementById('logo-preview-area').style.display = 'none';
    document.getElementById('logo-preview-thumb').src = '';
}

function removeLogoPlaceholder() {
    if (logoPlaceholderMarker) {
        logoPlaceholderMarker.remove();
        logoPlaceholderMarker = null;
    }
}

function addLogoPlaceholder(logoUrl) {
    removeLogoPlaceholder();


    const el = document.createElement('div');
    el.className = 'logo-placeholder-marker custom-marker';
    el.style.width = '140px';
    el.style.height = '100px';
    el.style.background = 'rgba(255,255,255,0.85)';
    el.style.border = '2px dashed rgba(77,148,197,0.8)';
    el.style.borderRadius = '6px';
    el.style.padding = '4px';
    el.style.overflow = 'hidden';
    el.style.boxSizing = 'border-box';
    el.style.zIndex = '9999';

    const logoImg = document.createElement('img');
    logoImg.alt = 'Business Logo';
    logoImg.style.width = '100%';
    logoImg.style.height = '100%';
    logoImg.style.objectFit = 'contain';
    logoImg.style.display = 'block';
    logoImg.style.pointerEvents = 'none';
    logoImg.style.userSelect = 'none';
    logoImg.draggable = false;

    logoImg.onload = function () {
        const natW = logoImg.naturalWidth;
        const natH = logoImg.naturalHeight;
        if (natW && natH) {
            const ratio = natW / natH;
            const containerW = 140;
            el.style.width = containerW + 'px';
            el.style.height = Math.round(containerW / ratio) + 'px';
        }
    };
    logoImg.src = logoUrl;
    el.appendChild(logoImg);

    const resizeHandle = document.createElement('div');
    resizeHandle.className = 'logo-placeholder-resize';
    resizeHandle.innerHTML = '⤡';
    el.appendChild(resizeHandle);

    const deleteBtn = document.createElement('button');
    deleteBtn.className = 'logo-placeholder-delete';
    deleteBtn.innerHTML = '×';
    deleteBtn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        removeLogoPlaceholder();
        resetLogoUI();
    });
    el.appendChild(deleteBtn);

    el.addEventListener('click', function (e) {
        e.stopPropagation();
        el.classList.toggle('selected');
    });

    document.getElementById('map').addEventListener('click', function () {
        el.classList.remove('selected');
    });

    el.addEventListener('dblclick', function (e) { e.stopPropagation(); });

    let isResizing = false, startX, startY, startW, startH, aspectRatio;

    function beginResize(clientX, clientY) {
        isResizing = true;
        startX = clientX;
        startY = clientY;
        startW = el.offsetWidth;
        startH = el.offsetHeight;
        aspectRatio = startW / (startH || 1);
        document.body.style.cursor = 'se-resize';
        el.classList.add('selected');
    }

    function doResize(clientX, clientY) {
        if (!isResizing) return;
        const dx = clientX - startX;
        const newW = Math.max(40, startW + dx);
        const newH = newW / aspectRatio;
        el.style.width = newW + 'px';
        el.style.height = newH + 'px';
    }

    function endResize() {
        isResizing = false;
        document.body.style.cursor = '';
    }

    resizeHandle.addEventListener('mousedown', function (e) {
        e.preventDefault();
        e.stopPropagation();
        beginResize(e.clientX, e.clientY);

        function onMove(ev) { ev.preventDefault(); doResize(ev.clientX, ev.clientY); }
        function onUp() { endResize(); document.removeEventListener('mousemove', onMove); document.removeEventListener('mouseup', onUp); }
        document.addEventListener('mousemove', onMove);
        document.addEventListener('mouseup', onUp);
    });

    resizeHandle.addEventListener('touchstart', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var t = e.touches[0];
        beginResize(t.clientX, t.clientY);

        function onTouchMove(ev) { ev.preventDefault(); var t2 = ev.touches[0]; doResize(t2.clientX, t2.clientY); }
        function onTouchEnd() { endResize(); document.removeEventListener('touchmove', onTouchMove); document.removeEventListener('touchend', onTouchEnd); }
        document.addEventListener('touchmove', onTouchMove, { passive: false });
        document.addEventListener('touchend', onTouchEnd);
    });

    // Create Mapbox marker at map center
    const center = map.getCenter();
    logoPlaceholderMarker = new mapboxgl.Marker({
        element: el,
        draggable: true
    }).setLngLat([center.lng, center.lat]).addTo(map);
}

/**
 * Get logo placeholder position data for saving.
 * Returns null if no logo, otherwise returns a JSON string with lng, lat, width, height.
 */
function getLogoPositionData() {
    if (!logoPlaceholderMarker || !uploadedLogoFilename) return null;
    const lngLat = logoPlaceholderMarker.getLngLat();
    const el = logoPlaceholderMarker.getElement();
    return JSON.stringify({
        lng: lngLat.lng,
        lat: lngLat.lat,
        width: el.offsetWidth,
        height: el.offsetHeight
    });
}


function createCustomMarker1(iconClass, title, font) {
    const customMarkerElement = document.createElement('div');
    customMarkerElement.className = 'custom-marker';

    customMarkerElement.style.color = 'white';
    customMarkerElement.style.height = 'auto';
    var selectedFont = document.getElementById('font-select').value;
    var fontSize = document.getElementById('font-size').value;

    let font1 = font;
    if (title) {
        const wrapper = document.createElement('div');
        wrapper.style.position = 'relative';
        wrapper.style.display = 'inline-block';
        wrapper.style.minWidth = '60px';
        wrapper.style.minHeight = '24px';

        const titleElement = document.createElement('p');
        titleElement.textContent = title;
        titleElement.style.fontFamily = selectedFont;
        titleElement.style.fontSize = ((font1 * fontSize) / gcd) + 'px';
        titleElement.classList.add('dragp');
        titleElement.style.position = 'relative';
        titleElement.style.display = 'inline-block';
        titleElement.style.minWidth = '50px';
        titleElement.style.minHeight = '20px';
        titleElement.style.padding = '5px';
        titleElement.style.border = '1px dashed transparent';
        titleElement.style.overflow = 'visible';
        titleElement.style.height = 'auto';
        titleElement.style.cursor = 'grab';
        titleElement.style.userSelect = 'none';
        titleElement.style.transition = 'border-color 0.15s ease';
        titleElement.style.whiteSpace = 'nowrap';
        const newHeight = titleElement.scrollHeight;
        titleElement.style.height = newHeight + 'px';
        wrapper.appendChild(titleElement);

        const resizeHandle = document.createElement('div');
        resizeHandle.innerHTML = '⤡';
        resizeHandle.style.cssText = 'width:20px;height:20px;position:absolute;right:-4px;bottom:-4px;cursor:se-resize;z-index:20;border-radius:4px;background:rgba(59,130,246,0.9);color:#fff;font-size:12px;display:none;align-items:center;justify-content:center;line-height:1;box-shadow:0 1px 4px rgba(0,0,0,0.3);touch-action:none;';
        wrapper.appendChild(resizeHandle);

        const toolbar = document.createElement('div');
        toolbar.style.cssText = 'position:absolute;top:-36px;left:50%;transform:translateX(-50%);display:none;align-items:center;gap:4px;background:#1a1a2e;border-radius:8px;padding:4px 6px;box-shadow:0 4px 15px rgba(0,0,0,0.3);z-index:30;white-space:nowrap;';

        const btnMinus = document.createElement('button');
        btnMinus.innerHTML = '<span style="font-size:14px;font-weight:700;">A−</span>';
        btnMinus.style.cssText = 'width:28px;height:28px;border:none;background:rgba(255,255,255,0.1);color:#fff;border-radius:5px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background 0.15s;';
        btnMinus.title = 'Decrease font size';
        btnMinus.addEventListener('mouseenter', function () { this.style.background = 'rgba(255,255,255,0.2)'; });
        btnMinus.addEventListener('mouseleave', function () { this.style.background = 'rgba(255,255,255,0.1)'; });
        btnMinus.addEventListener('click', function (e) {
            e.preventDefault(); e.stopPropagation();
            var cur = parseFloat(window.getComputedStyle(titleElement).fontSize);
            titleElement.style.fontSize = Math.max(8, cur - 2) + 'px';
        });

        const btnPlus = document.createElement('button');
        btnPlus.innerHTML = '<span style="font-size:14px;font-weight:700;">A+</span>';
        btnPlus.style.cssText = 'width:28px;height:28px;border:none;background:rgba(255,255,255,0.1);color:#fff;border-radius:5px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background 0.15s;';
        btnPlus.title = 'Increase font size';
        btnPlus.addEventListener('mouseenter', function () { this.style.background = 'rgba(255,255,255,0.2)'; });
        btnPlus.addEventListener('mouseleave', function () { this.style.background = 'rgba(255,255,255,0.1)'; });
        btnPlus.addEventListener('click', function (e) {
            e.preventDefault(); e.stopPropagation();
            var cur = parseFloat(window.getComputedStyle(titleElement).fontSize);
            titleElement.style.fontSize = Math.min(120, cur + 2) + 'px';
        });

        const btnDelete = document.createElement('button');
        btnDelete.innerHTML = '🗑';
        btnDelete.style.cssText = 'width:28px;height:28px;border:none;background:rgba(239,68,68,0.2);color:#ff6b6b;border-radius:5px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:13px;transition:background 0.15s;margin-left:2px;';
        btnDelete.title = 'Delete text';
        btnDelete.addEventListener('mouseenter', function () { this.style.background = 'rgba(239,68,68,0.4)'; });
        btnDelete.addEventListener('mouseleave', function () { this.style.background = 'rgba(239,68,68,0.2)'; });
        btnDelete.addEventListener('click', function (e) {
            e.preventDefault(); e.stopPropagation();
            text_marker.remove();
            const addedTitles = document.getElementById('added-titles');
            if (addedTitles.contains(customMarkerElement)) {
                addedTitles.removeChild(customMarkerElement);
            }
        });

        toolbar.appendChild(btnMinus);
        toolbar.appendChild(btnPlus);
        toolbar.appendChild(btnDelete);
        wrapper.appendChild(toolbar);

        let isSelected = false;

        function selectText() {
            isSelected = true;
            titleElement.style.border = '1px dashed rgba(59,130,246,0.7)';
            resizeHandle.style.display = 'flex';
            toolbar.style.display = 'flex';
        }

        function deselectText() {
            isSelected = false;
            titleElement.style.border = '1px dashed transparent';
            resizeHandle.style.display = 'none';
            toolbar.style.display = 'none';
        }

        wrapper.addEventListener('click', function (e) {
            e.stopPropagation();
            if (!isSelected) {
                selectText();
            }
        });

        wrapper.addEventListener('mouseenter', function () {
            if (!isSelected) {
                titleElement.style.border = '1px dashed rgba(255,255,255,0.3)';
                resizeHandle.style.display = 'flex';
            }
        });
        wrapper.addEventListener('mouseleave', function () {
            if (!isSelected) {
                titleElement.style.border = '1px dashed transparent';
                resizeHandle.style.display = 'none';
            }
        });

        document.getElementById('map').addEventListener('click', function () {
            deselectText();
        });

        let initialFontSize, initialWidth, initialHeight, isResizing = false;

        function startResize(clientX, clientY) {
            isResizing = true;
            const cs = window.getComputedStyle(titleElement);
            initialFontSize = parseFloat(cs.fontSize);
            initialWidth = titleElement.offsetWidth;
            initialHeight = titleElement.offsetHeight;
            titleElement.style.border = '1px dashed rgba(59,130,246,0.7)';
            document.body.style.cursor = 'se-resize';
            resizeHandle._startX = clientX;
            resizeHandle._startY = clientY;
        }

        function doResize(clientX, clientY) {
            if (!isResizing) return;
            const deltaX = clientX - resizeHandle._startX;
            const newWidth = Math.max(50, Math.min(900, initialWidth + deltaX));
            const scale = newWidth / initialWidth;

            titleElement.style.width = newWidth + 'px';
            titleElement.style.height = 'auto';
            titleElement.style.fontSize = Math.max(8, initialFontSize * scale) + 'px';
        }

        function endResize() {
            if (!isResizing) return;
            isResizing = false;
            document.body.style.cursor = '';
        }

        resizeHandle.addEventListener('mousedown', function (e) {
            e.preventDefault(); e.stopPropagation();
            startResize(e.clientX, e.clientY);

            function onMove(ev) { ev.preventDefault(); doResize(ev.clientX, ev.clientY); }
            function onUp() { endResize(); document.removeEventListener('mousemove', onMove); document.removeEventListener('mouseup', onUp); }
            document.addEventListener('mousemove', onMove);
            document.addEventListener('mouseup', onUp);
        });

        resizeHandle.addEventListener('touchstart', function (e) {
            e.preventDefault(); e.stopPropagation();
            var t = e.touches[0];
            startResize(t.clientX, t.clientY);

            function onTouchMove(ev) { ev.preventDefault(); var t2 = ev.touches[0]; doResize(t2.clientX, t2.clientY); }
            function onTouchEnd() { endResize(); document.removeEventListener('touchmove', onTouchMove); document.removeEventListener('touchend', onTouchEnd); }
            document.addEventListener('touchmove', onTouchMove, { passive: false });
            document.addEventListener('touchend', onTouchEnd);
        });

        customMarkerElement.appendChild(wrapper);

        // Hidden delete button for backwards compatibility
        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'Delete';
        deleteButton.style.display = 'none';
        customMarkerElement.appendChild(deleteButton);
    }
    return new mapboxgl.Marker({
        element: customMarkerElement,
        draggable: true
    });
}



let text_marker;
var title_item = [];

document.getElementById('add-title').addEventListener('click', () => {
    const title = document.getElementById('title-input').value;
    window.count = title_item.push(title);
    const font = document.getElementById('title-font').value;
    if (title) {
        const mapCenter = map.getCenter();

        text_marker = createCustomMarker1('fa fa-heart', title, font).setLngLat([mapCenter.lng, mapCenter.lat]).addTo(map);
    }
});

function changeFont() {
    var selectedFont = document.getElementById('font-select').value;
    document.querySelectorAll('.dragp').forEach(p => {
        p.style.fontFamily = selectedFont;
    });
}
window.changeFont = changeFont;



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

        const imageLoadPromises = [];

        const customMarkers = document.querySelectorAll('.custom-marker');
        console.log('Found custom markers:', customMarkers.length);

        customMarkers.forEach((marker, index) => {
            const markerRect = marker.getBoundingClientRect();
            const markerX = markerRect.left - mapRect.left - mapX;
            const markerY = markerRect.top - mapRect.top - mapY;

            console.log(`Marker ${index}: X=${markerX}, Y=${markerY}, Width=${markerRect.width}, Height=${markerRect.height}, TagName=${marker.tagName}`);

            let imgMarker = marker.querySelector('img');
            if (!imgMarker && marker.tagName === 'IMG') {
                imgMarker = marker;
            }

            if (imgMarker) {
                console.log('Found img marker:', imgMarker.src, 'Size:', markerRect.width, 'x', markerRect.height);
                const promise = new Promise((resolve) => {
                    const img = new Image();
                    img.crossOrigin = 'anonymous';
                    img.src = imgMarker.src;

                    const timeout = setTimeout(() => {
                        console.warn('Image load timeout for:', imgMarker.src);
                        resolve();
                    }, 5000);

                    img.onload = function () {
                        clearTimeout(timeout);
                        console.log('Image loaded, drawing at', markerX, markerY, 'size:', markerRect.width, 'x', markerRect.height);
                        ctx.drawImage(img, markerX, markerY, markerRect.width, markerRect.height);
                        resolve();
                    };
                    img.onerror = function (error) {
                        clearTimeout(timeout);
                        console.error("Error loading image:", imgMarker.src, error);
                        resolve();
                    };
                });
                imageLoadPromises.push(promise);
            } else {
                const textMarker = marker.querySelector('p');
                if (textMarker) {
                    console.log('Found text marker:', textMarker.textContent);
                    const textRect = textMarker.getBoundingClientRect();
                    const textX = textRect.left - mapRect.left - mapX;
                    const textY = textRect.top - mapRect.top - mapY;

                    ctx.font = window.getComputedStyle(textMarker).getPropertyValue('font');
                    ctx.fillStyle = window.getComputedStyle(textMarker).getPropertyValue('color');

                    // Draw text at proper position
                    ctx.textAlign = 'left';
                    ctx.textBaseline = 'top';
                    ctx.fillText(textMarker.textContent, textX, textY);
                } else {
                    const iconMarker = marker.querySelector('i');
                    if (iconMarker) {
                        console.log('Found icon marker at', markerX, markerY, 'size:', markerRect.width, 'x', markerRect.height, 'class:', iconMarker.className);

                        if (typeof html2canvas !== 'undefined') {
                            const promise = new Promise((resolve) => {
                                html2canvas(marker, {
                                    backgroundColor: null,
                                    scale: 1,
                                    useCORS: true
                                }).then(markerCanvas => {
                                    ctx.drawImage(markerCanvas, markerX, markerY);
                                    console.log('html2canvas rendered icon marker');
                                    resolve();
                                }).catch(err => {
                                    console.error('html2canvas error:', err);
                                    resolve();
                                });
                            });
                            imageLoadPromises.push(promise);
                        } else {
                            const centerX = markerX + (markerRect.width / 2);
                            const centerY = markerY + (markerRect.height / 2);
                            const color = window.getComputedStyle(iconMarker).getPropertyValue('color') || 'white';

                            // Draw background circle
                            ctx.fillStyle = color;
                            ctx.beginPath();
                            const radius = Math.min(markerRect.width, markerRect.height) / 2;
                            ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
                            ctx.fill();

                            // Draw icon text
                            ctx.fillStyle = 'white';
                            ctx.font = `bold ${radius}px Arial`;
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';

                            // Map Font Awesome classes to Unicode symbols
                            let symbol = '●';
                            if (iconMarker.className.includes('fa-heart')) {
                                symbol = '♥';
                            } else if (iconMarker.className.includes('fa-home')) {
                                symbol = '⌂';
                            } else if (iconMarker.className.includes('fa-star')) {
                                symbol = '★';
                            }

                            ctx.fillText(symbol, centerX, centerY);
                            console.log('Rendered icon marker with symbol:', symbol);
                        }
                    }
                }
            }
        });

        console.log('Image promises count:', imageLoadPromises.length);

        // Wait for all images to load before saving
        Promise.all(imageLoadPromises).then(() => {
            console.log('All images loaded, saving canvas');
            const dataURL = captureCanvas.toDataURL('image/png', 1.0);
            const input_widthInches = parseFloat(document.getElementById('width-inches').value);
            const input_heightInches = parseFloat(document.getElementById('height-inches').value);
            const final_count = window.count;

            const compass = CompassMaker._isVisible ? true : false;
            const addons = document.getElementById('add-on').checked;
            const frameValue = document.getElementById('frame_style') ? document.getElementById('frame_style').value : 'none';

            // Show loading overlay
            var loadingEl = document.getElementById('save-loading');
            if (loadingEl) loadingEl.style.display = 'flex';

            fetch('/save-image', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({
                    image: dataURL,
                    width: input_widthInches,
                    height: input_heightInches,
                    text: final_count,
                    compass: compass,
                    addons: addons,
                    frame_style: frameValue,
                    logo_filename: uploadedLogoFilename || null,
                    logo_position: getLogoPositionData(),
                    lat: map.getCenter().lat,
                    lng: map.getCenter().lng
                })
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Image saved successfully', data);
                    // Hide loading overlay
                    var loadingEl = document.getElementById('save-loading');
                    if (loadingEl) loadingEl.style.display = 'none';
                    if (data.map_id) {
                        window.location.href = '/map-detail?id=' + data.map_id;
                    }
                })
                .catch(error => {
                    console.error('Error saving image:', error);
                    // Hide loading overlay on error too
                    var loadingEl = document.getElementById('save-loading');
                    if (loadingEl) loadingEl.style.display = 'none';
                    alert('Error saving map. Please try again.');
                });
        });
    } else {
        alert('Overlay container has invalid dimensions.');
    }
});



$(".toggle_menu").on("click", function (event) {
    $("#menu").toggleClass("active");
    // On mobile, update toggle button position
    if (window.innerWidth <= 768) {
        var btn = document.querySelector('.toggle_menu');
        if ($("#menu").hasClass("active")) {
            btn.style.bottom = '12px';
        } else {
            btn.style.bottom = '';
        }
    }
});


document.getElementById('zoom-in').addEventListener('click', () => {
    map.zoomIn();
});

document.getElementById('zoom-out').addEventListener('click', () => {
    map.zoomOut();
});

// Frame selection and sidebars are now handled on map-detail page