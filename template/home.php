<?php
include_once 'header.php';
global $polls;
?>

<div class="row mt-3">
    <div class="col">
        <div class="result hidden">

        </div>
    </div>
</div>

<div class="row parties-wrapper justify-content-center hidden">
    <div class="col-4">
        <div class="parties">

        </div>
    </div>
    <div class="col-1">
        <button class="btn btn-primary calculate">Bereken</button>
    </div>
</div>

<div class="row justify-content-center empty hidden">
    <div class="col-8 d-flex justify-content-center">
        <h2 class="text-danger">Er is op dit moment nog geen poll toegevoegd</h2>
    </div>
</div>

<script src="js/Party.js"></script>
<script src="js/Poll.js"></script>
<script src="js/main.js"></script>
<?php
include_once 'footer.php';
?>
