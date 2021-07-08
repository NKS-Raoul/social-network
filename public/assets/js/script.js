// // mouseover on navigation bar link
// var links = document.querySelectorAll('nav li a');
// var nav_li = document.querySelectorAll('nav li');
// var nav_li_a_em = document.querySelectorAll('nav li a em');
// var tempo = 0;

// for (let i = 0; i < links.length; i++) {

//     links[i].onclick = () => {
//         let j = 0;
//         while (j < links.length) {
//             nav_li[j].style.borderBottom = 'none';
//             nav_li_a_em[j++].style.color = 'gray';
//         }
//         localStorage.setItem('nav_bar_index', i);
//         nav_li[i].style.borderBottom = '4px solid var(--site_color)';
//         nav_li_a_em[i].style.color = 'var(--site_color)';
//     }
// }

// Preloader animation
var preloader = document.querySelector('.contener');
window.addEventListener('load', function () {
    var temp = 1;
    var stop = setInterval(() => {
        preloader.style.opacity = '' + temp;
        temp -= 0.015;
        if (temp < 0) {
            preloader.style.visibility = 'hidden';
            preloader.style.animation = 'none';
            clearInterval(stop);
        }
    }, 10);

    // script for opening and closing button of menu
    let a = document.querySelectorAll('.bar');
    document.querySelector('.container_option').addEventListener('click', () => {
        a.forEach(ele => {
            ele.classList.contains('open') ? (
                ele.classList.replace('open', 'close') // oui on remplace open par close
            ) : (/* non */ ele.classList.contains('close') ?
                ele.classList.replace('close', 'open') /* oui pour close */
                : ele.classList.add('open') // nous sommes au premier click
            )
        });
    });


    // change_Mode(localStorage.getItem('mode'));

    document.querySelector('html').setAttribute('data-theme', localStorage.getItem('mode'));
    if (localStorage.getItem('mode') == 'dark') {
        change_mode.checked = true;
    } else {
        change_mode.checked = false;
    }
    // change_mode.checked = false;
    // document.querySelector('html').setAttribute('data-theme', 'dark');
});

// light and dark mode script /////////////////////////////////////////////////
let change_mode = document.getElementById('change_mode');
var round = document.querySelector('span.round');
round.onclick = () => {
    change_Mode();
}

function change_Mode() {
    if (!change_mode.checked) {
        localStorage.setItem('mode', "dark");
        document.querySelector('html').setAttribute('data-theme', 'dark');
    } else {
        localStorage.setItem('mode', "light");
        document.querySelector('html').setAttribute('data-theme', 'light');
    }
}

// responsive menu management
let container_option = document.querySelector('.container_option');
container_option.onclick = () => {
    let resp_menu = document.querySelector('.resp_menu');
    resp_menu.classList.toggle('move');
}
