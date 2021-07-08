/* /////////////////////////////////////////////////////////////////////// */
/*                       adding publication script                         */
/* /////////////////////////////////////////////////////////////////////// */
// getting files to computer
var file_adding_publication = document.querySelector("#file_adding_publication");
// alert("raoul");
file_adding_publication.onchange = () => {
  var curFiles = file_adding_publication.files;

  for (var i = 0; i < curFiles.length; i++) {
    var media_container = document.getElementById("subject_image_view");
    if (validFileType(curFiles[i]) == "image") {
      // alert(window.URL.createObjectURL(curFiles[i]));
      media_container.innerHTML = `<div class="media_zone w100">
                <img src=${window.URL.createObjectURL(
                  curFiles[i]
                )} alt="publication image">
            </div>
            <div class="separate_line"></div>`;
    } else if (validFileType(curFiles[i]) == "video") {
      media_container.innerHTML = `<div class="media_zone w100">
                <video src=${window.URL.createObjectURL(
                  curFiles[i]
                )} controls></video>
            </div>
            <div class="separate_line"></div>`;
    } else {
      // alert("Unknown file");
      media_container.innerHTML = `<div class="media_zone w100">
            <span class="ctrContent w100 errorMessage">Unknown File Type</span>
        </div>
        <div class="separate_line"></div>`;
    }
  }
};
// file size
function returnFileSize(number) {
  if (number < 1024) {
    return number + " octets";
  } else if (number >= 1024 && number < 1048576) {
    return (number / 1024).toFixed(1) + " Ko";
  } else if (number >= 1048576) {
    return (number / 1048576).toFixed(1) + " Mo";
  }
}

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
      // alert('bon !');
      return "video";
    }
  }
}
// for the Reset button
var reset_button = document.querySelector(".add_btn button[type=reset]");
reset_button.onclick = reset;
function reset() {
  document.getElementById("subject_image_view").innerHTML = "";
  file_adding_publication.value = "";
  document.getElementById("PublicationText").value = "";
  document.querySelector(".error_publication").innerHTML = "";
}
// manage change forme of textarea
let textarea = document.querySelector("textarea#PublicationText");
textarea.onfocus = () => {
  textarea.style.height = `150px`;
  textarea.style.borderRadius = `30px`;
};
textarea.onblur = () => {
  if (textarea.value.trim() == "" || textarea.value == "") {
    textarea.style.height = `45px`;
    textarea.style.borderRadius = `60px`;
    textarea.value = "";
  }
};
