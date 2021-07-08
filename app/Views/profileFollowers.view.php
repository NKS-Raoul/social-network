<body>

    <?php require_once($view.'components/navBar.php'); ?>

    <div id="profile_body">

        <?php require_once($view.'components/profileCover.php'); ?>

        <!-- publications subjects -->
        <div class="w75 column">

            <?php require_once($view.'components/resp_profileLinkBar.php'); ?>

            <div class="simple_flex w100 resp_profile_body">

                <?php require_once($view.'components/profileUpdateLink.php'); ?>

                <div class="flex_wrap resp_profile_subject ba">

                    <?php require_once($view.'components/followers.php'); ?>

                </div>
            </div>
        </div>
        <!-- end publications subjects -->
    </div>
    
    <?php require_once($view.'components/loader.php'); ?>
</body>
<script src="<?= $assets ?>js/script.js"></script>
<script src="<?= $assets ?>js/profile.js"></script>