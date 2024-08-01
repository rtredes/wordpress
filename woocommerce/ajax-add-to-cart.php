<?php

add_shortcode('grobrix_add_to_cart', function () {
    if (!is_user_logged_in()) {
        return false;
    }

    $id = get_the_ID();
    $product = wc_get_product($id);
    $product_cart_id = WC()->cart->generate_cart_id($id);
    $cart = WC()->cart->get_cart();
    $cart_item_key = WC()->cart->find_product_in_cart($product_cart_id);
    $quantity = 0;
    if (isset($cart[$cart_item_key])) {
        $quantity = $cart[$cart_item_key]['quantity'];
    }

    // Get the stock quantity
    $stock_quantity = $product->get_stock_quantity();

    if ($stock_quantity === null && !$product->is_sold_individually()) {
        $stock_quantity = 20;
    } else if (empty($stock_quantity) || $stock_quantity < 0) {
        $stock_quantity = 0;
    } else if ($product->is_sold_individually()) {
        $stock_quantity = 1;
    }

    ob_start();
?>
    <div class="custom-add-to-cart-wrap">
        <form action="" class="quantity-form">
            <div class="input-wrapper">
                <button class="minus">-</button>
                <input type="hidden" value="<?= $id ?>" name="product_id">
                <input data-sold-individually="<?= $product->is_sold_individually() ?>" type="number" name="quantity" value="<?= $quantity ?>" min="0" max="<?= $stock_quantity ?>" title="Quantity">
                <button class="plus">+</button>
            </div>
        </form>
        <button class="custom-add-to-cart-button">Add to Cart</button>
    </div>
<?php

    return ob_get_clean();
});

add_action('wp_head', function () { ?>
    <style>
        .custom-add-to-cart-wrap.active .custom-add-to-cart-button {
            display: none;
        }

        .custom-add-to-cart-wrap:not(.active) .quantity-form {
            display: none;
        }

        .input-wrapper {
            display: flex;
            padding: 0.25rem 5px;
            border: 1px solid #437148;
            border-radius: 9999px;
            align-items: center;
        }

        .input-wrapper input {
            flex: 10;
            border: initial;
            background: initial;
            outline: initial;
            text-align: center;
        }

        .input-wrapper :is(.plus, .minus) {
            border: initial;
            background: initial;
            font-size: 20px;
            color: #437148;
            cursor: pointer;
            height: 100%;
            line-height: 1em;
        }

        .input-wrapper :is(.plus:active, .minus:active) {
            transform: scale(1.25);
            transform-origin: center center;
        }

        .input-wrapper input::-webkit-outer-spin-button,
        .input-wrapper input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        .input-wrapper input[type=number] {
            -moz-appearance: textfield;
        }

        .custom-add-to-cart-button {
            background: #193C14;
            padding: 5px 15px;
            border-radius: 9999px;
            color: white;
            box-shadow: 0 0 1px 1px #193C14 inset;
            border: none;
            font-size: 2rem;
            font-weight: bold;
            width: 100%;
            transition: .3s ease;
        }

        .custom-add-to-cart-wrap {
            max-width: 200px;
        }
    </style>
<?php
});

add_action('wp_footer', function () { ?>
    <script>
        function initializeAddToCarts() {
            const wrappers = document.querySelectorAll('.custom-add-to-cart-wrap')
            wrappers.forEach(wrap => {
                const form = wrap.querySelector('.quantity-form')
                const input = form.querySelector('input[name=quantity]')
                const minus = form.querySelector('.minus')
                const plus = form.querySelector('.plus')

                const add_to_cart = wrap.querySelector('.custom-add-to-cart-button')

                function handleSubmit() {
                    let quantity = Number(input.value)
                    let productID = form.querySelector('[name=product_id]').value
                    jQuery(function($) {
                        $.ajax({
                            type: 'POST',
                            url: woocommerce_params.ajax_url,
                            data: {
                                'action': 'custom_add_to_cart',
                                'product_id': productID,
                                'quantity': quantity
                            },
                            beforeSend: function() {},
                            success: function(response) {},
                            error: function(xhr, textStatus, error) {}
                        });
                    })
                }

                function userHasInput(e) {
                    e.preventDefault()
                    handleSubmit()
                    return false
                }

                function checkValidity() {
                    let value = Number(input.value)

                }

                function add() {
                    let value = Number(input.value)
                    input.value = value + 1

                    if (value >= input.max) {
                        input.value = input.max
                        if (input.dataset.soldIndividually == '1' && input.max != 0) input.setCustomValidity('This product is sold individually')
                        else if (input.max == 0) input.setCustomValidity('This product is out of stock')
                    } else {
                        input.setCustomValidity('')
                    }
                }

                function min() {
                    let value = Number(input.value)
                    input.value = value - 1
                    if (input.value <= 0) wrap.classList.remove('active')
                    input.setCustomValidity('')
                }

                minus.onclick = min
                plus.onclick = add
                input.onfocus = (e) => input.blur()

                add_to_cart.onclick = () => {
                    wrap.classList.add('active')
                    add()
                    handleSubmit()
                }

                form.onsubmit = userHasInput
            })
        }

        document.addEventListener("DOMContentLoaded", function() {
            initializeAddToCarts()

            // Select the target element to watch
            const targetElement = document.querySelector('#shop-loop-grid');
            if (!targetElement) return false

            // Create a new MutationObserver instance
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    // Check if new nodes were added
                    if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                        initializeAddToCarts()
                    }
                });
            });

            // Start observing the target element
            observer.observe(targetElement, config = {
                childList: true
            });
        })
    </script>
<?php });


add_action('wp_ajax_custom_add_to_cart', 'custom_add_to_cart');
add_action('wp_ajax_nopriv_custom_add_to_cart', 'custom_add_to_cart');
function custom_add_to_cart()
{
    if (isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

        // Check if the item is already in the cart
        $product_cart_id = WC()->cart->generate_cart_id($product_id);

        $cart_item_key = WC()->cart->find_product_in_cart($product_cart_id);
        echo var_dump($cart_item_key);
        if ($cart_item_key) {
            // Item is already in the cart, update the quantity
            if ($quantity > 0) {
                WC()->cart->set_quantity($cart_item_key, $quantity);

                $message = 'Product quantity updated in the cart.';
                $response = array('success' => true, 'data' => array('message' => $message));
            } else {
                WC()->cart->remove_cart_item($cart_item_key);
                $message = 'Product removed from the cart.';
                $response = array('success' => true, 'data' => array('message' => $message));
            }
        } else {
            // Item is not in the cart, add it
            if ($quantity > 0) {
                $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity);
                if ($cart_item_key) {
                    $message = 'Product added to cart successfully.';
                    $response = array('success' => true, 'data' => array('message' => $message));
                } else {
                    $message = 'Unable to add the product to the cart.';
                    $response = array('success' => false, 'data' => array('message' => $message));
                }
            } else {
                $message = 'Invalid quantity.';
                $response = array('success' => false, 'data' => array('message' => $message));
            }
        }

        wp_send_json($response);
    }
}