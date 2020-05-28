$(document).ready(function () {
  var cityName;
  // var communes;
  var data;
  var map;
  var nameNb = 0;
  var valueDpt;
  var zoomOk = false;
  var inputCity;

  const FEMICON = new L.Icon({
    iconUrl: 'img/leaf-red.png',
    shadowUrl: 'img/leaf-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
  });

  const HOMICON = new L.Icon({
    iconUrl: 'img/leaf-green.png',
    shadowUrl: 'img/leaf-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
  });

  $('.submit-home').hide();
  $('#inputCity').hide();
  $('.container-map').hide();
  $('#js-map').hide();
  $('#phraseResult').hide();
  $('.pluriel').hide();

  // Autocomplete departments
  var depts = ["Ain", "Aisne", "Allier", "Alpes-de-Haute-Provence", "Hautes-Alpes", "Alpes-Maritimes", "Ardèche", "Ardennes", "Ariège", "Aube", "Aude", "Aveyron", "Bouches-du-Rhône", "Calvados", "Cantal", "Charente", "Charente-Maritime", "Cher", "Corrèze", "Corse-du-Sud", "Haute-Corse", "Côte-d’Or", "Côtes-d'Armor", "Creuse", "Dordogne", "Doubs", "Drôme", "Eure", "Eure-et-Loir", "Finistère", "Gard", "Haute-Garonne", "Gers", "Gironde", "Hérault", "Ille-et-Vilaine", "Indre", "Indre-et-Loire", "Isère", "Jura", "Landes", "Loir-et-Cher", "Loire", "Haute-Loire", "Loire-Atlantique", "Loiret", "Lot", "Lot-et-Garonne", "Lozère", "Maine-et-Loire", "Manche", "Marne", "Haute-Marne", "Mayenne", "Meurthe-et-Moselle", "Meuse", "Morbihan", "Moselle", "Nièvre", "Nord", "Oise", "Orne", "Pas-de-Calais", "Puy-de-Dôme", "Pyrénées-Atlantiques", "Hautes-Pyrénées", "Pyrénées-Orientales", "Bas-Rhin", "Haut-Rhin", "Rhône", "Haute-Saône", "Saône-et-Loire", "Sarthe", "Savoie", "Haute-Savoie", "Paris", "Seine-Maritime", "Seine-et-Marne", "Yvelines", "Deux-Sèvres", "Somme", "Tarn", "Tarn-et-Garonne", "Var", "Vaucluse", "Vendée", "Vienne", "Haute-Vienne", "Vosges", "Yonne", "Territoire de Belfort", "Essonne", "Hauts-de-Seine", "Seine-Saint-Denis", "Val-de-Marne", "Val-d’Oise"];
  var num = ["01", "02", "03", "04", "05", "06", "07", "08", "09",
    "10", "11", "12", "13", "14", "15", "16", "17", "18", "19",
    "2A", "2B", "21", "22", "23", "24", "25", "26", "27", "28", "29",
    "30", "31", "32", "33", "34", "35", "36", "37", "38", "39",
    "40", "41", "42", "43", "44", "45", "46", "47", "48", "49",
    "50", "51", "52", "53", "54", "55", "56", "57", "58", "59",
    "60", "61", "62", "63", "64", "65", "66", "67", "68", "69",
    "70", "71", "72", "73", "74", "75", "76", "77", "78", "79",
    "80", "81", "82", "83", "84", "85", "86", "87", "88", "89",
    "90", "91", "92", "93", "94", "95"
  ];
  var i = 0;
  var liste = [];
  var listeVilles = [];
  while (i < num.length) {
    liste.push(num[i] + " " + depts[i]);
    i++;
  }

  $('#inputDpt').autocomplete({
    source: liste
  });

  $('#inputCity').autocomplete({
    source: listeVilles
  });

  function getDpt() {
    $('#inputCity').show();
    valueDpt = $("#inputDpt").val().slice(0, 2);
    data = $.getJSON(`../data/OSM-communes-codeInseeOsm.json-${valueDpt}.json`, function (data) {
      // Crate an array
      listeVilles = Object.keys(data.communes);
      console.log(data);
      communes = data.communes;
      console.log(communes);
      $('#inputCity').off("autoComplete");
      $('#inputCity').autocomplete({
        source: listeVilles
      });
      $('inputCity').show();
    }).fail(function (jqxhr, textStatus, error) {
      var err = textStatus + ", " + error;
      console.log("Request Failed: " + err);
    });
  }

  $("#inputDpt").on("autocompleteselect", function () {
    setTimeout(getDpt, 200);
  })

  function getCity() {
    $('.submit-home').show();
    $('.submit-home').css('cursor','pointer');
  }

  $("#inputCity").on("autocompleteselect", function () {
    setTimeout(getCity, 200);
  })

  $("#searchCity").on("click", function () {
    $('.container-map').show();
    testFactor();
    zoomOk = false;
    var insert = "";
    inputCity = $("#inputCity").val();
    console.log(inputCity);
    cityName = $("#inputCity").val();
    console.log(communes[$("#inputCity").val()]);
    //  Test
    console.log(communes[$("#inputCity").val()][0]);
    console.log(communes[$("#inputCity").val()][1] + "");

    if ((communes[$("#inputCity").val()])[0] + "".length == 4) {
      insert = "0";
    }
    $("#cityname-h").html($('#inputCity').val());
    $("#results").html("<p>Résultats pour la commune de "+$("#inputCity").val()+"</p>")
    $("#results").html("<p>Test texte </p>");
    $("#results").html("<p>Code INSEE : " + insert + communes[$("#inputCity").val()][0] + "</p>");
    $("#results").append("<p>Code OSM : " + communes[$("#inputCity").val()][1] + "</p>");
    $("#results").append('<table id="table-results"><tr><th>Type</th><th>Nom du lieu</th><th>Nom de personne potentiel</th><th>Coordonnées</th><th>Nom trouvé sur Wikidata</th><th>Genre</th><th>Nom à trouver sur Wikidata</th></tr></table>')

    // Show Leaflet map
    $("#js-map").show();
    if (map == undefined) {
      map = L.map('js-map').setView([48.8534, 2.3488], 5);
      L.tileLayer('//{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
        attribution: 'donn&eacute;es &copy; <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
        minZoom: 1,
        maxZoom: 20
      }).addTo(map);
    }

    $.get("../data/BAN" + (insert + communes[$("#inputCity").val()][0]).slice(0, 2) + ".csv")
      .done(analyzeBanData)
      .fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
      });

    themes = ["sports", "education", "library"];
    themeLabels = {
      "sports": "équipement sportif",
      "education": "lieu d’enseignement",
      "library": "bibliothèque"
    }
    themeNumber = 0;
    sendGeodatamineQuery();
  });

  function analyzeBanData(data) {
    // Parse of OSM data with Papa Parse library
    var csv = Papa.parse(data).data;
    console.log(csv);  
    var i = 0;
    var insert = "";
    if ((communes[$("#inputCity").val()][0] + "").length == 4) {
      insert = "0";
    }
    var codeCommune = insert + communes[$("#inputCity").val()][0];
    for (element in Object.keys(csv)) {
      if (i > 0) {
        if (csv[i].length > 1) {
          if (csv[i][3] == codeCommune) {
            //console.log(csv[i]);
            addTableRow("voie", csv[i][2], csv[i][4] + " " + csv[i][5], "address");
          }
        }
      }
      i++;
    }
  }

  function addTableRow(topic, name, coord, topicCode) {
    if (name != "") {
      var coordinates = analyzeCoord(coord);
      var analyzedName = analyzeName(name, topicCode);
      if (analyzedName.slice(0, 3) == "de " || analyzedName.slice(0, 3) == "du " || analyzedName.slice(0, 4) == "des " || analyzedName.slice(0, 2) == "d'" || analyzedName.slice(0, 2) == "d’") {
        analyzedName = "";
      }
      if (analyzedName != "") {
        var lineNb = foundNames.length;
        if (!(foundNames.includes(analyzedName))) {
          foundNames[lineNb] = analyzedName;
        } else {
          lineNb = foundNames.indexOf(analyzedName);
        }

        $("table").append('<tr class="border_bottom count-street foundName' + lineNb + '"><td>' + topic + '</td><td class="placeName">' + name + '</td><td>' + analyzedName + '</td><td class="coord">' + coordinates + "</td></tr>");
      } else {
        $("table").append('<tr class="border_bottom count-street"><td>' + topic + "</td><td>" + name + '</td><td>' + analyzedName + "</td><td>" + coordinates + "</td></tr>");
      }
    }
  }

})