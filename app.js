// get all the categories
let categories = document.querySelectorAll(".category");

// iterate over all the categories
categories.forEach(category => {
  // attach a click event listener to each category
  category.addEventListener("click", event => {
    // prevent the default action of the link
    event.preventDefault();

    // remove active class from all categories
    categories.forEach(innerCategory => innerCategory.classList.remove("active"));

    // add the active class to the clicked category
    category.classList.add("active");

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
    // Reflow the element to reset the animation
    void card.offsetWidth;
    card.classList.add('animate');
  });
}
