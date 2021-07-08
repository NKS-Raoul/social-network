// changing new cover image
let new_background = document.getElementById("new_background");
new_background.onchange = () => {
  let bac = document.querySelector(".new_cover");
  bac.style.backgroundImage = `url(${window.URL.createObjectURL(
    new_background.files[0]
  )})`;
};

// change new profile image
let new_profile_img = document.getElementById("new_profile_img");
new_profile_img.onchange = () => {
  let bac = document.querySelector("#new_image");
  bac.src = window.URL.createObjectURL(new_profile_img.files[0]);
};

// list of courses
// var list_courses = [
//   "Mathematic",
//   "Programing",
//   "Physic",
//   "Chemistry",
//   "Electronic",
// ];
// var list_course_display = [];
// // var list_course_send = [];
// let list_course = document.querySelector(".list_courses");
// var indexA = 0;
// list_courses.forEach((element) => {
//   list_course.innerHTML += `<div class="flex_wrap list_tags" 
//   >
//     <div class="allTags alignCenter">
//         <input type="checkbox" name="courses[]" value="${element}" id="course${indexA}" style="display: none;" />
//         <label for="course${indexA}" class="simple_flex w100 h100" onclick="choose_courses('${element}',${indexA})">
//         <div class="tag alignCenter h100 w87_5">${element}</div>
//         <div class="ctrContent h100 w25 validateIcon">
//             <em class="forum-close"></em>
//         </div>
//         </label>
//     </div>
// </div>`;
//   indexA++;
// });
// function choose_courses(params = "", i = 0) {
//   var index = list_course_display.indexOf(params);
//   if (index == -1) {
//     list_course_display.push(params);
//     // list_course_send.push(params);
//     document.querySelectorAll("#add_profile .list_tags")[
//       i
//     ].style.border = `2px solid #0e73f8`;
//     document.querySelectorAll(".validateIcon")[
//       i
//     ].innerHTML = `<em class="forum-checkmark-round"></em>`;
//   } else {
//     list_course_display.splice(index, 1);
//     // list_course_send.splice(index, 1);
//     document.querySelectorAll("#add_profile .list_tags")[
//       i
//     ].style.border = `1px solid #0e131a`;
//     document.querySelectorAll(".validateIcon")[
//       i
//     ].innerHTML = `<em class="forum-close"></em>`;
//   }
// }
