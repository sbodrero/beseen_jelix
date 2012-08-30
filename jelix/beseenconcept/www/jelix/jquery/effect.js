/*
* As css doesn't permit action on parents elements we need to hack it with js
*/
$(document).ready(function() {
    $('#nav ul:first-child > li > a, #nav  ul:not(:first-child) > li > a').hover(function() {
        var link = $(this).closest('li.subRoot').find('a:first').attr('id');
        $('#'+link).addClass('hover'+link+'Button');
    }, function() {
        var link = $(this).closest('li.subRoot').find('a:first').attr('id');
        $('#'+link).removeClass('hover'+link+'Button');
    });
});