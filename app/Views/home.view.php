<body>

    <?php require_once($view."components/navBar.php"); ?>

    <!-- Body -->
    <div id="page-contents">

        <?php require_once($view."components/leftMenu.php"); ?>

        <?php require_once($view."components/rightMenu.php"); ?>

        <?php require_once($view."components/addPublication.php"); ?>

        <?php require_once($view."components/respPublicite.php"); ?>

        <?php require_once($view."components/publications.php"); 
        //var_dump($subjectList); ?>

    </div>
    <!-- end Body -->

    <?php require_once($view."components/loader.php"); ?>

</body>
<script src="<?= $assets ?>js/script.js"></script>
<script src="<?= $assets ?>js/index.js"></script>