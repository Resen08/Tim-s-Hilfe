<?php

class Auth {

    const MAX_SESSION_AGE = 1800;
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
                $_SESSION['timestamp'] = time();
                $this->createSecret($user);
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
        $secret = hash('sha256', $_SERVER['RMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
        $stmt = "UPDATE user SET secret='$secret' WHERE user='$user'";
        $this->db->query($stmt);
    }

    public function checkAuth(){     
        $secret = hash($_SERVER['RMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
        $stmt = "SELECT * FROM userDB WHERE secret='$secret' AND user='$_SESSION[user]'";
        $this->db->query($stmt);
        if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true || $this->db->affected_rows !=1){
            header("Location: index.php");
            exit();
        }
    }

    private function sessionRenew(){
        if(isset($_SESSION['timestamp'])){
            $diff = time() - $_SESSION['timestamp'];
            if($diff > self::MAX_SESSION_AGE){
                $_SESSION['timestamp'] = time();
            }
        } else{
            $this->logout();
        }
    }
}

