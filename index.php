<?php

include_once('class/HTMLPage.class.php');
include_once('class/Auth.class.php');

$html = new HTMLPage();
$auth = new Auth();

$errormsg = '';
if (isset($_POST['login'])) {
    $auth->login($_POST['user'], $_POST['pass']);
    exit();
}

if (isset($_GET['logout'])) {
    $auth->logout();
}

if (isset($_GET['error'])) {
    $error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_NUMBER_INT);
    if ($error == 401) {
        $errormsg = '<div class="error">Bitte geben Sie einen korrekten Benutzernamen und Passwort ein!</div>';
    }
}


print $html->head('Good News');
if($auth->checkLogin()){
    print '
    <div class="container">
        <section id="loginform">
            <h2>Good News</h2>
            '. $errormsg .'
            <form action="index.php" method="POST">
                <p>
                    <input type="text" name="user" placeholder="Benutzername">
                </p>
                <p>
                    <input type="password" name="pass" placeholder="Passwort">
                </p>
                <p>
                    <input type="submit" name="login" value="Login">
                </p>
            </form>
        </section>
    </div>';
}
print $html->foot();