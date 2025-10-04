<?php

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

function addPoll(): void
{
    $reader = new Xlsx();
    $reader->setReadDataOnly(true);
    $spreadsheet = $reader->load($_FILES['file']['tmp_name']);

    $spreadsheetCount = $spreadsheet->getSheetCount();

    for ($i = 0; $i < $spreadsheetCount; $i++) {
        $sheet = $spreadsheet->getSheet($i);
//        var_dump($sheet->getTitle());
        $pollName = $sheet->getTitle();
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
        $dataArray = $sheet->toArray();
        $first = true;
        foreach ($dataArray as $data) {
            if ($first) {
                $first = false;
                continue;
            }
            $partyName = $data[0];
            if (!$partyName) {
                break;
            }
            $partyId = checkPartyId($partyName);

            if (!$partyId) {
                createParty($partyName);
                $partyId = checkPartyId($partyName);
            }

            $partyId = $partyId['id'];
            $chairs = $data[1];
            createPartyPoll($partyId, $pollId, $chairs);
        }

    }
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