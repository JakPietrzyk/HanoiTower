<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require '../vendor/autoload.php';
require_once("rest.php");
require_once("database.php");

class API extends REST
{

    public $data = "";
    public $db;

    public function __construct()
    {
        parent::__construct();      // Init parent contructor
        $this->db = new db();             // Initiate Database
    }

    public function processApi()
    {

        $func = "_" . $this->_endpoint;
        if ((int) method_exists($this, $func) > 0) {
            $this->$func();
        } else {
            $this->response('Page not found', 404);
        }
    }


    private function _register()
    {
        // if($this->get_request_method() != "POST") {
        //     $this->response('',406);
        // }

        if (!empty($this->_request)) {
            try {

                $res = $this->db->addUser(json_decode($this->_request, true));
                if ($res) {
                    $result = array('return' => 'ok');
                    $this->response($this->json($result), 200);
                } else {
                    $result = array('return' => 'not added');
                    $this->response($this->json($result), 400);
                }
            } catch (Exception $e) {
                $this->response(json_encode(['msg' => 'Error processing request']), 500);

            }
        } else {
            $error = array('status' => "Failed", "msg" => "Invalid send data");
            $this->response($this->json($error), 400);
        }
    }


    private function _login()
    {
        // if($this->get_request_method() != "POST") {
        //     $this->response('',406);
        // }

        if (!empty($this->_request)) {
            try {
                $isLoginValid = $this->db->validateUser(json_decode($this->_request, true));
                if ($isLoginValid) {
                    // session_start();
                    $_SESSION['loggedIn'] = true;
                    setcookie('user_authenticated', true, time() + (86400 * 30), "/");
                    $result = array('return' => 'ok');
                    $this->response($this->json($result), 200);
                } else {
                    $error = array('status' => "Failed", "msg" => "Invalid send data");
                    $this->response($this->json($error), 400);
                }


            } catch (Exception $e) {
                $this->response(json_encode(['msg' => 'Error processing request']), 500);

            }
        } else {
            $error = array('status' => "Failed", "msg" => "Invalid send data");
            $this->response($this->json($error), 400);
        }
    }

    function _logout()
    {
        session_start();
        $_SESSION = [];
        session_destroy();
        $result = array('return' => 'ok');
        $this->response($this->json($result), 200);
    }

    function _sessionStatus()
    {
        session_start();
        if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
            $response = ['status' => 'active'];
        } else {
            $response = ['status' => 'inactive'];
        }
        $this->response($this->json($response), 200);
    }

    function _savePreferences()
    {
        session_start();
        try {
            if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
                $userPreferences = json_decode($this->_request, true);
                if (!empty($userPreferences)) {
                    $this->db->addPreferences($userPreferences);
                    $response = ['status' => 'ok'];
                    $this->response($this->json($response), 200);
                } else {
                    $error = ['status' => 'Failed', 'msg' => 'Invalid preferences data'];
                    $this->response($this->json($error), 400);
                }
            } else {
                $error = ['status' => 'Failed', 'msg' => 'User not logged in'];
                $this->response($this->json($error), 401);
            }
        } catch (Exception $e) {
            $error = ['status' => 'Failed', 'msg' => 'Error processing request'];
            $this->response($this->json($error), 500);
        }
    }

    function _getPreferences()
    {
        session_start();
        try {
            if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
                $preferences = $this->db->getPreferences();
                $response = ['status' => 'ok', 'preferences' => $preferences];
                header('Content-Type: application/json');
                $this->response($this->json($response), 200);
            } else {
                $error = ['status' => 'Failed', 'msg' => 'User not logged in'];
                $this->response($this->json($error), 401);
            }
        } catch (Exception $e) {
            $error = ['status' => 'Failed', 'msg' => 'Error processing request'];
            $this->response($this->json($error), 500);
        }
    }

    private function json($data)
    {
        if (is_array($data)) {
            return json_encode($data);
        }
    }
}

$api = new API;
$api->processApi();

?>