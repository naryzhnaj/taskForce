var closeModalLinks = document.getElementsByClassName("form-modal-close");

function closeModal() {
    var modal = document.getElementsByClassName("fade modal in")[0];
    modal.removeAttribute("style");
  }
  
for (var j = 0; j < closeModalLinks.length; j++) {
var closeModalLink = closeModalLinks[j];

closeModalLink.addEventListener("click", closeModal)
}

var closeButton = document.getElementById('close-modal');
if (closeButton) {
  closeButton.addEventListener("click", closeModal);
}

var starRating = document.getElementsByClassName("completion-form-star");

if (starRating.length) {
  starRating = starRating[0];

  starRating.addEventListener("click", function(event) {
    var stars = event.currentTarget.childNodes;
    var rating = 0;

    for (var i = 0; i < stars.length; i++) {
      var element = stars[i];

      if (element.nodeName === "SPAN") {
        element.className = "";
        rating++;
      }

      if (element === event.target) {
        break;
      }
    }

    var inputField = document.getElementById("rating");
    inputField.value = rating;
  });
}