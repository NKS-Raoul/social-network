<div class="flex1 messengerLeft">
    <div class="new_param bdrBottom">
        <em class="forum-cog" title="parameter"></em>
        <span>Messenger</span>
        <em class="forum-book" title="new conversation"></em>
    </div>
    <div class="messenger_search">
        <form action="" method="get">
            <button type="submit" class="btn1 w25 h100 ctrContent">
                <em class="forum-search"></em>
            </button>
            <input type="search" name="search_messenger" id="search_messenger" placeholder="Search in Messenger" class="w75 h100">
        </form>
    </div>
    <div class="receiver" id="contactsList">
        <script>
            let contactsList = <?= $contactsList ?>;
            // contactsList.forEach(con)
            // console.log(contactsList);

            function displayContact(params = []) {
                let displayContactList = document.getElementById('contactsList');
                displayContactList.innerHTML = "";
                params.forEach(element => {
                    displayContactList.innerHTML += `<a href="<?= $host ?>messenger/${element.user_id}" style="text-decoration: none; color: inherit;">
                            <div class="aDiscussion ${element.active ? "active" : ""}">
                                <div>
                                    <img src="<?= $files ?>users/profiles/${element.profile_image}" alt="receiver profile Image">
                                </div>
                                <div>
                                    <p>
                                        <span><strong>@${element.user_nickname}</strong></span>
                                        ` + formatHour(element) + `
                                    </p>
                                    <p>${(element.preview).length > 70  ? (element.preview).substr(0, 67)+" ...": element.preview}</p>
                                    ` + unreadMessages(element) + `
                                </div>
                            </div></a>`;
                });
            }

            // function for unreadMessages
            function unreadMessages(element = {}) {
                if (element.unreadMessages) {
                    return `<span class="chat-alert messAlert">${element.unreadMessages}</span>`;
                } else {
                    return ``;
                }
            }

            function formatHour(element) {
                return (element.lastMessageDate == '0') ? '' : `<span>${element.lastMessageDate}</span>`;
            }

            /************************************************************* */
            /*                      Asynchronous request                   */
            /************************************************************* */
            <?php if (isset($otherUser)) { ?>
                async function getContactsWithOther() {
                    const form = new FormData();
                    form.append("id", <?= $otherUser->user_id ?>);
                    await fetch("<?= $host ?>ajax/getContactsWithOther", {
                            body: form,
                            method: "POST"
                        })
                        .then((res) => res.json())
                        .then((result) => {
                            // console.log(result);
                            if (result.success) {
                                if (result.contacts[0].lastMessageAlready_receive == 0) {
                                    if (result.contacts[0].preview.indexOf("<strong") == -1 && result.contacts[0].user_id != <?= (isset($otherUser)) ? $otherUser->user_id : 0 ?>) {
                                        const son = new Audio("<?= $assets ?>sounds/notification.mp3");
                                        son.play();
                                        changeReceiverState(result.contacts[0].lastMessageId);
                                    }
                                }
                                contactsList = result.contacts;
                                displayContact(formatActive(result.contacts));
                            } else {
                                alert(result.error);
                            }
                        })
                        .catch((error) => console.log(error));
                }

                function formatActive(params = []) {
                    var tempContacts = [];
                    params.forEach(contact => {
                        tempContacts.push({
                            ...contact,
                            active: (contact.user_id == <?= (isset($otherUser)) ? $otherUser->user_id : 0 ?>)
                        });
                    });
                    return tempContacts;
                }

                setInterval(() => {
                    getContactsWithOther();
                }, 500);
            <?php } else { ?>
                async function getContacts() {
                    await fetch("<?= $host ?>ajax/getContacts", {
                            method: "POST"
                        })
                        .then((res) => res.json())
                        .then((result) => {
                            // console.log(result);
                            if (result.success) {
                                if (result.contacts[0].lastMessageAlready_receive == 0) {
                                    if (result.contacts[0].preview.indexOf("<strong") == -1 && result.contacts[0].user_id != <?= (isset($otherUser)) ? $otherUser->user_id : 0 ?>) {
                                        const son = new Audio("<?= $assets ?>sounds/notification.mp3");
                                        son.play();
                                        changeReceiverState(result.contacts[0].lastMessageId);
                                    }
                                }
                                contactsList = result.contacts;
                                displayContact(result.contacts);
                            } else {
                                alert(result.error);
                            }
                        })
                        .catch((error) => console.log(error));
                }
                setInterval(() => {
                    getContacts();
                }, 500);
            <?php } ?>
                /**
                 * async method to change the receive status of message
                 */
            async function changeReceiverState(message_id) {
                const form = new FormData();
                form.append("user_id", <?= $user->user_id ?>);
                form.append("message_id", message_id);
                await fetch("<?= $host ?>ajax/changeReceiverState", {
                        body: form,
                        method: "POST"
                    })
                    .then((res) => res.json())
                    .then((result) => {
                        console.log(result)
                    })
                    .catch((error) => console.log(error));
            }
            // execution
            displayContact(contactsList);
        </script>
    </div>
</div>