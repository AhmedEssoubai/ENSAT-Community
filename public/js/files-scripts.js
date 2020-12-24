var attachment_id = 0;
var attachment_count = 0;
var uploader_input = document.getElementById("attachment_loader");
var container;
var in_hold_files = [];
var attachments_changed = null;

function create_attachment(container_id){
    in_hold_files.forEach(x => {
        delete_attachment(x, true);
        attachment_id--;
    });
    in_hold_files = [];
    container = document.getElementById(container_id);
    var attachment = document.createElement("DIV");
    attachment.id = 'attachment_' + attachment_id;
    var attachment_input = document.createElement("INPUT");
    attachment_input.setAttribute("type", "file");
    attachment_input.hidden = true;
    var id = attachment_id;
    attachment_input.onchange = function(event){
        attachment_onchange(event, id);
    };
    attachment.appendChild(attachment_input);
    container.appendChild(attachment);
    attachment_input.click();
    in_hold_files.push(id);
    attachment_id++;
}

function attachment_onchange(event, id) {
    var target = event.target || event.srcElement;

    if (target.value.length == 0)
        delete_attachment(id);
    else{
        target.name = "attachments[]";
        var attachment = document.getElementById('attachment_' + id);
        attachment.classList.add('text-dgray');
        attachment.classList.add('attachment-box');
        var file_name = target.value.slice(target.value.lastIndexOf("\\") + 1);
        var name_container = document.createElement("DIV");
        name_container.classList.add('line-clamp');
        name_container.title = file_name;
        name_container.innerHTML = '<span class="mr-4 text-mgray"><i class="fas fa-paperclip"></i></span>' + file_name
        attachment.appendChild(name_container);
        // Delete button
        var del_btn = document.createElement("BUTTON");
        del_btn.setAttribute("type", "button");
        del_btn.classList.add('icon-hidden');
        del_btn.classList.add('icon-delete');
        del_btn.classList.add('btn-free');
        del_btn.innerHTML = '<span aria-hidden="true">&times;</span>';
        del_btn.onclick = function(){
            delete_attachment(id);
        };
        attachment.appendChild(del_btn);
    }
    in_hold_files = arrayRemove(in_hold_files, id);
    attachment_count++;
    if (attachments_changed)
        attachments_changed(attachment_count);
}

function delete_attachment(id, silence=false){
    var attachment = document.getElementById('attachment_' + id);
    attachment.parentNode.removeChild(attachment);
    if (silence)
        return;
    attachment_count--;
    if (attachments_changed)
        attachments_changed(attachment_count);
}