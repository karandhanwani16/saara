
class SearchDropdown {
    constructor(mainContainer, optionsList, onChangeCallback) {
        this.wrapper = document.querySelector(`#${mainContainer} .wrapper`);
        this.searchInp = document.querySelector(`#${mainContainer} input`);
        this.selectBtn = document.querySelector(`#${mainContainer} .select-btn`);
        this.options = document.querySelector(`#${mainContainer} .options`);
        this.optionsList = optionsList;
        this.currentValue = "";
        this.currentText = "";
        this.addOptions();
        this.setupEvents();

        // Callback function to be executed on change
        this.onChangeCallback = onChangeCallback;

    }

    addOptions() {
        this.options.innerHTML = "";
        this.optionsList.forEach(option => {
            const li = `<li data-id="${option.id}">${option.name}</li>`;
            this.options.insertAdjacentHTML("beforeend", li);
        });
    }

    addNewOptions(newOptions) {
        this.options.innerHTML = "";
        newOptions.forEach(option => {
            const li = `<li data-id="${option.id}">${option.name}</li>`;
            this.options.insertAdjacentHTML("beforeend", li);
        });
    }
    updateDropdown(selectedValue) {
        this.searchInp.value = "";
        this.addOptions();
        this.wrapper.classList.remove("active");
        this.selectBtn.firstElementChild.innerText = selectedValue;
    }

    setupEvents() {
        this.searchInp.addEventListener("keyup", () => {
            
            const searchWord = this.searchInp.value.toLowerCase();

            // Escape special characters in searchWord to avoid regex errors
            const escapedSearchWord = searchWord.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const regex = new RegExp(escapedSearchWord, 'i'); // 'i' flag for case-insensitive search

            const filteredOptions = this.optionsList.filter(option => regex.test(option.name.toLowerCase()));

            this.addFilteredOptions(filteredOptions);
        });

        this.selectBtn.addEventListener("click", () => {
            this.wrapper.classList.toggle("active")
        }
        );

        this.options.addEventListener("click", (e) => {
            if (e.target.tagName === "LI") {
                this.setSelectedText(e.target.innerText);
                this.setSelectedValue(e.target.attributes["data-id"].value, e.target.innerText);
                this.updateDropdown(e.target.innerText);
            }
        });
    }

    addFilteredOptions(filteredOptions) {
        this.options.innerHTML = "";
        if (filteredOptions.length > 0) {
            filteredOptions.forEach(option => {
                const isSelected = option.id === this.currentValue ? "selected" : "";
                const li = `<li data-id="${option.id}" class="${isSelected}">${option.name}</li>`;
                this.options.insertAdjacentHTML("beforeend", li);
            });
        } else {
            this.options.innerHTML = `<li><div class="add-new-product">Add New Product</div></li>`;

            const addNewProductButton = document.querySelector(".add-new-product");

            addNewProductButton.addEventListener("click", () => {
                this.searchInp.value = "";
                this.addOptions();
                this.wrapper.classList.remove("active");

                isNewProduct = true;

                const searchContainer = document.querySelector("#searchDropdownCont");
                const newProductContainer = document.querySelector("#newProductCont");
                searchContainer.style.display = "none";
                newProductContainer.style.display = "flex";

                const productThickness = document.querySelector("#txtrowproductthickness");
                const productSize = document.querySelector("#txtrowproductsize");
                const productHsn = document.querySelector("#txtrowproducthsn");

                productThickness.classList.remove("disabled");
                productSize.classList.remove("disabled");
                productHsn.classList.remove("disabled");

            });
        }
    }

    setSelectedValue(value, text) {
        this.currentValue = value;

        if (typeof this.onChangeCallback === 'function') {
            this.onChangeCallback(value, text);
        }
    }

    setSelectedText(text) {
        this.currentText = text;
    }

    getSelectedValue() {
        return this.currentValue;
    }

    getSelectedText() {
        return this.currentText;
    }

    reset(placeholder, fullReset = false) {
        this.searchInp.value = "";
        this.wrapper.classList.remove("active");
        this.selectBtn.firstElementChild.innerText = placeholder;
        this.currentValue = "";
        if (fullReset) {
            this.optionsList = [];
            this.addOptions();
        }
    }

}