<?php
session_start();

$isSessionSet = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
?>
<!DOCTYPE html>

<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Wieża Hanoi</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <header>
    <h1>Wieża Hanoi</h1>
    <div id="userButtons">
      <button id="loginButton" onclick="showLoginPanel()">Zaloguj</button>
      <button id="registerButton" onclick="showRegistrationPanel()">Zarejestruj</button>
      <button id="logoutButton" onclick="_logout()">Logout</button>
    </div>
  </header>

  <div id="content">
    <h2 onclick="toggleContent('whatIsHanoi')">Czym jest Wieża Hanoi?</h2>
    <div id="whatIsHanoi" class="collapsed">
      <ul>
        <li>
          <p>Wieża Hanoi to klasyczny problem w dziedzinie informatyki i matematyki. Jest to gra matematyczna, w której
            bierze
            udział trzy słupki oraz pewna liczba dysków różnej wielkości, które można przesuwać na dowolny słupek.
            Puzzle
            rozpoczyna się od ułożenia dysków w porządku rosnącym na jednym słupku, gdzie najmniejszy znajduje się na
            górze.</p>
        </li>
      </ul>
    </div>
    <h2 onclick="toggleContent('legend')">Legenda:</h2>
    <div id="legend" class="collapsed">
      <ul>
        <li>W wielkiej świątyni w Waranasi (w oryg. Benares, dawna nazwa tego miasta) znajdują się trzy diamentowe
          słupki.
          Przy stworzeniu świata Bóg umieścił na jednym z nich 64 złote krążki, umieszczone od największego do
          najmniejszego, co zostało nazwane wieżą Brahmy. Każdej nocy i każdego dnia kapłani przenoszą krążki ze
          słupka
          na słupek, zgodnie z zasadami narzuconymi przez Brahmę. Według nich kapłan może przemieszczać tylko jeden
          krążek równocześnie i może go umieścić albo na pustym słupku, albo na szczycie większego krążka. Gdy
          kapłani
          przeniosą całą wieżę z jednego słupka na dowolny inny, wieża i bramini rozsypią się w pył i nastąpi koniec
          świata.
          Gdyby legenda była prawdziwa, a kapłani byliby w stanie przemieszczać krążki w tempie jednego na sekundę,
          używając najmniejszej liczby ruchów, zajęłoby im to 2^64 - 1 sekundy, czyli około 585 miliardów lat, co
          jest
          około 42 razy dłuższe niż obecny wiek wszechświata.
      </ul>
    </div>
    <h2 onclick="toggleContent('gameRules')">Zasady Gry:</h2>
    <div id="gameRules" class="collapsed">
      <ul>
        <li>Można przesuwać tylko jeden dysk naraz.</li>
        <li>Każdy ruch polega na wzięciu górnego dysku z jednego ze słupków i umieszczeniu go na szczycie innego słupka
          lub na pustym słupku.</li>
        <li>Żaden dysk nie może być umieszczony na większym dysku.</li>
      </ul>
    </div>

    <h2 onclick="toggleContent('algorithm')">Algorytm:</h2>
    <div id="algorithm" class="collapsed">
      <p>Wieże Hanoi można łatwo rozwiązać za pomocą prostego algorytmu rekurencyjnego lub iteracyjnego. Minimalna
        liczba
        ruchów potrzebnych do
        rozwiazania Wieży Hanoi to 2^n - 1, gdzie n to liczba dysków.</p>
      <ul>
        <li>Oznaczmy kolejne słupki literami A, B i C.</li>
        <li>Niech n będzie liczbą krążków, które chcemy przenieść ze słupka A na słupek C posługując się słupkiem B jako
          buforem. </li>
      </ul>
      <h3>Rozwiązanie rekurencyjne:</h2>
        <ol>
          <li>przenieś (rekurencyjnie) n-1 krążków ze słupka A na słupek B posługując się słupkiem C</li>
          <li>przenieś jeden krążek ze słupka A na słupek C,</li>
          <li>przenieś (rekurencyjnie) n-1 krążków ze słupka B na słupek C posługując się słupkiem A</li>
        </ol>
        <h3>Przykładowa implementacja w języku C++</h3>
        <pre>
      void hanoi(int n, char A, char B, char C)
      {
        // przekłada n krążków z A korzystając z B na C
        if (n > 0)
        {
          hanoi(n-1, A, C, B);
          cout << A << " -> " << C << endl;
          hanoi(n-1, B, A, C);
        }
      }
    </pre>
    </div>
  </div>


  <div id="hanoiTower">
    <h2 id="animation">Przedstawienie algorytmu</h2>
    <svg id="tower" width="600" height="400"></svg>
    <div>
      <label for="diskNumber">Wybierz ilość krążków:</label>
      <select id="diskNumber">
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
      </select>

      <label for="animationSpeed">Szybkość animacji:</label>
      <input type="range" id="animationSpeed" min="1" max="10" value="5">

      <button id="solveButton">Rozwiąż</button>
      <button id="resetButton">Resetuj</button>
      <button id="savePreferencesButton" onclick="savePreferences()">Zapisz preferencje</button>
      <button id="loadPreferencesButton" onclick="setUserPreferences()">Ustaw zapisane preferencje</button>
    </div>
  </div>

  <div>

  </div>


  <div id="loginPanel" style="display: none">
    <h1>Logowanie</h1>
    <form id="loginForm">
      <label for="loginUsername">Nazwa użytkownika:</label>
      <input type="text" id="loginUsername" name="username" required>

      <label for="loginPassword">Hasło:</label>
      <input type="password" id="loginPassword" name="password" required>

      <button type="button" onclick="_login(this.form)">Zaloguj</button>
      <button type="button" onclick="cancelAction()">Anuluj</button>

    </form>
  </div>
  <div id="registrationPanel" style="display: none">
    <h1>Rejestracja</h1>
    <form id="registrationPForm">
      <label for="registerUsername">Nazwa użytkownika:</label>
      <input type="text" id="registerUsername" name="username" required>

      <label for="registerPassword">Hasło:</label>
      <input type="password" id="registerPassword" name="password" required>

      <button type="button" onclick="_register(this.form)">Zarejestruj</button>
      <button type="button" onclick="cancelAction()">Anuluj</button>
    </form>
  </div>
  <script>
    function toggleContent(id) {
      var element = document.getElementById(id);
      element.classList.toggle('collapsed');
      element.classList.toggle('expanded');
    }
  </script>
  <script>
    var isSessionSet = <?php echo json_encode($isSessionSet); ?>;
    function checkSessionStatus() {
      if (isSessionSet) {
        document.getElementById('registerButton').style.display = 'none';
        document.getElementById('loginButton').style.display = 'none';
        document.getElementById('logoutButton').style.display = 'inline-block';
        document.getElementById('savePreferencesButton').style.display = 'inline-block';
        document.getElementById('loadPreferencesButton').style.display = 'inline-block';
      } else {
        document.getElementById('registerButton').style.display = 'inline-block';
        document.getElementById('loginButton').style.display = 'inline-block';
        document.getElementById('logoutButton').style.display = 'none';
        document.getElementById('savePreferencesButton').style.display = 'none';
        document.getElementById('loadPreferencesButton').style.display = 'none';


      }
    }
    checkSessionStatus();
  </script>
  <script src="js/hanoi.js"></script>
  <script src="js/userActions.js"></script>

</body>

</html>