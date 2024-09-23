@include('front.inc.header')
        <div id="map">
            <div id="zoom-level-display">Zoom Level: 4</div>
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
                <button id="fly">Fly</button>
                <div id="added-titles"></div>
                <input type="text" id="title-input" placeholder="Enter Title">

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
                    <a id="downloadLink" href="#" >Download ↓</a>
                </div>

            </div>
        </nav>

        @include('front.inc.footer')
    </body>

    </html>