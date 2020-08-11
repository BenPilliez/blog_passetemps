import "rater-jquery";

export default class Rating {
  static init(option, element) {
    $(element).rate(option);
  }
}
