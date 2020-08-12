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
import "./module/modernizr.custom";
import "./module/toucheffects";
import "featherlight/src/featherlight.css";
import "featherlight/src/featherlight.js";
import slider from "./module/slider";
import rating from "./module/rating";
import cookie from "./module/cookie";

//Initialisation
cookie.init();
slider.init();

if ($(".rates").length) {
  $.ajax({
    url: `https://127.0.0.1:8000/rating/${$(".rates").attr("data-id")}`,
    method: "GET",
    success: function (data) {
      $(".rates").html(data.template);
      rating.init(
        {
          max_value: 5,
          step_size: 0.5,
          initial_value: 0,
          selected_symbol_type: "utf8_star", // Must be a key from symbols
          cursor: "default",
          readonly: true,
        },
        ".rating-show"
      );
    },
    error: function (error) {
      console.log(error);
    },
  });
}

rating.init(
  {
    max_value: 5,
    step_size: 0.5,
    initial_value: 0,
    selected_symbol_type: "utf8_star", // Must be a key from symbols
    cursor: "default",
    readonly: false,
    change_once: false, // Determines if the rating can only be set once
    ajax_method: "POST",
    url: `https://127.0.0.1:8000/rating/${$(".rating").attr("id")}`,
    additional_data: {}, // Additional data to send to the server
  },
  ".rating"
);

// Activate scrollspy to add active class to navbar items on scroll
$("body").scrollspy({
  target: "#mainNav",
  offset: 75,
});

$("[data-form]").click(function (event) {
  $("#comment-" + event.target.id).removeClass("hidden-form");
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

// On met à jour
$(".rating").on("updateSuccess", function (ev, data) {
  $(".rates").html(data.template);
  rating.init(
    {
      max_value: 5,
      step_size: 0.5,
      initial_value: 0,
      selected_symbol_type: "utf8_star", // Must be a key from symbols
      cursor: "default",
      readonly: true,
    },
    ".rating-show"
  );
});

// Update de la pagination en ajax
const test = function () {
  $("ul.pagination a ").click((e) => {
    e.preventDefault();

    $.ajax({
      url: e.target.href,
      method: "GET",
      success: function (data) {
        if ($(".cs-style-2").length) {
          $(".cs-style-2").html(data.template);
        }

        if ($(".comment-list").length) {
          $(".comment-list").html(data.template);
        }
        $(".pagination").html(data.pagination);
        test();
      },
      error: function (error) {
        console.log(error);
      },
    });
  });
};

test();

console.log("Hello Webpack Encore! Edit me in assets/js/app.js");
