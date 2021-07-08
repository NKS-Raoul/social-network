<!-- Left side bar -->
<aside id="left_menu" aria-label="left">

    <!-- profile card -->
    <?php if ($authObj->logged()) { ?>
        <div class="profile-card flex_row spcBContent" style="background: linear-gradient(to bottom, #27aae166, #1c75bccc),url(<?= $files ?>users/covers/<?= $userInfos->cover_image ?>) no-repeat 15% 15%;">
            <img src="<?= $files ?>users/profiles/<?= $userInfos->profile_image ?>" alt="user" class="profile-photo">
            <div class="column">
                <h5><a href="<?= $host ?>profile/<?= $user->user_nickname ?>" class="text-white"><?= $user->user_name ?></a></h5>
                <a href="<?= $host ?>profile/<?= $user->user_nickname ?>" class="text-white">( @<?= $user->user_nickname ?> )</a>
            </div>
        </div>
    <?php } ?>
    <!-- profile card end -->


    <!-- aside links -->
    <ul class="left_side-news-feed">
        <li>
            <a href="#">
                <em class="icon forum-medical"></em>
                <div>COVID-19 information center</div>
            </a>
        </li>
        <li>
            <a href="<?= $host ?>">
                <em class="icon forum-home"></em>
                <div>Home</div>
            </a>
        </li>




        <!-- message -->
        <?php if ($authObj->logged()) { ?>
            <li>
                <a href="<?= $host ?>messenger">
                    <em class="icon forum-chat"></em>
                    <div class="unreadMessage">
                        <!-- for unread message -->
                    </div>
                </a>
                <script>
                    // execute when you load the page
                    let unreadMessage = <?= json_encode($cptMessages->unreadMessages) ?>;

                    /**
                     * function for display number of unread messages
                     */
                    function displayingUnreadMessages(unreadMessage) {
                        let html = document.querySelector('.unreadMessage');
                        html.innerHTML = "Messenger";
                        if (unreadMessage != 0) {
                            html.innerHTML += `<span class="chat-alert">${unreadMessage}</span>`;
                        }
                    }

                    /**
                     * asynchronous function to get unread messages
                     */
                    async function getUnreadMessages() {
                        await fetch("<?= $host ?>ajax/getUnreadMessages", {
                                method: "POST"
                            })
                            .then((res) => res.json())
                            .then((result) => {
                                if (result.success) {
                                    unreadMessage = result.unreadMessage.unreadMessages;
                                    // console.log(unreadMessage);
                                    displayingUnreadMessages(unreadMessage);
                                } else {
                                    alert(result.error);
                                }
                            })
                            .catch((error) => console.log(error));
                    }
                    // #E4E6EB
                    // #3A3B3C
                    // execution
                    displayingUnreadMessages(unreadMessage);
                    setInterval(() => {
                        getUnreadMessages();
                    }, 2000);
                </script>
            </li>
        <?php } ?>
    </ul>
    <!-- aside links end -->

    <div class="separate_line"></div>

    <!-- chat proposition block -->
    <div id="chat-block">
        <div class="title">Chat online</div>
        <ul class="online-users list-inline" id="online-users">
            <!-- list of online user -->
        </ul>
        <script>
            var connectedUser = <?= json_encode($connectedUser) ?>;

            function displayingOnlineUser(params = []) {
                var onlineUsers = document.getElementById('online-users');
                onlineUsers.innerHTML = "";
                params.forEach(element => {
                    if (element.followings_id != <?= $user->user_id ?>) {
                        onlineUsers.innerHTML += `<li>
                            <a href="<?= $host ?>messenger/${element.followings_id}" title="@${element.user_nickname}">
                                <img src="<?= $files ?>users/profiles/${element.profile_image}" alt="user" class="img-responsive profile-photo">
                                <span class="online-dot"></span>
                            </a>
                        </li>`;
                    }
                });
            }

            async function getOnlineUser() {
                await fetch("<?= $host ?>ajax/getOnlineUser", {
                        method: "POST"
                    })
                    .then((res) => res.json())
                    .then((result) => {
                        if (result.success) {
                            connectedUser = result.onlineUser;
                            // console.log(connectedUser);
                            displayingOnlineUser(connectedUser);
                        } else {
                            alert(result.error);
                        }
                    })
                    .catch((error) => console.log(error));
            }
            displayingOnlineUser(connectedUser);
            setInterval(() => {
                getOnlineUser();
            }, 2000);
        </script>
        <?php require_once($view . 'components/footer.php'); ?>
    </div>
    <!-- chat proposition block end -->
</aside>
<!-- end left side bar -->