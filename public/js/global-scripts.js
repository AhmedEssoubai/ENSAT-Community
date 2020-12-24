jQuery(function(){
    $('.custom-file-input').on('change',function(){
        var fileName = $(this).val().slice($(this).val().lastIndexOf("\\") + 1);
        $(this).next('.custom-file-label').html(fileName);
    })
})

function arrayRemove(arr, value) { 
    return arr.filter(function(ele){ 
        return ele != value; 
    });
}

function liveURL(text) {
    var urlRegex = /(https?:\/\/[^\s]+)/g;
    return text.replace(urlRegex, function(url) {
        return '<a class="_link" href="' + url + '" target="_blank">' + url + '</a>';
    });
}

function liveURLAndYoutubeVideos(text) {
    var urlRegex = /(https?:\/\/[^\s]+)/g;
    var youtube = new RegExp(/(https:\/\/www.youtube.com\/watch\?v=(\w[\w|-]*))/g);
    return text.replace(urlRegex, function(url) {
        if (youtube.test(url))
            return '<div class="embed-responsive embed-responsive-16by9">' + 
                        '<iframe class="embed-responsive-item" src="' + url.replace('watch?v=', 'embed/') + '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>' + 
                    '</div>';
        return '<a class="_link" href="' + url + '" target="_blank">' + url + '</a>';
    });
}

function youtubeVideos(text) {
    //https://www.youtube.com/embed/BxdIaUvJr1Y
    //https://www.youtube.com/watch?v=BxdIaUvJr1Y
    //<iframe width="560" height="315" src="https://www.youtube.com/embed/BxdIaUvJr1Y" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    //var urlRegex = /((\?v=)(\w[\w|-]*))/g;
    var urlRegex = /(https:\/\/www.youtube.com\/watch\?v=(\w[\w|-]*))/g;
    return text.replace(urlRegex, function(url) {
        return '<div class="embed-responsive embed-responsive-16by9">' + 
                    '<iframe class="embed-responsive-item" src="' + url.replace('watch?v=', 'embed/') + '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>' + 
                '</div>';
    });
}

function youtubeVideosActive(text) {
    //var urlRegex = /(<a class="_link" href="https:\/\/www.youtube.com\/watch\?v=(\w[\w|-]*)<\/a>)/g;
    //<a href="https://www.youtube.com/watch?v=p3zsRbVo1T8" class="_link">https://www.youtube.com/watch?v=p3zsRbVo1T8</a>
    var urlRegex = /(<a href="https:\/\/www.youtube.com\/watch\?v=(\w[\w|-]*)" class="_link">https:\/\/www.youtube.com\/watch\?v=(\w[\w|-]*)<\/a>)/g;
    
    var urlRegex = /(<a href="https:\/\/www.youtube.com\/watch\?v=p3zsRbVo1T8" class="_link">https:\/\/www.youtube.com\/watch\?v=p3zsRbVo1T8<\/a>)/g;
    console.log(urlRegex);
    return text.replace(urlRegex, function(url) {
        console.log(url);
        url = url.replace('<a class="_link" href="', '').replace('<\/a>', '').replace('watch?v=', 'embed/')
        return '<div class="embed-responsive embed-responsive-16by9">' + 
                    '<iframe class="embed-responsive-item" src="' + url + '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>' + 
                '</div>';
    });
}

function bringLifeToLinks(id) {
    var element = document.getElementById(id);
    if (element)
        element.innerHTML = liveURL(element.innerHTML);
}

function bringFullLifeToLinks(id) {
    var element = document.getElementById(id);
    if (element)
        element.innerHTML = liveURLAndYoutubeVideos(element.innerHTML);
}

function submitForm(id) {
    document.getElementById(id).submit();
}

function submitFormOnEnter(event, id) {
    if (event.keyCode === 13) {
        event.preventDefault();
        document.getElementById(id).submit();
    }
}

function rboOnMouseOver(event, color)
{
    var target = event.target;
    target.style.setProperty('background-color', color);
    target.style.setProperty('color', 'white');
}

function rboOnMouseOut(event, color)
{
    var target = event.target;
    target.style.setProperty('background-color', 'transparent');
    target.style.setProperty('color', color);
}

function linkOnMouseOver(event, color)
{
    var target = event.target;
    target.style.setProperty('color', color);
    console.log('in');
}

function linkOnMouseOut(event, color)
{
    var target = event.target;
    target.style.setProperty('color', color);
    console.log('out');
}

function deleteElement(id)
{
    var element = document.getElementById(id);
    element.parentNode.removeChild(element);
}

function clickLink(link, container=null)
{
    if (container)
        document.getElementById(container).querySelectorAll('#' + link)[0].click();
    else
        document.getElementById(link).click();
}

function isEmpty(value)
{
    return value == null || value.trim() == "";
}

function stopEventPropagation(e)
{
    console.log('Hi');
    event.stopPropagation();
    /*if (!e) var e = window.event;
    e.cancelBubble = true;
    if (e.stopPropagation) e.stopPropagation();*/
}