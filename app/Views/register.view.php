<body class="register" style="background: url(<?= $assets ?>images/9.jpg) no-repeat;background-position-x: 0%;background-position-y: 0%;background-size: cover;">
    <div class="background">
        <div class="container">
            <div class="heading">
                <h2 class="head">Creation of Forum account</h2><br>
                <span>Already have an account ? &nbsp;<a href="<?= $host ?>login" class="link">Connect</a></span>
            </div>
            <div class="each-notes ctrContent">
                <span id="error"><?= isset($register_error) ? $register_error : "" ?></span>
            </div>
            <form action="" method="POST">
                <!--this below is the label for the name-->
                <div class="each-div">
                    <div class="label-div">
                        <label for="name" class="ft_size">Name*</label>
                    </div>
                    <input type="text" name="name" value="<?= $nameValue ?>" id="name" required autofocus><br>
                    <div class="each-notes">
                        <span id="error"><?= $name_error; ?></span>
                    </div>
                </div>

                <!--The nickname div-->
                <div class="each-div">

                    <div class="label-div">
                        <label for="nickname" class="ft_size">Nickname*</label>
                    </div>
                    <input type="text" name="nickname" value="<?= $nicknameValue ?>" id="nickname" required><br>
                    <div class="each-notes">
                        <span id="error"><?= $nickname_error; ?></span>
                    </div>
                </div>

                <!--this is the div for your email-->
                <div class="each-div">

                    <div class="label-div">
                        <label for="email" class="ft_size">Email address</label>
                    </div>
                    <input style="background: transparent;" value="<?= $emailValue ?>" type="email" name="email" id="email">
                    <div class="each-notes">
                        <span id="error"><?= $email_error; ?></span>
                    </div>
                </div><br />

                <!--this is the div for your entered password -->
                <div class="each-div">
                    <div class="label-div">
                        <label for="password" class="ft_size">Password*</label>
                    </div>
                    <input style="background: transparent;" type="password" name="password" id="password" title="Must contain at least one number and one uppercase and lowercase letter,and atleast 8 or more chracters" required>
                    <div class="each-notes">
                        <span id="error"><?= $password_error; ?></span>
                    </div>
                </div><br />

                <!--this is the div for the repeated password so as to comfirm-->
                <div class="each-div">
                    <div class="label-div">
                        <label for="confirm_password" class="ft_size">Confirm password*</label>
                    </div>
                    <input style="background: transparent;" type="password" name="confirm_password" id="confirm_password" required><br>
                    <div class="each-notes">
                        <span id="error"><?= $confirm_password_error; ?></span>
                    </div>
                </div>

                <div class="MoreInformation-div">
                    <span>You can disconnect from all communications : </span>
                    <p class="notes marVer10">
                        By clicking on the botton << SignUp>> just after, you accept and
                            recognise that the user of the social network website is based on <a href="" class="link">Condition
                                of the social network user</a> the comlimentary informations concerning the collection and
                            and the social network user of your personal information,notably the relative data
                            to the access, conservation, rectification, suppression, security,transferable
                            transfers and other subject , are available in <a href="" class="link"> confidentiality declaration
                                of the social network
                            </a>
                    </p>
                    <button name="signUp" type="submit" data-submit="...sending" id="accept-button">SignUp</button>
                </div>
            </form><br>

            <div class="footer">
                <a href="#" class="link">Help on your account</a>&nbsp;|&nbsp;
                <a href="#" class="link">Subscription</a>&nbsp;|&nbsp;
                <a href="#" class="link">Log Out</a>&nbsp;|&nbsp;
                <a href="#" class="link">Using condition and privacy</a>&nbsp;|&nbsp;
                <a href="#" class="link">Preferences in cookies</a>
            </div>
        </div>
    </div>
    <?php require_once($view . 'components/footer.php'); ?>
</body>