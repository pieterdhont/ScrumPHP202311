"use strict";
const rdb_particulier = document.getElementById("particulier");
const rdb_zakelijk = document.getElementById('zakelijk');
const bedrijfsnaam = document.getElementById('label_bedrijfsnaam');
const btwnummer = document.getElementById('label_BTWNummer');

rdb_particulier.onchange = function() {
    if (rdb_particulier.checked == true) {
        bedrijfsnaam.style.display = "none";
        btwnummer.style.display = "none";
    } else {
        bedrijfsnaam.style.display = "block";
        btwnummer.style.display = "block";
    }
}

rdb_zakelijk.onchange = function() {
    if (rdb_particulier.checked == true) {
        bedrijfsnaam.style.display = "none";
        btwnummer.style.display = "none";
    } else {
        bedrijfsnaam.style.display = "block";
        btwnummer.style.display = "block";
    }
}


let checkbox = document.getElementById('gelijkadres');
let leveradres_container = document.getElementById('leveradres_container');

checkbox.onchange = function() {

    if (checkbox.checked === false) {
        leveradres_container.style.display = "block";
    } else {
        leveradres_container.style.display = "none";
    }

    document.getElementById('lever_straat').value = document.getElementById('factuur_straat').value;
    document.getElementById('lever_huisnummer').value = document.getElementById('factuur_huisnummer').value;
    document.getElementById('lever_bus').value = document.getElementById('factuur_bus').value;
    document.getElementById('lever_postcode').value = document.getElementById('factuur_postcode').value;
    document.getElementById('lever_plaats').value = document.getElementById('factuur_plaats').value;
}

//Update de cookie met de aangepaste hoeveelheid
document.getElementById('update-caddy').onclick = function () {
    //Straight black magic wizardry
    const arr_winkelmand = [];
    const artikel_boxes = document.getElementsByClassName('artikel-info');
    for (const artikel_box of artikel_boxes) {
        let jsonLine = artikel_box.dataset.order_object;
        jsonLine = jsonLine.replace('_', ' ').slice(0, -1);
        const newAantal = artikel_box.querySelector('.bestel_aantal').value;
        if (newAantal > 0) {
            arr_winkelmand.push(JSON.parse(jsonLine));
            arr_winkelmand[arr_winkelmand.length - 1].aantal = newAantal
        }
    }
    document.cookie = "order=" + JSON.stringify(arr_winkelmand);
    window.location.href = "bestellingDoorgeven.php";

}
