/* ========================================================================
* OKIA: Globals
* ======================================================================== */

jQuery(function($) {

    // Global toggler
    $('.js-toggle-next').on('click', function() {
        $(this).toggleClass('open');
    });

    $('.js-toggle-mobile-subnav').on('click', function() {
        $(this).parent().toggleClass('open');
    });

    // Swipebox
    //
    $('.gallery-thumb__url').swipebox({
        hideBarsDelay: 6000
    });

    //
    //
    $('[data-toggle="tooltip"]').tooltip();
});
