// get all the categories
let categories = document.querySelectorAll(".category");

// iterate over all the categories
categories.forEach(category => {
  // attach a click event listener to each category
  category.addEventListener("click", event => {
    // prevent the default action of the link
    event.preventDefault();

    // Add the animating class
    category.classList.add("animating");

    // After the animation finishes, remove the animating class
    setTimeout(function() {
        category.classList.remove("animating");
    }, 1000); // Corresponds to the duration of the animation

    // if the shift key is pressed
    if (event.shiftKey) {
      category.classList.toggle("active");
    } else {
      // if the shift key is not pressed
      if (category.classList.contains("active")) {
        // if the category is already active, remove the class
        category.classList.remove("active");
        categories.forEach(innerCategory => innerCategory.classList.remove("active"));
      } else {
        // if the category is not active, add the class and remove from others
        categories.forEach(innerCategory => innerCategory.classList.remove("active"));
        category.classList.add("active");
      }
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
