/*
* css rule before not supported by ie7 and ie8 so we dynamically add
* the ul list type style with js in the main menu
*/
$(document).ready(function() {
    var arrowImg = '<img alt="Image puce" src="/themes/default/Images/puceFleche.png">';
    $('#nav  ul:not(:first-child) > li > a span').prepend(arrowImg).css('visibility','hidden');
    $('#nav  ul:not(:first-child) > li > a').hover(function() {
        $(this).find("span").css('visibility','visible');
    }, function() {
        $(this).find("span").css('visibility','hidden');
    });
})