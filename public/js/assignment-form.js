var assigned_label;
var selected_target;
var target_list;
var all_option;

$(document).ready(function(){
    assigned_label = document.getElementById("assigned-btn");
    target_list = document.getElementById("target-list");
    all_option = document.getElementById("opt-0");
    change_selected_target(document.getElementById("assigned_type").selectedIndex);
    $('#assigned-btn').on("click", function(){
        $("#assigned-list").slideToggle();
    });
    $('#assigned_type').on("change", function(){
        change_selected_target($(this).prop('selectedIndex'));
    });
    $('#opt-0').on("change", all_target_changed);
});

function change_selected_target(value)
{
    selected_target = value;
    target_list.innerHTML = "";
    if (selected_target == 0)
    {
        assigned_label.innerHTML = "All students";
        for(i = 0; i < students_ids.length; i++)
            target_list.innerHTML += '<div><input type="checkbox" class="custom-control-input" id="opt-' + (i + 1) + '" name="targets[]" value="' + students_ids[i] + '" onchange="target_selected()" disabled checked>' + 
                                '<label class="custom-control-label w-100" for="opt-' + (i + 1) + '">' + students_names[i] + '</label></div>';
    }
    else
    {
        assigned_label.innerHTML = "All groups";
        for(i = 0; i < groups_ids.length; i++)
            target_list.innerHTML += '<div><input type="checkbox" class="custom-control-input" id="opt-' + (i + 1) + '" name="targets[]" value="' + groups_ids[i] + '" onchange="target_selected()" disabled checked>' + 
                                '<label class="custom-control-label w-100" for="opt-' + (i + 1) + '">' + groups_names[i] + '</label></div>';
    }
    all_option.checked = true;
}

function target_selected()
{
    var opts = target_list.getElementsByTagName("INPUT");
    var all = true;
    for (opt of opts) {
        if (!opt.checked)
        {
            all = false;
            break;
        }
    };
    if (all)
    {
        all_option.checked = true;
        all_target_changed();
    }
}

function all_target_changed(){
    var opts = target_list.getElementsByTagName("INPUT");
    for (opt of opts) {
        opt.disabled = all_option.checked;
        opt.checked = all_option.checked;
    };
}