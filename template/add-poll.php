<?php
include_once 'header.php';
?>


<div class="row">
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

<?php
include_once 'footer.php';
?>
