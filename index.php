<?php
session_start();

$isSessionSet = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Hanoi Tower</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <header>
    <h1>Hanoi Tower</h1>
    <div id="userButtons">
      <button id="loginButton" onclick="showLoginPanel()">Login</button>
      <button id="registerButton" onclick="showRegistrationPanel()">Register</button>
      <button id="logoutButton" onclick="_logout()">Logout</button>
    </div>
  </header>

  <div id="hanoiTower">
    <svg id="tower" width="600" height="400"></svg>
    <div>
      <label for="diskNumber">Select number of disks:</label>
      <select id="diskNumber">
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
      </select>

      <label for="animationSpeed">Animation Speed:</label>
      <input type="range" id="animationSpeed" min="1" max="10" value="5">

      <button id="solveButton">Solve</button>
      <button id="resetButton">Reset</button>
      <button id="savePreferencesButton" onclick="savePreferences()">Save Preferences</button>
    </div>
  </div>

  <div id="loginPanel" style="display: none">
    <h2>Login</h2>
    <form id="loginForm">
      <label for="loginUsername">Username:</label>
      <input type="text" id="loginUsername" name="username" required>

      <label for="loginPassword">Password:</label>
      <input type="password" id="loginPassword" name="password" required>

      <button type="button" onclick="_login(this.form)">Login</button>
      <button type="button" onclick="cancelAction()">Cancel</button>

    </form>
  </div>
  <div id="registrationPanel" style="display: none">
    <h2>Register</h2>
    <form id="registrationPForm">
      <label for="registerUsername">Username:</label>
      <input type="text" id="registerUsername" name="username" required>

      <label for="registerPassword">Password:</label>
      <input type="password" id="registerPassword" name="password" required>

      <button type="button" onclick="_register(this.form)">Register</button>
      <button type="button" onclick="cancelAction()">Cancel</button>
    </form>
  </div>
  </div>
  <script>
    var isSessionSet = <?php echo json_encode($isSessionSet); ?>;
    if (isSessionSet) {
      document.getElementById('registerButton').style.display = 'none';
      document.getElementById('loginButton').style.display = 'none';
      document.getElementById('logoutButton').style.display = 'inline-block';
      // document.getElementById('savePreferencesButton').style.display = 'inline-block';
    } else {
      document.getElementById('registerButton').style.display = 'inline-block';
      document.getElementById('loginButton').style.display = 'inline-block';
      document.getElementById('logoutButton').style.display = 'none';
      document.getElementById('savePreferencesButton').style.display = 'none';
    }
  </script>
  <script src="js/hanoi.js"></script>
  <script src="js/userActions.js"></script>
</body>

</html>