/* /////////////////////////////////////////////////////////////////////// */
/*                       adding message script                             */
/* /////////////////////////////////////////////////////////////////////// */
// images and videos adding to the messages
let imagesToDisplay = []; // Pour les images à afficher
let imagesToSend = []; // Pour les images à envoyer dans le FormData

var imagesType = ["image/jpeg", "image/jpg", "image/png"];
var videosType = ["video/mp4", "video/avi"];
//   valid file type
function validFileType(file) {
  for (var i = 0; i < imagesType.length; i++) {
    if (file.type === imagesType[i]) {
      return "image";
    }
  }
  for (var j = 0; j < videosType.length; j++) {
    if (file.type == videosType[j]) {
      return "video";
    }
  }
}

/**
 * "onchange" event on file's selector
 */
document.querySelector("#message_file").addEventListener("change", (e) => {
  // if our array is not empty
  if (e.target.files.length) {
    // we make a loop on array of files
    for (let i = 0; i < e.target.files.length; i++) {
      // we add the image in the array to send
      imagesToSend.push(e.target.files[i]);

      // we get the new url road of the file and we add it to the array to show
      imagesToDisplay.push({
        src: window.URL.createObjectURL(e.target.files[i]),
        type: validFileType(e.target.files[i]),
      });

      // we show the images
      renderImages();
    }
  }
});

/**
 * @param {object} media image to show
 * @param {number} index index in array of media
 *
 * @return {string} the elements to display
 */
function template(media, index) {
  if (media.type === "image") {
    return `<div class="column h100">
            <em class="forum-close" onclick="imagesToDisplay.splice(${index}, 1); imagesToSend.splice(${index}, 1); renderImages();"></em>
            <img src=${media.src} alt="choose image"/>
        </div>`;
  }
  if (media.type === "video") {
    return `<div class="column h100">
            <em class="forum-close" onclick="alert(imagesToDisplay.splice(${index}, 1));imagesToDisplay.splice(${index}, 1); imagesToSend.splice(${index}, 1); renderImages();"></em>
            <video src=${media.src} controls></video>
        </div>`;
  }
}

/**
 * Function which show all media by "imageToDisplay"
 */
function renderImages() {
  let collection = document.querySelector(".messBody + form > div.messageAttach");

  // we start by empty the element
  html(collection, "", "");
  let i = 0; // initialization of index

  // for every element of the array
  imagesToDisplay.forEach((media) => {
    html(collection, template(media, i++), "add");
  });
}

/**
 * this function is used to add a content to an html element
 * @param {HTMLElement} node a html element
 * @param {string} value value to add
 * @param {"add"|""} mode used to know if there is an adding of replacing
 */
function html(node, value, mode) {
  if (value !== undefined) {
    if (mode == "add") {
      node.innerHTML += value;
    } else {
      node.innerHTML = value;
    }
  } else {
    return node.innerHTML;
  }
}

// ///////////////////////////
// for responsive design
let openProfileList = document.querySelector(".openProfileList");
openProfileList.onclick = () => {
  let messengerRight = document.querySelector(".messengerRight");
  messengerRight.classList.toggle("move");
};
let openContactList = document.querySelector(".openContactList");
openContactList.onclick = () => {
  let messengerLeft = document.querySelector(".messengerLeft");
  messengerLeft.classList.toggle("moveLeft");
};


