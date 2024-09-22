//preloader

document.addEventListener("DOMContentLoaded", function() {
    var preloader = document.getElementById('preloader');
    var content = document.querySelector('.content-isi');

    content.style.display = 'none';

    window.onload = function() {
        preloader.style.display = 'flex';
        preloader.style.display = 'none';
        content.style.display = 'flex';

    };

});

//dropdown

document.addEventListener('DOMContentLoaded', function () {
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    dropdownToggles.forEach(function (toggle) {
        const dropdownContent = toggle.nextElementSibling;
        const chevron = toggle.querySelector('.fas');

        toggle.addEventListener('click', function () {
            dropdownContent.classList.toggle('show');
            chevron.classList.toggle('fa-chevron-down');
            chevron.classList.toggle('fa-chevron-right');
        });
    });
});


//dropdown mobile

document.getElementById('dataMenu').addEventListener('click', function(event) {
    event.preventDefault();
    const kelolaDataDiv = document.querySelector('.menu-hover .keloladata');
    const karcisDataDiv = document.querySelector('.menu-hover .karcisdata');
    const userDataDiv = document.querySelector('.menu-hover .userdata');

    if (kelolaDataDiv.classList.contains('show')) {
        kelolaDataDiv.classList.remove('show');
    } else {
        kelolaDataDiv.classList.add('show');
        karcisDataDiv.classList.remove('show');
        userDataDiv.classList.remove('show');
    }
});

document.getElementById('karcisMenu').addEventListener('click', function(event) {
    event.preventDefault();
    const kelolaDataDiv = document.querySelector('.menu-hover .keloladata');
    const karcisDataDiv = document.querySelector('.menu-hover .karcisdata');
    const userDataDiv = document.querySelector('.menu-hover .userdata');

    if (karcisDataDiv.classList.contains('show')) {
        karcisDataDiv.classList.remove('show');
    } else {
        karcisDataDiv.classList.add('show');
        kelolaDataDiv.classList.remove('show');
        userDataDiv.classList.remove('show');
    }
});




//preview image


// UL Li




