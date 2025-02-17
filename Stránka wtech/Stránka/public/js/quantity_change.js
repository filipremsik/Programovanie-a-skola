const incrementQuantity = document.getElementById('incrementQuantity');
const decrementQuantity = document.getElementById('decrementQuantity');
const quantity = document.getElementById('quantityValue');

incrementQuantity.addEventListener('click', function(){
    quantity.value=parseInt(quantity.value)+1;
});

decrementQuantity.addEventListener('click', function(){
    if(quantity.value>1){
        quantity.value=parseInt(quantity.value)-1;
    }
});