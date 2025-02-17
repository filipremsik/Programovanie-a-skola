
const menu = document.getElementById('menu');
const menuToggle = document.getElementById('menuToggle');
const menuClose = document.getElementById('menuClose');
const authPriceNav = document.getElementById('numberOfCartItemAuth');

// const searchResultsHolder = document.getElementById("searchResultsHolder");
// const searchResults = document.getElementById("searchResults");

// let timerItself;


fetch('/cart-items/count',{method: 'GET'})
.then(response => response.json())
.then(data => {
    console.log(data);
    authPriceNav.innerHTML = data;
});

const price = document.getElementById('priceChange');
const priceHolder = document.getElementById('priceHolder');


menuToggle.addEventListener('click', () => {
    menuToggle.classList.toggle('max-lg:block');
    menu.classList.toggle('hidden');
    menuClose.classList.toggle('hidden');

    const searchInputSmall = document.getElementById('searchBarInputSmall');
    
    searchInputSmall.addEventListener('focusout', () => {
        searchInputSmall.value = '';
        setTimeout(function(){
            searchResults.classList.add('hidden');
        },500);
    });
    
    searchInputSmall.addEventListener('input', () => {
        clearTimeout(timerItself);
    
        let query = searchInputSmall.value;
        searchResultsHolder.innerHTML = '';
    
        if (query.length === 0) {
            return;
        }
        
        timerItself=setTimeout(function(){
            fetch('/api/v1/search/products?search=' + query, {method: 'GET'})
                .then(response => response.json())
                .then(data => {
                    console.log(data);
    
                    searchResults.classList.remove('hidden');
    
                    console.log(data.length);
    
                    data.forEach(product => {
                        const productDiv = document.createElement('div');
                        productDiv.classList.add('flex','justify-between');
                        productDiv.innerHTML = `
                        <a href="/single-page/${product.id}" class=" hover:font-bold">${product.name}</a>
                        <p>${product.price} €</p>
                        `;
                        searchResultsHolder.appendChild(productDiv);
                        console.log(searchResultsHolder);
                    });
    
                    if (data.length === 0) {
                        let productDiv = document.createElement('div');
                        productDiv.innerHTML = `
                            <p>No results found</p>
                        `;
                        searchResultsHolder.appendChild(productDiv);
                    }
                    
                })
                .catch(error => {
                    console.error(error);
                });
        },500);
    });
    
    searchInputSmall.addEventListener('focusout', () => {
        searchInputSmall.value = '';
        setTimeout(function(){
            searchResults.classList.add('hidden');
        },500);
    });


});

menuClose.addEventListener('click', () => {
    menuToggle.classList.toggle('max-lg:block');
    menu.classList.toggle('hidden');
    menuClose.classList.toggle('hidden');
});


