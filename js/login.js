function validate () {
    let userid = document.getElementById('userid').value;
    let pw = document.getElementById('pw').value;

    if (userid == "" || pw == "") {
        alert('Please do not leave the fields empty');
        return false;
    }
    return true;
}

document.getElementById('create').addEventListener('click', function (){
    location.href = 'new_account.php';
});