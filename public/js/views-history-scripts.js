let resource = 0;
let history_loaded = false;

jQuery(function(){
    $('#view-history').on('click',function(){
        $(this).next().slideToggle("slow");
        if (!history_loaded)
        {
            history_loaded = true;
            //$(this).children(/*".history-list.first"*/"#history-list").slideToggle();
            axios.get('/resources/' + resource + '/views')
                .then(response => {
                    const views = response.data;
                    let items = '';
                    for (x in views.ids)
                    {
                        let date = views.dates[x];
                        items += '<div id="view_' + views.ids[x] + '" class="post align-items-center" ' + (date ? '' : 'style="opacity: 0.4"') + '>' + 
                            '<div class="mr-3">' + 
                                '<img src="' + views.imgs[x] + '" alt="profile image" class="avatar rounded-circle"/>' + 
                            '</div>' + 
                            '<div class="flex-grow-1 mr-2">' + views.names[x] + '</div>' + 
                            (date ? '<div>seen at ' + date + ' <span class="text-bold">(' + diffForHumans(date) + ')</span></div>' : '') + 
                        '</div>';
                    }
                    $(this).next('.history-list').html(items);
                })
                .catch(error => history_loaded = false);
        }
        /*var fileName = $(this).val().slice($(this).val().lastIndexOf("\\") + 1);
        $(this).next('.custom-file-label').html(fileName);*/
    })
})