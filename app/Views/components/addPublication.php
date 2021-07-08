<!-- adding publication zone -->

<div id="add_subject">
    <!-- profile card -->
    <div class="profile-card flex_row spcBContent resp_profile_card" style="background: linear-gradient(to bottom, #27aae1cc, #1c75bccc),url(<?= $files ?>users/covers/<?= $userInfos->cover_image ?>) no-repeat;">
        <img src="<?= $files ?>users/profiles/<?= $userInfos->profile_image ?>" alt="user" class="profile-photo">
        <div class="column">
            <h5><a href="<?= $host ?>profile/<?= $user->user_nickname ?>" class="text-white"><?= $user->user_name ?></a></h5>
            <a href="<?= $host ?>profile/<?= $user->user_nickname ?>" class="text-white">( @<?= $user->user_nickname ?> )</a>
        </div>
    </div>
    <!-- profile card end -->

    <!-- chat proposition block -->
    <div id="chat-block" class="messages_block">

        <div class="title">Chat online</div>
        <ul class="online-users list-inline" id="online-users-resp">
            <!-- list of online user -->
        </ul>
        <?php if ($vue == "home") : ?>
            <script>
                var connectedUser = <?= json_encode($connectedUser) ?>;
                var onlineUsers = document.getElementById('online-users-resp');
                onlineUsers.innerHTML = "";
                connectedUser.forEach(element => {
                    onlineUsers.innerHTML += `<li>
                        <a href="<?= $host ?>messenger/${element.followings_id}" title="@${element.user_nickname}">
                            <img src="<?= $files ?>users/profiles/${element.profile_image}" alt="user" class="img-responsive profile-photo">
                            <span class="online-dot"></span>
                        </a>
                    </li>`;
                });
            </script>
        <?php endif; ?>
    </div>
    <!-- chat proposition block end -->



    <div>


        <div class="spcBContent w100 marVer10 marHor10 addPublicationText">
            <div class="marHor2_5 imageContainer imageContainer50">
                <img src="<?= $files ?>users/profiles/<?= $userInfos->profile_image ?>" alt="images profile">
            </div>
            <div class="w87_5 marHor2_5">
                <textarea id="PublicationText" class="w100" placeholder="What on your mind ? @<?= $user->user_nickname ?>" style="background: var(--inputBg);"></textarea>
            </div>
        </div>
        <div class="w100 ctrContent colorRed error_publication"></div>
        <div class="separate_line"></div>
        <!-- media to upload | we add this content with script -->
        <div id="subject_image_view">
            <!-- media view -->
        </div>

        <!-- button -->
        <div class="add_btn spcBContent">
            <label class="btn flex1" for="file_adding_publication">
                <em class="forum-images"></em>&nbsp;&nbsp;
                Images/Videos
            </label>
            <input type="file" name="adding_publication_file" id="file_adding_publication" style="display: none;">
            <button type="reset" class="btn flex_wrap flex1 ctrContent">
                <em class="forum-delete colorRed"></em>&nbsp;&nbsp;
                Reset All
            </button>&nbsp;&nbsp;
            <button type="submit" class="btn flex1 ctrContent flex_wrap btnAddPublication">
                <em class="forum-add"></em>&nbsp;&nbsp;
                Add Publication
            </button>
        </div>
        <?php if (!isset($userProfile) || (isset($userProfile) && $userProfile[0]->user_id == $user->user_id)) : ?>
        <script src="<?= $assets ?>js/add_publication.js"></script>
        <script>
            /* /////////////////////////////////////////////////////////////////////// */
            /*             async function adding publication script                    */
            /* /////////////////////////////////////////////////////////////////////// */
            async function addPublication(text = "", media) {

                if (text.trim() == "" && !media) {
                    document.querySelector('.error_publication').innerHTML = "Can't add publication all empty field";
                } else {
                    const form = new FormData(); // form object

                    form.append("text", text);
                    form.append("publicationFile", media);
                    form.append("user_id", <?= $user->user_id ?>);
                    // alert(media);
                    await fetch('<?= $host ?>ajax/addPublication', {
                            body: form,
                            method: "POST"
                        })
                        .then(res => res.json())
                        .then((result) => {
                            if (result.success) {
                                document.querySelector('.error_publication').innerHTML = "";
                                file_adding_publication.value = "";
                                document.getElementById('PublicationText').value = "";
                                document.getElementById('subject_image_view').innerHTML = "";
                                <?php if (!isset($userProfile) || (isset($userProfile) && $userProfile[0]->user_id == $user->user_id)) : ?>
                                    getPublicationsAjax();
                                <?php endif; ?>
                                textarea.style.height = `45px`;
                                textarea.style.borderRadius = `60px`;
                            } else {
                                document.querySelector('.error_publication').innerHTML = result.error;
                            }
                        })
                        .catch((error) => console.log(error));
                }
            }
            // adding publication
            document.querySelector('.btnAddPublication').onclick = () => {
                let PublicationText = document.getElementById('PublicationText').value;

                addPublication(PublicationText, file_adding_publication.files[0]);
            }
        </script>
    <?php endif; ?>
    </div>

</div>

<!-- end adding publication zone -->