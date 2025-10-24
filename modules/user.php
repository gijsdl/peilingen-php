<?php
function addFlash($class, $message): void
{
    $_SESSION['flash'][] = [
        'class' => $class,
        'message' => $message
    ];
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
    $query = $db->prepare('SELECT * FROM user WHERE username = :username');
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

    removeTokenOfUser($user['id']);

    if(!generateToken($user)){
        return;
    }

    $_SESSION['user'] = $user['username'];
    addFlash('success', 'Je bent ingelogd');
    header('location: /new-poll');
    exit();
}

function generateToken($user): bool
{
    try {
        $token = bin2hex(random_bytes(16));
        try {
            global $db;
            $query = $db->prepare('INSERT INTO token (user_id, token) VALUES (:id, :token)');
            $query->bindParam('id', $user['id']);
            $query->bindParam('token', $token);
            $query->execute();
        } catch (PDOException $e){
            addFlash('danger', $e->getMessage());
            return false;
        }
        setcookie('token', $token, 0);
        return true;
    } catch (\Random\RandomException $e){
        addFlash('danger', $e->getMessage());
        return false;
    }
}

function logout()
{
    removeTokenOfUser(checkUser($_SESSION['user'])['id']);
    unset($_SESSION['user']);
    addFlash('success', 'Je bent uitgelogd');
    header('location: /');
    exit();
}

function removeOldTokens(): void
{
    try {
        global $db;
        $query =  $db->prepare('SELECT * FROM token');
        $query->execute();
        $tokens = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($tokens as $token){
            $date = new DateTime($token['date']);
            $date->add(new DateInterval('P1D'));
            $now = new DateTime();
            if ($date < $now){
                $query = $db->prepare('DELETE FROM token WHERE user_id = :id');
                $query->bindParam(':id', $token['user_id']);
                $query->execute();
            }
        }
    }catch (PDOException $e){
        addFlash('danger', $e->getMessage());
    }

}

function removeTokenOfUser($user): void
{
    try {
        global $db;
        $query = $db->prepare('DELETE FROM token WHERE user_id = :user');
        $query->bindParam('user', $user);
        $query->execute();
        removeCookieToken();
    } catch (PDOException $e){
        addFlash('danger', $e->getMessage());
    }
}

function removeCookieToken(): void
{
    if (isset($_COOKIE['token'])) {
        unset($_COOKIE['token']);
        setcookie('token', '', time() - 3600, '/');
    }
}