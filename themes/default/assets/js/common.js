/**
 * Common functions
 */

/**
 * Hide flash messages block
 */
$(document).ready(function() {
    $(".flash_messages .close").click(function(){
        $(".flash_messages").fadeOut();
    });
});

/**
 * Update cart data after product added.
 */
function reloadSmallCart()
{
    $("#cart").load('/cart/renderSmallCart');
}

/**
 * Add product to cart from list
 * @param data
 * @param textStatus
 * @param jqXHR
 * @param redirect
 */
function processCartResponseFromList(data, textStatus, jqXHR, redirect)
{
    var productErrors = $('#productErrors');
    if(data.errors)
    {
        window.location = redirect
    }else{
        reloadSmallCart();
        $('html, body').animate({scrollTop:0}, 'slow');
        $("#cart").fadeOut().fadeIn().fadeOut().fadeIn();
    }
}

function applyCategorySorter(el)
{
    window.location = $(el).val();
}