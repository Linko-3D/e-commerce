// get all the categories
let categories = document.querySelectorAll(".category");

// iterate over all the categories
categories.forEach(category => {
  // attach a click event listener to each category
  category.addEventListener("click", event => {
    // prevent the default action of the link
    event.preventDefault();

    // if the category has the active class, remove it
    // otherwise, remove the active class from all categories and add it to the clicked category
    if (category.classList.contains("active")) {
      category.classList.remove("active");
    } else {
      categories.forEach(innerCategory => innerCategory.classList.remove("active"));
      category.classList.add("active");
    }

    // add the animate class to all cards
    animateCards();
  });
});

// get the select menu
let selectMenu = document.querySelector("select[name='departments']");

// attach a change event listener to the select menu
selectMenu.addEventListener("change", animateCards);

// Animation when page loads
window.onload = animateCards;

// Function to animate all cards
function animateCards() {
  // remove the animate class from all cards and then add it again
  document.querySelectorAll('.card').forEach(card => {
    card.classList.remove('animate');
    void card.offsetWidth;
    card.classList.add('animate');
  });
}
