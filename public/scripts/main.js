const menuButton = document.querySelector(".menu-button");
const navList = document.querySelector(".nav-links");

if (menuButton && navList) {
  menuButton.addEventListener("click", () => {
    navList.classList.toggle("open");
  });
}
