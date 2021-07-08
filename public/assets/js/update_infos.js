let lists_countries = [
    "Cameroon",
    "Tchad",
    "Nigeria",
    "Niger",
    "Benin",
    "Congo",
    "Gabon",
    "Equatorial G",
    "RD Congo",
    "Soudan",
    "Angola",
    "Ethiopia",
    "Morocco",
    "Ghana",
    "Madagascar",
    "South Africa",
    "Mozambique",
    "Dubai",
    "Mexico",
    "Canada",
    "United States",
    "Brasilia",
    "British"
];

var countries = document.querySelector('.countries');
lists_countries.forEach(element => {
    countries.innerHTML += `<option value=${element}>${element}</option>`
});