require([
    'jquery',
    'jquery/ui'
], function ($) {
    $(document).ready(function () {
        $('.job-btn-accordion').click(function () {

            let selector = $(this).parent();
            if (selector.hasClass('active')) {
                $('.item-accordion').removeClass('active');
            } else {
                $('.item-accordion').removeClass('active');
                selector.addClass('active');
            }
        });
        $('.job-btn-go').click(function () {
            let jobTitle = $(this).attr('data-job');
            $('#job_title').val(jobTitle);
        });
    });

});
