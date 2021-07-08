<div class="w100 flex_wrap">
    <?php foreach ($followers as $follower) {?>
        <div class="friend-card w45 marVer10">
            <div class="w100 s_cover">
                <img src="<?= $files ?>users/covers/<?= $follower->cover_image ?>" alt="profile-cover" class="img-responsive">
            </div>
            <div class="card-info">
                <img src="<?= $files ?>users/profiles/<?= $follower->profile_image ?>" alt="user" class="profile-photo-lg">
                <div class="friend-info">
                    <a href="#" class="pull-right text-green">My Friend</a>
                    <h5><a href="<?= $host ?>profile/<?= $follower->user_nickname ?>" class="profile-link">
                    <?= $follower->user_name."(  @".$follower->user_nickname."  )" ?>
                    </a></h5>
                    <p class="colorText"><?= $follower->school_at ?></p>
                </div>
            </div>
        </div>
    <?php } ?>
</div>