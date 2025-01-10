document.addEventListener("DOMContentLoaded", function() {
  if (!getCookie("cookieConsent")) {
    showCookieConsent();
  }
});

function showCookieConsent() {
  const cookieConsent = document.getElementById("cookieConsent");
  cookieConsent.style.display = "block";
}

function acceptCookies() {
  const cookieConsent = document.getElementById("cookieConsent");
  cookieConsent.style.display = "none";
  setCookie("cookieConsent", "true", 30); // Cookie vervalt na 30 dagen
}

// Functie om de waarde van de cookie in te stellen met vervaldatum
function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  var expires = "expires=" + d.toUTCString();
  document.cookie = cname + "=" + cvalue + "; " + expires + ";path=/";
}

// Functie om de waarde van de cookie op te halen
function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
          c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
          return c.substring(name.length, c.length);
      }
  }
  return "";
}
