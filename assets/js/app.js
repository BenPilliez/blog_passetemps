/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "../css/app.scss";
import $ from "jquery";
import "bootstrap";
import "@fortawesome/fontawesome-free/css/all.min.css";
import "@fortawesome/fontawesome-free/js/all.js";
import "./modernizr.custom";
import "./toucheffects";
import "featherlight/src/featherlight.css";
import "featherlight/src/featherlight.js";

// Activate scrollspy to add active class to navbar items on scroll
$("body").scrollspy({
  target: "#mainNav",
  offset: 75,
});

// Collapse Navbar
var navbarCollapse = function () {
  if ($("#mainNav").offset().top > 100) {
    $("#mainNav").addClass("navbar-scrolled");
  } else {
    $("#mainNav").removeClass("navbar-scrolled");
  }
};
// Collapse now if page is not at top
navbarCollapse();
// Collapse the navbar when page is scrolled
$(window).scroll(navbarCollapse);

console.log("Hello Webpack Encore! Edit me in assets/js/app.js");
