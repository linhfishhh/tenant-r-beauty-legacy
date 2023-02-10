{{--<head>--}}
    {{--<script src="https://www.gstatic.com/firebasejs/7.8.2/firebase.js"></script>--}}
    {{--<script src="https://www.gstatic.com/firebasejs/ui/4.4.0/firebase-ui-auth__vi.js"></script>--}}
    {{--<link type="text/css" rel="stylesheet" href="https://www.gstatic.com/firebasejs/ui/4.4.0/firebase-ui-auth.css" />--}}
{{--</head>--}}
@enqueueJS('firebase', getThemeAssetUrl('libs/firebase/firebase.js'), JS_LOCATION_HEAD)
@enqueueJS('firebase-ui-auth', getThemeAssetUrl('libs/firebase/firebase-ui-auth__vi.js'), JS_LOCATION_HEAD)
@enqueueCSS('firebase-ui-auth-css', getThemeAssetUrl('libs/firebase/css/firebase-ui-auth.css'))
<body>
<div class="float-form-login" id="float-form-login">
    <div class="form-wrapper">
        <form id="form-global-login" novalidate>
            <div class="form-content" id="login_type">
                <div class="form-content" id="firebaseui-container"></div>
                <div class="buttons">
                    <div class="hoac-tiep-tuc">
                        Bạn có thể đăng nhập qua
                    </div>
                    <button type="button" class="facebook">
                        <i class="fa fa-facebook"></i>
                        <span>Facebook</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@unique('login_form_script')
<script>
    async function handleSignedInUser(user) {
        const idToken = await user.getIdToken();
        $.ajax({
            url: '{!! route('api.auth.loginWithFirebase') !!}',
            type: 'post',
            dataType: 'json',
            data: {
                idToken: idToken,
                email: user.email,
                phone: user.phoneNumber,
            },
            xhrFields: {
                withCredentials: true
            },
            beforeSend: function(){

            },
            success: function (json) {
                // TODO: open welcome dialog
                console.log(json);
                $('#modal-login-nt').modal('hide');
                $('#modal-account-success').modal('show');
            },
            complete: function(){

            },
            error: function (json) {
                handleErrorMessage(form, json);
            }
        });
    }

    function getUiConfig() {
        return {
            'callbacks': {
                // Called when the user has been successfully signed in.
                'signInSuccessWithAuthResult': function(authResult, redirectUrl) {
                    if (authResult.user) {
                        handleSignedInUser(authResult.user);
                    }
                    // Do not redirect.
                    return false;
                }
            },
            // Opens IDP Providers sign-in flow in a popup.
            'signInFlow': 'popup',
            'signInOptions': [
                {
                    provider: firebase.auth.PhoneAuthProvider.PROVIDER_ID,
                    defaultCountry: 'VN'
                    // recaptchaParameters: {
                    //     size: getRecaptchaMode()
                    // }
                },
            ],
        };
    }
    var config = {
        apiKey: "AIzaSyDrRnfUA4g5dFyz7yi0Qegkx1rnVq_dUbI",
        authDomain: "isalon-booking.firebaseapp.com",
        projectId: "isalon-booking",
    };
    firebase.initializeApp(config);
    // Initialize the FirebaseUI Widget using Firebase.
    var ui = new firebaseui.auth.AuthUI(firebase.auth());
    // To apply the default browser preference instead of explicitly setting it.
    firebase.auth().useDeviceLanguage();
    ui.start('#firebaseui-container', getUiConfig());

    // login callback
    function loginCallback(response) {
        console.log(response);
        if (response.status === "PARTIALLY_AUTHENTICATED") {
            var code = response.code;
            /*var csrf = response.state;*/
            // Send code to server to exchange for access token
            $.ajax({
                url: '{!! route('frontend.login.socialLoginV2') !!}',
                type: 'post',
                dataType: 'json',
                data: {
                    token: code,
                    provider: 'accountkit',
                    phone: document.getElementById("phone_number").value,
                    _token: "{{ csrf_token() }}"
                },
                xhrFields: {
                    withCredentials: true
                },
                beforeSend: function(){

                },
                success: function (json) {
                    // TODO: open welcome dialog
                    console.log(json);
                    $('#modal-login-nt').modal('hide');
                    $('#modal-account-success').modal('show');
                    {{--return false;
                    var rl = '{!! url('') !!}';
                    if(json.hasOwnProperty('RedirectTo')){
                        rl = json.RedirectTo;
                    }
                    // window.location = rl;
                    window.location = '{!! Request::url() !!}';
                    --}}
                },
                complete: function(){

                },
                error: function (json) {
                    handleErrorMessage(form, json);
                }
            });
        }
        else if (response.status === "NOT_AUTHENTICATED") {
            // handle authentication failure
        }
        else if (response.status === "BAD_PARAMS") {
            // handle bad parameters
        }
    }

    // phone form submission handler
    function smsLogin() {
        var countryCode = '+84';
        var phoneNumber = document.getElementById("phone_number").value;
        // AccountKit.login(
        //     'PHONE',
        //     {countryCode: countryCode, phoneNumber: phoneNumber}, // will use default values if not specified
        //     loginCallback
        // );
        ui.start('#firebaseui-container', getUiConfig());
        return false;
    }
    // email form submission handler
    function emailLogin() {
        var emailAddress = document.getElementById("email").value;
        AccountKit.login(
            'EMAIL',
            {emailAddress: emailAddress},
            loginCallback
        );
    }
</script>
<script type="text/javascript">

    $(function () {
        function validateLogin() {
            var phone = $('#form-global-login [name=phone]').val();
            if (phone && phone.length > 0) {
                $('#form-global-login .login').prop('disabled', false);
            } else {
                $('#form-global-login .login').prop('disabled', true);
            }
        }
        $('#form-global-login button.google').click(function () {
            window.location = '{!! route('frontend.login.social', ['provider' => 'google']) !!}';
        });
        $('#form-global-login button.facebook').click(function () {
            window.location = '{!! route('frontend.login.social', ['provider' => 'facebook']) !!}';
        });
        $('#form-global-login [name=phone]').on('change paste keyup', function() {
            validateLogin();
        });
        validateLogin();
        $('#form-global-login').submit(function (event) {
            event.preventDefault();
            smsLogin();
            return false;
        });
        $('body').on('click', function (e) {
            var  l = $('.float-form-login.active').length;
            if(l){
                $('.float-form-login').removeClass('active');
            }
        });
        $('.float-form-login').click(function (e) {
            e.stopPropagation();
        });
        $('.float-form-login .close-btn').click(function (e) {
            e.stopPropagation();
            $(this).parents('.float-form-login').removeClass('active');
        });
    });
</script>
@endunique
</body>