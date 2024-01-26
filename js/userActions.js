const registrationPanel = document.getElementById("registrationPanel");
const mainPanel = document.getElementById("hanoiTower");
const loginPanel = document.getElementById("loginPanel");
const content = document.getElementById("content");
function collapseAll() {
    loginPanel.style.display = "none";
    registrationPanel.style.display = "none";
    mainPanel.style.display = "none";
    content.style.display = "none";
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
    content.style.display = "block";
}

function _register(form) {
    xhr = new XMLHttpRequest();
    var user = {};
    user.username = form.username.value.trim();
    user.password = form.password.value.trim();

    if (!user.username  || !user.password) {
        alert('Login i hasło nie mogą być puste!');
        return;
    }

    txt = JSON.stringify(user);
    xhr.open("POST", url + "register", true);
    xhr.setRequestHeader('Content-Type', 'application/json')
    xhr.addEventListener("load", e => {
       if (xhr.status == 200) {
        cancelAction();
       }
       else if(xhr.status == 400)
       {
        alert("Login jest już zajęty");
       }
       else
       {
        alert(xhr.response);
       }
    })
    xhr.send(txt);
 }
 
 function _login(form) {
    xhr = new XMLHttpRequest();
    var user = {};
    user.username = form.username.value;
    user.password = form.password.value;
    txt = JSON.stringify(user);
    xhr.open("POST", url + "login", true);
    xhr.setRequestHeader('Content-Type', 'application/json')
    xhr.addEventListener("load", e => {
       if (xhr.status == 200) {
        isSessionSet = true;
        checkSessionStatus();
        cancelAction();
       }
       else if(xhr.status == 400)
       {
        alert("Niepoprawne dane logowania");
       }
       else
       {
        alert(xhr.response);
       }
       return;
    })
    xhr.send(txt);
 }

 function _logout()
 {
    xhr = new XMLHttpRequest();
    xhr.open("POST", url + "logout", true);
    xhr.setRequestHeader('Content-Type', 'application/json')
    xhr.addEventListener("load", e => {
       if (xhr.status == 200) {
        isSessionSet = false;
        checkSessionStatus();
       }
       else
       {
        alert(xhr.response);
       }
    })
    xhr.send('{}');
 }

function savePreferences()
{
    xhr = new XMLHttpRequest();
    var preferences = {};
    let numberOfDisks = parseInt(document.getElementById('diskNumber').value);
    let animationSpeed = parseInt(document.getElementById('animationSpeed').value);
    preferences.numberOfDisks = numberOfDisks;
    preferences.animationSpeed = animationSpeed;
    txt = JSON.stringify(preferences);
    xhr.open("POST", url + "savePreferences", true);
    xhr.setRequestHeader('Content-Type', 'application/json')
    xhr.addEventListener("load", e => {
       if (xhr.status == 200) {
        checkSessionStatus();
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

