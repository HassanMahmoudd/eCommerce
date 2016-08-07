/* global $, alert, console */

$(function () {
    
    'use strict';
    
    //  Switch Between Login And SignUp
    
    $('.login-page h1 span').click(function () {
        
        $(this).addClass('selected').siblings().removeClass('selected');
        
        $('.login-page form').hide();
        
        $('.' + $(this).data('class')).fadeIn(100);
        
    });
    
    // Trigger the SelectBoxIt
    
    $("select").selectBoxIt( {
        
        autoWidth: false
        
    });
    
    // Hide Placeholder On Form Focus
    
    $('[placeholder]').focus(function () {
        
        $(this).attr('data-text', $(this).attr('placeholder'));
        
        $(this).attr('placeholder', '');
        
    }).blur(function () {
        
        $(this).attr('placeholder', $(this).attr('data-text'));
        
    });
    
    // Add asterisk on required fields
    
    $('input').each(function () {
        
        if ($(this).attr('required') === 'required') {
            
            $(this).after('<span class="asterisk">*</span>');
        
        }
        
    });
    
    // Confirmation Message on button
    
    $('.confirm').click(function () {
        
        return confirm("Are you sure?");
    });
    
    $('.live').keyup(function () {
        
       $($(this).data('class')).text($(this).val());
        
    });
    
    
    
});