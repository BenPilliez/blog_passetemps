// Initialisation de cookieconsent

export default class Cookie {
  static init() {
    window.cookieconsent.initialise({
      container: document.getElementById("cookieconsent"),
      palette: {
        popup: { background: "#fff" },
        button: { background: "#aa0000" },
      },
      revokable: true,
      onStatusChange: function (status) {
        console.log(this.hasConsented() ? "enable cookies" : "disable cookies");
      },
      law: {
        regionalLaw: false,
      },
      location: true,
    });
  }
}
