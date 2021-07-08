<div class="w100 h12_5 link spcBContent resp_link_bar">
    <ul class="list-inline profile-menu h100 spcBContent">
        <li><a href="<?= $host ?>profile/<?= $userProfile[0]->user_nickname ?>">Publication</a></li>

        <?php if (!isset($userProfile) || (isset($userProfile) && $userProfile[0]->user_id == $user->user_id)) : ?>
            <li><a href="<?= $host ?>update_infos/<?= $userProfile[0]->user_nickname ?>" class="active">Edit information</a></li>
        <?php endif; ?>

        <li><a href="<?= $host ?>follower/<?= $userProfile[0]->user_nickname ?>">Followers</a></li>
        <li><em class="forum-information"></em></li>
    </ul>
    <ul class="follow-me list-inline h100 spcBContent">
        <?php if (!$supplementInfos->verify && $userProfile[0]->user_id != $user->user_id) : ?>
            <li><button class="btn-primary">Add Friend</button></li>
        <?php endif; ?>


        <?php if ($userProfile[0]->user_id != $authObj->getUserId()) : ?>
            <li><a href="<?= $host ?>messenger/<?= $userProfile[0]->user_id ?>">
                    <button class="btn-primary">
                        <em class="forum-chat"></em>&nbsp;Message
                    </button>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</div>