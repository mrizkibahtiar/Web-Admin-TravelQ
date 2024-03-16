const checkboxBurger = document.querySelector('.nav-mobile #menu');
const navActive = document.querySelector('.nav-active');

const translateNavbar = () =>
    navActive.style.transform = window.innerWidth > 768 ? 'translateX(0px)' : 'translateX(200px)';

checkboxBurger.addEventListener('click', function () {
    navActive.style.transform = checkboxBurger.checked ? 'translateX(0px)' : 'translateX(200px)';
});

translateNavbar()
window.addEventListener('resize', translateNavbar);

const icons = document.querySelectorAll('#content .second a svg');
const offers = document.querySelectorAll('#content .second a');

offers.forEach((offer, i) => {
    offer.addEventListener('mouseenter', () => {
        icons[i].style.opacity = '100%';
    });

    offer.addEventListener('mouseleave', () => {
        icons[i].style.opacity = '0%';
    });
});
