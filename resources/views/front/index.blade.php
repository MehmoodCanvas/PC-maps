@include('front.inc.header')

<div class="top_db_btn">
    <a href="{{url('dashboard')}}">Dashboard</a>
</div>
<div id="map">
    <div id="zoom-level-display">Zoom Level: <span id='zoom_count'>4</span></div>
    <div id="map-container">
        <div id="overlay-container">
            <div id="preview-overlay">
                <div id="overlay-width" class="overlay-width">Width: </div>
                <div id="overlay-height" class="overlay-height">Height: </div>
            </div>
        </div>
    </div>
</div>
<nav id="menu">
    <button class="toggle_menu"><i class="fa-solid fa-bars"></i></button>
    <div class="scroll_container">

        <div class="hi_box">
            <h1 class="heading">1. FIND YOUR LOCATION</h1>
            <input id="location-input" type="text" placeholder="Search for a location or postal code">
            <div id="location-suggestions" class="suggestions"></div>
            <div class="form-group">
                <input id="latitude" type="text" placeholder="Latitude">
                <input id="longitude" type="text" placeholder="Longitude">
            </div>
            <div class="form-group">
                <button id="fly">Fly to Location</button>

            </div>

        </div>
        <div class="form-group form-group-center">
            <div class="form-group_f">
                <div class="form-group_n mb-2">
                    <button id="zoom-in"><i class="fa-solid fa-plus"></i> Zoom In</button>
                    <button id="zoom-out"><i class="fa-solid fa-minus"></i> Zoom Out</button>
                </div>
            </div>
            <button id="north-direction" type="button" class="toggle_btn_1">
                <i class="fa-solid fa-compass"></i> North
            </button>
        </div>
        {{-- STEP 2: CHOOSE MAP SIZE --}}
        <div class="hi_box">
            <h1 class="heading">2. CHOOSE MAP SIZE</h1>
            <div class="form-group">
                <input id="width-inches" value="12" type="number" placeholder="Width (inches)" min="5" max="84">
                <input id="height-inches" value="6" type="number" placeholder="Height (inches)" min="5" max="84">
            </div>
            <div class="form-group">
                <button id="preview-button">Preview Size</button>
            </div>
            <p class="size-hint">Tip: Sizes from 5" to 84" supported</p>

        </div>

        {{-- STEP 3: ADD TEXT --}}
        <div class="hi_box">
            <h1 class="heading">3. ADD TEXT</h1>
            <input type="text" id="title-input" placeholder="Enter your text (e.g. The Parsons)">
            <div class="form-group gap_5">
                <select id="font-select" onchange="changeFont()">
                    <option value="CopperplateDefault">Copperplate</option>
                    <option value="Arial, sans-serif">Arial</option>
                    <option value="'Times New Roman', serif">Times New Roman</option>
                    <option value="Georgia, serif">Georgia</option>
                    <option value="'EB Garamond', serif">Garamond</option>
                    <option value="'Playfair Display', serif">Playfair Display</option>
                    <option value="'Montserrat', sans-serif">Montserrat</option>
                    <option value="'Raleway', sans-serif">Raleway</option>
                    <option value="'Merriweather', serif">Merriweather</option>
                    <option value="'Lato', sans-serif">Lato</option>
                    <option value="'Roboto', sans-serif">Roboto</option>
                    <option value="'Poppins', sans-serif">Poppins</option>
                </select>
            </div>
            <input type="hidden" id="title-font" value="1">
            <input type="hidden" id="font-size" value="16">
            <button id="add-title">Add Text to Map</button>
            <p class="size-hint">After adding, drag to position &amp; resize with corner handle</p>
        </div>
        <div id="added-titles"></div>

        <div class="hi_box">
            <h1 class="heading">4. ADD EMBELLISHMENTS</h1>
            <div class="embellishment-grid">
                <a href="#!" id="compasss-add" class="embellishment-btn" title="Add Compass Rose">
                    <i class="fa-solid fa-compass"></i>
                    <span>Compass</span>
                </a>
                <a href="#!" id="marker-toggle" class="embellishment-btn" title="Add Heart Marker">
                    <i class="fa-solid fa-heart"></i>
                    <span>Heart</span>
                </a>
                <a href="#!" id="house-toggle" class="embellishment-btn" title="Add House Marker">
                    <i class="fa-solid fa-house"></i>
                    <span>House</span>
                </a>
                <a href="#!" id="star-toggle" class="embellishment-btn" title="Add Star Marker">
                    <i class="fa-solid fa-star"></i>
                    <span>Star</span>
                </a>
            </div>
            <p class="size-hint">Click multiple times to add more. Right-click a marker to remove it.</p>

            {{-- LOGO UPLOAD --}}
            <div class="logo-upload-section">
                <h2 class="logo-upload-title"><i class="fa-solid fa-image"></i> Business Logo</h2>
                <label for="logo-file-input" class="logo-upload-dropzone" id="logo-dropzone">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <span>Click to upload logo</span>
                    <small>PNG, JPG, SVG, WebP — Max 5MB</small>
                </label>
                <input type="file" id="logo-file-input" accept="image/png,image/jpeg,image/svg+xml,image/webp"
                    style="display:none;">
                <div id="logo-preview-area" style="display:none;">
                    <div class="logo-preview-card">
                        <img id="logo-preview-thumb" src="" alt="Logo preview">
                        <div class="logo-preview-info">
                            <span id="logo-preview-name">logo.png</span>
                            <button id="logo-remove-btn" type="button" title="Remove logo">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <p class="size-hint">Drag the placeholder box on the map to position your logo</p>
                </div>
            </div>
        </div>

        {{-- Hidden frame_style defaults to none here, will be chosen on detail page --}}
        <input type="hidden" id="frame_style" name="frame_style" value="none">

        {{-- Hidden checkboxes for JS compatibility --}}
        <input type="checkbox" name="add_on" id="add-on" style="display:none;">
        <input type="checkbox" name="" id="wooden-on" style="display:none;">

        {{-- STEP 6: SAVE --}}
        <div class="hi_box hi_box_save">
            <h1 class="heading">6. SAVE &amp; GET PRICE</h1>
            <a id="downloadLink" href="#" class="save-map-btn">
                <i class="fa-solid fa-floppy-disk"></i> Save Map &amp; View Price
            </a>
        </div>

        <textarea name="" id="addon-text" cols="30" rows="10" style="display:none;"></textarea>

        {{-- LOADING OVERLAY --}}
        <div id="save-loading" class="save-loading-overlay" style="display:none;">
            <div class="save-loading-content">
                <div class="save-loading-spinner"></div>
                <p>Saving your map...</p>
            </div>
        </div>

    </div>
</nav>

{{-- PRICE CONFIRMATION MODAL --}}
<div id="price-modal" class="price-modal-overlay" style="display:none;">
    <div class="price-modal">
        <button id="pm-close-btn" class="price-modal-close">&times;</button>
        <h2><i class="fa-solid fa-map-location-dot"></i> Your Map Quote</h2>
        <div class="price-modal-preview" id="pm-preview-img"></div>
        <div class="price-modal-details">
            <div class="price-modal-row">
                <span>Map Dimensions</span>
                <span><span id="pm-width">-</span> × <span id="pm-height">-</span></span>
            </div>
            <div class="price-modal-row">
                <span>Frame Style</span>
                <span id="pm-frame">No Frame</span>
            </div>
            <div class="price-modal-row price-modal-total">
                <span>Total Price</span>
                <span id="pm-price">$0.00</span>
            </div>
        </div>
        <div class="price-modal-actions">
            <a id="pm-checkout-btn" href="#" class="pm-btn pm-btn-primary">
                <i class="fa-solid fa-cart-shopping"></i> Proceed to Payment
            </a>
            <button id="pm-decline-btn" class="pm-btn pm-btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> No Thank You
            </button>
        </div>
    </div>
</div>

@include('front.inc.footer')
<script>
    $(window).resize(function () {
        var windowHeight = $(window).height();
        if (windowHeight < 630) {
            $('#menu').addClass('active');
        } else {
            $('#menu').removeClass('active');
        }
    });
</script>
</body>

</html>