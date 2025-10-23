<?php
include_once 'header.php';
?>


<div class="row justify-content-center mt-3">
    <div class="col">
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
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.16.8/dist/xlsx.full.min.js"
        integrity="sha256-Ic7HP804IrYks4vUqX1trFF1Nr33RjONeJESZnYxsOY="
        crossorigin="anonymous">
</script>
<script src="js/add-poll.js"></script>

<?php
include_once 'footer.php';
?>
