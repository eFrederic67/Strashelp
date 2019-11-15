function verifDate(yd,md,bd) {
    let today = new Date();
    let yt = today.getFullYear();
    let mt = today.getMonth()+1;
    let dt = today.getDate();

    if (yt-yd >18) {
        return true;
    } else if (yt - yd <18) {
        return false
    } else {
        if (mt-md >0) {
            return true;
        } else if (mt - md <0) {
            return false
        } else {
            if (dt-bd > 0) {
                return true;
            } else if (dt - bd < 0) {
                return false
            } else {
                return true
            }
        }
    }
}


function toggleInscription(classe) {
    let bd = document.getElementById('dayOfBirth').value;
    let md = document.getElementById('monthOfBirth').value;
    let yd = document.getElementById('yearOfBirth').value;
    let cp = document.getElementById('zipcode2').value;

    let retourMajeur = verifDate(yd, md, bd)

    if (bd && md && yd && cp) {
        if (retourMajeur === false) {
            alert('Vous devez être majeur(e) pour pouvoir vous inscrire sur le site.');
            document.location.href = "https://fr.wikipedia.org/wiki/Majorit%C3%A9_civile";
        } else if (cp == 67000 || cp == 67100 || cp == 67200) {

            let cp2 = document.getElementById('zipcode');
            cp2.setAttribute("value", cp);

            let el = document.getElementsByClassName(classe);
            for (let i = 0; i < el.length; i++) {
                if (el[i].style.display === 'block') {
                    el[i].style.display = 'none'
                } else if (el[i].style.display === 'none') {
                    el[i].style.display = 'block'
                }
            }
        } else {
            alert('Cette association est réservée aux Strasbourgeois(es).');
        }
    }else {
        alert("Veuillez remplir tous les champs")
    }

}

function countId(id) {
    var counter = 0;
    $("img").each(function() {
        if(this.id==id) {
            counter+=1;
        }
    });
}


function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#profilPic')
                .attr('src', e.target.result)
                // .width(150)
                .height(150);
            $('#profilPicHover')
                .attr('src', e.target.result)
                // .width(150)
                .height(150);
        };

        reader.readAsDataURL(input.files[0]);
    }
}