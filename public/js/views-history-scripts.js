let item = 0;
let history_loaded = false;
let base_link = 'resources'
let files = [];

jQuery(function(){
    $('#view-history').on('click',function(){
        $(this).next().slideToggle("slow");
        if (!history_loaded)
        {
            history_loaded = true;
            //$(this).children(/*".history-list.first"*/"#history-list").slideToggle();
            axios.get('/' + base_link + '/' + item + '/views')
                .then(response => {
                    const views = response.data;
                    let items = '';
                    for (x in views.ids)
                    {
                        let date = views.dates[x];
                        const files_has_history = files && files.length > 0;
                        let files_history = '';
                        if (files_has_history)
                            for (y in files)
                            {
                                let ddate = views.files_history[x][y];
                                files_history += '<div class="post post-dark d-block"><div class="d-flex align-items-center" ' + (ddate ? '' : 'style="opacity: 0.4"') + '><div class="flex-grow-1 mr-2">' + files[y] + '</div>' + (ddate ? '<div>downloaded at ' + ddate + ' <span class="text-bold">(' + diffForHumans(new Date(ddate)) + ')</span></div>' : '') + '</div></div>';
                            }
                        items += '<div id="view_' + views.ids[x] + '" class="post d-block">' + 
                            '<div class="students-files d-flex align-items-center ' + (files_has_history ? 'cursor-pointer' : '') + '" ' + (date ? '' : 'style="opacity: 0.4"') + '><div class="mr-3">' + 
                                '<img src="' + views.imgs[x] + '" alt="profile image" class="avatar rounded-circle"/>' + 
                            '</div>' + 
                            '<div class="flex-grow-1 mr-2">' + views.names[x] + '</div>' + 
                            (date ? '<div>seen at ' + date + ' <span class="text-bold">(' + diffForHumans(new Date(date)) + ')</span></div>' : '') + 
                        '</div>' + (files_has_history ?
                        '<div class="text-dgray" style="display: none">' + 
                            '<div class="border-top pl-md-5 mt-3">' + files_history + '</div>' + 
                        '</div>' : '') + '</div>';
                    }
                    $(this).next().children('.loading:first').slideUp();
                    let hl = $(this).next().children('.history-list:first');
                    hl.html(items);
                    hl.slideDown('slow');
                    $('.students-files.cursor-pointer').on('click',function(){
                        $(this).next().slideToggle("slow");
                    });
                    //$(this).children(/*".history-list.first"*/"#history-list").slideToggle();
                })
                .catch(error => history_loaded = false);
        }
        /*var fileName = $(this).val().slice($(this).val().lastIndexOf("\\") + 1);
        $(this).next('.custom-file-label').html(fileName);*/
    });
})