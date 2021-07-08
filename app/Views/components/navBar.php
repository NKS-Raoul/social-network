<!-- Navigation bar -->
<nav class="row navBar">
    <div class="container spcBContent">
        <div class="h100 spcBContent">
            <a href="<?= $host ?>" class="ctrContent imageContainer imageContainer50" id="logo_link">
                <em class="iconWhite forum-twitch"></em>
            </a>
            <form action="" method="get" class="searchForm">
                <input type="search" name="search" class="w75" id="search" placeholder="Search on site">
                <button class="ctrContent w25 btn1" type="submit"><em class="iconW forum-search"></em></button>
            </form>
        </div>









        <ul class="ctrContent link resp_link">
            <li class="ctrContent flex1 h100">
                <a href="<?= $host ?>" class="all_place ctrContent ">
                    <em class="icon forum-home"></em>
                    <span class="tooltip">HOME</span>
                </a>
            </li>



            <li class="ctrContent flex1 h100">
                <a href="<?= $host ?>messenger" class="all_place ctrContent ">
                    <em class="icon forum-chat"></em>
                    <span class="tooltip">CHAT</span>
                </a>
            </li>
        </ul>






        <!-- profile name and image -->
        <div class="ctrContent h100 resp_cont_profile">
            <!-- if the user is connect -->
            <!-- <div class="resp_profile flex_row">
                    <a href="<?= $host ?>login" class="white flex1 marHor5"><button class="btn secondary all_place">Log In</button></a>
                    <a href="<?= $host ?>register" class="white flex1 marHor5"><button class="btn secondary all_place">Register</button></a>
                </div> -->
            <a href="<?= $host ?>profile/<?= $user->user_nickname ?>" class="resp_profile profileRight flex1 h100 spcBContent">
                <div class="imageContainer imageContainer50 navBarImg">
                    <img src="<?= $files ?>users/profiles/<?= $userInfos->profile_image ?>" alt="Profile Image">
                </div>
                <div class="leftAlign h100 userName50"><?= $user->user_name ?></div>
            </a>




            <!-- button for more options -->
            <div class="container_option">
                <div class="bar bar-1"></div>
                <div class="bar bar-2"></div>
                <div class="bar bar-3"></div>
            </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label class="switch">
                <input type="checkbox" id="change_mode">
                <span class="slider round"></span>
                <span class="tooltip">Change Mode</span>
            </label>
        </div>
    </div>




    <!-- proposition list -->
    <div class="list_Proposition" id="list_proposition">
        <a href="<?= $host ?>searchResult" class="profileRight flex1 h100 spcBContent bdrBottom padVer5">
            <div class="imageContainer imageContainer50">
                <img src="<?= $assets ?>images/user-12.jpg" alt="Profile Image">
            </div>
            <div class=" userName50 h100">
                <p>userName50</p>
                <p style="font-size: 12px;">publication</p>
            </div>
        </a>
        <a href="<?= $host ?>searchResult" class="profileRight textLeft result bdrBottom">
            <p>result</p>
            <p style="font-size: 12px;">publication</p>
        </a>
    </div>
    <script>
        // area of proposition about what your writing on search area
        let list_proposition = document.getElementById('list_proposition');
        let search = document.getElementById('search');
        search.oninput = async () => {
            search.value.length != 0 ? list_proposition.style.display = 'flex' :
                list_proposition.style.display = 'none';
            if (search.value.trim() != '') {
                const form = new FormData();
                form.append("text", search.value);
                form.append("user_id", <?= $user->user_id ?>);
                await fetch("<?= $host ?>ajax/search", {
                        body: form,
                        method: "POST"
                    })
                    .then((res) => res.json())
                    .then((result) => {
                        console.log(result);
                        list_proposition.innerHTML = '';
                        result.searchList.forEach(element => {
                            if (element.type == 'user') {
                                list_proposition.innerHTML += `<a href="<?= $host ?>profile/${element.user_nickname}" class="profileRight flex1 h100 spcBContent bdrBottom padVer5">
                                            <div class="imageContainer imageContainer50">
                                                <img src="<?= $files ?>users/profiles/${element.profile_image}" alt="Profile Image">
                                            </div>
                                            <div class=" userName50 h100">
                                                <p>${element.user_nickname}</p>
                                                <p style="font-size: 12px;">${element.type}</p>
                                            </div>
                                        </a>`
                            } else {
                                list_proposition.innerHTML += `<a href="<?= $host ?>comments/${element.publication_id}" class="profileRight textLeft result bdrBottom">
                                                    <p>${element.comment_text}</p>
                                                    <p style="font-size: 12px;">${element.type}</p>
                                                </a>`;
                            }
                        });
                    })
                    .catch((error) => console.log(error));
            }
        }
    </script>






    <!-- responsive menu -->
    <div class="resp_menu fixed_Posi">
        <!-- aside links -->
        <ul class="left_side-news-feed">
            <li>
                <a href="#">
                    <em class="icon forum-medical"></em>
                    <div>COVID-19 information center</div>
                </a>
            </li>



            <li>
                <a href="<?= $host ?>home">
                    <em class="icon forum-home"></em>
                    <div>Home</div>
                </a>
            </li>




            <li>
                <a href="<?= $host ?>messenger">
                    <em class="icon forum-chat"></em>
                    <div class="unreadMessage1">Messenger
                        <?php if ($cptMessages->unreadMessages != 0) { ?>
                            <span class="chat-alert"><?= $cptMessages->unreadMessages ?></span>
                        <?php } ?>
                    </div>
                </a>
            </li>
            <script>
                // execute when you load the page
                let unreadMessage1 = <?= json_encode($cptMessages->unreadMessages) ?>;

                /**
                 * function for display number of unread messages
                 */
                function displayingUnreadMessages1(unreadMessage) {
                    var html = document.querySelector('.unreadMessage1');
                    html.innerHTML = "Messenger";
                    html.innerHTML += `<span class="chat-alert">${unreadMessage}</span>`;
                }

                /**
                 * asynchronous function to get unread messages
                 */
                async function getUnreadMessages1() {
                    await fetch("<?= $host ?>ajax/getUnreadMessages", {
                            method: "POST"
                        })
                        .then((res) => res.json())
                        .then((result) => {
                            if (result.success) {
                                unreadMessage1 = result.unreadMessage.unreadMessages;
                                // console.log(unreadMessage);
                                displayingUnreadMessages1(unreadMessage1);
                            } else {
                                alert(result.error);
                            }
                        })
                        .catch((error) => console.log(error));
                }
                // #E4E6EB
                // #3A3B3C
                // execution
                displayingUnreadMessages1(unreadMessage1);
                setInterval(() => {
                    getUnreadMessages1();
                }, 2000);
            </script>

        </ul>
        <!-- aside links end -->






        <form action="" method="get" class="searchForm resp_form">
            <input type="search" name="search" class="w75" id="research" placeholder="Search on site">
            <button class="ctrContent w25 btn1" type="submit"><em class="iconW forum-search"></em></button>
        </form>
    </div>
</nav>
<!-- End navigation bar -->