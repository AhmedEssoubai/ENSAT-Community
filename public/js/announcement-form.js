var target_list;
var all_option;
var btn_publish;

$(document).ready(function(){
    target_list = document.getElementById("targets-list");
    all_option = document.getElementById("opt-0");
    btn_publish = document.getElementById("btn-submit");
    $('#classes-btn').on("click", function(){
        $("#classes-list").slideToggle();
    });
    $('#opt-0').on("change", all_target_changed);
    $('#targets-list INPUT').on("change", target_selected);
});

function target_selected()
{
    var opts = target_list.getElementsByTagName("INPUT");
    var all = true;
    var one = false;
    for (opt of opts) {
        if (!opt.checked)
        {
            all = false;
            if (one)
                break;
        }
        else
        {
            one = true;
            if (!all)
                break;
        }
    };
    all_option.checked = all;
    btn_publish.disabled = !one;
}

function all_target_changed()
{
    var opts = target_list.getElementsByTagName("INPUT");
    for (opt of opts)
        opt.checked = all_option.checked;
    btn_publish.disabled = opts.length == 0 || !all_option.checked;
}