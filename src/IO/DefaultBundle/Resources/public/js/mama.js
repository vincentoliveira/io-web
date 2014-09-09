/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function(){
    
    $('#welcomeCarousel').carousel({
        interval: 4000
     });
     
    $('#welcomeCarousel').bind('slid.bs.carousel', function (event) {
	var relatedTarget = event.relatedTarget;
	var body_class = $(relatedTarget).attr('body-class');
	console.log(body_class);
	$('body').removeClass().addClass(body_class);
     });
});


