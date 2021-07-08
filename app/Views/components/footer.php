<footer style="background-color: transparent !important;">
    <div class="home-div">
        <span>©
            <span class="date"></span>
            <script>
                var date = new Date();
                document.querySelector('.date').innerHTML = date.getFullYear();
            </script>
            &nbsp;&nbsp;The social NetWork&nbsp;&nbsp;
            <a href="<?= $host ?>">Home</a>&nbsp;&nbsp;


            <?php if ($authObj->logged()) { ?>
                |&nbsp;&nbsp;<a href="<?= $host ?>messenger">Messenger</a>&nbsp;&nbsp;|
            <?php } ?>


            <a href="<?= $host ?>register">SignUp</a>&nbsp;&nbsp;|
            <a href="">PrivacyPolicy</a>&nbsp;&nbsp;|
            <a href="">Term Of Use</a>
        </span>
    </div>
</footer>