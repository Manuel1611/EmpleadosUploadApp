(function changeImage() {
    var imgInput = document.getElementById('myinputfile');
    imgInput.addEventListener('change', function(event) {
        var file = event.target.files[0];
        var reader = new FileReader();
        reader.onload = function(event) {
            var img = document.getElementById('btn-upload');
            img.style.background = 'url(' + event.target.result + ')';
            img.style.backgroundPosition = 'center center';
            img.style.backgroundRepeat = 'no-repeat';
            img.style.backgroundSize = 'cover';
        }
        reader.readAsDataURL(file);
    });
})();