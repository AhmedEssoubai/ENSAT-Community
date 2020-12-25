
function favorite(discussion_id) {
    if (document.getElementById("fa_" + discussion_id + "_fav").classList.contains("active")) {
        document.getElementById("likes_" + discussion_id).innerHTML =
            Number(document.getElementById("likes_" + discussion_id).innerHTML) - 1;
    } else {
        document.getElementById("likes_" + discussion_id).innerHTML =
            Number(document.getElementById("likes_" + discussion_id).innerHTML) + 1;
    }
    document.getElementById("fa_" + discussion_id + "_fav").classList.toggle("active");
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", "/index.php/discussions/" + discussion_id + "/favorite", true);
    xhttp.send();
}

function bookmark(discussion_id) {
    if (document.getElementById("fa_" + discussion_id + "_book").classList.contains("active")) {
        document.getElementById("fa_" + discussion_id + "_book").classList.remove("fa");
        document.getElementById("fa_" + discussion_id + "_book").classList.add("far");
    } else {
        document.getElementById("fa_" + discussion_id + "_book").classList.remove("far");
        document.getElementById("fa_" + discussion_id + "_book").classList.add("fa");
    }
    document.getElementById("fa_" + discussion_id + "_book").classList.toggle("active");
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", "/index.php/discussions/" + discussion_id + "/bookmark", true);
    xhttp.send();
}

function deletePost(type) {
    var post_id = document.getElementById("d-post-id").value;
    var post = document.getElementById('p_' + post_id);
    post.innerHTML = 
        'The ' + type + ' has been deleted' +
        '<button type="button" class="close icon-r" onclick="deleteElement(\'p_' + post_id + '\')">' +
            '<span aria-hidden="true">&times;</span>' +
        '</button>';
    post.classList.add("rkm-alert-danger", "justify-content-between");
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", '/index.php/' + type + 's/d/' + post_id, true);
    xhttp.send();
}

jQuery(function(){
    $('#delete_post').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);
        modal.find('#d-post-id').val(id);
    });
    $('#edit_comment').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id'); // Extract info from data-* attributes
        var modal = $(this);
        //modal.find('#ctext').text($('#cmt_' + id + '_content').text());
        console.log(id);
        modal.find('#comment-id').val(id);
        modal.find('#comment-text').val($('#cmt_' + id + '_content').text());
    });
    $('#delete_comment').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);
        modal.find('#d-comment-id').val(id);
    });
});

// Show new post form and hide the new post button
function openPostForm(){
    document.getElementById("newPost").classList.add("d-none");
    document.getElementById("postForm").classList.remove("d-none");
}

// Hide new post form and show the new post button
function closePostForm(){
    document.getElementById("postForm").classList.add("d-none");
    document.getElementById("newPost").classList.remove("d-none");
}

// Full post page
function addComment(discussion_id, img, fullname) {
    var cmt_content = document.getElementById("cmt_content");
    if (!cmt_content.value)
        return;
    var btn_replay = document.getElementById("btn_replay");
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("count").innerHTML = Number(document.getElementById("count").innerHTML) + 1;
            var comt = JSON.parse(this.responseText);
            
            var comments = document.getElementById("comments");
            comments.innerHTML = 
                            '<div id="c_' + comt.id + '" class="post">' + 
                            '<div class="mr-3 avatar-40">' + 
                            '<img src="' + img + '" alt="profile image" class="img-fluid rounded-circle" />' + 
                            '</div>' + 
                            '<div class="flex-grow-1">' + 
                            '<div class="d-flex align-items-center">' + 
                            '<strong class="text-black my-0 mr-2">' + fullname + '</strong>' + 
                            '<strong class="text-mgray mr-2"> • </strong>' + 
                            '<small class="text-mgray">' + diffForHumans(new Date(comt.created_at)) + '</small>' + 
                            '</div>' + 
                            '<div class="mt-3">' + 
                            '<p class="text-mgray mb-3" id="cmt_' + comt.id + '_content">' + comt.content + '</p>' + 
                            '<div class="d-flex">' + 
                            '<div class="d-flex ml-2 align-items-center dropdown">' + 
                            '<small class="text-mgray icon-hidden" id="cmt_' + comt.id + '_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></small>' + 
                            '<div class="dropdown-menu rkm-dropdown-menu" aria-labelledby="cmt_' + comt.id + '_options">' + 
                            '<button class="dropdown-item rkm-dropdown-item" type="button" data-toggle="modal" data-target="#edit_comment" data-id="' + comt.id + '">Edit</button>' + 
                            '<button type="button" class="dropdown-item rkm-dropdown-item" data-toggle="modal" data-target="#delete_comment" data-id="' + comt.id + '">Delete</a>' + 
                            '</div></div></div></div></div></div>' + comments.innerHTML;
            btn_replay.innerHTML = "Replay";
            btn_replay.disabled = false;
            /*document.getElementById("comments").innerHTML += '<li id="c_' + comt.id + '"><div class="media mb-3">' +
                '<img src="' + img + '" class="mr-3 com-avatare rounded-circle" alt="avatar"/>' +
                '<div class="media-body d-flex justify-content-between"><div>' +
                    '<h6 class="text-muted mt-0 mb-3">' + fullname + '</h6>' +
                    '<p>' + comt.content + '</p>' +
                    '<small class="text-muted">' + dateString + '</small></div>' +
                    '<div class="d-flex mb-4 pr-5 align-items-center dropdown">' + 
                        '<span class="icon-mute-2" id="post_' + comt.id + '_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></span>' + 
                        '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="cmt_' + comt.id + '_options">' + 
                            '<button class="dropdown-item" type="button" data-toggle="modal" data-target="#edit_comment" data-id="' + comt.id + '">Éditer</button>' + 
                            '<button type="button" class="dropdown-item" data-toggle="modal" data-target="#delete_comment" data-id="' + comt.id + '">Supprimer</a>' + 
                        '</div>' + 
                    '</div>' + 
                '</div>' +
                '</div></li>';*/
        }
    };
    btn_replay.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Working...';
    btn_replay.disabled = true;
    xhttp.open("GET", "/index.php/comments?discussion=" + discussion_id + "&content=" + cmt_content.value, true);
    xhttp.send();
    document.getElementById("cmt_content").value = '';
}

function deleteComment() {
    document.getElementById("count").innerHTML = Number(document.getElementById("count").innerHTML) - 1;
    var id = document.getElementById("d-comment-id").value;
    var cmt = document.getElementById('c_' + id);
    cmt.innerHTML = 
        'A comment has been deleted' +
        '<button type="button" class="close icon-r" onclick="deleteElement(\'c_' + id + '\')">' +
            '<span aria-hidden="true">&times;</span>' +
        '</button>';
    cmt.classList.add("rkm-alert-danger", "justify-content-between");
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", "/index.php/comments/d/" + id, true);
    xhttp.send();
}
//This is my first edited comment
function editComment() {
    var id = document.getElementById("comment-id").value;
    document.getElementById("cmt_" + id + "_content").innerHTML =  document.getElementById("comment-text").value;
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", "/index.php/comments/" + id + "/update?content=" + document.getElementById("comment-text").value, true);
    xhttp.send();
}