<body id="add_profile" style="background-image: url(<?= $assets ?>images/9.jpg);background-size: cover;background-position: 0% 0%;">
    <form method="POST" action="" enctype="multipart/form-data" class="container">
        <div class="header h50 w100 ctrContent">
            <div class="w75 h100 simple_flex new_cover" style="background-image: url(<?= $assets ?>images/default.png);background-size: cover;background-position: 0% 0%;">
                <label for="new_background" title="Add your new cover" class="imageContainer imageContainer50 add_background ctrContent">
                    <em class="forum-camera siteColor"></em>
                    <input type="file" name="new_background" id="new_background" style="display: none;">
                </label>
            </div>
        </div>
        <div class="w100 h50 ctrContent">
            <div class="w37_5 add_newimg_profile">
                <h2 class="textCenter">More Information</h2>
                <div class="w100 ctrContent padVer5">
                    <div class="imageContainer imageContainer150">
                        <img src="<?= $assets ?>images/default.png" id="new_image" alt="new profile image">
                    </div>
                </div>
                <h3 class="textCenter padVer5">Add Profile and cover image to begin</h3>
                <div class="spcEContent padVer5">
                    <label title="Add your new profile image" for="new_profile_img" class="btn-primary ctrContent w25">
                        <em class="forum-camera"></em>
                        <input type="file" name="new_profile_img" id="new_profile_img" style="display: none;">
                    </label>
                    <button type="submit" name="addCourse" class="btn-primary ctrContent w25">
                        <em class="forum-checkmark-round"></em>
                    </button>
                </div>
            </div>
        </div>
    </form>
    <?php require_once($view . 'components/footer.php'); ?>
</body>
<script src="<?= $assets ?>js/new_profile.js"></script>