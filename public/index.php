<?php

require '../modules/db.php';
require '../vendor/autoload.php';
require '../modules/poll.php';
require '../modules/common.php';

$request = $_SERVER['REQUEST_URI'];
$params = explode("/", $request);
$title = "Peilingen";
session_start();

switch ($params[1]) {
    case 'new-poll' :

        if (isset($_POST["submit"])) {
            if ($_FILES['file']['tmp_name'] === "") {
                addFlash('danger', 'Er is geen bestand toegevoegd!');
            } else {
                addPoll();
                addFlash('success', 'De poll is toegevoegd');
                header('Location: /');
            }
        }

        include_once "../template/add-poll.php";
        break;
    case 'get-polls':
        header('Content-type: application/json');
        echo json_encode(getAllPolls());
        break;
    case 'get-party':
        header('Content-type: application/json');
        echo json_encode(getAllPartyOrdered($params[2]));
        break;
    default:
        include_once "../template/home.php";
        break;
}