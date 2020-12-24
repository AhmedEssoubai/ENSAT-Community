attachments_changed = function(count)
{
    document.getElementById("submit_work").disabled = count <= 0;
};