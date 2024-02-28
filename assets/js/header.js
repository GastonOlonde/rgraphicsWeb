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
        nav.style.backgroundColor = "var(--color-background)";
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




const themeToggle = document.querySelector('.theme');
const b = document.body;

// Vérifier si une préférence de thème est stockée dans localStorage
const themeStocke = localStorage.getItem('theme');
if (themeStocke) {
    b.classList.add(themeStocke);
    mettreAJourBoutonTheme(themeStocke);
}

themeToggle.addEventListener('click', () => {
    if (b.classList.contains('dark')) {
        definirTheme('light');
    } else {
        definirTheme('dark');
    }
});

function definirTheme(theme) {
    b.classList.toggle('dark', theme === 'dark');
    mettreAJourBoutonTheme(theme);
    // Stocker la préférence de thème actuelle dans localStorage
    localStorage.setItem('theme', theme);
    location.reload();
}

function mettreAJourBoutonTheme(theme) {
    const estModeSombre = theme === 'dark';
    themeToggle.innerHTML = estModeSombre ? 'Clair' : 'Sombre';
    themeToggle.style.backgroundColor = estModeSombre ? '#ffffffa6' : '#333333a9';
    themeToggle.style.color = estModeSombre ? '#000' : '#fff';
    themeToggle.style.filter = estModeSombre ? 'drop-shadow(2px 3px 1px rgba(255,255,255,0.30))' : '';
}

