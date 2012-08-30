/*
* Insert sheet and pdf img in resources list page on ie7 not supportong :before and :after
*/
$(document).ready(function(){	
    var summarySheet = '<img alt="Image Sommaire" src="/themes/default/Images/sheet.png"';
    $('span.summary').append(summarySheet);
    var pictoPdf = '<img alt="Image Sommaire" src="/themes/default/Images/pdfLogo.png"';
    $('span.pictoPdf').append(pictoPdf);
});