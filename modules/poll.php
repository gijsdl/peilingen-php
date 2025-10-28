<?php

function addPoll(): void
{
    try {
        $object = json_decode(file_get_contents('php://input'));
        if (!checkToken($object->token)) {
            http_response_code(500);
            echo json_encode(['status' => 'AUTHERROR']);
            return;
        }
        foreach ($object->polls as $poll) {
            $pollName = $poll->poll;
            if ($pollName === "Eerste kamer"){
                foreach ($poll->parties as $data){
                    $partyName = $data->Partij;
                    if (!$partyName) {
                        break;
                    }
                    $partyId = checkPartyId($partyName);
                    addFirstChamber($partyId['id'], $data->Zetels);
                }
            }else {
                $pollId = checkPollId($pollName);
                $pollFound = true;

                if (!$pollId) {
                    createPoll($pollName);
                    $pollFound = false;
                    $pollId = checkPollId($pollName);
                }

                $pollId = $pollId['id'];

                if ($pollFound) {
                    removeOldData($pollId);
                }

                $dataArray = $poll->parties;
                foreach ($dataArray as $data) {
                    $partyName = $data->Partij;
                    if (!$partyName) {
                        break;
                    }
                    $partyId = checkPartyId($partyName);

                    if (!$partyId) {
                        createParty($partyName);
                        $partyId = checkPartyId($partyName);
                    }
                    $partyId = $partyId['id'];
                    $chairs = $data->Zetels;
                    createPartyPoll($partyId, $pollId, $chairs);
                }
            }
        }
        http_response_code(200);
        echo json_encode(['status' => 'OK']);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error']);
    }
}

function checkToken($token): mixed
{
    global $db;
    $query = $db->prepare('SELECT token FROM token where token = :token');
    $query->bindParam('token', $token);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

function checkPollId(string $name): mixed
{
    global $db;
    $query = $db->prepare('SELECT id FROM poll WHERE name = :name');
    $query->bindParam('name', $name);
    $query->execute();

    return $query->fetch(PDO::FETCH_ASSOC);

}

function createPoll(string $name): void
{
    global $db;
    $query = $db->prepare('INSERT INTO poll (name) VALUES (:name)');
    $query->bindParam('name', $name);
    $query->execute();
}

function removeOldData(int $id): void
{
    global $db;
    $query = $db->prepare('DELETE FROM party_poll WHERE poll_id = :id');
    $query->bindParam('id', $id);
    $query->execute();
}

function checkPartyId(string $name): mixed
{
    global $db;
    $query = $db->prepare('SELECT id FROM party WHERE name = :name');
    $query->bindParam('name', $name);
    $query->execute();

    return $query->fetch(PDO::FETCH_ASSOC);

}

function createParty(string $name): void
{
    global $db;
    $query = $db->prepare('INSERT INTO party (name) VALUES (:name)');
    $query->bindParam('name', $name);
    $query->execute();
}

function createPartyPoll(int $partyId, int $pollId, int $chairs): void
{
    global $db;
    $query = $db->prepare('INSERT INTO party_poll (party_id, poll_id, chairs) VALUES (:partyId, :pollId, :chairs)');
    $query->bindParam('partyId', $partyId);
    $query->bindParam('pollId', $pollId);
    $query->bindParam('chairs', $chairs);
    $query->execute();
}

function addFirstChamber(int $id, int $chairs): void
{
    global $db;
    $query = $db->prepare('UPDATE party SET first_chamber_chairs = :chairs WHERE id = :id');
    $query->bindParam('chairs', $chairs);
    $query->bindParam('id', $id);
    $query->execute();
}

function getAllPolls(): string|array
{
    try {
        global $db;
        $query = $db->prepare('SELECT * FROM poll');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }catch (PDOException $e){
        http_response_code(500);
        return ['status' =>'error'];
    }

}

function getAllPartyOrdered($id): string|array
{
    try {
        global $db;
        $query = $db->prepare('SELECT p.name, p.first_chamber_chairs ,pp.chairs FROM party_poll AS pp LEFT JOIN  party AS p ON p.id = pp.party_id WHERE pp.poll_id = :id ORDER BY chairs desc');
        $query->bindParam('id', $id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }catch (PDOException $e){
        http_response_code(500);
        return 'error';
    }
}