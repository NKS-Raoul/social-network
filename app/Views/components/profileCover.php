<!-- page cover -->
<div class="w100">
    <div class="imageContainer-cover" style="background: url(<?= $files ?>users/covers/<?= $userProfile[1]->cover_image ?>) no-repeat;background-position-x: 0%;background-position-y: 0%;background-size: cover;">


        <?php if ($userProfile[0]->user_id == $authObj->getUserId()) : ?>
            <form class="abs_Posi flex_row" action="" method="POST" enctype="multipart/form-data">
                <label for="cover_image" class="btn-primary" title="Change cover image">
                    <em class="forum-camera"></em>
                    <input type="file" name="change_cover_image" id="cover_image" style="display: none;">
                </label>&nbsp;
                <button class="btn-primary ctrContent valid_change_cover" name="changeCover" type="button">
                    <em class="forum-checkmark-round"></em>
                </button>
            </form>
            <script>
                var valid_change_cover = document.querySelector('.valid_change_cover');
                valid_change_cover.onclick = changingCover;
                async function changingCover() {
                    var a = document.querySelector('#cover_image');
                    const form = new FormData();
                    form.append('file', a.files[0]);
                    form.append("user_id", <?= $user->user_id ?>);
                    await fetch("<?= $host ?>ajax/changeCover", {
                            method: "POST",
                            body: form
                        })
                        .then((res) => res.json())
                        .then((result) => {
                            // console.log(result);
                            var a = document.querySelector('.imageContainer-cover').style;
                            a.backgroundImage = `url(<?= $files ?>users/covers/${result.cover_image.cover_image})`;
                            valid_change_cover.style.visibility = `hidden`;
                            alert("Your cover image has be changed");
                        })
                        .catch((error) => console.log(error));
                }
            </script>
        <?php endif; ?>




        <div class="w100 h12_5 link link_bar">
            <form class="column profile_card resp_profile_card_form" method="POST" action="" enctype="multipart/form-data">
                <label title="Change profile image" for="change_profile_image" class="imageContainer imageContainer200">
                    <img src="<?= $files ?>users/profiles/<?= $userProfile[1]->profile_image ?>" alt="User's Profile">


                    <?php if ($userProfile[0]->user_id == $authObj->getUserId()) : ?>
                        <input type="file" name="change_profile_image" id="change_profile_image" style="display: none;" />
                    <?php endif; ?>

                </label>
                <button type="button" name="changeProfile" class="btn-primary abs_Posi w25 ctrContent">
                    <em class="forum-checkmark-round"></em>
                </button>
                <h2><?= $userProfile[0]->user_name ?><br>(@<?= $userProfile[0]->user_nickname ?>)</h2>
                <script>
                    var changeProfile = document.querySelector('button[name=changeProfile]');
                    changeProfile.onclick = changingProfile;
                    async function changingProfile() {
                        var a = document.querySelector('#change_profile_image');
                        const form = new FormData();
                        form.append('file', a.files[0]);
                        form.append("user_id", <?= $user->user_id ?>);
                        await fetch("<?= $host ?>ajax/changeProfile", {
                                method: "POST",
                                body: form
                            })
                            .then((res) => res.json())
                            .then((result) => {
                                // console.log(result);
                                var a = document.querySelector('label[for=change_profile_image] img');
                                var b = document.querySelector('.navBarImg img');
                                b.src = `<?= $files ?>users/profiles/${result.profile_image.profile_image}`;
                                a.src = `<?= $files ?>users/profiles/${result.profile_image.profile_image}`;
                                changeProfile.style.visibility = `hidden`;
                                alert("Your profile image has be changed");
                            })
                            .catch((error) => console.log(error));
                    }
                </script>
            </form>





            <ul class="list-inline profile-menu resp_profile-menu h100 spcBContent">
                <li><a href="<?= $host ?>profile/<?= $userProfile[0]->user_nickname ?>">Publications</a></li>

                <?php if (!isset($userProfile) || (isset($userProfile) && $userProfile[0]->user_id == $user->user_id)) : ?>
                    <li><a href="<?= $host ?>update_infos/<?= $userProfile[0]->user_nickname ?>">Edit information</a></li>
                <?php endif; ?>
                <li><a href="<?= $host ?>followers/<?= $userProfile[0]->user_nickname ?>">Followers</a></li>
            </ul>




            <ul class="follow-me resp_follow-me list-inline h100 spcBContent">
                <li class="follow"><?= $supplementInfos->cptFollower ?> follower(s)</li>
                <li class="follow"><?= $supplementInfos->cptFollowing ?> following(s)</li>
                <?php if (!$supplementInfos->verify && $userProfile[0]->user_id != $user->user_id) : ?>
                    <li>
                        <button class="btn-primary" id="addFriend" onclick="addFollowings(<?= $userProfile[0]->user_id ?>)" style="cursor: pointer;">Add Friend</button>
                    </li>
                    <script>
                        // request to follow back a user
                        async function addFollowings(user_id = 0) {
                            const following_id = new FormData();
                            following_id.append("following_id", user_id);
                            await fetch("<?= $host ?>ajax/addFollowings", {
                                    body: following_id,
                                    method: "POST"
                                })
                                .then((res) => res.json())
                                .then((result) => {
                                    if (!result.success) {
                                        console.log(result.error);
                                    }
                                })
                                .catch((error) => console.log(error));
                        }
                    </script>
                <?php endif; ?>


                <?php if ($userProfile[0]->user_id != $authObj->getUserId()) : ?>
                    <li>
                        <a href="<?= $host ?>messenger/<?= $userProfile[0]->user_id ?>">
                            <button class="btn-primary">
                                <em class="forum-chat"></em>&nbsp;Message
                            </button>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <!-- <script>
        console.log(<?= json_encode($supplementInfos->verify) ?>)
    </script> -->
</div>
<!-- end page cover -->