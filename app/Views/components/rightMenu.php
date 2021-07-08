<!-- right side bar  -->
<aside id="right_menu" aria-label="right">

    <!-- publicity area -->
    <div class="publicity_area w100">
        <h3 class="small_title">Sponsored</h3>
        <div class="image_text">
            <img src="<?= $assets ?>images/user-20.jpg" alt="An image of a sponsored publication">
            <div>"the invite text for user come and linking aksalkscalsck alsckaslckaslc max 100
                lettres lettres lettres"</div>
        </div>
    </div>
    <!-- end publicity area -->




    <div class="separate_line"></div>




    <!-- notification -->
    <?php if ($authObj->logged()) { ?>
        <br>
        <a href="<?= $host ?>logout">
            <button class="btn-primary btn" id="btnLogOut" style="cursor: pointer;" title="Log out of the website">
                Log out
            </button>
        </a><br>
        <h3 class="small_title w100 textLeft">Who to follow</h3>
        <div id="follow_suggestion" class="w100">
            <!-- list of persons who follow you and you can follow back -->
        </div>
        <script>
            let follow = <?= json_encode($follow) ?>;

            function displayingFollow(follow) {
                let follow_suggestion = document.getElementById('follow_suggestion');
                follow_suggestion.innerHTML = "";
                follow.forEach(element => {
                    follow_suggestion.innerHTML += `<div class="feed-item w100">
                        <div class="imageContainer imageContainer50">
                            <img src="<?= $files ?>users/profiles/${element.profile_image}" alt="user's profile">
                        </div>
                        <div class="live-activity">
                            <p><a href="<?= $host ?>profile/${element.user_nickname}" class="profile-link">@${element.user_nickname}</a></p>
                            <p class="text-muted" onclick="addFollowings(${element.user_id})" style="color: green;cursor: pointer;">Follow</p>
                        </div>
                    </div>`;
                });
            }

            // request to get suggestion about follow back
            async function getFollow() {
                await fetch("<?= $host ?>ajax/getFollow", {
                        method: "POST"
                    })
                    .then((res) => res.json())
                    .then((result) => {
                        if (result.success) {
                            follow = result.follow;
                            // console.log(followBack);
                            displayingFollow(follow);
                        } else {
                            alert(result.error);
                        }
                    })
                    .catch((error) => console.log(error));
            }

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

            // 
            // function followBackUser() {
            //     if (window.XMLHttpRequest) {
            //         var xhr = new XMLHttpRequest();
            //         xhr.open("POST", "<?= $host ?>ajax/getFollowBack", true);
            //         xhr.onreadystatechange = function() {
            //             if (this.readyState == 4 && this.status == 200) {
            //                 // let aaaaa = ;
            //                 alert(eval(this.response));
            //             }
            //         }
            //         // const form = new FormData();
            //         xhr.send(null);
            //     } else {
            //         if (window.ActiveXObject) {
            //             var xhr = new ActiveXObject("Microsoft.XMLHTTP");
            //             // Reprendre les lignes 4 à 10
            //         } else {
            //             alert("Votre navigateur ne peut pas envoyer des requêtes HTTP via XHR");
            //         }
            //     }
            //     return false;
            // }

            // execution
            displayingFollow(follow);
            setInterval(() => {
                getFollow();
            }, 3000);
        </script>
    <?php } ?>
    <!-- end notifications -->
</aside>
<!-- end right side bar  -->