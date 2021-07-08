<!-- information on user -->
<div class="w25">
    <div class="user_infos posRel">
        <div class="w100 textCenter colorText">
            <strong>ABOUT</strong>
        </div>
        <?php if (!empty($supplementInfos->school_at)) : ?>
            <div class="w100 simple_flex">
                <em class="forum-university siteColor w12_5"></em>
                <div class="w87_5 colorText">School at : <strong><?= $supplementInfos->school_at ?></strong></div>
            </div>
        <?php endif; ?>

        <?php if (!empty($supplementInfos->country)) : ?>
            <div class="w100 simple_flex ">
                <em class="forum-home siteColor w12_5"></em>
                <div class="w87_5 colorText">Live in : <strong><?= $supplementInfos->country ?></strong></div>
            </div>
        <?php endif; ?>

        <?php if (!empty($supplementInfos->sex)) : ?>
            <div class="w100 simple_flex">
                <em class="forum-<?= $supplementInfos->sex == 'male'? "man" : "woman" ?> siteColor w12_5"></em>
                <div class="w87_5 colorText">Sex : <strong><?= $supplementInfos->sex ?></strong></div>
            </div>
        <?php endif; ?>

        <?php if (!empty($supplementInfos->phoneNumber)) : ?>
            <div class="w100 simple_flex">
                <em class="forum-telephone siteColor w12_5"></em>
                <div class="w87_5 colorText">Number : <strong><?= $supplementInfos->phoneNumber ?></strong></div>
            </div>
        <?php endif; ?>

        <?php if (!empty($supplementInfos->user_email)) : ?>
            <div class="w100 simple_flex">
                <em class="forum-email siteColor w12_5"></em>
                <div class="w87_5 colorText"><strong>Email</strong>:&nbsp; <?= $supplementInfos->user_email ?></div>
            </div>
        <?php endif; ?>

        <?php if (!empty($supplementInfos->descriptionI)) : ?>
            <div class="w100 simple_flex">
                <em class="forum-email siteColor w12_5"></em>
                <div class="w87_5 colorText">About me : <strong><?= $supplementInfos->descriptionI ?></strong></div>
            </div>
        <?php endif; ?>

        <div class="w100 simple_flex resp_follow">
            <em class="forum-people siteColor w12_5"></em>
            <div class="w87_5 colorText"><strong id="nbrFolWer"><?= $supplementInfos->cptFollower ?></strong>&nbsp; follower(s)</div>
        </div>
        <div class="w100 simple_flex resp_follow">
            <em class="forum-people-outline siteColor w12_5"></em>
            <div class="w87_5 colorText"><strong id="nbrFolWing"><?= $supplementInfos->cptFollowing ?></strong>&nbsp; following(s)</div>
        </div>
    </div>
    <!-- <script>
        console.log(<?= json_encode($supplementInfos) ?>);
    </script> -->
</div>&nbsp;&nbsp;&nbsp;
<!-- end information on user -->