@include('front.inc.header')
<style>
    body {
        background: #f8f9fa;
        color: #333;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .detail-wrapper {
        max-width: 1100px;
        margin: 50px auto;
        padding: 0 20px;
    }
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 30px;
    }
    @media (max-width: 900px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
    .map-preview-card {
        background: #fdfdfd;
        border-radius: 12px;
        padding: 80px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
        display: flex;
        justify-content: center;
        align-items: center;
        position: sticky;
        top: 20px;
        min-height: 500px;
        border: 1px solid #eee;
    }
    .map-image-container {
        position: relative;
        width: 100%;
        max-width: 480px;
        transition: all 0.3s ease;
        z-index: 1;
    }
    .map-image-container img {
        width: 100%;
        height: auto;
        display: block;
        box-shadow: 0 15px 45px rgba(0,0,0,0.1);
    }
    
    /* Clean Color Border Frame Overlay */
    .frame-active-overlay {
        position: absolute;
        top: -30px;
        left: -30px;
        right: -30px;
        bottom: -30px;
        z-index: 10;
        pointer-events: none;
        display: none; /* Shown via JS */
        border: 30px solid #333; /* Color set via JS */
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    }

    .frame-disclaimer {
        background: #fff8e1;
        border: 1px solid #ffe082;
        border-radius: 8px;
        padding: 12px;
        margin-top: 15px;
        font-size: 0.75rem;
        color: #795548;
        line-height: 1.4;
    }
    .frame-disclaimer i {
        color: #ffa000;
        margin-right: 5px;
    }

    .order-summary-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        border: 1px solid #f0f0f0;
    }
    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 20px;
        color: #333;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 2px solid #f8f9fa;
        padding-bottom: 12px;
    }
    .section-title i {
        color: #4d94c5;
    }
    .detail-list {
        margin-bottom: 25px;
    }
    .detail-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f8f9fa;
        color: #666;
        font-size: 0.95rem;
    }
    .total-price-box {
        background: #fdfdfd;
        border: 1px solid #f0f0f0;
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .total-label {
        font-weight: 600;
        font-size: 1rem;
    }
    .total-amount {
        font-size: 1.6rem;
        font-weight: 700;
        color: #4d94c5;
    }

    .frame-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-top: 15px;
    }
    .frame-item {
        border: 2px solid #eee;
        border-radius: 8px;
        padding: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        background: #fff;
    }
    .frame-item:hover {
        border-color: #ddd;
        background: #fcfcfc;
    }
    .frame-item.active {
        border-color: #4d94c5;
        background: #f0f8ff;
    }
    .frame-thumb {
        width: 100%;
        height: 60px;
        background-size: cover;
        background-position: center;
        border-radius: 4px;
        margin-bottom: 8px;
        background-color: #f8f9fa;
        border: 1px solid #eee;
    }
    .frame-name {
        font-size: 0.8rem;
        font-weight: 600;
        color: #444;
    }
    .btn-checkout {
        display: block;
        width: 100%;
        background: #4d94c5;
        color: white;
        text-align: center;
        padding: 15px;
        border-radius: 30px;
        font-weight: 700;
        margin-top: 30px;
        text-decoration: none;
        transition: all 0.2s;
        box-shadow: 0 4px 6px rgba(77, 148, 197, 0.2);
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
    }
    .btn-checkout:hover {
        background: #3887be;
        transform: translateY(-1px);
        color: white;
        box-shadow: 0 6px 12px rgba(77, 148, 197, 0.3);
    }
    
    .loading-overlay {
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.7);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        backdrop-filter: blur(2px);
    }
    .spinner {
        width: 35px;
        height: 35px;
        border: 3px solid #eee;
        border-top-color: #4d94c5;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

<div class="detail-wrapper">
    <div class="detail-grid">
        <!-- Map Preview -->
        <div class="map-preview-card">
            <div class="map-image-container" id="mapCombinedPreview">
                <img src="{{url('public/storage/images/maps/' . $map->map_image)}}" id="mainMapImage" alt="Your Map">
                <div id="frameEffectOverlay" class="frame-active-overlay"></div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="sidebar-info">
            <div class="order-summary-card">
                <h2 class="section-title"><i class="fas fa-file-invoice"></i> Order Overview</h2>
                
                <div class="detail-list">
                    <div class="detail-item">
                        <span>Dimensions</span>
                        <strong>{{$map->map_width}}" × {{$map->map_height}}"</strong>
                    </div>
                    <div class="detail-item">
                        <span>Base Price</span>
                        <span>${{number_format($map->map_base_cost, 2)}}</span>
                    </div>
                    @if($map->map_addon_cost > 0)
                    <div class="detail-item">
                        <span>Add-ons</span>
                        <span>${{number_format($map->map_addon_cost, 2)}}</span>
                    </div>
                    @endif
                    <div id="frameCostRow" style="{{$map->map_frame == 'none' ? 'display:none' : ''}}">
                        <div class="detail-item">
                            <span>Frame Customization</span>
                            <span id="displayFrameCost">$0.00</span>
                        </div>
                    </div>
                </div>

                <div class="total-price-box">
                    <span class="total-label">Total Amount:</span>
                    <span class="total-amount" id="displayTotalPrice">${{number_format($map->map_price, 2)}}</span>
                </div>

                <div style="margin-top: 30px;">
                    <h3 class="section-title" style="font-size: 1rem;"><i class="fas fa-border-all"></i> Selection of Frame</h3>
                    <div class="frame-grid">
                        <div class="frame-item {{($map->map_frame == 'none' || empty($map->map_frame)) ? 'active' : ''}}" data-frame="none">
                            <div class="frame-thumb" style="display: flex; align-items: center; justify-content: center; background: #fafafa;">
                                <i class="fas fa-ban" style="color: #ccc; font-size: 1.5rem;"></i>
                            </div>
                            <div class="frame-name">No Frame</div>
                        </div>
                        
                        @foreach($frames as $frame)
                        @php
                            $frameName = str_replace(' background removed.png', '', $frame);
                            $isActive = ($map->map_frame == $frame);
                        @endphp
                        <div class="frame-item {{$isActive ? 'active' : ''}}" data-frame="{{$frame}}">
                            <div class="frame-thumb" style="background-image: url('{{url('public/frames/' . $frame)}}');"></div>
                            <div class="frame-name">{{$frameName}}</div>
                        </div>
                        @endforeach
                    </div>

                    <div class="frame-disclaimer">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Disclaimer:</strong> The frame preview on the map is for color reference only. Your physical frame will be constructed with the high-quality material shown in the sample thumbnails above.
                    </div>
                </div>

                <a href="{{url('checkout?id=' . $map->map_id)}}" class="btn-checkout">
                    Complete Order <i class="fas fa-arrow-right" style="margin-left: 8px; font-size: 0.8rem;"></i>
                </a>
                
                <p style="text-align: center; font-size: 0.75rem; color: #999; margin-top: 20px;">
                    <i class="fas fa-shield-alt"></i> Payments are secure and encrypted
                </p>
                <div style="text-align: center; margin-top: 15px;">
                    <a href="{{url('create-map')}}" style="color: #666; text-decoration: none; font-size: 0.85rem; font-weight: 500;">
                        <i class="fas fa-edit"></i> Edit Map Design
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="loading-overlay" id="loadingOverlay">
    <div class="spinner"></div>
</div>

<script src="{{asset('assets/front/js/jquery-3.6.3.min.js')}}"></script>
<script>
    $(document).ready(function() {
        const mapId = "{{$map->map_id}}";
        const frameOverlay = $('#frameEffectOverlay');
        const mapImage = $('#mainMapImage');
        
        // Color mapping for frame types to fill the sides
        const frameColorMap = {
            'C22': '#1a0f0a',  // Dark Ebony
            'E1': '#8b7355',   // Antique Bronze
            'E17': '#d2b48c',  // Natural Wood
            'H1': '#bf9b30',   // Ornate Gold
            'H2': '#111111',   // Black
            'H9': '#d1d1d1',   // Silver
            'J12': '#3d2b1f',  // Walnut
            'none': 'transparent'
        };

        // Initial frame set
        applyFrameStyles("{{$map->map_frame}}");

        $('.frame-item').click(function() {
            const frameVal = $(this).data('frame');
            
            // UI Update
            $('.frame-item').removeClass('active');
            $(this).addClass('active');
            
            // Visual Preview
            applyFrameStyles(frameVal);
            
            // DB Update
            updateFrameInDB(frameVal);
        });

        function applyFrameStyles(frame) {
            if (frame === 'none') {
                frameOverlay.hide();
                $('#frameCostRow').hide();
            } else {
                // Get the frame prefix for color (e.g. C22)
                const frameKey = frame.split(' ')[0];
                const frameColor = frameColorMap[frameKey] || '#333';

                // Show overlay and apply solid border color (hollow center)
                frameOverlay.show().css({
                    'display': 'block',
                    'border-color': frameColor,
                    'background-color': 'transparent'
                });

                $('#frameCostRow').show();
            }
        }

        function updateFrameInDB(frame) {
            $('#loadingOverlay').css('display', 'flex');
            
            $.ajax({
                url: "{{url('update-map-frame')}}",
                method: "POST",
                data: {
                    _token: "{{csrf_token()}}",
                    map_id: mapId,
                    frame_style: frame
                },
                success: function(response) {
                    $('#displayTotalPrice').text('$' + response.new_price);
                    $('#displayFrameCost').text('$' + response.frame_cost);
                    $('#loadingOverlay').hide();
                },
                error: function() {
                    alert('Failed to update frame. Please try again.');
                    $('#loadingOverlay').hide();
                }
            });
        }
    });
</script>
@include('front.inc.footer')
