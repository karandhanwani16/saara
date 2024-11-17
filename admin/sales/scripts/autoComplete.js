const searchInput = document.querySelector('#customer-name');
const autoCompleteCont = document.querySelector('.auto--complete');
const resultCont = document.querySelector('.result--cont');
let isSearchOpen = false;
let customerEmail = document.querySelector("#customer-email");
let customerPhone = document.querySelector("#customer-phone");

function loadSearchResult(data) {
    resultCont.innerHTML = '';
    data.forEach(d => {
        const searchResultDiv = document.createElement('div');
        searchResultDiv.className = "result--option";
        searchResultDiv.innerText = d;

        //add event listener to each result
        searchResultDiv.addEventListener('click', e => {
            searchInput.value = e.target.innerText;
            resultCont.classList.add('hidden');
            customerEmail.value = "";
            customerPhone.value = "";
            loadCustomerDetails(e.target.innerText);
            hideSearchInput();
        })

        resultCont.appendChild(searchResultDiv);
    })
}

async function loadCustomerDetails(customerName) {
    const data = await getCustomerDetails(customerName);
    if (data.status === "success") {
        loadCustomerDetailsView(data.data);
    } else {
        alert(data.error);
    }
}

function loadCustomerDetailsView(data) {
    customerEmail.value = data.email;
    customerPhone.value = data.phone;
}

function getCustomerDetails(customerName) {
    return new Promise((resolve, reject) => {
        fetch("./services/loadCustomerDetails.php", {
            method: "POST",
            body: JSON.stringify({
                name: customerName
            })
        })
            .then(response => {
                if (!response.ok) {
                    reject({
                        status: "error",
                        error: "Network response was not ok"
                    })
                }
                return response.json();
            })
            .then(data => {
                resolve({
                    status: "success",
                    data
                });
            })
            .catch(error => {
                reject({
                    status: "error",
                    error
                });
            });
    })
}

async function fetchCustomerData(searchTerm) {
    if (searchTerm !== '') {
        resultCont.innerHTML = "<div class='result--option--disabled'>Loading...</div>";
        const data = await getData(searchTerm);
        if (data.length > 0) {
            loadSearchResult(data);
        } else if (data.length === 0) {
            resultCont.innerHTML = "<div class='result--option--disabled'>No results found</div>";
        }
    }
}


function hideSearchInput() {
    isSearchOpen = false;
    autoCompleteCont.classList.remove('auto--complete--focused');
    resultCont.classList.add('hidden');
}

searchInput.addEventListener('keyup', e => {
    customerEmail.value = "";
    customerPhone.value = "";
    fetchCustomerData(e.target.value);
})

searchInput.addEventListener('focus', e => {
    isSearchOpen = true;
    autoCompleteCont.classList.add('auto--complete--focused');
    resultCont.classList.remove('hidden');
})

document.addEventListener('click', e => {
    if (e.target !== searchInput) {
        if (isSearchOpen) {
            hideSearchInput();
        }
    }
})


function getData(searchValue) {
    return new Promise((resolve, reject) => {
        fetch("./services/getSearchData.php", {
            method: "POST",
            body: JSON.stringify({
                value: searchValue
            })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                resolve(data);
            })
            .catch(error => {
                reject(error);
            });
    });
}

