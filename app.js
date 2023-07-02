// get all the categories
let categories = document.querySelectorAll(".category");

// iterate over all the categories
categories.forEach(function(category) {

  // attach a click event listener to each category
  category.addEventListener("click", function(event) {

    // prevent the default action of the link
    event.preventDefault();

    // remove active class from all categories
    categories.forEach(function(innerCategory) {
      innerCategory.classList.remove("active");
    });

    // add the active class to the clicked category
    this.classList.add("active");

  });
});
