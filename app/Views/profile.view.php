<body>

    <?php require_once($view . 'components/navBar.php'); ?>

    <!-- Body -->
    <div id="profile_body">
        <?php require_once($view . 'components/profileCover.php'); ?>
        
        <!-- publications subjects -->
        <div class="w75 column">

            <?php require_once($view . 'components/resp_profileLinkBar.php'); ?>

            <?php require_once($view . 'components/resp_profileBody.php'); ?>

        </div>
        <!-- end publications subjects -->

    </div>
    <!-- End body  -->

    <?php require_once($view . 'components/loader.php'); ?>
</body>
<script src="<?= $assets ?>js/script.js"></script>
<script src="<?= $assets ?>js/profile.js"></script>
<script src="<?= $assets ?>js/add_publication.js"></script>