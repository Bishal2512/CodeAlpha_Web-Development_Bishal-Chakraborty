let currentPage = 1;

function showPage(pageNumber) {
    const pages = document.getElementsByClassName("page");
    for (let i = 0; i < pages.length; i++) {
        pages[i].style.display = "none";
    }
    document.getElementById(`page${pageNumber}`).style.display = "block";
    currentPage = pageNumber;
}

function nextPage() {
    saveFormData();
    if (currentPage < 5) {
        showPage(currentPage + 1);
    }
}

function prevPage() {
    saveFormData();
    if (currentPage > 1) {
        showPage(currentPage - 1);
    }
}

function saveFormData() {
    const form = document.getElementById("surveyForm");
    const formData = new FormData(form);
    for (const pair of formData.entries()) {
        localStorage.setItem(pair[0], pair[1]);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    showPage(1);
});