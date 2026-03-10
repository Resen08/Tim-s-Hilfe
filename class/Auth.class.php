<?php

class Auth {


    private $db;

    public function __construct() {
        include('DB.class.php');
        $dbc = new DB();
        $this->db = $dbc->getDB();
    }

    public function login($user, $pass) {

        $user = $this->db->real_escape_string($user);
        $pass = $this->db->real_escape_string($pass);
        $stmt = "SELECT pass FROM userDB WHERE user = '$user'";
        $result = $this->db->query($stmt);
        if ($row = $result->fetch_assoc()) {
            if (password_verify($pass, $row['pass'])) {
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $user;
                header("Location: dashboard.php");
                return;
            }
        }
        $this->log($user);            
        header('Location: index.php?error=401');

    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: index.php');
    }

    private function log($user) {
        $stmt = "INSERT INTO loginlog (ip, user) VALUE ('$_SERVER[REMOTE_ADDR]', '$user')";
        $this->db->query($stmt);

    }

    public function checkLogin(){
        $stmt = "SELECT COUNT(*) as anzahl FROM loginlog WHERE ts > (NOW() - INTERVAL 10 MINUTE) AND ip = '$_SERVER[REMOTE_ADDR]'";
        $result = $this->db->query($stmt);
        $row = $result->fetch_assoc();
        if ($row['anzahl'] > 5){
            return false;
        }
        return true;
    }

    private function createSecret($user){
        $secret = hash($_SERVER['RMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
        $stmt = "UPDATE user SET secret='$secret' WHERE user='$user'";
        $this->db->query($stmt);
    }

    public function checkAuth(){     
        if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
            header("Location: index.php");
            exit();
        }
        $secret = hash($_SERVER['RMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
        $stmt = "UPDATE * SET secret='$secret' WHERE user='$_SESSION[user]'";
        $this->db->query($stmt);
        if($this->db->affected_rows !=1){
            header("Location: index.php");
            exit();
        }
    }
}

