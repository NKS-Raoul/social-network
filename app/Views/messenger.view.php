
<body>
    <?php require_once($view."components/navBar.php");  ?>

    <!-- Message zone -->
    <div class="messenger">
        
        <?php require_once($view.'components/contactLists.php');   ?>

        <?php require_once($view.'components/messagesLists.php');  ?>

        <?php require_once($view.'components/messengerProfile.php');  ?>
        
    </div>
    <!-- End message zone -->

    <?php require_once($view.'components/loader.php'); ?>
    
</body>
<script src="<?= $assets ?>js/script.js"></script>