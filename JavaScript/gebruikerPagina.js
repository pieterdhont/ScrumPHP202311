"use strict";

//-------------------------------------------------------opmaak van het menu-----------------------------------------------------

const linkAccount = document.getElementById('link-accountoverzicht');
const linkBestelgeschiedenis = document.getElementById('link-bestelgeschiedenis');
const linkMijnreviews = document.getElementById('link-mijnreviews');

if (localStorage.getItem('displayed-window') !== null) {


    switch (localStorage.getItem('displayed-window')) {
        case 'accountoverzicht':
            document.getElementById('Accountoverzicht-container').style.display = "flex";
            document.getElementById('Bestelgeschiedenis-container').style.display = "none";
            document.getElementById('Mijnreviews-container').style.display = "none";
            linkAccount.classList.add('actief')
            linkBestelgeschiedenis.classList.remove('actief');
            linkMijnreviews.classList.remove('actief');
          break;
        case 'bestelgeschiedenis':
            document.getElementById('Accountoverzicht-container').style.display = "none";
            document.getElementById('Bestelgeschiedenis-container').style.display = "block";
            document.getElementById('Mijnreviews-container').style.display = "none";
            linkAccount.classList.remove('actief')
            linkBestelgeschiedenis.classList.add('actief')
            linkMijnreviews.classList.remove('actief');
            break;
        case 'reviews':
            document.getElementById('Accountoverzicht-container').style.display = "none";
            document.getElementById('Bestelgeschiedenis-container').style.display = "none";
            document.getElementById('Mijnreviews-container').style.display = "flex";
            linkAccount.classList.remove('actief')
            linkBestelgeschiedenis.classList.remove('actief');
            linkMijnreviews.classList.add('actief')
          break;       
    }


}

//Toggle tussen de tabbladen
document.getElementById('link-accountoverzicht').onclick = function () {
    document.getElementById('Accountoverzicht-container').style.display = "flex";
    document.getElementById('Bestelgeschiedenis-container').style.display = "none";
    document.getElementById('Mijnreviews-container').style.display = "none";
    localStorage.setItem("displayed-window", "accountoverzicht");

    if (!linkAccount.classList.contains('actief')) {
        linkAccount.classList.add('actief');
        linkBestelgeschiedenis.classList.remove('actief');
        linkMijnreviews.classList.remove('actief');
    }
}

//Toggle tussen de tabbladen
document.getElementById('link-bestelgeschiedenis').onclick = function () {
    document.getElementById('Accountoverzicht-container').style.display = "none";
    document.getElementById('Bestelgeschiedenis-container').style.display = "block";
    document.getElementById('Mijnreviews-container').style.display = "none";
    localStorage.setItem("displayed-window", "bestelgeschiedenis");

    if (!linkBestelgeschiedenis.classList.contains('actief')) {
        linkBestelgeschiedenis.classList.add('actief');
        linkAccount.classList.remove('actief');
        linkMijnreviews.classList.remove('actief');
    }
}

//Toggle tussen de tabbladen
document.getElementById('link-mijnreviews').onclick = function () {
    document.getElementById('Accountoverzicht-container').style.display = "none";
    document.getElementById('Bestelgeschiedenis-container').style.display = "none";
    document.getElementById('Mijnreviews-container').style.display = "flex";
    localStorage.setItem("displayed-window", "reviews");

    if (!linkMijnreviews.classList.contains('actief')) {
        linkMijnreviews.classList.add('actief');
        linkAccount.classList.remove('actief');
        linkBestelgeschiedenis.classList.remove('actief');
    }
}












//Toon een melding als de klant een bestelling wilt annuleren
const annuleer_links = document.querySelectorAll('.annuleerBestelling');
for (const link of annuleer_links) {
    link.onclick = function () {
        if (confirm("Weet u zeker dat u deze bestelling wilt annuleren? Dit kan niet meer ongedaan gemaakt worden.") == true) {
            window.location.href = 'gebruiker.php?annuleerBestelling=' + link.dataset.bestelid;
        }
    }
}

//Toon een melding als de klant een review wilt verwijderen
const verwijderReviewLink = document.querySelectorAll('.verwijder-review-link');
for (const link of verwijderReviewLink) {

    link.onclick = function () {
        if (confirm("Weet u zeker dat u deze review wilt annuleren? Dit kan niet meer ongedaan gemaakt worden.") == true) {
            window.location.href = 'gebruiker.php?deletereview=' + link.dataset.reviewid;
        }
    }
}





//Voor het bewerken van een review
const bewerkReviewLink = document.querySelectorAll('.bewerk-review-link');
for (const link of bewerkReviewLink) {
    link.onclick = function () {
        const reviewContainers = document.querySelectorAll('.review-container');
        for (const container of reviewContainers) {
            if (container.dataset.reviewid == link.dataset.reviewid) {
                const formOfContainer = container.querySelector('form');
                if (formOfContainer.style.display == "block") {
                    formOfContainer.style.display = "none";
                } else {
                    //toon form
                    formOfContainer.style.display = "block";
                    //
                    formOfContainer.querySelector('textarea').value = link.dataset.commentaar;
                    formOfContainer.querySelector('input[type=number]').value = link.dataset.score;
                }
            }
        }
    }
}


//melding voor het blokkeren van een user account
const blokkeer_account = document.getElementById('account-blokkeren');
blokkeer_account.onclick = function() {
    if (confirm("Weet u zeker dat u uw account wil blokkeren? Dit kan niet meer ongedaan gemaakt worden.") == true) {
        window.location.href = 'gebruiker.php?blokkeeruser=' + blokkeer_account.dataset.userid;
    }
}
