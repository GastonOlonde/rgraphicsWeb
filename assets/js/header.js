let toggle = document.querySelector(".toggle");
let body = document.querySelector('body');

toggle.addEventListener('click',function(){
    body.classList.toggle("open");
});



// window.addEventListener('scroll', () => {
//     const header = document.querySelector("header");

//     if (window.scrollY > 0) {
//         header.classList.add("scrolled");
//         console.log("window.scrollY");
//     }
//     else {
//         header.classList.remove("scrolled");
//     }
    
// });

let lastScroll = 0;

window.addEventListener('scroll', () => {   
    let currentScroll = window.pageYOffset || document.documentElement.scrollTop;
    if (currentScroll > lastScroll){
        document.querySelector("header").classList.add("scrolled");
    } else {
        document.querySelector("header").classList.remove("scrolled");
    }
    lastScroll = currentScroll <= 0 ? 0 : currentScroll; // For Mobile or negative scrolling
}, false);