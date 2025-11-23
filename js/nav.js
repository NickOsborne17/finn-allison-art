document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const menuList = document.getElementById('menuList');

    if (menuToggle && menuList) {
        menuToggle.addEventListener('click', function() {
            menuList.classList.toggle('active');
            menuToggle.classList.toggle('active');
        });
    }
});