<?php

require '../modules/db.php';
require '../modules/poll.php';

$request = $_SERVER['REQUEST_URI'];
$params = explode("/", $request);
$title = "Peilingen";

switch ($params[1]) {
    case 'new-poll' :
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
    default:
        include_once "../template/home.php";
        break;
}