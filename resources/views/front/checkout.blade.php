<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{asset('assets/front/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/front/css/all.css')}}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <link
            rel="stylesheet"
            type="text/css"
            href="https://www.paypalobjects.com/webstatic/en_US/developer/docs/css/cardfields.css"
        />
        <title>Checkout - PC Maps</title>
        <style>
            body {
                background: #f8f9fa;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            .checkout-wrapper {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }
            .checkout-header {
                background: white;
                padding: 20px 0;
                border-bottom: 1px solid #e0e0e0;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            }
            .checkout-header-logo img {
                height: 50px;
            }
            .checkout-container {
                flex: 1;
                padding: 40px 20px;
            }
            .checkout-content {
                max-width: 1200px;
                margin: 0 auto;
            }
            .checkout-title {
                text-align: center;
                margin-bottom: 40px;
                color: #333;
            }
            .checkout-title h1 {
                font-size: 32px;
                font-weight: 600;
                margin-bottom: 10px;
            }
            .order-summary {
                background: white;
                border-radius: 10px;
                padding: 30px;
                margin-bottom: 30px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            }
            .order-summary-title {
                font-size: 20px;
                font-weight: 600;
                margin-bottom: 20px;
                border-bottom: 2px solid #f0f0f0;
                padding-bottom: 15px;
            }
            .map-preview {
                display: flex;
                gap: 20px;
                margin-bottom: 20px;
            }
            .map-image {
                flex: 0 0 200px;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            }
            .map-image img {
                width: 100%;
                height: 200px;
                object-fit: cover;
            }
            .map-details {
                flex: 1;
            }
            .detail-row {
                display: flex;
                justify-content: space-between;
                padding: 12px 0;
                border-bottom: 1px solid #f0f0f0;
            }
            .detail-row:last-child {
                border-bottom: none;
            }
            .detail-label {
                font-weight: 500;
                color: #666;
            }
            .detail-value {
                font-weight: 600;
                color: #333;
            }
            .price-total {
                background: #f9f9f9;
                padding: 15px;
                border-radius: 8px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 15px;
            }
            .price-total .label {
                font-size: 18px;
                font-weight: 600;
                color: #333;
            }
            .price-total .amount {
                font-size: 28px;
                font-weight: 700;
                color: #0066cc;
            }
            .payment-methods {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 30px;
                margin-top: 30px;
            }
            @media (max-width: 768px) {
                .payment-methods {
                    grid-template-columns: 1fr;
                }
                .map-preview {
                    flex-direction: column;
                }
                .map-image {
                    flex: 0 0 auto;
                }
            }
            .payment-section {
                background: white;
                border-radius: 10px;
                padding: 30px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            }
            .payment-section-title {
                font-size: 18px;
                font-weight: 600;
                margin-bottom: 20px;
                color: #333;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .payment-section-title i {
                color: #0066cc;
                font-size: 22px;
            }
            .card_container {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }
            .card_container div {
                margin-bottom: 0;
            }
            .card_container label {
                font-weight: 500;
                color: #333;
                margin-bottom: 8px;
                display: block;
            }
            .card_container input[type="text"],
            .card_container input[type="tel"],
            .card_container input[type="email"] {
                width: 100%;
                padding: 12px;
                border: 1px solid #ddd;
                border-radius: 6px;
                font-size: 14px;
                transition: border-color 0.3s;
            }
            .card_container input[type="text"]:focus,
            .card_container input[type="tel"]:focus,
            .card_container input[type="email"]:focus {
                outline: none;
                border-color: #0066cc;
                box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
            }
            .card_container input:disabled {
                background: #f5f5f5;
                cursor: not-allowed;
            }
            #card-field-submit-button {
                background: #0066cc;
                color: white;
                padding: 12px 30px;
                border: none;
                border-radius: 6px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: background 0.3s;
                width: 100%;
                margin-top: 20px;
            }
            #card-field-submit-button:hover {
                background: #0052a3;
            }
            .paypal-button-container {
                margin-bottom: 20px;
            }
            #result-message {
                margin-top: 20px;
                padding: 15px;
                border-radius: 6px;
                display: none;
            }
            #result-message.success {
                background: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
                display: block;
            }
            #result-message.error {
                background: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
                display: block;
            }
            .checkout-footer {
                background: white;
                padding: 20px 0;
                border-top: 1px solid #e0e0e0;
                text-align: center;
                color: #666;
                font-size: 14px;
            }
        </style>
        <script
            src="https://www.paypal.com/sdk/js?client-id=AcfyQW_yPIfAZNZ_9Y-E_LtG-Xjh-nRZjRcCt0PbLlth56dgTON7RkDVrnkN3G2jt6fzK2f-rAHa0qvM&buyer-country=US&currency=USD&components=buttons,card-fields&enable-funding=venmo"
        ></script>
    </head>
    <body>
        <div class="checkout-wrapper">
            <header class="checkout-header">
                <div class="container">
                    <div class="checkout-header-logo">
                        <a href="{{url('/')}}">
                            <img src="{{asset('assets/front/images/logo.png')}}" alt="PC Maps Logo">
                        </a>
                    </div>
                </div>
            </header>

            <div class="checkout-container">
                <div class="checkout-content">
                    <div class="checkout-title">
                        <h1>Order Checkout</h1>
                        <p>Complete your purchase securely</p>
                    </div>

                    <!-- Order Summary -->
                    <div class="order-summary">
                        <div class="order-summary-title">
                            <i class="fas fa-map"></i> Order Summary
                        </div>
                        
                        <div class="map-preview">
                            <div class="map-image">
                                <img src="{{$maps->map_data}}" alt="Map Preview">
                            </div>
                            <div class="map-details">
                                <div class="detail-row">
                                    <span class="detail-label">Map ID:</span>
                                    <span class="detail-value">#{{$maps->map_id}}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Dimensions:</span>
                                    <span class="detail-value">{{$maps->map_width}}" × {{$maps->map_height}}"</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Type:</span>
                                    <span class="detail-value">Custom Map Print</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Format:</span>
                                    <span class="detail-value">High Resolution PNG</span>
                                </div>
                                <div class="price-total">
                                    <span class="label">Order Total:</span>
                                    <span class="amount">${{$maps->map_price}}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="payment-methods">
                        <!-- PayPal Section -->
                        <div class="payment-section">
                            <div class="payment-section-title">
                                <i class="fab fa-paypal"></i> PayPal
                            </div>
                            <div id="paypal-button-container" class="paypal-button-container"></div>
                            <p style="text-align: center; color: #999; font-size: 12px; margin-top: 10px;">Secure payment powered by PayPal</p>
                        </div>

                        <!-- Card Payment Section -->
                        <div class="payment-section">
                            <div class="payment-section-title">
                                <i class="fas fa-credit-card"></i> Credit Card
                            </div>
                            <div id="card-form" class="card_container">
                                <div id="card-name-field-container"></div>
                                <div id="card-number-field-container"></div>
                                <div id="card-expiry-field-container"></div>
                                <div id="card-cvv-field-container"></div>

                                <div>
                                    <label for="card-billing-address-line-1">Address Line 1</label>
                                    <input
                                        type="text"
                                        id="card-billing-address-line-1"
                                        name="card-billing-address-line-1"
                                        autocomplete="off"
                                        placeholder="Street address"
                                    />
                                </div>
                                <div>
                                    <label for="card-billing-address-line-2">Address Line 2</label>
                                    <input
                                        type="text"
                                        id="card-billing-address-line-2"
                                        name="card-billing-address-line-2"
                                        autocomplete="off"
                                        placeholder="Apartment, suite, etc. (optional)"
                                    />
                                </div>
                                <div>
                                    <label for="card-billing-address-admin-area-line-1">City</label>
                                    <input
                                        type="text"
                                        id="card-billing-address-admin-area-line-1"
                                        name="card-billing-address-admin-area-line-1"
                                        autocomplete="off"
                                        placeholder="City"
                                    />
                                </div>
                                <div>
                                    <label for="card-billing-address-admin-area-line-2">State</label>
                                    <input
                                        type="text"
                                        id="card-billing-address-admin-area-line-2"
                                        name="card-billing-address-admin-area-line-2"
                                        autocomplete="off"
                                        placeholder="State"
                                    />
                                </div>
                                <div>
                                    <label for="card-billing-address-country-code">Country</label>
                                    <input
                                        type="text"
                                        id="card-billing-address-country-code"
                                        name="card-billing-address-country-code"
                                        autocomplete="off"
                                        placeholder="Country code"
                                        value="US"
                                        disabled
                                    />
                                </div>
                                <div>
                                    <label for="card-billing-address-postal-code">Zip/Postal Code</label>
                                    <input
                                        type="text"
                                        id="card-billing-address-postal-code"
                                        name="card-billing-address-postal-code"
                                        autocomplete="off"
                                        placeholder="Postal/zip code"
                                    />
                                </div>
                                <button id="card-field-submit-button" type="button">
                                    <i class="fas fa-lock"></i> Pay with Card
                                </button>
                            </div>
                            <p style="text-align: center; color: #999; font-size: 12px; margin-top: 10px;">Secure payment processing</p>
                        </div>
                    </div>

                    <div id="result-message"></div>
                </div>
            </div>

            <footer class="checkout-footer">
                <div class="container">
                    <p>&copy; 2026 PC Maps. All rights reserved. | <a href="#" style="color: #666; text-decoration: none;">Privacy Policy</a> | <a href="#" style="color: #666; text-decoration: none;">Terms of Service</a></p>
                </div>
            </footer>
        </div>

        <script src="{{asset('assets/front/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('assets/front/js/jquery-3.6.3.min.js')}}"></script>
        <script>
    paypal
    .Buttons({
        createOrder: createOrderCallback,
        onApprove: onApproveCallback,
        onError: function (error) {
            // Do something with the error from the SDK
        },

        style: {
            shape: "rect",
            layout: "vertical",
            color: "gold",
            label: "paypal",
        },
        message: {
            amount: {{$maps->map_price}},
        } ,
    })
    .render("#paypal-button-container"); 

const cardField = window.paypal.CardFields({
    createOrder: createOrderCallback,
    onApprove: onApproveCallback,
    style: {
        input: {
            "font-size": "16px",
            "font-family": "courier, monospace",
            "font-weight": "lighter",
            color: "#ccc",
        },
        ".invalid": { color: "purple" },
    },
});

if (cardField.isEligible()) {
    const nameField = cardField.NameField({
        style: { input: { color: "blue" }, ".invalid": { color: "purple" } },
    });
    nameField.render("#card-name-field-container");

    const numberField = cardField.NumberField({
        style: { input: { color: "blue" } },
    });
    numberField.render("#card-number-field-container");

    const cvvField = cardField.CVVField({
        style: { input: { color: "blue" } },
    });
    cvvField.render("#card-cvv-field-container");

    const expiryField = cardField.ExpiryField({
        style: { input: { color: "blue" } },
    });
    expiryField.render("#card-expiry-field-container");

    document
        .getElementById("card-field-submit-button")
        .addEventListener("click", () => {
            cardField
                .submit({
                    billingAddress: {
                        addressLine1: document.getElementById(
                            "card-billing-address-line-1"
                        ).value,
                        addressLine2: document.getElementById(
                            "card-billing-address-line-2"
                        ).value,
                        adminArea1: document.getElementById(
                            "card-billing-address-admin-area-line-1"
                        ).value,
                        adminArea2: document.getElementById(
                            "card-billing-address-admin-area-line-2"
                        ).value,
                        countryCode: document.getElementById(
                            "card-billing-address-country-code"
                        ).value,
                        postalCode: document.getElementById(
                            "card-billing-address-postal-code"
                        ).value,
                    },
                    
                })
                .then(() => {
                });
        });
}
async function createOrderCallback() {
    try {
        const response = await fetch("{{ url('post-create-order') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",  
            },
            body: JSON.stringify({
                cart: [
                    {
                        id: "{{ $_GET['id'] }}",
                        quantity: 1,
             
                    },
                ],
            }),
        });

        const orderData = await response.json();

        if (orderData.id) {
            return orderData.id;
        } else {
            throw new Error("Order creation failed");
        }
    } catch (error) {
        console.error(error);
    }
}

async function onApproveCallback(data) {
    try {
       var order_address_one=document.getElementById("card-billing-address-line-1").value;
       var order_address_two=document.getElementById("card-billing-address-line-2").value;
       var order_zip_code= document.getElementById("card-billing-address-postal-code").value;
        const response = await fetch(`{{ url('/orders') }}/${data.orderID}/capture`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}", 
            },
            body: JSON.stringify({ "price": {{$maps->map_price}},"callid":{{$maps->map_id}} ,"order_address_one":order_address_one,'order_address_two':order_address_two,"order_zip_code":order_zip_code}), 
        });

        const orderData = await response.json();

        if (orderData.status === 'COMPLETED') {
            alert("Transaction completed successfully");
        } else {
            alert("Transaction failed");
        }
    } catch (error) {
        console.error(error);
    }
}
function resultMessage(message) {
    const container = document.querySelector("#result-message");
    container.innerHTML = message;
}
</script>