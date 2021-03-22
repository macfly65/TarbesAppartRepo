/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/global.scss';

//import '../css/animate.css';
//import '../css/aoss.css';
//import '../css/app.css';
//import '../css/fl-bigmug-line.css';
//import '../css/magnific-popup.css';
//import '../css/mediaelementplayer.css';
//import '../css/owl.carousel.min.css';
//import '../css/owl.theme.default.min.css';
//import '../css/progressbar.css';
//import '../css/style.css';
//import '../css/_site-base.scss';
//import '../css/_site-blocks.scss';
//import '../css/_site-navbar.scss';
//import '../css/style.scss';



// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';
// import $ from 'boostrap';

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// or you can include specific pieces
require('bootstrap/js/dist/tooltip');
require('bootstrap/js/dist/popover');


function saveNewPositions() {
    var positions = [];
    $('.updated').each(function () {
        positions.push([$(this).attr('data-index'), $(this).attr('data-position')]);
        $(this).removeClass('updated');
    });

    $.ajax({
        url: 'autopromo',
        method: 'POST',
        dataType: 'text',
        data: {
            update: 1,
            positions: positions
        }, success: function (response) {
            $('#notification').show();
            $( "#notification" ).hide(4000);
        }
    });
}
$(".alert").delay(4000).hide("slow");

/*!
    * Start Bootstrap - SB Admin v6.0.0 (https://startbootstrap.com/templates/sb-admin)
    * Copyright 2013-2020 Start Bootstrap
    * Licensed under MIT (https://github.com/BlackrockDigital/startbootstrap-sb-admin/blob/master/LICENSE)
    */
(function($) {
    "use strict";

    // Add active state to sidbar nav links
    var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
    $("#layoutSidenav_nav .sb-sidenav a.nav-link").each(function() {
        if (this.href === path) {
            $(this).addClass("active");
        }
    });

    // Toggle the side navigation
    $("#sidebarToggle").on("click", function(e) {
        e.preventDefault();
        $("body").toggleClass("sb-sidenav-toggled");
    });
})(jQuery);

