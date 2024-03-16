const eye = document.getElementById('eye');
const password = document.getElementById('password');

eye.addEventListener('click', () => {
    eye.classList.toggle('fill-white');
    if (eye.classList.contains('fill-white')) {
        password.setAttribute('type', 'text');
    } else {
        password.setAttribute('type', 'password');
    }
})