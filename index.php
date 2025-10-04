<?php

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

require 'vendor/autoload.php';
if (isset($_POST)) {
    $reader = new Xlsx();
    $reader->setReadDataOnly(true);
    $spreadsheet = $reader->load($_FILES['file']['tmp_name']);

    $spreadsheetCount = $spreadsheet->getSheetCount();

    for ($i = 0; $i < $spreadsheetCount; $i++){
        $sheet = $spreadsheet->getSheet($i);
        var_dump($sheet->getTitle());
        echo '<br>';
        $data = $sheet->toArray();
        var_dump($data);
        echo '<br>';
    }

//    var_dump($spreadsheet);
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Document</title>
</head>
<body class="bg-info-subtle">
<div class="container">
    <div class="row">
        <div class="col">
            <div class="mb-3">
                <form class="needs-validation" novalidate method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="file" class="form-label">Peiling</label>
                        <input type="file" class="form-control file-input" id="file" name="file"
                               accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                               required>
                        <div class="invalid-feedback">Vul een bestand in!</div>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary submit-btn">Verzenden</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous">
</script>
</body>
</html>
