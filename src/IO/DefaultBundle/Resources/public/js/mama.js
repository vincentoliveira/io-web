/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    // hover article
    $('.article').mouseover(function() {
        $(this).find('.article-btn').show();
    });
    $('.article').mouseout(function() {
        $(this).find('.article-btn').hide();
    });

    $('.article').find('.article-btn').click(function(e) {
        $(this).siblings('.article-content').toggle();
        e.preventDefault();
    });
    $('.article').find('.article-content').toggle();

    $('.article').find(".article-vignette").mouseenter(function() {
        console.log("ENTER");
        $(this).addClass("hover");
    });
    $('.article').find(".article-vignette").mouseleave(function() {
        $(this).removeClass("hover");
    });

    // carousel
    $('#welcomeCarousel').carousel({
        interval: 4000
    });

    $('#welcomeCarousel').bind('slid.bs.carousel', function(event) {
        var relatedTarget = event.relatedTarget;
        var body_class = $(relatedTarget).attr('body-class');
        console.log(body_class);
        $('body').removeClass().addClass(body_class);
    });
});


