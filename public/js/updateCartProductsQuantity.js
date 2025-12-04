// cartTools.js
const popupStyles = `
<style id="cartPopupStyles">
  .cart-popup {
    position: fixed;
    top: 16px;
    right: -380px;
    width: 360px;
    max-width: calc(100% - 32px);
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.25);
    padding: 16px 20px 20px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    transition: right 1s ease, opacity 1s ease, transform 1s ease;
    opacity: 0;
    transform: translateY(-10px);
    z-index: 10000;
  }

  .cart-popup.show {
    right: 16px;
    opacity: 1;
    transform: translateY(0);
  }

  .cart-popup-close {
    position: absolute;
    top: 2px;
    right: 10px;
    font-size: 2rem;
    cursor: pointer;
    color: #6b7280;
    line-height: 1;
  }
  .cart-popup-close:hover {
    color: #111827;
  }

  .cart-popup-header {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 8px;
  }

  .cart-popup-thumb {
    width: 56px;
    height: 56px;
    border-radius: 4px;
    overflow: hidden;
    flex-shrink: 0;
    background: #f3f4f6;
  }

  .cart-popup-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .cart-popup-title {
    font-size: 14px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 2px;
  }

  .cart-popup-line {
    font-size: 13px;
    color: #6b7280;
  }

  .cart-popup-added {
    font-size: 14px;
    font-weight: 700;
    margin-top: 4px;
    color: #111827;
  }

  .cart-popup-total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 12px 0 16px;
    padding-top: 8px;
    border-top: 1px solid #e5e7eb;
    font-size: 14px;
  }

  .cart-popup-total-label {
    color: #374151;
    font-weight: 500;
  }

  .cart-popup-total-amount {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
  }

  .cart-popup-button {
    display: block;
    width: 100%;
    text-align: center;
    background: #1f2933;
    color: #ffffff;
    border-radius: 999px;
    padding: 10px 16px;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .08em;
    border: none;
    cursor: pointer;
  }

  .cart-popup-button:hover {
    filter: brightness(1.05);
  }
</style>
`;


function updateCartTotal(callback) {
    $.ajax({
        type: "GET",
        url: '/cart-info',
        success: function (xhr) {

            const moneyAR = (v) => {
                if (typeof v === 'string' && (v.includes('.') || v.includes(','))) {
                    return v.startsWith('$') ? v : `$${v}`;
                }

                const num = Number(v);
                const nf = new Intl.NumberFormat('es-AR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                return `$${nf.format(num)}`;
            };

            const totalFormatted = moneyAR(xhr.order_total);
            callback(totalFormatted);
        },
        error: function () {
            $('#items-summary-container').html("");
            callback('$0,00');
        }
    });
}


function formatMoneyAR(value) {
    const nf = new Intl.NumberFormat('es-AR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    return `$${nf.format(Number(value) || 0)}`;
}


function loadCartPanelData() {
    $.ajax({
        type: 'GET',
        url: '/cart-info',
        success: function (cart) {
            const products = cart.products || [];

            const itemsHtml = products.map(function (p) {
                // precio unitario aproximado (por las dudas)
                const unitPrice = p.quantity ? (p.subtotal / p.quantity) : p.subtotal;

                return `
                <div class="cart-panel-item">
                    <div class="cart-panel-item-img">
                        <img src="${p.pic}" alt="${p.product_name}">
                    </div>

                    <div class="cart-panel-item-info">
                        <div class="cart-panel-item-name p-0">${p.product_name}</div>

                        <div class="d-flex align-items-center">
                            <div class="color-box"
                                 data-color="${p.color}"
                                 title="${p.color_name}"
                                 style="background:${p.color}; width:18px; height:18px; margin-right:0.5rem;">
                            </div>
                            <span class="small text-muted">${p.color_name}</span>
                        </div>

                        <div class="cart-panel-item-qty">
                            <button type="button" class="cartProductQuantityButton" data-action="minus" data-id="${p.product_variant_id}">−</button>
                            <span>${p.quantity}</span>
                            <button type="button" class="cartProductQuantityButton" data-action="plus" data-id="${p.product_variant_id}">+</button>
                        </div>
                    </div>

                    <div class="cart-panel-item-price">
                        ${formatMoneyAR(unitPrice)}
                    </div>
                </div>`;
            }).join('');

            $('#cartPanelItems').html(itemsHtml);

            const orderTotal = cart.order_total_after_coupon_applied ?? cart.order_total ?? 0;

            $('#cartPanelTotal').text(formatMoneyAR(orderTotal));


            function updateCartProductQuantity(productVariantId, action) {
                axios.put('/carts/products/update-quantity', {
                    productVariantId: productVariantId,
                    action: action
                })
                    .then(function () {
                        loadCartPanelData();
                    })
                    .catch(function (error) {
                        console.error('Error updating quantity:', error);
                    });
            }

            document.querySelectorAll('.cartProductQuantityButton').forEach(function (el) {
                el.addEventListener('click', function (button) {
                    let productVariantId = button.target.getAttribute('data-id')
                    let action = button.target.getAttribute('data-action')
                    updateCartProductQuantity(productVariantId, action);
                })
            })

        },
        error: function () {
            $('#cartPanelItems').html('<p>No se pudo cargar el carrito.</p>');
            $('#cartPanelTotal').text('$0,00');
        }
    });
}

function openCartPanel(){

    $('#cartPanelOverlay').addClass('is-open');
    $('#cartPanel').addClass('is-open');
    loadCartPanelData();

    $('#cartPanelOverlay').on('click', function () {
        $('#cartPanelOverlay').removeClass('is-open');
        $('#cartPanel').removeClass('is-open');
    });

    $('#cartPanelClose').on('click', function () {
        $('#cartPanelOverlay').removeClass('is-open');
        $('#cartPanel').removeClass('is-open');
    });

    $('#cartPopupClose').click()

}

function updateCartCounter(isProductAddedToCart = false) {
    $.ajax({
        url: '/calculate-cart-total-items',
        method: 'GET',
        success: function (data) {

            const lastProduct = data.products[data.products.length - 1];

            updateCartTotal(function (total) {

                const totalItems = data.products.reduce(
                    (sum, product) => sum + Number(product.quantity || 0),
                    0
                );

                    let popupContent = `
                  <div class="cart-popup" id="cartPopup">
                    <span class="cart-popup-close" id="cartPopupClose">&times;</span>

                    <div class="cart-popup-header">
                      <div class="cart-popup-thumb">
                        <img src="${lastProduct.image}" alt="${lastProduct.name}">
                      </div>
                      <div>
                        <div class="cart-popup-title">${lastProduct.name}</div>
                        <div class="cart-popup-line">
                          ${lastProduct.quantity} x ${lastProduct.price_formatted ?? ''}
                        </div>
                        <div class="cart-popup-added">¡Agregado al carrito!</div>
                      </div>
                    </div>

                    <div class="cart-popup-total-row">
                      <span class="cart-popup-total-label">
                        Total (${totalItems} productos):
                      </span>
                      <span class="cart-popup-total-amount" id="cartTotal">
                        ${total}
                      </span>
                    </div>

                    <button class="cart-popup-button" onclick="openCartPanel()">
                      Ver carrito
                    </button>
                  </div>
                `;


            $('#cart_counter').html(`<h1>${totalItems}</h1>`);
            $('#cart_counter_responsive').html(`<h1>${totalItems}</h1>`);

            if(isProductAddedToCart){
                if (!document.querySelector('#cartPopupStyles')) {
                    $('head').append($(popupStyles).attr('id', 'cartPopupStyles'));
                }

                if ($('#cartPopup').length) {
                    $('#cartPopup').remove();
                }

                $('body').append(popupContent);

                $('#cartPopup').addClass('show');

                $('body').on('click', '#cartPopupClose', function () {
                    $('#cartPopup').removeClass('show');
                    setTimeout(() => $('#cartPopup').remove(), 300);
                });
            }


        })
    }
})
    }

updateCartCounter();
