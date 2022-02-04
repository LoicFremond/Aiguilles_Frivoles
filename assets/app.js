/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';


const $ = require('jquery');

require('bootstrap');
require('select2');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

$(document).ready(() => {
    $('[data-toggle="popover"]').popover();
    $('select').select2();
    $('.toast').toast({delay: 2500});
    $('.toast').toast('show');
});
