// for fixed the component when i am scrolling

window.onscroll = () => {
    document.querySelector('div.user_infos').classList.toggle('fixed_info_zone', window.scrollY > 490);
}

// change profile image
let profile_change = document.querySelector('#change_profile_image');
profile_change.onchange = ()=>{
    let img = document.querySelector('label[for=change_profile_image] img');
    img.src = window.URL.createObjectURL(profile_change.files[0]);
    document.querySelector('form.profile_card button').style.visibility = "visible";
}
// change cover background
let cover_change = document.querySelector('#cover_image');
cover_change.onchange = ()=>{
    let cover = document.querySelector(".imageContainer-cover");
    cover.style.backgroundImage = `url(${window.URL.createObjectURL(cover_change.files[0])})`;
    let valid_cover_img = document.querySelector('.valid_change_cover');
    valid_cover_img.style.visibility = "visible";
}


// for responsive design
let infos = document.querySelector('li em.forum-information');
infos.onclick = ()=>{
    let resp_profile_body = document.querySelector('.resp_profile_body > div:first-child');
    resp_profile_body.classList.toggle('moveLeft1');
}