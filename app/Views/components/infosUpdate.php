<div class="frst_div w100">
    <h2><em class="forum-checkmark"></em>&nbsp;&nbsp;&nbsp; Edit basic information</h2>
    <p>
        welcome to our website. You can modifed your information her<br />
        and pout another name or new password.<br /><br />
    </p>
    <div id="principal_div">
        <script>
            console.log(<?= json_encode($supplementInfos) ?>);
        </script>
        <form action="" method="POST">
            <div class="spcBContent flex_wrap w100">
                <div>
                    <label for="firstName">Name*</label><br />
                    <input type="text" value="<?= $userProfile[0]->user_name ?>" name="name" placeholder="Example: Kelly" required class="space borderRaduis">
                </div>
                <div>
                    <label for="pseudo">Nickname*</label><br />
                    <input type="text" value="<?= $userProfile[0]->user_nickname ?>" name="nickname" placeholder="Example: Webmaster3.0" required class="space borderRaduis">
                </div>
            </div>
            <div class="space w100">
                <label for="email" class="space">E-mail</label><br />
                <input type="email" name="email" value="<?= $supplementInfos->user_email ?>" placeholder="exemple@gmail.com" class="space borderRaduis" style="width: 100%;">
            </div>
            <div class="space w100">
                <label for="date">Date of birth</label><br />
                <input type="date" value="<?= $supplementInfos->date_birth ?>" name="birth_date" class="space borderRaduis">
            </div>


            <div class="space w100">
                <label for="sex">Sex : </label>
                <input type="radio" name="sex" id="male" value="male">
                <label for="male">Male</label>
                <input type="radio" name="sex" id="female" value="female">
                <label for="female">Female</label>
            </div>
            <script>
                var temp = <?= json_encode($supplementInfos->sex) ?>;
                if(temp == "female") {document.querySelector("#female").checked = true;}
                if (temp == "male") {
                    document.querySelector("#male").checked = true;
                }
                // alert(temp);
            </script>


            <div class="space w100">
                <label for="email" class="space">School</label><br />
                <input type="text" value="<?= $supplementInfos->school_at ?>" name="school" placeholder="Ex: University of Dschang" class="space borderRaduis w100">
            </div>
            <div class="space w100">
                <label for="country">Country</label><br />
                <select name="country" selected="raoul" class="space borderRaduis countries">
                    <option selected><?= $supplementInfos->country ?></option>
                </select>
            </div>
            <div class="space w100">
                <label for="email" class="space">Phone number</label><br />
                <input type="text" value="<?= $supplementInfos->phoneNumber ?>" name="phoneNumber" placeholder="Ex: University of Dschang" class="space borderRaduis w100">
            </div>
            <div style="margin-top: 18px;" class="w100">
                <span><strong>About me</strong></span><br />
                <textarea name="description" ols="50%" rows="7" placeholder="Your description" class="space borderRaduis"><?= $supplementInfos->descriptionI ?></textarea>
            </div>
            <div class="w100">
                <button type="submit" name="saveChanges" class="btn-primary save">Save Changes</button>
            </div>
        </form>
    </div>
</div>