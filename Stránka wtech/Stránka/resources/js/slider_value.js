const slider = document.getElementById('sliderPrice');
const output = document.getElementById('inputPrice');

output.value = slider.value;

slider.addEventListener('input', function(){
    output.value=this.value;
});