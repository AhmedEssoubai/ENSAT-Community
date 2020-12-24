function action(url, id) {
    var user = document.getElementById('pending' + id);
    user.parentNode.removeChild(user);
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", url, true);
    xhttp.send();
    count--;
    document.getElementById("count").innerHTML = count;
    if (count == 0)
        document.getElementById("list").innerHTML += '<div class="col text-muted text-center py-2 px-2"> <h2 class="my-3" style="font-size: 3em"><i class="fab fa-cloudversify"></i></h2> <h4 class="my-3">No user in pending list</h4> </div>';
}