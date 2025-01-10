"use strict";


//Initializeer een lege array voor het winkelmandje in op te slaan
let arr_winkelmand = [];



//laad cookie in als deze al bestaat
loadOrderCookie();
toonCaddy();




/*Haal de gegevens uit het element waar op geklikt word*/
/*In dit geval worden de gegevens van de artikels uit de data-tag gehaald, maar dit kan veranderen*/
const all_artikels = document.querySelectorAll('.toevoegenAanWinkelmandKnop');
for (const artikel of all_artikels) {
    artikel.onclick = function () {
        voegToeAanCaddy(artikel.dataset.id, artikel.dataset.naam, artikel.dataset.prijs, artikel.dataset.voorraad);
        saveCaddyAsCookie();
    }
}


/* In productpagina knop voor het toevoegen van aantal in winkelmand*/
const toevoegenMetAantalAanWinkelmand = document.getElementById("toevoegenMetAantalAanWinkelmand");

if (toevoegenMetAantalAanWinkelmand !== null) {
    toevoegenMetAantalAanWinkelmand.onclick = function () {
        voegToeAanCaddy(toevoegenMetAantalAanWinkelmand.dataset.id, toevoegenMetAantalAanWinkelmand.dataset.naam, toevoegenMetAantalAanWinkelmand.dataset.prijs, toevoegenMetAantalAanWinkelmand.dataset.voorraad);
        saveCaddyAsCookie();
    }
}


//  Voor de aantallen te vergelijken met de voorraad van artikelen in de bestelpagina
const elementen_aantallen = document.querySelectorAll('.bestel_aantal');
for (const element_aantal of elementen_aantallen) {
    element_aantal.onchange = function () {
        if (parseInt(element_aantal.value) > parseInt(element_aantal.dataset.voorraad)) {
            console.log('error thrown');
            alert('Er zijn niet meer stuks in voorraad.');
            element_aantal.value = element_aantal.dataset.voorraad;
        }
    }
}

function controleerVoorraad(artikelId) {
    //Controleer hoeveel stuks er van dit artikelId in de array zitten
    //loop door de array

    for (let i = 0; i < arr_winkelmand.length; i++) {
        //Als het gegeven artikelId overeenkomt met een lijn uit de array

        if (arr_winkelmand[i].id === artikelId) {

            //controleer als de voorraad bereikt is
            if (arr_winkelmand[i].aantal >= arr_winkelmand[i].artikelVoorraad) {
                //return true als de voorraad bereikt of overschreven is
                return true;
            } else {
                //return false als de voorraad nog niet bereikt is 
                return false;
            }
        }
    }
}


function voegToeAanCaddy(artikelId, artikelNaam, artikelPrijs, voorraad) {
    //controleer als het aantal de voorraad niet overschrijdt.
    if (controleerVoorraad(artikelId) == true) {
        alert('Er zijn niet meer stuks in voorraad.');
    } else {
        //controleer als het winkelmandje het artikel al bevat
        let gevonden_index = -1;
        for (let i = 0; i < arr_winkelmand.length; i++) {
            if (arr_winkelmand[i].id === artikelId) {
                gevonden_index = i;
            }
        }
        //Als het winkelmandje het artikel al bevat, tel het aantal op
        if (gevonden_index !== -1) {
            arr_winkelmand[gevonden_index].aantal++;
        } else {
            //Als het winkelmandje het artikel nog niet bevat, voeg het toe met aantal 1
            arr_winkelmand.push({ id: artikelId, naam: artikelNaam, aantal: 1, prijs: artikelPrijs, artikelVoorraad: voorraad });
        }

        toonCaddy();
    }
}


function toonCaddy() {
    //verwijder alle lijnen uit de array als het aantal 0 is
    function removeZeroQuantityItems(arr) {
        return arr.filter(item => item.aantal !== 0);
    }
    arr_winkelmand = removeZeroQuantityItems(arr_winkelmand);

    let table = document.getElementById("selectie");

    //Verwijder eventueel bestaande data
    while (table.rows.length > 1) {
        table.deleteRow(table.rows.length - 1);
    }

    //Voor elk element in arr_winkelmand, voeg het toe aan de table
    for (const x of arr_winkelmand) {
        //Insert een lege rij en voeg deze onderaan toe
        let row = table.insertRow(-1);
        //Insert naam in cell[0] van de nieuwe rij
        let cell0 = row.insertCell(0);
        cell0.innerHTML = x.naam;
        //Insert aantal in cell[1] van de nieuwe rij
        let cell1 = row.insertCell(1);
        cell1.innerHTML = '<input type="number" min="0" max="99" required class="aantal" value="' + x.aantal + '" oninput"=checkInput(this)">';
        //Insert prijs in cell[2] van de nieuwe rij
        let cell2 = row.insertCell(2);
        cell2.innerHTML = "€" + parseFloat(x.prijs).toFixed(2);
        //Insert prijs in cell[3] van de nieuwe rij
        let cell3 = row.insertCell(3);
        cell3.innerHTML = "€" + (x.aantal * x.prijs).toFixed(2);
        //insert afbeelding in cell[4] van de nieuwe rij
        let cell4 = row.insertCell(4);
        let img_verwijderUitWinkelmand = document.createElement("img");
        img_verwijderUitWinkelmand.src = "assets/verwijderUitWinkelmand.png";
        img_verwijderUitWinkelmand.onclick = function () {
            //verwijder de rij uit de table en de data uit de array
            arr_winkelmand.splice((row.rowIndex - 1), 1);
            toonCaddy();
            saveCaddyAsCookie();

        }
        cell4.appendChild(img_verwijderUitWinkelmand);
    }

    //Creër een table foot met het totaal bedrag
    let footer = table.createTFoot();
    let row = footer.insertRow(0);

    //Insert "Totaal" in cell[0] van de nieuwe rij
    let cell0 = row.insertCell(0);
    cell0.innerHTML = "<strong>Totaal</strong>";

    //Insert bedrag in cell[1] van de nieuwe rij
    let cell1 = row.insertCell(1);
    cell1.colSpan = 4;
    let totaal = 0;
    for (const x of arr_winkelmand) {
        totaal += (x.prijs * x.aantal);
    }
    cell1.innerHTML = "<strong>€" + totaal.toFixed(2) + "</strong>";

    //Voeg een lege cel toe
    let cell2 = row.insertCell(2);
    cell2.innerHTML = "";

    /*Get het totale aantal artikelen in arr_winkelmand*/
    let aantalArtikelen = 0;
    for (const artikel of arr_winkelmand) {
        aantalArtikelen += parseInt(artikel.aantal);
    }

    document.getElementById('winkelmandBadge').innerText = parseInt(aantalArtikelen);

    let all_aantallen = document.querySelectorAll('.aantal');
    for (const ele_aantal of all_aantallen) {
        ele_aantal.onchange = function () {
            /*Pak de values van alle input elementen en steek deze in een array genaamd arr_aantallen*/
            let arr_aantallen = [];
            const artikel_aantallen = document.querySelectorAll('#selectie input[type=number]')
            for (const x of artikel_aantallen) {
                arr_aantallen.push(parseInt(x.value));
            }

            //Voor elke lijn in arr_winkelmand, zet het aantal naar de respectievelijke input aantal value
            for (let i = 0; i < arr_winkelmand.length; i++) {
                if (arr_aantallen[i] <= arr_winkelmand[i].artikelVoorraad) {
                    arr_winkelmand[i].aantal = arr_aantallen[i];
                } else {
                    alert('Er zijn niet meer stuks in voorraad.');
                }
            }

            toonCaddy();
            saveCaddyAsCookie();
        }
    }
}
function loadOrderCookie() {
    let name = "order=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            arr_winkelmand = JSON.parse(c.substring(name.length, c.length));
        }
    }
}

function saveCaddyAsCookie() {
    document.cookie = "order=" + JSON.stringify(arr_winkelmand);
}

function checkInput(input) {
    if (input.value > 99) {
        input.value = 99;
    }
    if (input.value == '') {
        input.value = 0;
    }
}
