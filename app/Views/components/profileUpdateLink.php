<?php if($userProfile[0]->user_id == $user->user_id) {?>
<!-- information on user -->
<div class="w25">
    <div class="user_infos">
        <a href="<?= $host ?>update_infos/<?= $user->user_nickname ?>" class="w100 simple_flex">
            <em class="forum-information siteColor w12_5"></em>
            &nbsp;&nbsp;&nbsp;
            <div class="w87_5 colorText">Update information</div>
        </a>
        <a href="<?= $host ?>updatePassword/<?= $user->user_nickname ?>" class="w100 simple_flex">
            <em class="forum-information siteColor w12_5"></em>
            &nbsp;&nbsp;&nbsp;
            <div class="w87_5 colorText">Update password</div>
        </a>
        <div class="w100 simple_flex resp_follow">
            <em class="forum-people siteColor w12_5"></em>
            &nbsp;&nbsp;&nbsp;
            <div class="w87_5 colorText"><strong><?= $supplementInfos->cptFollower ?></strong>&nbsp; followers</div>
        </div>
        <div class="w100 simple_flex resp_follow">
            <em class="forum-people-outline siteColor w12_5"></em>
            &nbsp;&nbsp;&nbsp;
            <div class="w87_5 colorText"><strong><?= $supplementInfos->cptFollowing ?></strong>&nbsp; followings</div>
        </div>
    </div>
</div>&nbsp;&nbsp;&nbsp;
<!-- end information on user -->
<?php } ?>