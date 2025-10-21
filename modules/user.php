<?php
function addFlash($class, $message): void
{
    $_SESSION['flash']['class'] = $class;
    $_SESSION['flash']['message'] = $message;
}

function makeUser(): void
{
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $password = $_POST['password'];
    $repeat = $_POST['repeat'];

    if (empty($username) || empty($password) || empty($repeat)){
        addFlash('danger', 'Vul alle velden in');
        return;
    }

    if (checkUser($username)) {
        addFlash('danger', 'De gebruiker bestaat al');
        return;
    }

    if ($password !== $repeat){
        addFlash('danger', 'De wachtwoorden zijn niet gelijk');
        return;
    }

    $password = password_hash($password, PASSWORD_BCRYPT, ['cost' =>13]);

    global $db;
    try {
        $query = $db->prepare('INSERT INTO user (username, password) VALUES (:username, :password)');
        $query->bindParam('username', $username);
        $query->bindParam('password', $password);
        $query->execute();
        addFlash('success', 'De gebruiker is aangemaakt');
        header('location: /login');
        exit();
    } catch (PDOException $e){
        addFlash('danger', $e->getMessage());
    }


}

function checkUser($username): mixed
{
    global $db;
    $query = $db->prepare('SELECT username, password FROM user WHERE username = :username');
    $query->bindParam('username', $username);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

function login():void
{
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $password = $_POST['password'];
    $user = checkUser($username);
    if (!$user || !password_verify($password, $user['password'])){
        addFlash('danger', 'Je gebruikersnaam of wachtwoord klopt niet');
        return;
    }

    $_SESSION['user'] = $user['username'];
    addFlash('success', 'Je bent ingelogd');
    header('location: /new-poll');
}

function logout()
{
    unset($_SESSION['user']);
    addFlash('success', 'Je bent uitgelogd');
    header('location: /');
}