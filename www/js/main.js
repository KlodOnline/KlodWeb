/*
	Commandes Client - ver. 0.2.1
	C.BOULLARD - Tout droits réservés
*/

// Detect language ....
// let x = document.cookie;
let language = (
  window.navigator.userLanguage || window.navigator.language
).slice(0, 2);
// Create tanslator with the dict
const translator = $("html").translate({ lang: "en", t: dict }); //use English

// Called while clicked
function updateMainContent(content) {
  $("div#main").html(content);
  translator.lang(language);
}
function ChangeLoginMenu(username) {
  $("a#login").removeClass("trn");
  $("a#login").text(username);
  $("a#login").attr("id", "account");
}
function RestoreLoginMenu(username) {
  $("a#account").addClass("trn");
  $("a#account").text("__LOGIN__");
  $("a#account").attr("id", "login");
}
setInterval(function () {
  // console.log('trying to find...');
  $("div.status").each(function (i, obj) {
    var address = $(obj).attr("address");
    $.ajax({
      data: "address=" + address,
      url: "./pages/world_status.php",
      cache: false,
    }).done(function (html) {
      $(obj).html(html);
      // Translate all please.
      translator.lang(language);
    });
  });
}, 2000);

function sendForm(button) {
  var url = $(button.target.form).attr("action"); // <-- récupere url du form
  var data = $(button.target.form).serialize(); // <-- ... et ses data, bien sur !

  $.ajax({
    method: "POST",
    data: data + "&action=" + url + "&submit=true",
    url: "./pages/" + url + ".php",
    cache: false,
  }).done(function (html) {
    updateMainContent(html);
  });
}

(function ($) {
  // Code éxécuté au chargement du DOM
  $(document).ready(function () {
    // Translate all please.
    translator.lang(language);

    // Change language, please !
    $("body").on("click", "a.lang", function () {
      var data = this.id;
      language = data;
      translator.lang(language);
    });

    // Les lien de class "main" :
    // Ces onglets servent à appeler un script php
    $("body").on("click", "a.main", function () {
      // this.onselectstart=function(){return false};     // <-- Mais pourquoi ça?!
      var data = this.id; // <-- nom de l'onglet que l'on veut afficher

      // On affiche l'onglet en question :
      $.ajax({
        url: "./pages/" + data + ".php",
        cache: false,
      }).done(function (html) {
        updateMainContent(html);
        // checkServerStatus();
      });
    });

    // Gestion des formulaires :
    // Bouttons clssqiues
    $("body").on("click", "input#validate", function (event) {
      this.onselectstart = function () {
        return false;
      }; // <-- Mais pourquoi ça?!
      event.preventDefault(); // <-- Stop form from submitting normally
      sendForm(event);
    });

    $("body").on("click", "button#validate", function (event) {
      this.onselectstart = function () {
        return false;
      }; // <-- Mais pourquoi ça?!
      event.preventDefault(); // <-- Stop form from submitting normally
      sendForm(event);
    });
  });
})(jQuery);
