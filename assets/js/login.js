/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
//import '../css/login.scss';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';
// import $ from 'boostrap';

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

require('./aos');
require('./circleaudioplayer');
require('./main');
require('./mediaelement-and-player.min');
require('./owl.carousel.min');
require('./player');
require('./slick.min');

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