const regex1 = /^decrementQuantity(\d+)$/;
const regex2 = /^incrementQuantity(\d+)$/;
const regex3 = /^cartItemId(\d+)$/;

const allElements = document.body.getElementsByTagName("*");

const matchedElements1 = Array.from(allElements).filter(element => regex1.test(element.id));
const matchedElements2 = Array.from(allElements).filter(element => regex2.test(element.id));
const matchedElements3 = Array.from(allElements).filter(element => regex3.test(element.id));

const quantityInputs = Array.from(document.body.getElementsByTagName("input"));

const transportElement = document.getElementById("transportPrice");
const preTotalPrice = document.getElementById("preTotalPrice");
const finalPrice = document.getElementById("finalPrice");
const taxLessPrice = document.getElementById("taxLessPrice");

const totalPriceInputFinal = document.getElementById("totalPriceInputFinal");

const additionalPaymentDiv = document.getElementsByClassName("additionalPayment");

let additionalPrice=0;
let timer=null;

matchedElements1.forEach((element,index) => {
    const cart_item_id = matchedElements3[index];
    const id = element.id.match(regex1)[1];        
    const quantity_element = document.getElementById(`quantity${id}`);

    quantity_element.addEventListener("input",()=>{
        clearTimeout(timer)

        console.log(quantity_element.value,cart_item_id.value);

        if(quantity_element.value<0 || quantity_element.value>50){
            quantity_element.value=1;
        }

        timer = setTimeout(function(){

            this.fetchQuantity(cart_item_id,quantity_element)
        },500);
    });

    element.addEventListener("click", () => {
        clearTimeout(timer)

        const quantity = document.getElementById(`quantity${id}`);
        if (quantity.value > 1) {
            quantity.value--;
        }
        else  {
            console.log("Deleted item's id: ", cart_item_id.value)

            fetch('api/v1/cart-items/delete/' + cart_item_id.value,
                {method: "DELETE"})
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    document.getElementById('cartItemDiv'+id).remove();
                    
                    var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                    window.history.replaceState({ path: newurl }, '', newurl);
                });
        }

        timer=setTimeout(function(){
        this.fetchQuantity(cart_item_id,quantity_element)
        },500);

    });
});

matchedElements2.forEach((element,index) => {
    element.addEventListener("click", () => {
        clearTimeout(timer);

        const id = element.id.match(regex2)[1];
        const cart_item_id = matchedElements3[index];       
        const quantity_element = document.getElementById(`quantity${id}`);

        if(quantity_element.value<50){
            quantity_element.value++;
        }

        timer=setTimeout(function(){
            this.fetchQuantity(cart_item_id,quantity_element);
        },500);
    
    });
});


quantityInputs.forEach(input => {
    input.addEventListener("input", function(){
        input.checked=true;

        switch(input.value){
            case "osobny_odber":
                additionalPrice+=0;
                break;
            case "SP" :
                additionalPrice+=3.75;
                transportElement.innerHTML="3,75 €";

                break;
            case "packeta" :
                additionalPrice+=5.50;
                transportElement.innerHTML="5,50 €";

                break;
            case "dobierka" :
                additionalPrice+=2.00;

                break;
            case "kartou_online" :
                additionalPrice+=0;

                break;
        }
        preTotalPriceFormatted = preTotalPrice.innerHTML.replace(" €", "");
        preTotalPriceFormatted = preTotalPriceFormatted.replace(" ", "");
        preTotalPriceFormatted = preTotalPriceFormatted.replace(",", ".");

        display_price = (parseFloat(preTotalPriceFormatted) + additionalPrice).toFixed(2).toString() + ' €';

        finalPrice.innerHTML = display_price.replace(".", ",");
        totalPriceInputFinal.value = (parseFloat(preTotalPriceFormatted) + additionalPrice).toFixed(2);
    });
});

function fetchQuantity(cart_item_id, quantity){
    fetch('api/v1/cart-items/update-quantity?cart_item_id=' + cart_item_id.value + '&quantity=' + quantity.value,
        {method: "PUT", headers: {"Content-Type": "application/json"}})
        .then(response => response.json())
        .then(data => {
            console.log(data);
            window.location.reload();
        });
}

