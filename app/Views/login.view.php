<body class="log_sign_bg" style="background: url(<?= $assets ?>images/9.jpg) no-repeat;background-position-x: 0%;background-position-y: 0%;background-size: cover;">
    <div class="container">
        <form action="" method="POST" class="connection-div">
            <h2 class="heading">Connection to<br /> account ..... </h2>

            <div class="error textCenter"><?= isset($login_error) ? $login_error : "" ?></div>

            <div class="input-div">
                <input type="text" name="username" id="input-name" <?= isset($username) ? "value='$username'" : "placeholder='Nickname or email'"; ?> style="background: transparent;">
                <div class="error"><?= isset($name_error) ? $name_error : "" ?></div>

                <input type="password" name="password" id="input-password" placeholder="Password" style="background: transparent;">
                <div class="error"><?= isset($password_error) ? $password_error : "" ?></div>

            </div>

            <div class="foot">
                <button id="connect-button" style="color: #fefefe;" type="submit" name="connect">Connect</button><br>
                <div class="link-div">
                    <a href="#">Forgot password ... ?</a>
                </div>
            </div>
        </form>

        <div class="creat-account">
            <div class="foot">
                <p>Don't have an account ...... ? </p>
                <a href="<?= $host ?>register" class="bout2-link">
                    <button id="creation-button">
                        Create account
                    </button>
                </a>
                <div class="secondLink-div">
                    &copy;.... <a href="">Term of use</a> &nbsp;|&nbsp; <a href="">Politic and privacy</a>
                </div>


            </div>
        </div>


    </div>
    <?php require_once($view . 'components/footer.php'); ?>

</body>