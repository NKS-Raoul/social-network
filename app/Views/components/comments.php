<h2 class="w100 textCenter siteColor">
    Comments
</h2>
<!-- a comment -->
<div class="w100 commentList"></div>

<script>
    let commentList = <?= json_encode($commentsList) ?>;

    function displayComments(commentList = []) {
        let commentArea = document.querySelector('.commentList');
        commentArea.innerHTML = '';
        console.log(commentList);
        commentList.forEach((element) => {
            commentArea.innerHTML += `<div class="w100 marVer10 borderLeft" style="margin: 15px 0;">
                        <div class="w100 simple_flex aComment">
                            <div class="ctrContent">
                                <div class="imageContainer imageContainer50 marHor5">
                                    <img src="<?= $files ?>/users/profiles/${element.profile_image}" alt="image of the user">
                                </div>
                            </div>
                            <div class="column marHor5  marVer2_5 padHor10 padVer5">
                                <p class="marVer2_5">
                                    <a href="<?= $host ?>profile/${element.user_nickname}">
                                        <strong>@${element.user_nickname}</strong>
                                    </a>
                                </p>
                                <p class="colorText">${element.comment_text}</p>
                            </div>
                        </div>
                        <div class="flex_end">
                            <div class="w75 answerArea${element.comment_id}">
                                
                            </div>
                        </div>
                        <div class="simple_flex w100 marVer10">
                            <div class="marHor2_5 imageContainer imageContainer30">
                                <img src="<?= $files ?>/users/profiles/<?= $userInfos->profile_image ?>" alt="images profile">
                            </div>
                            ` + displayDeleteComment(element) + `
                            <div class="w87_5 marHor2_5 addResponse">
                                <input type="text" id="addAnswer${element.comment_id}" class="w100" placeholder="Answer to @${element.user_nickname}">
                            </div>
                            <div class="cycle cycle30 ctrContent" onclick="addAnswer(${element.comment_id}, ${element.user_id})">
                                <em class="forum-airplane siteColor"></em>
                            </div>
                        </div>
                        <div class="w100 ctrContent colorRed error_comment${element.comment_id}"></div>
                    </div>`;
            displayAnswer(element.answerList, element.comment_id);
        });
    }

    function displayDeleteComment(params = {}) {
        if (params.user_id == <?= $user->user_id ?>) {
            return `<div class="marHor2_5 cycle cycle30 ctrContent" title="delete this comment"
                            onclick="deleteComment(${params.comment_id})">
                                <em class="forum-delete colorRed"></em>
                            </div>`
        }
        return ``;
    }

    function displayAnswer(params = [], comment_id) {
        console.log(params);
        let html = document.querySelector('.answerArea' + comment_id);
        html.innerHTML = '';
        params.forEach(element => {
            html.innerHTML += `<div class="w100 simple_flex aComment">
                                    <div class="ctrContent">
                                        <div class="imageContainer imageContainer50 marHor5">
                                            <img src="<?= $files ?>/users/profiles/${element.profile_image}" alt="image of the user">
                                        </div>
                                    </div>
                                    <div class="column marHor5  marVer2_5 padHor10 padVer5">
                                        <p class="marVer2_5">
                                            <a href="<?= $host ?>profile/${element.user_nickname}">
                                                <strong>@${element.user_nickname}</strong>
                                            </a>
                                        </p>
                                        <p class="colorText">
                                            ` + displayToUser(element.to_user, element.user_id) + `
                                            ${element.answer}
                                        </p>
                                    </div>
                                </div>`;
        });
    }

    function displayToUser(params = {}, adder_id) {
        if (params.user_id == adder_id) {
            return ``;
        } else {
            return `<a href="<?= $host ?>profile/${params.user_nickname}">
                        <strong>&nbsp;&nbsp;&nbsp; @${params.user_nickname}</strong> :
                    </a>`;
        }
    }
    /* /////////////////////////////////////////////////////////////////// */
    /*                         Asynchronous request                        */
    /* /////////////////////////////////////////////////////////////////// */
    async function deleteComment(comment_id) {
        const form = new FormData();
        form.append("comment_id", comment_id);
        form.append("user_id", <?= $user->user_id ?>);
        form.append("publication_id", publicationList[0].publication_id);

        await fetch("<?= $host ?>ajax/deleteComment", {
                method: "POST",
                body: form
            })
            .then((res) => res.json())
            .then((result) => {
                if (result.success) {
                    displayComments(result.comments);
                    displayCptComments(publicationList[0].publication_id, result.cptComments);
                } else {
                    document.querySelector(".error_comment" + comment_id).innerHTML = result.error;
                }
            })
            .catch((error) => console.log(error));
    }

    /**
     * add an answer
     */
    async function addAnswer(comment_id, to_user_id) {
        const form = new FormData();
        var s = document.querySelector('#addAnswer' + comment_id);
        if (s.value.trim() == "") {
            document.querySelector(".error_comment" + comment_id).innerHTML = "Can't add an empty answer";
        } else {
            form.append("comment_id", comment_id);
            form.append("user_id", <?= $user->user_id ?>);
            form.append("answer_text", s.value);
            form.append("to_user_id", to_user_id);

            await fetch("<?= $host ?>ajax/addAnswer", {
                    method: "POST",
                    body: form
                })
                .then((res) => res.json())
                .then((result) => {
                    if (result.success) {
                        displayAnswer(result.answers, comment_id);
                        s.value = "";
                    } else {
                        document.querySelector(".error_comment" + comment_id).innerHTML = result.error;
                    }
                })
                .catch((error) => console.log(error));
        }
    }
    // execution
    displayComments(commentList);
</script>





<!-- end a comment -->