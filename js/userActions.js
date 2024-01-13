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

const url = "http://localhost:4010//~1pietrzyk/zad/hanoiTower/php/";
const xhr = new XMLHttpRequest();
function _register(form) {
    var user = {};
    user.username = form.username.value;
    user.password = form.password.value;
    txt = JSON.stringify(user);
    xhr.open("POST", url + "register", true);
    xhr.setRequestHeader('Content-Type', 'application/json')
    xhr.addEventListener("load", e => {
       if (xhr.status == 200) {
        //   document.getElementById('data').innerHTML = '';
        //   document.getElementById('result').innerHTML = JSON.stringify(xhr.response);
        cancelAction();
       }

       else {
        try {
            const errorResponse = JSON.parse(xhr.response);
            if (errorResponse && errorResponse.msg) {
                alert('Error: ' + errorResponse.msg);
            } else {
                alert('Error: Unknown error occurred.');
            }
        } catch (e) {
            alert('Error: Unable to parse the error response.');
            console.error('Error response:', xhr.response);
        }
       }
    })
    xhr.send(txt);
 }
 
 function _login(form) {
    var user = {};
    user.username = form.username.value;
    user.password = form.password.value;
    txt = JSON.stringify(user);
    xhr.open("POST", url + "login", true);
    xhr.setRequestHeader('Content-Type', 'application/json')
    xhr.addEventListener("load", e => {
       if (xhr.status == 200) {
        //   document.getElementById('data').innerHTML = '';
        //   document.getElementById('result').innerHTML = JSON.stringify(xhr.response);
        cancelAction();
       }

       else {
        try {
            const errorResponse = JSON.parse(xhr.response);
            if (errorResponse && errorResponse.msg) {
                alert('Error: ' + errorResponse.msg);
            } else {
                alert('Error: Unknown error occurred.');
            }
        } catch (e) {
            alert('Error: Unable to parse the error response.');
            console.error('Error response:', xhr.response);
        }
       }
    })
    xhr.send(txt);
 }