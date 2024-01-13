const registrationPanel = document.getElementById("registrationPanel");
const mainPanel = document.getElementById("hanoiTower");
const loginPanel = document.getElementById("loginPanel");
function collapseAll() {
    loginPanel.style.display = "none";
    registrationPanel.style.display = "none";
    mainPanel.style.display = "none";
}

function showLoginPanel() {
    collapseAll();
    loginPanel.style.display = "block";
}

function showRegistrationPanel() {
    collapseAll();
    registrationPanel.style.display = "block";
}

function cancelAction()
{
    collapseAll();
    mainPanel.style.display = "flex";
}
