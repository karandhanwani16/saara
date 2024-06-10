function isValid() {
    isValidResult = true;

    inputs.forEach(input => {
        if (input.type != "radio") {

            let isFilled = requiredValidator(input);
            let elementId = input.attributes["data-id"].value;
            if (!isFilled) {
                getCorrespondingErrorText(errorTexts, elementId, errorMessages.blankMessage);
                input.focus();
                input.classList.add("error-inp");
                isValidResult = false;
            } else {
                hideErrorText(errorTexts, elementId);
                input.classList.remove("error-inp");
            }
        }
    });
    dropdowns.forEach(dropdown => {
        let isFilled = requiredValidator(dropdown);
        let elementId = dropdown.attributes["data-id"].value;
        if (!isFilled) {
            getCorrespondingErrorText(errorTexts, elementId, errorMessages.blankMessage);
            dropdown.focus();
            dropdown.classList.add("error-inp");
            isValidResult = false;
        } else {
            hideErrorText(errorTexts, elementId);
            dropdown.classList.remove("error-inp");
        }
    });
    return isValidResult;
}