// Load the SDK asynchronously
(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
        return;
    }
    
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
if (typeof qodeSocialLoginVars !== 'undefined') {
    var facebookAppId = qodeSocialLoginVars.social.facebookAppId;
}
if (facebookAppId) {
    window.fbAsyncInit = function () {
        FB.init({
            appId: facebookAppId, //265124653818954 - test app ID
            cookie: true,  // enable cookies to allow the server to access
            xfbml: true,  // parse social plugins on this page
            version: 'v2.5' // use version 2.5
        });

        window.FB = FB;
    };
}

(function ($) {
    "use strict";

    var socialLogin = {};
    if ( typeof qode !== 'undefined' ) {
        qode.modules.socialLogin = socialLogin;
    }

    socialLogin.qodeUserLogin = qodeUserLogin;
    socialLogin.qodeUserRegister = qodeUserRegister;
    socialLogin.qodeUserLostPassword = qodeUserLostPassword;
    socialLogin.qodeInitLoginWidgetModal = qodeInitLoginWidgetModal;
    socialLogin.qodeInitFacebookLogin = qodeInitFacebookLogin;
    socialLogin.qodeInitGooglePlusLogin = qodeInitGooglePlusLogin;
    socialLogin.qodeUpdateUserProfile = qodeUpdateUserProfile;

    $(document).ready(qodeOnDocumentReady);
    $(window).load(qodeOnWindowLoad);
    $(window).resize(qodeOnWindowResize);
    $(window).scroll(qodeOnWindowScroll);

    /**
     * All functions to be called on $(document).ready() should be in this function
     */
    function qodeOnDocumentReady() {
        qodeInitLoginWidgetModal();
        qodeUserLogin();
        qodeUserRegister();
        qodeUserLostPassword();
        qodeUpdateUserProfile();
	    qodeInitLoginDropDownPosition();
    }

    /**
     * All functions to be called on $(window).load() should be in this function
     */
    function qodeOnWindowLoad() {
        qodeInitFacebookLogin();
        qodeInitGooglePlusLogin();
    }

    /**
     * All functions to be called on $(window).resize() should be in this function
     */
    function qodeOnWindowResize() {
    }

    /**
     * All functions to be called on $(window).scroll() should be in this function
     */
    function qodeOnWindowScroll() {
    }

    /**
     * Initialize login widget modal
     */
    function qodeInitLoginWidgetModal() {

        var modalOpener = $('.qode-login-opener'),
            modalCloser = $('.qode-membership-close-modal .icon_close'),
            modalHolder = $('.qode-login-register-holder');

        if (modalOpener) {
            var tabsHolder = $('.qode-login-register-content');

            //Init opening login modal
            modalOpener.click(function (e) {
                e.preventDefault();
                modalHolder.fadeIn(300);
                modalHolder.addClass('opened');
            });

            //Init closing login modal
            modalHolder.click(function (e) {
                if (modalHolder.hasClass('opened')) {
                    modalHolder.fadeOut(300);
                    modalHolder.removeClass('opened');
                }
            });
            $('.qode-login-register-content').click(function (e) {
                e.stopPropagation();
            });

            //Closing of modal when X is pressed
            modalCloser.click(function (e) {
                e.preventDefault();
                if (modalHolder.hasClass('opened')) {
                    modalHolder.fadeOut(300);
                    modalHolder.removeClass('opened');
                }
            });

            // on esc too
            $(window).on('keyup', function (e) {
                if (modalHolder.hasClass('opened') && e.keyCode == 27) {
                    modalHolder.fadeOut(300);
                    modalHolder.removeClass('opened');
                }
            });

            //Init tabs
            tabsHolder.tabs();
        }
    }

	/**
	 * Initialize login dropdown position
	 */
	function qodeInitLoginDropDownPosition() {

		var dropdown = $('.header_bottom .qode-login-dropdown');

		if (dropdown && dropdown.parent().offset()) {
				var browserWidth = $(window).width();
				var boxedLayout; // boxed layout width

				var menuItemPosition = dropdown.parent().offset().left;
				var subMenuWidth = dropdown.width();
				var menuItemFromLeft = browserWidth - menuItemPosition;

				if(menuItemFromLeft < subMenuWidth){
					dropdown.addClass('qode-dd-right-position');
				}


		}
	}

    /**
     * Login user via Ajax
     */
    function qodeUserLogin() {
        $('.qode-login-form').on('submit', function (e) {
            e.preventDefault();
            var ajaxData = {
                action: 'qode_membership_login_user',
                security: $(this).find('#qode-login-security').val(),
                login_data: $(this).serialize()
            };
            $.ajax({
                type: 'POST',
                data: ajaxData,
                url: QodeAdminAjax.ajaxurl,
                success: function (data) {
                    var response;
                    response = JSON.parse(data);

                    qodeRenderAjaxResponseMessage(response);
                    if (response.status == 'success') {
                        window.location = response.redirect;
                    }
                }

            });
            return false;
        });
    }

    /**
     * Register New User via Ajax
     */
    function qodeUserRegister() {

        $('.qode-register-form').on('submit', function (e) {

            e.preventDefault();
            var ajaxData = {
                action: 'qode_membership_register_user',
                security: $(this).find('#qode-register-security').val(),
                register_data: $(this).serialize()
            };

            $.ajax({
                type: 'POST',
                data: ajaxData,
                url: QodeAdminAjax.ajaxurl,
                success: function (data) {
                    var response;
                    response = JSON.parse(data);

                    qodeRenderAjaxResponseMessage(response);
                    if (response.status == 'success') {
                        window.location = response.redirect;
                    }
                }
            });

            return false;
        });
    }

    /**
     * Reset user password
     */
    function qodeUserLostPassword() {

        var lostPassForm = $('.qode-reset-pass-form');
        lostPassForm.submit(function (e) {
            e.preventDefault();
            var data = {
                action: 'qode_membership_user_lost_password',
                user_login: lostPassForm.find('#user_reset_password_login').val()
            };
            $.ajax({
                type: 'POST',
                data: data,
                url: QodeAdminAjax.ajaxurl,
                success: function (data) {
                    var response = JSON.parse(data);
                    qodeRenderAjaxResponseMessage(response);
                    if (response.status == 'success') {
                        window.location = response.redirect;
                    }
                }
            });
        });
    }

    /**
     * Response notice for users
     * @param response
     */
    function qodeRenderAjaxResponseMessage(response) {

        var responseHolder = $('.qode-membership-response-holder'), //response holder div
            responseTemplate = _.template($('.qode-membership-response-template').html()); //Locate template for info window and insert data from marker options (via underscore)

        var messageClass;
        if (response.status === 'success') {
            messageClass = 'qode-membership-message-succes';
        } else {
            messageClass = 'qode-membership-message-error';
        }

        var templateData = {
            messageClass: messageClass,
            message: response.message
        };

        var template = responseTemplate(templateData);
        responseHolder.html(template);
    }

    /**
     * Facebook Login
     */
    function qodeInitFacebookLogin() {
        var loginForm = $('.qode-facebook-login-holder');
        loginForm.submit(function (e) {
            e.preventDefault();
            window.FB.login(function (response) {
                qodeFacebookCheckStatus(response);
            }, {scope: 'email, public_profile'});
        });

    }

    /**
     * Check if user is logged into Facebook and App
     *
     * @param response
     */
    function qodeFacebookCheckStatus(response) {
        if (response.status === 'connected') {
            // Logged into your app and Facebook.
            qodeGetFacebookUserData();
        } else if (response.status === 'not_authorized') {
            // The person is logged into Facebook, but not your app.
            console.log('Please log into this app');
        } else {
            // The person is not logged into Facebook, so we're not sure if
            // they are logged into this app or not.
            console.log('Please log into Facebook');
        }
    }

    /**
     * Get user data from Facebook and login user
     */
    function qodeGetFacebookUserData() {
        console.log('Welcome! Fetching information from Facebook...');
        FB.api('/me', 'GET', {'fields': 'id, name, email, link, picture'}, function (response) {
            var nonce = $('.qode-facebook-login-holder [name^=qode_nonce_facebook_login]').val();
            response.nonce = nonce;
            response.image = response.picture.data.url;
            var data = {
                action: 'qode_membership_check_facebook_user',
                response: response
            };
            $.ajax({
                type: 'POST',
                data: data,
                url: QodeAdminAjax.ajaxurl,
                success: function (data) {
                    var response;
                    response = JSON.parse(data);

                    qodeRenderAjaxResponseMessage(response);
                    if (response.status == 'success') {
                        window.location = response.redirect;
                    }
                }
            });

        });
    }

    /**
     * Google Login
     */
    function qodeInitGooglePlusLogin() {

        if (typeof qodeSocialLoginVars !== 'undefined') {
            var clientId = qodeSocialLoginVars.social.googleClientId;
        }
        if (clientId) {
            gapi.load('auth2', function () {
                window.auth2 = gapi.auth2.init({
                    client_id: clientId
                });
                qodeInitGooglePlusLoginButton();
            });
        } else {
            var loginForm = $('.qode-google-login-holder');
            loginForm.submit(function (e) {
                e.preventDefault();
            });
        }

    }

    /**
     * Initialize login button for Google Login
     */
    function qodeInitGooglePlusLoginButton() {

        var loginForm = $('.qode-google-login-holder');
        loginForm.submit(function (e) {
            e.preventDefault();
            window.auth2.signIn();
            qodeSignInCallback();
        });

    }

    /**
     * Get user data from Google and login user
     */
    function qodeSignInCallback() {
        var signedIn = window.auth2.isSignedIn.get();
        if (signedIn) {
            var currentUser = window.auth2.currentUser.get(),
                profile = currentUser.getBasicProfile(),
                nonce = $('.qode-google-login-holder [name^=qode_nonce_google_login]').val(),
                userData = {
                    id: profile.getId(),
                    name: profile.getName(),
                    email: profile.getEmail(),
                    image: profile.getImageUrl(),
                    link: 'https://plus.google.com/' + profile.getId(),
                    nonce: nonce
                },
                data = {
                    action: 'qode_membership_check_google_user',
                    response: userData
                };
            $.ajax({
                type: 'POST',
                data: data,
                url: QodeAdminAjax.ajaxurl,
                success: function (data) {
                    var response;
                    response = JSON.parse(data);

                    qodeRenderAjaxResponseMessage(response);
                    if (response.status == 'success') {
                        window.location = response.redirect;
                    }
                }
            });
        }
    }

    /**
     * Update User Profile
     */
    function qodeUpdateUserProfile() {
        var updateForm = $('#qode-membership-update-profile-form');
        if ( updateForm.length ) {
            var btnText = updateForm.find('button'),
                updatingBtnText = btnText.data('updating-text'),
                updatedBtnText = btnText.data('updated-text');

            updateForm.on('submit', function (e) {
                e.preventDefault();
                var prevBtnText = btnText.html();
                btnText.html(updatingBtnText);

                var ajaxData = {
                    action: 'qode_membership_update_user_profile',
                    data: $(this).serialize()
                };

                $.ajax({
                    type: 'POST',
                    data: ajaxData,
                    url: QodeAdminAjax.ajaxurl,
                    success: function (data) {
                        var response;
                        response = JSON.parse(data);

                        // append ajax response html
                        qodeRenderAjaxResponseMessage(response);
                        if (response.status == 'success') {
                            btnText.html(updatedBtnText);
                            window.location = response.redirect;
                        } else {
                            btnText.html(prevBtnText);
                        }
                    }
                });
                return false;
            });
        }
    }

})(jQuery);