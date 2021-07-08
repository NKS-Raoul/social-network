<!--A publication of publication -->
<div id="allSubject">
    <div class="allPublication"></div>
</div>
<!-- script for ajax -->
<script>
    let publicationList = <?= json_encode($publicationList) ?>;


    function displayingPublications(publicationList = []) {
        let allPublication = document.querySelector('.allPublication');
        // console.log(publicationList);
        allPublication.innerHTML = "";
        publicationList.forEach(element => {
            allPublication.innerHTML += `<article id="aPublication" class="w100 aPublication marVer10" style+"margin-bottom: 10px">
            <div class="spcBContent padHor10 padVer5 pubHeader">
                <div class="w37_5 flex_row ">
                    <div class="imageContainer imageContainer50 marHor5">
                        <img src="<?= $files ?>users/profiles/${element.profile_image}" alt="image of the user">
                    </div>
                    <div class="column marHor5 ">
                        <p class="marVer2_5"><a href="<?= $host ?>profile/${element.user_nickname}">
                                <strong>@${element.user_nickname}</strong>
                            </a></p>
                        <p>${element.post_at}</p>
                    </div>
                </div>
                <?php if ($vue != "comments") : ?>
                ` + authDelete(element) + `
                <?php endif; ?>
            </div>
            <div class="textLeft padHor10 padVer5 pubText">
                ${element.publication_text}
            </div>
            <div class="w100 ctrContent pubMedia">
                ` + displayingMediaOfPublication(element) + `
            </div>
            <div class="padHor10 padVer5 spcBContent reactArea">
                <div class="flex_row">
                    ` + displayingLikes(element) + `
                    ` + displayingDisLikes(element) + `
                    <a href="<?= $host ?>comments/${element.publication_id}" class="comments">
                        <button class="btn marHor2_5" id="pubBtnComment">
                            <em class="forum-chat marHor2_5"></em>&nbsp;Comments
                        </button>
                    </a>
                </div>
                <div class="ctrContent">
                    <span class="strLike${element.publication_id}">${element.strLike},</span>&nbsp;
                    <span id="cptComment${element.publication_id}"></span>
                </div>
            </div>
            <div class="ctrContent">
                <div class="separate_line" style="width: 80% !important;"></div>
            </div>
            <div class="w100 padHor10 padVer5 commentArea">
                <div class="w100" id="commentArea${element.publication_id}">
                    
                </div>
            </div>
            <div class="flex_row addPublication">
                <div class="w87_5">
                    <input type="text" class="w100 h100"
                        id="addPubComment${element.publication_id}" 
                        placeholder="Add a comment here @${element.user_nickname}">
                </div>
                <div class="w12_5 h100 ctrContent" onclick="addComment(${element.publication_id})">
                    <em class="forum-airplane"></em>
                </div>
            </div>
            <div class="w100 ctrContent colorRed error_publication${element.publication_id}"></div>
        </article>`;
            displayCptComments(element.publication_id, element.cptComments);
            <?php if ($vue != "comments") : ?>
                displayingComments(element.publication_id, element.comments);
            <?php endif; ?>
        });
    }

    function displayCptComments(publication_id, cptComments) {
        document.querySelector("#cptComment" + publication_id).innerHTML = cptComments + " Comments";
    }

    function authDelete(param = {}) {
        <?php if ($authObj->logged()) { ?>
            return (param.user_id == <?= $user->user_id ?> ? `<div class="ctrContent"
                    onclick="deletePublication(${param.publication_id})">
                    <em class="forum-delete marHor10 colorRed" title="delete this publication"></em>
                </div>` : ``);
        <?php } ?>
    }

    function displayingMediaOfPublication(params = {}) {
        if (params.media != null) {
            if (params.type == 'image') {
                return `<div class="ctrContent">
                    <img src="<?= $files ?>publications/images/${params.media}" loading="lazy" alt="posted image">
                </div>`;
            } else if (params.type == 'video') {
                return `<div class="ctrContent">
                    <video src="<?= $files ?>publications/videos/${params.media}" controls></video>
                </div>`;
            } else {
                return ``;
            }
        } else {
            return ``;
        }

    }

    function displayingComments(publication_id, params = []) {
        var html = document.getElementById('commentArea' + publication_id);
        html.innerHTML = "";
        params.forEach(element => {
            html.innerHTML += `<div class="w100 simple_flex  aComment">
                        <div class="ctrContent">
                            <div class="imageContainer imageContainer50 marHor5">
                                <img src="<?= $files ?>users/profiles/${element.profile_image}" alt="image of the user">
                            </div>
                        </div>
                        <div class="column marHor5  marVer2_5 padHor10 padVer5">
                            <p class="marVer2_5"><a href="<?= $host ?>profile/${element.user_nickname}">
                                    <strong>@${element.user_nickname}</strong>
                                </a></p>
                            <p>${element.comment_text}</p>
                        </div>
                    </div>`;
        });

        return html;
    }

    function displayingLikes(params = {}) {
        if (params.my_like && (params.my_like.like_value == "1")) {
            return `<button class="btn marHor2_5" onclick="addLikes(${1}, ${params.publication_id});"
                        id="pubBtnLike${params.publication_id}">
                        <em class="forum-thumb_sup marHor2_5 siteColor"></em>&nbsp;${params.likes._like}
                    </button>`;
        }

        return `<button class="btn marHor2_5" onclick="addLikes(${1}, ${params.publication_id});"
                    id="pubBtnLike${params.publication_id}">
                        <em class="forum-thumb_sup marHor2_5"></em>&nbsp;${params.likes._like}
                    </button>`;
    }

    function displayingDisLikes(params = {}) {
        if (params.my_like && (params.my_like.like_value == "2")) {
            return `<button class="btn marHor2_5" onclick="addLikes(${2}, ${params.publication_id});"
                        id="pubBtnDislike${params.publication_id}">
                        <em class="forum-thumb_down marHor2_5" style="color: red;"></em>&nbsp;
                        ${params.dislikes.dislikes}
                    </button>`;
        }

        return `<button class="btn marHor2_5" onclick="addLikes(${2}, ${params.publication_id});"
                        id="pubBtnDislike${params.publication_id}">
                        <em class="forum-thumb_down marHor2_5"></em>&nbsp;
                        ${params.dislikes.dislikes}
                    </button>`;
    }

    // managing like changing
    function likeManagement(params, publication_id) {
        var aLike = document.getElementById('pubBtnLike' + publication_id);
        var aDislike = document.getElementById('pubBtnDislike' + publication_id);
        // aLike.style.color = `var(--site_color)`;
        aLike.innerHTML = `<em class="forum-thumb_sup marHor2_5 siteColor"></em>&nbsp;${params.likes._like}`;
        // aDislike.style.color = `var(--colorText)`;
        aDislike.innerHTML = `<em class="forum-thumb_down marHor2_5"></em>&nbsp;
                            ${params.dislikes.dislikes}`;
        // alert("Likes");
    }
    // managing dislike changing
    function dislikeManagement(params, publication_id) {
        var aLike = document.getElementById('pubBtnLike' + publication_id);
        var aDislike = document.getElementById('pubBtnDislike' + publication_id);
        // aLike.style.color = `var(--colorText)`;
        aLike.innerHTML = `<em class="forum-thumb_sup marHor2_5"></em>&nbsp;${params.likes._like}`;
        // aDislike.style.color = `var(--site_color)`;
        aDislike.innerHTML = `<em class="forum-thumb_down marHor2_5" style="color: red;"></em>&nbsp;
                            ${params.dislikes.dislikes}`;
        // alert("Dislikes");
    }
    // like and dislike annulation
    function likeDislikeAnnulation(params, publication_id) {
        var aLike = document.getElementById('pubBtnLike' + publication_id);
        var aDislike = document.getElementById('pubBtnDislike' + publication_id);
        // aLike.style.color = `var(--colorText)`;
        aLike.innerHTML = `<em class="forum-thumb_sup marHor2_5"></em>&nbsp;${params.likes._like}`;
        // aDislike.style.color = `var(--colorText)`;
        aDislike.innerHTML = `<em class="forum-thumb_down marHor2_5"></em>&nbsp;
                        ${params.dislikes.dislikes}`;
        // alert("Annulation");
    }
    /******************************************************* */
    /*                Asynchronous Request                   */
    /******************************************************* */
    /**
     * asynchronous getting publications
     */
    async function getPublicationsAjax() {
        await fetch('<?= $host ?>ajax/getPublications', {
                method: "POST"
            })
            .then((res) => res.json())
            .then((result) => {
                if (result.success) {
                    publicationList = result.publications;
                    // alert(publicationList);
                    displayingPublications(publicationList);
                } else {
                    alert("error during getting");
                }
            })
            .catch((error) => console.log(error));
    }
    // add a like or a dislike
    async function addLikes(likeValue = 0, publication_id) {
        const form = new FormData();
        form.append("like_value", likeValue);
        form.append("publication_id", publication_id);
        form.append("user_id", <?= $user->user_id ?>);
        await fetch('<?= $host ?>ajax/addLikes', {
                body: form,
                method: "POST"
            })
            .then((res) => res.json())
            .then((result) => {
                // console.log(result);
                if (result.success) {
                    var like = result.like;
                    if (like.my_like) {
                        if (like.my_like.like_value == "1") {
                            likeManagement(like, publication_id)
                            var lk = document.querySelector(".strLike" + publication_id);
                            lk.innerHTML = "You and " + (like.likes._like - 1) + " person(s) like,";
                        } else if (like.my_like.like_value == "2") {
                            dislikeManagement(like, publication_id)
                            var lk = document.querySelector(".strLike" + publication_id);
                            lk.innerHTML = (like.likes._like) + " person(s) like,";
                        }
                    } else {
                        likeDislikeAnnulation(like, publication_id);
                        var lk = document.querySelector(".strLike" + publication_id);
                        lk.innerHTML = (like.likes._like) + " person(s) like,";
                    }
                } else {
                    alert(result.error);
                }
            })
            .catch((error) => console.log(error));
    }
    // delete a subject
    async function deletePublication(publication_id) {
        const form = new FormData();
        form.append("publication_id", publication_id);
        form.append("user_id", <?= $user->user_id ?>);
        await fetch('<?= $host ?>ajax/deletePublication', {
                body: form,
                method: "POST"
            })
            .then((res) => res.json())
            .then((result) => {
                if (result.success) {
                    publicationList = result.publications;
                    displayingPublications(publicationList);
                } else {
                    alert(result.error);
                }
                // console.log(result);
            })
            .catch((error) => console.log(error));
    }
    // request for adding comments
    async function addComment(publication_id) {
        let pubComment = document.getElementById('addPubComment' + publication_id);
        if (pubComment.value.trim() == '') {
            document.querySelector(".error_publication" + publication_id).innerHTML = "Can't not add an empty field";
        } else {
            const form = new FormData();
            form.append("comment", pubComment.value);
            form.append("publication_id", publication_id);
            form.append("user_id", <?= $user->user_id ?>);
            <?php if ($vue == "comments") : ?>
                form.append("vue", "comments");
            <?php endif; ?>
            await fetch('<?= $host ?>ajax/addComment', {
                    body: form,
                    method: "POST"
                })
                .then((res) => res.json())
                .then((result) => {
                    if (result.success) {
                        <?php if ($vue != "comments") : ?>
                            displayingComments(publication_id, result.comments);
                        <?php else : ?>
                            displayComments(result.comments);
                        <?php endif; ?>
                        displayCptComments(publication_id, result.cptComments);
                        pubComment.value = "";
                        document.querySelector(".error_publication" + publication_id).innerHTML = "";
                    } else {
                        document.querySelector(".error_publication" + publication_id).innerHTML = result.error;
                    }
                    // console.log(result);
                })
                .catch((error) => console.log(error));
        }

    }

    // execution
    displayingPublications(publicationList);
</script>

<!-- End of a publication of subject -->