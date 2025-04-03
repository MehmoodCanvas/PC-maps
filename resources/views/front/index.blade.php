@include('front.inc.header')

        <div class="top_db_btn">
            <a href="{{url('dashboard')}}">Dashboard</a>
        </div>
        <div id="map">
            <div id="zoom-level-display">Zoom Level: <span id='zoom_count'>4</span></div>
            <div id="map-container">
                <div id="overlay-container">
                    <div id="preview-overlay">
                        <div id="preview-overlay">
                            <div id="overlay-width" class="overlay-width">Width: </div>
                            <div id="overlay-height" class="overlay-height">Height: </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <nav id="menu">
            <button class="toggle_menu"><i class="fa-solid fa-bars"></i></button>
            <h1 class="heading">CHOOSE LOCATION</h1>


            <input id="location-input" type="text" placeholder="Search for a location or enter a postal code">
            <div class="scroll_container">
                <div id="location-suggestions" class="suggestions"></div>
                <div class="form-group">
                    <input id="latitude" type="text" placeholder="Latitude">
                    <input id="longitude" type="text" placeholder="Longitude">
                </div>

                <div class="form-group">
                  <button id="zoom-in"><i class="fa-solid fa-plus"></i></button>
                  <button id="zoom-out"> <i class="fa-solid fa-minus"></i></button>
                </div>
                <button id="fly">Fly</button>
                <div id="added-titles"></div>
                <input type="text" id="title-input" placeholder="Enter Title (2 Free Rows)">

                <div class="form-group">
                    <select id="title-font">
                        <option value="1">1 inch</option>
                        <option value="0.5">0.5 inch</option>
                    </select>
                    <select id="font-select" onchange="changeFont()">
                        <option value="CopperplateDefault">Copperplate</option>

                    </select>
                </div>


                <button id="add-title">Add Title</button>

                <div class="form-group">
                    <a href="#!" id="marker-toggle" class="toggle_btn_1"><i class="fa-solid fa-heart"></i></a>
                    <a href="#!" id="house-toggle" class="toggle_btn_1"><i class="fa-solid fa-house"></i></a>
                    <a href="#!" id="star-toggle" class="toggle_btn_1"><i class="fa-solid fa-star"></i></a>

                </div>


                <div class="form-group">
                    <input id="width-inches" value="12" type="number" placeholder="Width (inches)">
                    <input id="height-inches" value="6" type="number" placeholder="Height (inches)">
                </div>

                <div class="form-group">
                    <button id="preview-button">Preview</button>
                    <a id="downloadLink" href="#" >Save Map ↓</a>
                </div>
                <div class="form_group_input">
                    <label class="material-checkbox">
                        <input type="checkbox" name="compasss" value="compasss" id="compasss-add">
                        <span class="checkmark"></span>
                            Add Compass ($4.99)
                      </label>
                </div>
                <div class="form_group_input">
                    <label class="material-checkbox">
                        <input type="checkbox" name="add_on" id="add-on">
                        <span class="checkmark"></span>
                        Add On
                      </label>
                </div>
                <div class="form_group_input">
                    <label class="material-checkbox">
                        <input type="checkbox" name="" id="wooden-on">
                        <span class="checkmark"></span>
                        Additional Wooden Frame
                      </label>
                </div>
                <div class="form-group">
                    <textarea name="" id="addon-text" cols="30" rows="10"></textarea>
                </div>
            </div>

        </nav>

        @include('front.inc.footer')
    </body>

    </html>