// get all the categories
let categories = document.querySelectorAll(".category");

// iterate over all the categories
categories.forEach(category => {

  // attach a click event listener to each category
  category.addEventListener("click", function(event) {

    // prevent the default action of the link
    event.preventDefault();

    // remove active class from all categories
    categories.forEach(innerCategory => {
      innerCategory.classList.remove("active");
    });

    // add the active class to the clicked category
    this.classList.add("active");

    // trigger the card animation
    document.querySelectorAll('.card').forEach(card => {
      card.classList.remove('animate');
      void card.offsetWidth; // Reflow the element to reset the animation
      card.classList.add('animate');
    });

  });
});

// Animation when page loads
window.onload = function() {
  document.querySelectorAll('.card').forEach(card => {
      card.classList.add('animate');
  });
}
