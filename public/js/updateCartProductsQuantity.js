// cartTools.js
function updateCartCounter() {
    $.ajax({
        url: '/calculate-cart-total-items',
        method: 'GET',
        success: function (data) {
            $('#cart_counter').html(`<h1>${data}</h1>`);
            $('#cart_counter_responsive').html(`<h1>${data}</h1>`);
        },
        error: function (xhr, status, error) {
            console.error('Error fetching cart counter:', error);
        }
    });
}

updateCartCounter();
