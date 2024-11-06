require(['jquery'], function ($) {
    let currentScroll = 0;
    let isChange = false;
    $(document).ready(function () {
        $(window).scroll(function (e) {
            if (isChange) {
                $(window).scrollTop(currentScroll);
                isChange = false;
            }
            currentScroll = document.documentElement.scrollTop || document.body.scrollTop;
        });
        $('.div-video-container .video').click(function (event) {
            let videoUrl = $(this).attr('data-video');
            // Create iframe
            let iframe = document.createElement('div');

            iframe.innerHTML = '<p>x</p><iframe width="698" height="389" src="' + videoUrl + '?autoplay=1&loop=0&autopause=0&queue-enable=false" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
            let video = iframe.childNodes[1];
            event.target.parentNode.replaceChild(video, event.target);
            video.requestFullscreen();
            event.preventDefault();
        });
        $('body').on('click', '.close-filters', function () {
            $('html').removeClass('filter-sidebar-open');
        })
        $('.header-right').click(function () {

        });
        $(document).on("click", function (e) {
            let container = $("#layered-filter-block");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                $('html').removeClass('filter-sidebar-open');
            }
        });
        $(document).on('click', '#close-fitter-sidebar', function () {
            let selector = $('html');
            if (!selector.hasClass('filter-sidebar-open')) {
                selector.addClass('filter-sidebar-open');
            } else {
                selector.removeClass('filter-sidebar-open');
            }
            return false;
        });
        $('.main-menu-items .col-md-3 p').click(function () {
            let parent = $(this).parent();
            if ($(parent).hasClass('active')) {
                $(parent).removeClass('active');
            } else {
                $(parent).addClass('active');
            }
        });
        $(document).on('click', '.filter-options-item', function () {
            isChange = true;
        })
        $('.all-products > a').click(function (e) {
            $('.all-products > .plus')[0].click();
            e.preventDefault();
            return;
        });
        $('.custom-menu > a').click(function (e) {
            $('.custom-menu > .plus')[0].click();
            e.preventDefault();
            return;
        });
        $('.about-menu > a').click(function (e) {
            $('.about-menu > .plus')[0].click();
            e.preventDefault();
            return;
        });
        $('#btn-minicart-close').click(function () {

        });
        $(document).on('click', '.switcher-currency', function () {

        });
        if (!$("select[name='country_id']").hasClass("loaded")) {
            setInterval(function () {
                $("select[name='country_id'] > option").each(function () {
                    $("select[name='country_id']").addClass("loaded")
                    if ($(this).val() === undefined || $(this).val() === "") {
                        $(this).remove();
                    }
                });
            }, 1000);
        }
    });
});


//-------------------------- POPUP CURRENCY

function openPopupCurrency() {
    require(['jquery', 'mage/url', 'Magento_Ui/js/modal/modal'],
        function ($, url, modal) {
            let modaloption = {
                type: 'popup',
                modalClass: 'region-selector',
                responsive: true,
                innerScroll: true,
                clickableOverlay: false,
                buttons: [],
            };
            let callforoption = modal(modaloption, $('.callfor-popup'));
            callforoption.openModal();
        });
}


//-------------------------- PASSWPRD TOGGLE


function viewPassword() {
    var passwordInput = document.getElementById('pass');
    var passStatus = document.getElementById('password__toggle');

    if (passwordInput.type == 'password') {
        passwordInput.type = 'text';
        passStatus.className = 'disable';

    } else {
        passwordInput.type = 'password';
        passStatus.className = 'enable';
    }
}

function currentPassword() {
    var currentPasswordInput = document.getElementById('current-password');
    var currentPassStatus = document.getElementById('cpassword__toggle');

    if (currentPasswordInput.type == 'password') {
        currentPasswordInput.type = 'text';
        currentPassStatus.className = 'disable';

    } else {
        currentPasswordInput.type = 'password';
        currentPassStatus.className = 'enable';
    }
}

function newPassword() {
    var newPasswordInput = document.getElementById('password');
    var newPassStatus = document.getElementById('newPassword__toggle');

    if (newPasswordInput.type == 'password') {
        newPasswordInput.type = 'text';
        newPassStatus.className = 'disable';

    } else {
        newPasswordInput.type = 'password';
        newPassStatus.className = 'enable';
    }
}

function confirmPassword() {
    var confirmPasswordInput = document.getElementById('password-confirmation');
    var confirmPassStatus = document.getElementById('confirmPassword__toggle');

    if (confirmPasswordInput.type == 'password') {
        confirmPasswordInput.type = 'text';
        confirmPassStatus.className = 'disable';

    } else {
        confirmPasswordInput.type = 'password';
        confirmPassStatus.className = 'enable';
    }
}

function registerPassword() {
    var registerPasswordInput = document.getElementById('password');
    var registerPassStatus = document.getElementById('registerPassword__toggle');

    if (registerPasswordInput.type == 'password') {
        registerPasswordInput.type = 'text';
        registerPassStatus.className = 'disable';

    } else {
        registerPasswordInput.type = 'password';
        registerPassStatus.className = 'enable';
    }
}

function registerCPassword() {
    var registerCPasswordInput = document.getElementById('password-confirmation');
    var registerCPassStatus = document.getElementById('registerCPassword__toggle');

    if (registerCPasswordInput.type == 'password') {
        registerCPasswordInput.type = 'text';
        registerCPassStatus.className = 'disable';

    } else {
        registerCPasswordInput.type = 'password';
        registerCPassStatus.className = 'enable';
    }
}