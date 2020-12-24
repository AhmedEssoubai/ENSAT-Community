function join(groupe_id, user_id) {
    var xhttp = new XMLHttpRequest();
    var btn = document.getElementById('btn_g' + groupe_id);
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (btn.innerHTML == 'Joindre')
            {
                btn.classList.remove("btn-primary");
                btn.classList.remove("text-white");
                btn.classList.add("btn-os");
                btn.innerHTML = 'Quitter';
            }
            else
            {
                btn.classList.remove("btn-os");
                btn.classList.add("btn-primary");
                btn.classList.add("text-white");
                btn.innerHTML = 'Joindre';
            }
        }
    };
    var url = '/groupes/' + groupe_id + '/join';
    if (btn.innerHTML != 'Joindre')
        url = '/groupes/' + groupe_id + '/' + user_id + '/leave'
    xhttp.open("GET", url, true);
    xhttp.send();
}
