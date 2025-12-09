// Preview de imagem para userprofile e animal
// Copiado do frontend/web/js/image-preview.js

document.addEventListener("DOMContentLoaded", function () {
  var inputs = document.querySelectorAll(
    "input[type=file][data-image-preview]"
  );
  inputs.forEach(function (input) {
    var previewId = input.getAttribute("data-image-preview");
    var preview = document.getElementById(previewId);
    if (preview) {
      input.addEventListener("change", function (e) {
        if (e.target.files && e.target.files[0]) {
          var reader = new FileReader();
          reader.onload = function (ev) {
            preview.src = ev.target.result;
            preview.style.display = "block";
          };
          reader.readAsDataURL(e.target.files[0]);
        }
      });
    }
  });
});
