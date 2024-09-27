function getProductDetails(id) {
    return new Promise((resolve, reject) => {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var result = JSON.parse(this.responseText);
                showAlert(result.message, result.status);
                if (result.status == "success") {
                    resolve(result);
                } else {
                    reject(result);
                }
            }
        };
        xmlhttp.open("POST", `services/getProductDetails.php`, true);
        xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xmlhttp.send("id=" + id);
    })
}