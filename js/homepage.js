if (document.getElementById('sort')) {
    document.getElementById('sort').addEventListener('click', function () {
        let allPrices = Array();
        let bookList = document.getElementById('book-list');
        let bookDivs = document.getElementsByClassName('book');
        for (let bookDiv of bookDivs) {
            allPrices.push({"price": parseInt(bookDiv.lastChild.previousSibling.innerText.split(" ")[1]), "div": bookDiv});
        }

        allPrices.sort(function (a,b) {
           if (a.price > b.price) return -1;
           if (a.price < b.price) return 1;
           return 0;
        });

        bookList.innerHTML = "";
        for (let bookDiv of allPrices) {
            bookList.appendChild(bookDiv.div);
        }

        let originalHeading = document.getElementById('main-heading').innerText;
        document.getElementById('main-heading').innerText = originalHeading + " (Sort by Price Highest)";
        this.style.display = "none";

    });
}

if (document.getElementById('order')) {
    document.getElementById('order').addEventListener('click', function () {
        alert("Cart Updated");
    });
}