attachments_changed = function(count)
{
    document.getElementById("submit_work").disabled = count <= 0;
};
let submissions_loaded = false;

jQuery(function(){
    $('#submissions').on('click',function(){
        $(this).next().slideToggle("slow");
        if (!submissions_loaded)
        {
            submissions_loaded = true;
            axios.get('/assignments/' + item + '/submissions')
                .then(response => {
                    const assigneds = response.data;
                    let items = '', no_image = assigneds.imgs.length == 0;
                    console.log(assigneds);
                    for (x in assigneds.ids)
                    {
                        let date = assigneds.dates[x];
                        const has_submit = date != null;
                        let submission_files = '';
                        if (has_submit)
                        {
                            let files = assigneds.files[assigneds.ids[x]];
                            for (id in files)
                                submission_files += '<div class="post post-dark align-items-center line-clamp" title="' + files[id] + '"><a href="/files/s/' + id + '" target="_blank">' + files[id] + '</a></div>';
                        }
                        items += '<div id="submission_' + assigneds.ids[x] + '" class="post d-block">' + 
                            '<div class="submission-files d-flex align-items-center ' + (has_submit ? 'cursor-pointer' : '') + '" ' + (has_submit ? '' : 'style="opacity: 0.4"') + '><div class="mr-3">' + 
                                '<img src="' + (no_image ? '/img/default.png' : assigneds.imgs[x]) + '" alt="image" class="avatar rounded-circle"/>' + 
                            '</div>' + 
                            '<div class="flex-grow-1 mr-2">' + assigneds.names[x] + '</div>' + 
                            (has_submit ? '<div>submitted at ' + date + ' <span class="text-bold">(' + diffForHumans(new Date(date)) + ')</span></div>' : '') + 
                        '</div>' + (has_submit ?
                        '<div class="text-dgray" style="display: none">' + 
                            '<div class="border-top pl-md-5 mt-3">' + submission_files + '</div>' + 
                        '</div>' : '') + '</div>';
                    }
                    $(this).next().children('.loading:first').slideUp();
                    let sl = $(this).next().children('.submissions-list:first');
                    sl.html(items);
                    sl.slideDown('slow');
                    $/*(this).children*/('.submission-files.cursor-pointer').on('click',function(){
                        $(this).next().slideToggle("slow");
                    });
                    //$(this).children(/*".history-list.first"*/"#history-list").slideToggle();
                })
                .catch(error => /*submissions_loaded = false*/console.log(error));
        }
        /*var fileName = $(this).val().slice($(this).val().lastIndexOf("\\") + 1);
        $(this).next('.custom-file-label').html(fileName);*/
    });
});