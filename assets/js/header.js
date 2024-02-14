let toggle = document.querySelector(".toggle");
let body = document.querySelector('body');
let header = document.querySelector("header");
let nav = document.querySelector("nav");


toggle.addEventListener('click',function(){
    body.classList.toggle("open");
});

// apparition du header au chargement de la page avec une translation depuis le haut
window.addEventListener('load', () => {
    header.style.top = "0";
});




let lastScroll = 0;

window.addEventListener('scroll', () => {   
    let currentScroll = window.pageYOffset || document.documentElement.scrollTop;
    if (currentScroll > lastScroll){
        document.querySelector("header").classList.add("scrolled");
    } else {
        document.querySelector("header").classList.remove("scrolled");
    }

    if (currentScroll <= 100) {
        nav.style.backgroundColor = "transparent";
    } else {
        nav.style.backgroundColor = "#ffffff";
    }

    lastScroll = currentScroll <= 0 ? 0 : currentScroll; // For Mobile or negative scrolling
}, false);

// Récupérer le nom de fichier correspondant à la page actuelle
const currentPage = window.location.pathname.split('/').pop();

// Récupérer tous les liens de menu
const menuLinks = document.querySelectorAll('.menu li a');

// Parcourir les liens et vérifier si leur href correspond au nom de fichier de la page actuelle
menuLinks.forEach(link => {
    const linkPath = link.getAttribute('href').split('/').pop();

    if (currentPage === linkPath) {
        link.parentElement.classList.add('active');
    }
});
