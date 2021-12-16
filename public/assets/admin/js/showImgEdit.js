var img = document.getElementById('btn-upload');
var imgurl = img.dataset.url;

if(imgurl != undefined) {
    img.style.background = 'url(' + imgurl + ')';   
}

img.style.backgroundPosition = 'center center';
img.style.backgroundRepeat = 'no-repeat';
img.style.backgroundSize = 'cover';

(function changeImage() {
    var imgInput = document.getElementById('myinputfile');
    imgInput.addEventListener('change', function(event) {
        var file = event.target.files[0];
        var reader = new FileReader();
        reader.onload = function(event) {
            
            img.style.background = 'url(' + event.target.result + ')';
            img.style.backgroundPosition = 'center center';
            img.style.backgroundRepeat = 'no-repeat';
            img.style.backgroundSize = 'cover';
    
        }
        reader.readAsDataURL(file);
    });
    
})();