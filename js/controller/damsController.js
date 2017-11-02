/*****************************************************************************
 * Ajax controler for d-framework 
 * author : dams
 * Dams Technology Copyright © 2014-3018 www.dams-labs.net 
 *****************************************************************************/



(function ($, window, document, undefined) {
    "use strict";

    var _ajaxControllerUrl = "php/controller/AjaxController.php";
    var  $modal = $("div#modal");

    // set Enter key (13) event on login form
    var setEnterKey = function(){
        $("form input").keydown(function(evt) {
            if(evt.keyCode == 13){
                $("form").submit();
                return false; //avoid modal close
            }
        });      
    }

    // get and show Login modal
    var loginShow = function(evt) {        
        $.ajax({
            url : _ajaxControllerUrl,
            method: "POST",
            data : { action : 'showLogin' }
        })
        .done(function(data){
            if(data !== undefined && data !== null){
            	$modal.html(data);
                setEnterKey();
                //init the modal (ZF6)
                var $modalContent = new Foundation.Reveal($('#modal_content'));
                $modalContent.open();
            }
        });
    };
    
    // get and show About modal
    var aboutShow = function(evt) {
        $.ajax({
            url : _ajaxControllerUrl,
            method: "POST",
            data : { action : 'showAbout' }
        })
        .done(function(data){
            if(data !== undefined && data !== null){
            	$modal.html(data);
                setEnterKey();
                //init the modal (ZF6)
                var $modalContent = new Foundation.Reveal($('#modal_content'));
                $modalContent.open();
            }
        });
    };
    
    // get and show Example modal
    var exampleShow = function(evt) {
        $.ajax({
            url : _ajaxControllerUrl,
            method: "POST",
            data : { action : 'showExample' }
        })
        .done(function(data){
            if(data !== undefined && data !== null){
            	$modal.html(data);
                setEnterKey();
                //init the modal (ZF6)
                var $modalContent = new Foundation.Reveal($('#modal_content'));
                $modalContent.open();
            }
        });
    };
    

   
    // login form submit event
    var loginSubmit = function(evt) {
        evt.preventDefault();
        var saltPwd = "108357"; //this is for example, use your own salt and SSL connexion
        var CODE_SUCCESS = "success";
        var CODE_ERROR = "error";
        var CODE_MAX_ATTEMPT = "attemptmax";
        
        var $form = $(evt.target);

        
        var user = $form.find("#username")[0],
        	pwd = $form.find("#password")[0],
            data = $form.find("#data")[0],
            $messageBox = $form.find("#login_notification");

        //hash stuff for basic login, but better to use SSL SECURE CONNEXION
        var pwd_salted = pwd.value + saltPwd;        
        var pwd_hash = hex_sha256(pwd_salted); //hex_sha256 defined in modules/jsSha2/sha256.js
      
        //sending
        $.ajax({
            url : _ajaxControllerUrl,
            method: 'POST',
            dataType: 'json',
            data : {
                action : 'login',
                username : user.value,
                password : pwd_hash,
                data : data.value
            }
        })
        .done(function(data){
            //receiving
            if(data !== undefined && data !== null){
                if(data.code !== undefined && data.code === CODE_SUCCESS){
                    document.location.href = "/";
                
                }else if(data.code !== undefined && data.code === CODE_MAX_ATTEMPT){
                    var messageHtml = "<span class='error'>" + data.message + "</span>";
                    $messageBox.html(messageHtml);
                    $form.find("#submit")[0].disabled = true;

                }else if(data.code !== undefined && 
                        data.code === CODE_ERROR && 
                        data.message !== undefined){
                    var messageHtml = "<span class='error'>" + data.message + "</span>";
                    $messageBox.html(messageHtml);
                }
            }
        })        
        .fail(function(jqXHR, textStatus, errorThrown){
            //receiving error
            var messageHtml = "<span class='error'>" 
                + textStatus + " : " 
                + errorThrown + "</span>";
            $messageBox.html(messageHtml);
        })        
        .always(function(){
            //cleaning
            user.value = '';
            pwd.value = '';
            data.value = '';
        });

        return false;
    };

    // logout
    var logoutClick = function(evt) {
        $.ajax({
            url : _ajaxControllerUrl,
            method: 'POST',
            dataType: 'json',
            data : { action : 'logout' }
        })
        .always(function(){
            document.location.href = "/";
        });

    };
    
    // init buttons events
    var initEvents = function()
    {
      // cleaning previous events
      $(document).off('submit', '#login_form');
      
      // modals show 
      $("#a_modal_login").click(loginShow);      
      $("#a_modal_about").click(aboutShow);    
      $("#a_modal_examples").click(exampleShow);    
      // logout event
      $("#a_modal_logout").click(logoutClick);      

      // form submit event
      $(document).on('submit', '#login_form', loginSubmit);
    };


    // call on $(document).dams()
    $.fn.dams = function () {
        var args = Array.prototype.slice.call(arguments, 0);

        initEvents();
    };

}(jQuery, window, window.document));

