jQuery(function(){
    $('.delete-alert').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);
        modal.find('#d-item-id').val(id);
    });
});

function send_action(url) {
    window.location.href = url + document.getElementById('d-item-id').value;
}