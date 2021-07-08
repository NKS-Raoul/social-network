
<body class="row">

    <?php require_once($view.'components/navBar.php'); ?>

    <!-- Answer body -->
    <div id="answer_body" class="w62_5">

        <div class="w100">

            <?php require_once($view.'components/publications.php'); ?>

        </div>

        <div class="w100">

            <?php require_once($view.'components/comments.php'); ?>

        </div>
    </div>
    <!-- End answer body -->


    <?php require_once($view.'components/loader.php'); ?>
</body>
<script src="<?= $assets ?>js/script.js"></script>