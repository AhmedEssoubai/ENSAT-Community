let ids = new Map();
let values = new Map();
let imgs = new Map();
let selected = new Map();

function selectLoad(select_id, url, loading_id){
    if (!ids.has(select_id))
    {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                _ids = JSON.parse(this.responseText).ids;
                _values = JSON.parse(this.responseText).values;
                _imgs = JSON.parse(this.responseText).imgs;
                ids.set(select_id, _ids);
                values.set(select_id, _values);
                imgs.set(select_id, _imgs);
                selected.set(select_id, new Array());
                //loading = document.getElementById(loading_id);
                //loading.parentNode.removeChild(loading);
                let options_ui = document.getElementById(select_id).getElementsByClassName("options-list")[0];
                options_ui.innerHTML = "";
                for (i = 0; i < _ids.length; i++) {
                    options_ui.innerHTML += 
                        '<button id="option_' + i + '" class="free-list item-full" type="button" onclick="addOption(\'' + select_id + '\', ' + i + ')">' +
                            '<div class="d-flex p-2 align-items-center">' +
                                '<div class="pr-3">' +
                                    '<img src="' + _imgs[i] + '" class="img-fluid rounded-circle" width="26px"/>' +
                                '</div>' +
                                '<div class="flex-grow-1 d-flex flex-column text-left">' +
                                    '<span>' + _values[i] + '</span>' +
                                '</div>' +
                            '</div>' +
                        '</button>';
                }
            }
        };
        xhttp.open("GET", url, true);
        xhttp.send();
    }
}

function selectStartSearch(select_id) {
    //$("#" + select_id + " .options-list:first-child").fadeIn();
    document.getElementById(select_id).getElementsByClassName("options-list")[0].style.display = "block";
}

function selectSearch(event, select_id) {
    let filter = event.target.value.trim().toLowerCase();
    let options = document.getElementById(select_id).getElementsByClassName("options-list")[0].getElementsByTagName("button");
    let _values = values.get(select_id);
    for (i = 0; i < options.length; i++) {
        value = _values[i];
        alert(selected.get(select_id).indexOf(ids.get(select_id)[i]));
        if (selected.get(select_id).indexOf(ids.get(select_id)[i]) == -1 && value.toLowerCase().search(filter) > -1) {
            options[i].style.display = "";
        } else {
            options[i].style.display = "none";
        }
    }
}

function selectEndSearch(select_id) {
    $("#" + select_id + " .options-list:first-child").fadeOut();
}

function menuSearch(event, options_id) {
    var val = event.target.value.trim().toLowerCase();
    var list_ui = $('#results_list').empty();
    var empty = true;
    members = JSON.parse(this.responseText).names;
    ids = JSON.parse(this.responseText).ids;
    imgs = JSON.parse(this.responseText).imgs;
    for (x in members)
    {
        list_ui.append(
            '<button id="professor_" class="free-list item-full row" onclick="addItem(' + x + ')">' +
                '<div class="d-flex p-3 align-items-center">' +
                    '<div class="pr-3">' +
                        '<img src="' + imgs[x] + '" class="img-fluid rounded-circle" width="48px"/>' +
                    '</div>' +
                    '<div class="flex-grow-1 d-flex flex-column">' +
                        '<h6>' + members[x] + '</h6>' +
                    '</div>' +
                '</div>' +
            '</button>'
        );
        empty = false;
    }
    if (empty)
        document.getElementById("empty_list").classList.remove("d-none");
} 

function removeOption(id){
    document.getElementById("selected_list").removeChild(document.getElementById("selected_prof_" + id));
    selected = arrayRemove(selected, id);
}

function addOption(select_id, index){
    var btn = document.createElement("BUTTON");
    btn.id = "selected_option_" + ids[i];
    btn.classList.add('free-list');
    btn.classList.add('border');
    btn.classList.add('rounded-pill');
    btn.classList.add('m-2');
    btn.classList.add('mr-3');
    btn.type = "button";
    var select = document.getElementById(select_id);
    var selected_ui = select.getElementsByClassName("selected-options")[0];
    var input = selected_ui.getElementsByClassName("search-input")[0];
    btn.addEventListener("click", function() { removeItem(select_id, index); });
    btn.innerHTML = '<div class="d-flex p-1 align-items-center">' + 
                        '<div class="pr-3">' + 
                            '<img src="' + imgs.get(select_id)[index] + '" class="img-fluid rounded-circle" width="20px"/>' + 
                        '</div>' + 
                        '<span class="small text-muted pr-2">' + values.get(select_id)[index] + '</span>' + 
                    '</div>';
    selected_ui.insertBefore(btn, input);
    selected.get(select_id).push(ids.get(select_id)[i]);
    select.getElementsByClassName("options-list")[0].getElementsByTagName("button")[index].style.display = "none";
    input.value = "";
}