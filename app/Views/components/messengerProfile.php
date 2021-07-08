<div class="flex1 bdrLeft messengerRight">
    <div class="message_profile w100 h25 ctrContent">
        <a href="<?= $host ?>profile/<?= $user->user_nickname ?>" class="imageContainer imageContainer150 borderW">
            <img src="<?= $files ?>users/profiles/<?= $userInfos->profile_image ?>" alt="profile of the user">
        </a>
    </div>



    <?php if ($authObj->logged()) { ?>
        <h3 class="small_title w100 textLeft">Who to follow back</h3>
        <div id="followBack_suggestion" class="w100">
            <!-- list of persons who you can follow -->
        </div>
        <script>
            let followBack = <?= json_encode($followBack) ?>;
            // console.log(followBack);

            function displayingFollowBack(followBack) {
                let followBack_suggestion = document.getElementById('followBack_suggestion');
                followBack_suggestion.innerHTML = "";
                followBack.forEach(element => {
                    followBack_suggestion.innerHTML += `<div class="feed-item">
                        <div class="imageContainer imageContainer50">
                            <img src="<?= $files ?>users/profiles/${element.profile_image}" alt="user's profile">
                        </div>
                        <div class="live-activity">
                            <p><a href="<?= $host ?>profile/${element.user_nickname}" class="profile-link">@${element.user_nickname}</a> is following you</p>
                            <p class="text-muted" onclick="addFollowings(${element.user_id})" style="color: green;cursor: pointer;">Follow Back</p>
                        </div>
                    </div>`;
                });
            }

            // request to get suggestion about follow back
            async function getFollowBack() {
                await fetch("<?= $host ?>ajax/getFollowBack", {
                        method: "POST"
                    })
                    .then((res) => res.json())
                    .then((result) => {
                        if (result.success) {
                            followBack = result.followBack;
                            // console.log(followBack);
                            displayingFollowBack(followBack);
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
                await fetch("<?= $host ?>ajax/addFollowings", {body: following_id, method: "POST"})
                .then((res)=>res.json())
                .then((result)=>{
                    if (!result.success) {
                        console.log(result.error);
                    }
                })
                .catch((error)=>console.log(error));
            }



            // // 
            // // function followBackUser() {
            // //     if (window.XMLHttpRequest) {
            // //         var xhr = new XMLHttpRequest();
            // //         xhr.open("POST", "<?= $host ?>ajax/getFollowBack", true);
            // //         xhr.onreadystatechange = function() {
            // //             if (this.readyState == 4 && this.status == 200) {
            // //                 // let aaaaa = ;
            // //                 alert(eval(this.response));
            // //             }
            // //         }
            // //         // const form = new FormData();
            // //         xhr.send(null);
            // //     } else {
            // //         if (window.ActiveXObject) {
            // //             var xhr = new ActiveXObject("Microsoft.XMLHTTP");
            // //             // Reprendre les lignes 4 à 10
            // //         } else {
            // //             alert("Votre navigateur ne peut pas envoyer des requêtes HTTP via XHR");
            // //         }
            // //     }
            // //     return false;
            // // }

            // execution
            displayingFollowBack(followBack);
            setInterval(() => {
                getFollowBack();
            }, 3000);
        </script>
    <?php } ?>
</div>