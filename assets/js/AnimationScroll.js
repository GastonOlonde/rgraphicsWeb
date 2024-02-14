

document.addEventListener('DOMContentLoaded', () => {
    const observeElements = (targetClass, threshold, delay, transformStyle, initialTransform) => {
        const elements = document.querySelectorAll(targetClass);
        const options = {
            threshold: threshold
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transition = 'transform 1s, opacity 1s';
                    // délais de la transition duration
                    entry.target.style.transitionDuration = delay + 's';
                    entry.target.style.transform = transformStyle;
                    observer.unobserve(entry.target);
                }
            });
        }, options);

        elements.forEach(element => {
            element.style.transform = initialTransform;
            element.style.opacity = '0.5';

            observer.observe(element);
        });
    };

    // pour les éléments .hiddenY
    observeElements('.hiddenY', 0.1, 1, 'translateY(0)', 'translateY(50px)');

    // pour les éléments .hiddenX
    observeElements('.hiddenX', 0.2, 1, 'translateX(0)', 'translateX(-50px)');

    // pour les scales
    observeElements('.hiddenScale', 0.2, 1, 'scale(1)', 'scale(0.5)');

    // effet retourné sur l'axe z
    observeElements('.hiddenRotateY', 0.2, 2, 'rotateY(0)', 'rotateY(90deg)');

    // // effet de hauteurs qui par de 0 jusqu'a auto
    // observeElements('.hiddenH', 0.2, 1, 'height:0', 'height:auto');
});


var isScrolling;




document.addEventListener('scroll', function () {
    // Annule la rotation en cours (s'il y en a une)
    clearTimeout(isScrolling);

    // Obtient la position de défilement actuelle
    var scrolled = window.scrollY;

    // Applique la rotation proportionnelle au défilement
    var rotationAngle = scrolled * 0.2 - 10; // Vous pouvez ajuster ce coefficient pour changer la vitesse de rotation
    document.querySelector('.star').style.transform = 'rotate(' + rotationAngle + 'deg)';
    document.querySelector('.star-background').style.transform = 'rotate(' + rotationAngle + 'deg)';

    // Démarre une nouvelle rotation dans 50 millisecondes après l'arrêt du défilement
    isScrolling = setTimeout(function() {
        document.querySelector('.star-background').style.transition = 'transform 0.5s ease-out';
        document.querySelector('.star-background').style.transform = 'rotate(' + rotationAngle + 'deg)';
        document.querySelector('.star').style.transition = 'transform 0.5s ease-out';
        document.querySelector('.star').style.transform = 'rotate(' + rotationAngle + 'deg)';
    }, 50);
});


var swiper = new Swiper('.swiper-container', {
    effect: 'coverflow',
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: 'auto',
    coverflowEffect: {
    rotate: 15,
    stretch: 0,
    depth: 100,
    modifier: 1,
    slideShadows: true,
    },
    loop: true,
    autoplay: {
        delay: 3000,
    },
    speed: 2000,
    easing: 'ease-in-out',
})






$(document).ready(function () {
    // Fonction pour le défilement fluide
    $(".section-link").on('click', function (event) {
        event.preventDefault();
        var target = $(this).attr('href');
        $('html, body').animate({
            scrollTop: $(target).offset().top
        }, 1000);
    });

    // Mettre à jour le texte du bouton en fonction de la section active
    $(window).on('scroll', function () {
        var scrollPosition = $(this).scrollTop();

        $('.section').each(function () {
            var top = $(this).offset().top - 500;
            var bottom = top + $(this).outerHeight();

            if (scrollPosition >= top && scrollPosition <= bottom) {
                var sectionName = $(this).data('section-name');
                $(".dropdown-btn-text").text(sectionName);
            }
        });
    });
});
