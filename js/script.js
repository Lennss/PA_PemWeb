// Toggle class active untuk hamburger menu
const navbarNav = document.querySelector(".navbar-nav");
// ketika hamburger menu di klik
document.querySelector("#hamburger-menu").onclick = () => {
  navbarNav.classList.toggle("active");
};

// Toggle class active untuk search form
// const searchForm = document.querySelector('.search-form');
// const searchBox = document.querySelector('#search-box');

// document.querySelector('#search-button').onclick = (e) => {
//   searchForm.classList.toggle('active');
//   searchBox.focus();
//   e.preventDefault();
// };

// Toggle class active untuk shopping cart
const shoppingCart = document.querySelector(".shopping-cart");
document.querySelector("#shopping-cart-button").onclick = (e) => {
  shoppingCart.classList.toggle("active");
  e.preventDefault();
};

// Klik di luar elemen
const hm = document.querySelector("#hamburger-menu");
const sb = document.querySelector("#search-button");
const sc = document.querySelector("#shopping-cart-button");

document.addEventListener("click", function (e) {
  if (!hm.contains(e.target) && !navbarNav.contains(e.target)) {
    navbarNav.classList.remove("active");
  }

  if (!sb.contains(e.target) && !searchForm.contains(e.target)) {
    searchForm.classList.remove("active");
  }

  if (!sc.contains(e.target) && !shoppingCart.contains(e.target)) {
    shoppingCart.classList.remove("active");
  }
});

function showProductDetails(name, price, description, image) {
  document.getElementById("popup-name").innerText = name;
  document.getElementById("popup-price").innerText = price;

  document.getElementById("popup-description").innerHTML = description;

  document.getElementById("popup-image").src = image;

  const popup = document.getElementById("product-popup");
  popup.style.display = "flex";
}

function hideProductDetails() {
  const popup = document.getElementById("product-popup");
  popup.style.display = "none";
}

window.onclick = function (event) {
  const popup = document.getElementById("product-popup");
  if (event.target === popup) {
    hideProductDetails();
  }
};
