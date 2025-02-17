const slider = document.getElementById('sliderPrice');
const output = document.getElementById('inputPrice');
const oder_by = document.getElementById('order_by');
const clear_filter = document.getElementById('clearFilter');

let previousText = '';

const regex = /^priceChange(\d+)$/;

const allElements = document.body.getElementsByTagName("*");

const matchedElements = Array.from(allElements).filter(element => regex.test(element.id));

matchedElements.forEach(element => {
    element.addEventListener('mouseenter',function(){
        previousText = this.innerText;
        this.innerText = 'Kúpiť';
    });

    element.addEventListener('mouseleave',function(){
        this.innerText = previousText;
    });
});


output.value = slider.value;

slider.addEventListener('input', function(){
    output.value=this.value;
});

oder_by.addEventListener('change', function(){
    document.getElementById('productsDisplayStart').submit();
});

clear_filter.addEventListener('click', function(){
    $all_generated_selects=document.getElementsByClassName('generatedSelect')
    for (let i = 0; i < $all_generated_selects.length; i++) {
        $all_generated_selects[i].value='all';
    }
    slider.value=0;
    output.value=0;
});