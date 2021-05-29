$( "#invoice-form" ).submit(function( event ) {
    let userid = "userid";
    let pw = "pw";
    if (document.getElementById('userid')) {
        userid = document.getElementById('userid').value;
    }

    if (document.getElementById('pw')) {
        pw = document.getElementById('pw').value;
    }

    let full_name = document.getElementById('full-name').value;
    let addr_1 = document.getElementById('addr-1').value;
    let city = document.getElementById('city').value;
    let country = document.getElementById('country').value;
    let zip_code = document.getElementById('zip-code').value;

    if (userid == "" || pw == "" || full_name == "" || addr_1 == ""
        || city == "" || country == "" || zip_code == "") {
        alert('Please do not leave the fields empty');
    }
    else if (document.getElementById('warning').style.display != "none") {
        alert("Username Duplicated!")
    }
    else {
        this.submit();
    }
    event.preventDefault();
});

if (document.getElementById('userid')) {
    $("#userid").blur(function (event) {
        checkDuplicate(document.getElementById('userid').value);
    })
}

function checkDuplicate(userid) {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            let valid = this.responseText.includes("VALID ACCOUNT");
            if (valid) {
                document.getElementById('warning').style.display = "none";
                console.log("valid");
            }
            else {
                document.getElementById('warning').style.display = "block";
                console.log("not valid");
            }
        }
    };
    xhttp.open("GET", `create.php?action=checkoutValidate&userid=${userid}`, true);
    xhttp.send();
}