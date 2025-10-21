<?php
include_once 'header.php';
?>


<div class="row justify-content-center mt-3">
    <div class="col-3">
        <form class="needs-validation" novalidate method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="username" class="form-label">Gebruikersnaam</label>
                <input type="text" class="form-control file-input" id="username" name="username" required>
                <div class="invalid-feedback">Vul een gebruikersnaam in!</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Wachtwoord</label>
                <input type="password" class="form-control file-input" id="password" name="password" required>
                <div class="invalid-feedback">Vul een wachtwoord in!</div>
            </div>
            <button type="submit" name="submit" class="btn btn-primary submit-btn">Verzenden</button>
        </form>
    </div>
</div>
<?php
include_once 'footer.php';
?>
