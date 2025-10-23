<?php

require '../modules/db.php';
require '../modules/poll.php';
require '../modules/user.php';

$request = $_SERVER['REQUEST_URI'];
$params = explode("/", $request);
$title = "Peilingen";
session_start();
removeOldTokens();

switch ($params[1]) {
    case 'new-poll' :
        if (!checkUser($_SESSION['user'])){
            header('location: /login');
        }
        $title .= ' | nieuwe peiling';
        include_once "../template/add-poll.php";
        break;
    case 'add-poll':
        header('Content-type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            addPoll();
        }
        break;
    case 'get-polls':
        header('Content-type: application/json');
        echo json_encode(getAllPolls());
        break;
    case 'get-party':
        header('Content-type: application/json');
        echo json_encode(getAllPartyOrdered($params[2]));
        break;
    case 'login':
        $title .= ' | login';
        if (isset($_POST['submit'])){
            login();
        }
        include_once '../template/login.php';
        break;
    case 'make-user':
        $title .= ' | gebruiker aanmaken';
        if (!checkUser($_SESSION['user'])){
            header('location: /login');
        }
        if (isset($_POST['submit'])){
            makeUser();
        }
        include_once '../template/make-user.php';
        break;
    case 'logout':
        logout();
        break;
    default:
        $title .= ' | home';
        include_once "../template/home.php";
        break;
}