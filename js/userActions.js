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



// function checkSessionStatus() {
//     if (isSessionSet) {
//         document.getElementById('registerButton').style.display = 'none';
//         document.getElementById('loginButton').style.display = 'none';
//         document.getElementById('logoutButton').style.display = 'inline-block';
//         document.getElementById('savePreferencesButton').style.display = 'inline-block';
//     } else {
//         document.getElementById('registerButton').style.display = 'inline-block';
//         document.getElementById('loginButton').style.display = 'inline-block';
//         document.getElementById('logoutButton').style.display = 'none';
//         document.getElementById('savePreferencesButton').style.display = 'none';
//     }
// }

function _register(form) {
    var user = {};
    user.username = form.username.value;
    user.password = form.password.value;
    txt = JSON.stringify(user);
    xhr.open("POST", url + "register", true);
    xhr.setRequestHeader('Content-Type', 'application/json')
    xhr.addEventListener("load", e => {
       if (xhr.status == 200) {
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
        isSessionSet = true;
        checkSessionStatus();
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

 function _logout()
 {
    xhr.open("POST", url + "logout", true);
    xhr.setRequestHeader('Content-Type', 'application/json')
    xhr.addEventListener("load", e => {
       if (xhr.status == 200) {
        isSessionSet = false;
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
    xhr.send('{}');
 }

function savePreferences()
{
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

