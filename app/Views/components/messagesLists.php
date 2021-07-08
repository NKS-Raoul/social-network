<?php if (!empty($otherUser)) : ?>
    <div class="flex2 messages bdrLeft">
        <div class="spcBContent">
            <em class="forum-arrow-dropleft-circle openContactList"></em>
            <?= $otherUser->user_name ?>
            <em class="forum-arrow-dropright-circle openProfileList"></em>
        </div>
        <ul class="messBody">
            <!-- area of messages -->
        </ul>
        <form action="" enctype="multipart/form-data" method="post" class="w100" id="sendingMess">
            <label for="message_file" style="left: 0;" class="abs_Posi forum-image attachment ctrContent" aria-hidden="true"></label>
            <label for="" class="abs_Posi forum-happy ctrContent" style="left: 50px;" id="addSmiley"></label>
            <div class="abs_Posi write h75 colorText" placeholder="Write your message" contenteditable="true"></div>
            <!-- <input type="text" name="messages" placeholder="Write your message...@<?= $user->user_nickname ?>"> -->
            <input style="display: none" type="file" name="message_file" id="message_file" multiple="">
            <button class="abs_Posi btn1" style="right: 0;" type="button" onclick="addMessage();"><em class="forum-airplane" aria-hidden="true"></em></button>

            <div class="flex_row messageAttach">
                <!-- place for messages attachment -->
            </div>
            <div class="flex_wrap smileyList h50" style="background: var(--componentBg); overflow-y: scroll; display: none;">
                <!-- place for emojis -->
            </div>
        </form>
        <script src="<?= $assets ?>js/messenger.js"></script>
        <script>
            


            // manage smiley adding
            let btnAddSmiley = document.querySelector('#addSmiley');
            btnAddSmiley.onclick = () => {
                var smileyList = document.querySelector('.smileyList');
                smileyList.style.display = smileyList.style.display == "none" ? "flex" : "none";
            }

            // console.log(<?= json_encode($smileyTable) ?>);
            let smileys = <?= json_encode($smileyTable) ?>;
            var ss = document.querySelector('.smileyList');
            ss.innerHTML = "";
            smileys.forEach(element => {
                ss.innerHTML += element;
            });
            // add smiley
            let smiley = document.querySelectorAll('.smiley');
            smiley.forEach(element => {
                element.onclick = () => {
                    // alert("raoul")
                    var a = document.querySelector('.write');
                    a.innerHTML += element.innerHTML;
                }
            });


            let messagesList = <?= $messages ?>;
            var scroll = document.querySelector('.messBody');
            // console.log(messagesList);

            function displayingMessages(messages = []) {
                let displayMessages = document.querySelector('.messBody');
                displayMessages.innerHTML = '';
                messages.forEach(message => {
                    displayMessages.innerHTML += `<li class="${message.type}">
                    <div>
                        ` + attachment(message.attachments) + `
                    </div>
                    <p class="flex_wrap" style="overflow: hidden;">${message.message}</p>
                </li>`;
                });
                var sendSmiley = document.querySelectorAll('.send > p > img');
                sendSmiley.forEach((elem)=>{
                    elem.src = elem.src.replace("<?= $host ?>messenger/imgSmileys2021", '<?= $assets ?>');
                })
                var receiveSmiley = document.querySelectorAll('.replies > p > img');
                receiveSmiley.forEach((elem)=>{
                    elem.src = elem.src.replace("<?= $host ?>messenger/imgSmileys2021", '<?= $assets ?>');
                })
            }

            function attachment(params = []) {
                var string = '';
                params.forEach(element => {
                    element.type == "image" ? string += `<img src="<?= $files ?>chats/images/${element.media}" alt="">` :
                        string += `<video src="<?= $files ?>chats/videos/${element.media}" controls></video>`;
                });
                return string;
            }

            /************************************************************* */
            /*                      Asynchronous request                   */
            /************************************************************* */
            async function getMessages() {
                const form = new FormData();
                form.append("id", <?= $otherUser->user_id ?>);
                await fetch("<?= $host ?>ajax/getMessages", {
                        body: form,
                        method: "POST"
                    })
                    .then((res) => res.json())
                    .then((result) => {
                        if (result.success) {
                            if (result.messages.length != messagesList.length) {
                                if (result.messages[result.messages.length - 1].sender_id == <?= $otherUser->user_id ?> && messagesList.length != 0) {
                                    const son = new Audio("<?= $assets ?>sounds/inbox.mp3");
                                    son.play();
                                }
                                messagesList = result.messages;
                                displayingMessages(result.messages);
                                scroll.scrollTo(0, scroll.scrollHeight);
                            }
                        } else {
                            alert(result.error);
                        }
                    })
                    .catch((error) => console.log(error));
            }
            /**
             * function adding a new message
             */
            async function addMessage() {
                let messageForm = document.querySelector('div.write');
                const form = new FormData();
                // alert(messageForm.innerHTML);
                // alert(messageForm.message_file.files);
                // var a = messageForm.innerHTML.replace("<?= $assets ?>", "imgSmileys2021");
                // alert(a);
                if (messageForm.innerHTML.trim() == '') {
                    if (imagesToDisplay.length != 0) {
                        form.append("message", "");
                    } else {
                        return false;
                    }
                } else {
                    var a = document.querySelectorAll('div.write img');
                    a.forEach((elem)=>{
                        elem.src = elem.src.replace("<?= $assets ?>", "imgSmileys2021");
                    })
                    form.append("message", messageForm.innerHTML);
                }

                let i = 0;
                imagesToSend.forEach(element => {
                    form.append("files" + (i++), element);
                });

                form.append("user_id", <?= $user->user_id ?>);
                form.append("otherUser_id", <?= $otherUser->user_id ?>);

                await fetch("<?= $host ?>ajax/newMessage", {
                        body: form,
                        method: "POST"
                    })
                    .then(res => res.json())
                    .then((result) => {
                        imagesToDisplay = [];
                        imagesToSend = [];
                        renderImages();
                        messageForm.innerHTML = "";
                    })
                    .catch((error) => console.log(error));
            }

            // execution
            displayingMessages(messagesList);
            scroll.scrollTo(0, scroll.scrollHeight*2);
            setInterval(() => {
                getMessages();
            }, 250);
        </script>
    </div>

<?php else : ?>


    <div class="flex2 bdrLeft ctrContent flex_wrap">
        <div class="w50 ctrContent">
            <img src="<?= $assets ?>images/noMessages.jpg" alt="No messages found">
        </div><br />
        <h1 class="alignCenter">
            No Selected User conversation ! <br>
            Please chooser a receiver user
        </h1>
    </div>

<?php endif; ?>