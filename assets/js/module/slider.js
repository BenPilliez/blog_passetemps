import "slick-carousel";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";

// Initialisation de slick

export default class Slider {
  static init() {
    $("[data-slider]").slick({
      mobileFirst: true,
      dots: true,
      arrows: true,
    });
  }
}
