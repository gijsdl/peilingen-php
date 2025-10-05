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

<div class="row parties-wrapper hidden">
    <div class="col-6">
        <div class="parties">

        </div>
    </div>
    <div class="col-6">
        <button class="btn btn-primary calculate">Bereken</button>
    </div>
</div>

<script src="js/Party.js"></script>
<script src="js/Poll.js"></script>
<script src="js/main.js"></script>
<?php
include_once 'footer.php';
?>
