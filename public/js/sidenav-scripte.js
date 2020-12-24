function openNav() {
    /*document.getElementById("side_menu").style.display = "block";
    document.getElementById("side_menu").style.width = "350px";*/
    $("#side_menu").show();
    $("#side_menu").animate({width: "350px"});
    $("#side_menu_content").fadeIn(1000);
}

function closeNav() {
    //document.getElementById("side_menu").style.width = "0px";
    $("#side_menu_content").fadeOut("fast");
    $("#side_menu").animate({width: "0px"}, 500, "swing", function(){$("#side_menu").hide()});
}