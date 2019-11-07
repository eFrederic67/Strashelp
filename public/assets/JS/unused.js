document.addEventListener('DOMContentLoaded',function() {
    document.getElementById('dayOfBirth').onchange=changeEventHandler;
    document.getElementById('monthOfBirth').onchange=changeEventHandler;
    document.getElementById('yearOfBirth').onchange=changeEventHandler;
},false);

function changeEventHandler(event) {
    //   if verifDate()    dayOfBirth
    let bd = document.getElementById('dayOfBirth').value;
    let md = document.getElementById('monthOfBirth').value;
    let yd = document.getElementById('yearOfBirth').value;

    if(bd && md && yd) {
        let el = document.getElementsByClassName('ageDisclaimer');
        let retourMajeur = verifDate(yd, md, bd)
        for (let i = 0; i < el.length; i++) {
            if (retourMajeur) {
                el[i].style.display = 'none'
            } else {
                el[i].style.display = 'block'
            }
        }
    }
}