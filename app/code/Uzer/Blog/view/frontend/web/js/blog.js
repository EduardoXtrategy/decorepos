require(['jquery', 'mage/mage', 'rokanthemes/owl'], function ($) {
    $(document).ready(function () {
        let selector = $(".news-trends-container .new-trends-box-container");
        console.log(selector.length);
        if (selector.length > 0) {
            selector.owlCarousel({
                loop: true,
                responsiveClass: true,
                autoWidth: true,
                margin: 20,
                itemsDesktop: [1000, 3],
                itemsDesktopSmall: [900, 3],
                itemsTablet: [600, 2],
                itemsMobile: [400, 1],
                center: true,
                lazyLoad: true
            });
        }


        $('.news-trends-container .new-trends-box-container').hide();
        let $toShow = $('.news-trends-container .news-trends-list ul li:first-child');
        $toShow.addClass('active');
        let attribute = $toShow.attr('tab');
        console.log('attributing');
        console.log(attribute);
        $('#uzer-tab-' + attribute).show();
        $('.news-trends-container .news-trends-list .category').click(function () {
            console.log('Debugging');
            $('.news-trends-container .news-trends-list ul li').removeClass('active');
            $('.news-trends-container .new-trends-box-container').hide();
            $(this).addClass('active');
            let attribute = $(this).attr('tab');
            $('#uzer-tab-' + attribute).show();
        });
    });
});
