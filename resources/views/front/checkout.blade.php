<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link
            rel="stylesheet"
            type="text/css"
            href="https://www.paypalobjects.com/webstatic/en_US/developer/docs/css/cardfields.css"
        />
        <title>Checkout</title>
        <script
            src="https://www.paypal.com/sdk/js?client-id=AcfyQW_yPIfAZNZ_9Y-E_LtG-Xjh-nRZjRcCt0PbLlth56dgTON7RkDVrnkN3G2jt6fzK2f-rAHa0qvM&buyer-country=US&currency=USD&components=buttons,card-fields&enable-funding=venmo"
        ></script>
    </head>
    <body>
        <div id="paypal-button-container" class="paypal-button-container"></div>
        <div id="card-form" class="card_container">
            <div id="card-name-field-container"></div>
            <div id="card-number-field-container"></div>
            <div id="card-expiry-field-container"></div>
            <div id="card-cvv-field-container"></div>

            
            <div>
                <label for="card-billing-address-line-1">Billing Address</label>
                <input
                    type="text"
                    id="card-billing-address-line-1"
                    name="card-billing-address-line-1"
                    autocomplete="off"
                    placeholder="Address line 1"
                />
            </div>
            <div>
                <input
                    type="text"
                    id="card-billing-address-line-2"
                    name="card-billing-address-line-2"
                    autocomplete="off"
                    placeholder="Address line 2"
                />
            </div>
            <div>
                <input
                    type="text"
                    id="card-billing-address-admin-area-line-1"
                    name="card-billing-address-admin-area-line-1"
                    autocomplete="off"
                    placeholder="Admin area line 1"
                />
            </div>
            <div>
                <input
                    type="text"
                    id="card-billing-address-admin-area-line-2"
                    name="card-billing-address-admin-area-line-2"
                    autocomplete="off"
                    placeholder="Admin area line 2"
                />
            </div>
            <div>
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
                <input
                    type="text"
                    id="card-billing-address-postal-code"
                    name="card-billing-address-postal-code"
                    autocomplete="off"
                    placeholder="Postal/zip code"
                />
            </div>
            <br /><br />
            <button id="card-field-submit-button" type="button">
                Pay now with Card
            </button>
        </div>
        <p id="result-message"></p>
        <script src="app.js"></script>
    </body>
</html>
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

            console.table(billingAddress);
        const response = await fetch(`{{ url('/orders') }}/${data.orderID}/capture`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}", 
            },
            body: JSON.stringify({ "price": {{$maps->map_price}},"callid":{{$maps->map_id}} }), 
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