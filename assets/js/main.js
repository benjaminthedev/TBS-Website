/*
 * Mobile Nav
 */

console.log('Updated JS');

$('#mobile_nav').find('select').change(function (e) {
    var val = $(this).val();

    if (val)
        window.location.href = val;
});

/**
 * Cookies
 */

function createCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}



/*
 * Offer Bar
 */

var offer_bar = $('#offer');

offer_bar.find('.close_btn').click(function () {
    offer_bar.slideUp();
    $('body').removeClass('offer_open');
    $('#mobile_nav').attr('style', '');

    createCookie('hideOffer', true, 7);

});

$(document).ready(function () {
    if (!readCookie('hideOffer')) {
        $('#offer').show();
    }
});

/*
 * Search Form
 */

var search_form = $('#search_form');

search_form.find('input').on({
    "focus": function () {
        search_form.addClass('focus');
    },
    "blur": function () {
        search_form.removeClass('focus');
    }
}
);


/*
 * Flex Slider Inits
 */

$('.slider').flexslider({
    directionNav: false,
    start: function (slider) {
        slider.addClass('flex_loaded');
    }
});

$('.product_ads').flexslider({
    touch: false,
    controlNav: false,
    directionNav: false,
    pauseOnAction: false,
    start: function (slider) {
        slider.addClass('flex_loaded');
    }
}
);

$('.product_image_slider').flexslider({
    touch: false,
    controlNav: false,
    directionNav: false,
    pauseOnAction: false,
    slideshow: false,
    start: function (slider) {
        slider.addClass('flex_loaded');
    }
}
);


/*
 * Product Tabs
 */

var product_tabs = $('#product_tabs');

var tab_body = product_tabs.find('.owls');

var tab_control = product_tabs.find('.controls a');

var sliding = false;

tab_body.flexslider({
    directionNav: false,
    controlNav: false,
    slideshow: false,
    keyboard: false,
    touch: false, animationLoop: false,
    after: function () {
        sliding = false;
    },
    start: function (slider) {
        slider.addClass('flex_loaded');
    }
});

tab_control.click(function (e) {
    e.preventDefault();
    if (!sliding) {
        var tab = $(this).data('tab');
        tab_body.flexslider(tab);
        $(this).addClass('active');
        tab_control.not(this).removeClass('active');
        sliding = true;
    }
});

$('.product_owl').each(function () {
    $(this).owlCarousel({
        loop: false,
        rewind: true,
        margin: 30,
        nav: false,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        navText: ['<img src="' + img_url + '/prev.png" alt="">', '<img src="' + img_url + '/next.png" alt="">'],
        responsive: {
            0: {
                items: 1
            },
            420: {
                items: 2
            },
            768: {
                items: 3,
                nav: true
            },
            1200: {
                items: 4,
                nav: true
            }
        }
    });
});

/*
 * Product Images
 */

$('.gallery_owl').owlCarousel({
    loop: true,
    margin: 0,
    nav: true,
    navText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'],
    responsive: {
        0: {
            items: 2
        },
        380: {
            items: 3
        },
        1200: {
            mouseDrag: false,
        }
    }
});


$(document).on('click', '.gallery_owl .item', function () {
    var image = $(this).data('image');
    var slide = $(this).data('slider');
    $('#' + slide).flexslider(image);
});

/*
 * Reload on added to cart
 */

$(document.body).on('added_to_cart', function () {
    location.reload();
});

/*
 * Contact form 7 Form
 */

$('.wpcf7').submit(function () {
    setTimeout(function () {
        $('div.wpcf7-response-output').slideUp();
    }, 5000)
});

/*
 * Qty Buttons
 */


$('.quantity').on('click', '.plus', function (e) {


    var $input = $(this).parents('.quantity').find('input.qty_input');
    var val = parseInt($input.val());
    var step = $input.attr('step');
    var max = $input.attr('max');
    step = 'undefined' !== typeof (step) ? parseInt(step) : 1;
    if (parseInt(max) === val)
        step = 0;

    $input.val(val + step).change();
});
$('.quantity').on('click', '.minus',
    function (e) {
        var $input = $(this).parents('.quantity').find('input.qty_input');
        var val = parseInt($input.val());
        var step = $input.attr('step');
        var min = $input.attr('min');
        step = 'undefined' !== typeof (step) ? parseInt(step) : 1;
        if (parseInt(min) === val)
            step = 0;
        if (val > 0) {
            $input.val(val - step).change();
        }
    });


/*
 * Count Down
 */

function start_onload(last_hour) {
    var timeout_message = document.getElementById('count_down_text');
    var currentTime = new Date();
    var hours = currentTime.getHours();
    var minutes = currentTime.getMinutes();
    var seconds = currentTime.getSeconds();
    var day = currentTime.getDay();
    var expire_time = 0; // in seconds
    var text = "You're too late for same day shipping!";
    if (hours < last_hour && day !== 6 && day !== 0) {
        expire_time += (last_hour - hours - 1) * 3600;
        expire_time += (59 - minutes) * 60;
        expire_time += (59 - seconds);
    }
    else {
        timeout_message.innerHTML = text;
        return;
    }
    expire_time = currentTime.getTime() + 1000 * expire_time;

    function countdown_session_timeout() {
        var current_time = new Date().getTime();
        var remaining = Math.floor((expire_time - current_time) / 1000);
        if (remaining > 0) {
            hours = Math.floor(remaining / 3600);
            minutes = Math.floor((remaining - hours * 3600) / 60);
            seconds = remaining % 60;
            var hours_suffix = hours === 1 ? 'HR' : 'HRS';
            var minutes_suffix = minutes === 1 ? 'MIN' : 'MINS';
            var seconds_suffix = seconds === 1 ? 'SECOND' : 'SECONDS';
            text = "<span>" + hours + " <small>" + hours_suffix + "</small></span>";
            text = text + "<span>" + minutes + " <small>" + minutes_suffix + "</small></span>";
            text = text + "<span>" + seconds + " <small>" + seconds_suffix + "</small></span>";
            timeout_message.innerHTML = text;
            setTimeout(countdown_session_timeout, 1000);
        } else {
            $('#want-it-by').remove();
            timeout_message.innerHTML = text;
        }
    }

    countdown_session_timeout();
}


if ($('#count_down_text').length)
    start_onload(14);


/*
 * Content Tabs
 */

$('.content_tab header').click(function () {
    var parent = $(this).parent();
    parent.toggleClass('active');
    parent.find('.content').slideToggle();
});


/*
 * Variation Slider
 */

$(document).ready(function () {
    if ($('#pa_colour').length)
        switch_slide_index();
});

$(".single_variation_wrap").on("show_variation", function (event, variation) {
    switch_slide_index();
});

$(".single_variation_wrap").on("hide_variation", function (event, variation) {
    switch_slide_index(0);
});

function switch_slide_index(index) {
    var variationName = $('#pa_colour').val();

    $('.variation_product_slider > .active').removeClass('active').fadeOut(function () {
        var x = 0;

        if (index) {
            x = index;
        }

        $('.variation_product_slider > div').each(function (index) {
            if ($(this).data('variation-name') == variationName) {
                x = index;
            }
        });

        $('.variation_product_slider > div').eq(x).fadeIn().addClass('active');
    });
}

/*
 * Calc Shipping
 */

$(document).on('change', '#calc_shipping_country', function () {
    $('#calc_shipping').trigger('click');
});

/*
 * Addtional Info
 */

$(document).on('input', '#additional_info', function () {

    var val = $(this).val();

    $.ajax({
        url: ajax_url,
        type: 'post',
        dataType: 'json',
        cache: false,
        data: {
            action: 'additional_info',
            val: val
        },
        success: function (response) {
            console.log(response);

        }
    })
});


/*
 * Coupon
 */

$(document.body).on('click', 'a.showcoupon', function (e) {
    e.preventDefault();
    $('.checkout_coupon').slideToggle();
});


/*
 * Checkout Validation
 */

var errors = [],

    // Validation configuration
    conf = {
        onElementValidate: function (valid, $el, $form, errorMess) {
            if (!valid) {
                // gather up the failed validations
                errors.push({ el: $el, error: errorMess });
            }
        }
    },

    // Optional language object
    lang = {};

$.formUtils.loadModules('logic,security');


var checkout_form = $('.woocommerce-checkout');

$('.check_checkout_valid').on('click', function (e) {
    e.preventDefault();
    $('.invalid').removeClass('invalid');
    $('.checkout_form_error_text').each(function () {
        $(this).remove();
    });
    errors = [];
    if (!checkout_form.isValid(lang, conf, false)) {
        $.each(errors, function (key, value) {
            value.el.parent().addClass('invalid').append('<span class="checkout_form_error_text">' + value.error + '</span>');
        });
        $('html, body').animate({
            scrollTop: $(".invalid").first().offset().top
        }, 1000);
    } else {
        $('.woocommerce').addClass('loading');
        $('.your_details').removeClass('active').addClass('done');
        $('.your_payment').addClass('active');
        $('html, body').animate({
            scrollTop: $(".woocommerce").first().offset().top
        }, 1000);
        setTimeout(function () {
            $('.woocommerce').removeClass('loading').addClass('checkout_3');
        }, 2000);

        $('#billing_fields').find(':input').each(function () {
            var field = $(this).attr('name');
            var value = $(this).val();
            if (value && ~this.className.indexOf('select'))
                value = $(this).find('option:selected').text();
            $('#deliver_info_js').find('.' + field).html(value);
        });
        var clear = !$('#ship-to-different-address-checkbox').prop("checked");
        $('#shipping_fields').find(':input').each(function () {
            var field = $(this).attr('name');
            var value = $(this).val();
            if (value && ~this.className.indexOf('select'))
                value = $(this).find('option:selected').text();
            if (clear) {
                value = '';
                $('#deliver_info_js').find('.no_ship').show();
            } else {
                $('#deliver_info_js').find('.no_ship').hide();
            }
            $('#deliver_info_js').find('.' + field).html(value);
        });

    }
});


$('.edit_deliver').click(function (e) {
    $('.your_details').removeClass('done').addClass('active');
    $('.your_payment').removeClass('active');
    $('.woocommerce').addClass('loading');
    $('html, body').animate({
        scrollTop: $(".woocommerce").first().offset().top
    }, 1000);
    setTimeout(function () {
        $('.woocommerce').removeClass('loading checkout_3');
    }, 2000);

});

$('.brands-menu-navigation a').click(function (e) {
    e.preventDefault();
    var tab = $(this).attr('href');
    tab = $(tab);
    $(this).addClass('active');
    $('.brands-menu-navigation a').not(this).removeClass('active');
    tab.show();
    $('.brands-menu-tab').not(tab).hide();

});

$('#mobile_nav .menu-item-has-children > a').click(function (e) {
    e.preventDefault();
    var parent = $(this).parent();
    var submenu = parent.find('> .sub-menu');
    $(this).toggleClass('active');
    submenu.slideToggle();
});

$('.search_expand_toggle').click(function (e) {
    e.preventDefault();
    $('#main_header').find('.middle_header').slideToggle();
    $('#mobile_nav').removeClass('active');
    $('body').removeClass('nav_open');

});

$('.menu_expand_toggle').click(function (e) {
    e.preventDefault();
    $('#offer').slideUp();
    $('#mobile_nav').toggleClass('active');
    $('body').toggleClass('nav_open');
});



$('.readmore').readmore({
    speed: 75,
    moreLink: '<a href="#" class="btn btn-primary">Read more</a>',
    lessLink: '<a href="#" class="btn btn-primary">Read less</a>',
    collapsedHeight: $('#readmore-top').outerHeight()
});


function disableProceedToCheckoutBtn() {
    if ($('#terms').is(':checked')) {

    } else {
        $('.checkout-button').attr('disabled')
    }
}

$(document).ready(function () {
    if ($('#terms').length) {

    }
})