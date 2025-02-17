const searchInput = document.getElementById("searchBarInput");
const searchResultsHolder = document.getElementById("searchResultsHolder");
const searchResults = document.getElementById("searchResults");

let timerItself;

searchResultsHolder.classList.remove('hidden');

searchInput.addEventListener('input', () => {
    clearTimeout(timerItself);

    let query = searchInput.value;
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

searchInput.addEventListener('focusout', () => {
    searchInput.value = '';
    setTimeout(function(){
        searchResultsHolder.classList.toggle('hidden');
    },500);
});