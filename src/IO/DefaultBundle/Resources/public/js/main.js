/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function enableButtons() {
    $('.btn-add-product').off('click');
    $('.btn-add-product').click(function() {
        var productId = $(this).attr("product-id");
        var productOptionsId = "#product" + productId + "_options";

        if ($(this).hasClass("options-selected") || $(productOptionsId).length === 0) {
            // add product
            var actionUrl = $(this).attr("action-url");
            var options = [];
            $(productOptionsId).find('.option').each(function() {
                options.push($(this).find('input:checked').val());
            });

            // call internal api
            $.post(actionUrl, {'product_id': productId, 'options': options}, function(data) {
                $('.modal').modal('hide');
                $("#cart").html($(data).find("#cart").html());
                enableButtons();
            });
        } else {
            // show options form
            $(productOptionsId).modal({'show': true});
        }
    });

    $('.btn-remove-product').off('click');
    $('.btn-remove-product').click(function() {
        var actionUrl = $(this).attr("action-url");
        var productId = $(this).attr("product-id");
        var extra = $(this).attr("extra");

        // call internal api
        $.post(actionUrl, {'product_id': productId, 'extra': extra}, function(data) {
            $("#cart").html($(data).find("#cart").html());
            enableButtons();
        });
    });
    
    hideShowPostcode();
    $("input[name=order_type]").change(function() {
        hideShowPostcode();
        
        var form = $(this).parents('form');
        var url = form.attr("action");
        $.post(url, form.serialize());
    });
    
    $('input[name=order_delivery_date]').change(function() {
        var form = $(this).parents('form');
        var url = form.attr("action");
        $.post(url, form.serialize());
    });
    
    $('input[name=order_postcode]').change(function() {
        var form = $(this).parents('form');
        var url = form.attr("action");
        $.post(url, form.serialize());
    });
}

function hideShowPostcode() {
    var value = $("input[name=order_type]:checked").val();
    if (value === "livraison") {
        $('.order-postcode').show().prop("required", true);
    } else {
        $('.order-postcode').hide().prop("required", false);
    }
}


$(document).ready(function() {
    enableButtons();
    
    $('.has-tooltip').tooltip()
});
