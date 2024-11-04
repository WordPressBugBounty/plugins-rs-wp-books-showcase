jQuery(document).ready(function($) {
    var BookReview = function($scope, $) {
        var bookReviewSliderWrapper = jQuery('.review_slider_active').not('.slick-initialized');
        bookReviewSliderWrapper.each(function(index) {
            var lScreen, mScreen, sScreen;
            if (jQuery(this).data('lscreen')) {
                lScreen = jQuery(this).data('lscreen');
            } else {
                lScreen = 3;
            }
            if (jQuery(this).data('mscreen')) {
                mScreen = jQuery(this).data('mscreen');
            } else {
                mScreen = 2;
            }
            if (jQuery(this).data('sscreen')) {
                sScreen = jQuery(this).data('sscreen');
            } else {
                sScreen = 1;
            }
            var reviewSlider = jQuery(this);
            if (reviewSlider.hasClass('slick-initialized')) {
                reviewSlider.slick('unslick');
            }
            reviewSlider.slick({
                infinite: true,
                margin: 30,
                dots: false,
                arrows: false,
                slidesToShow: parseInt(lScreen),
                slidesToScroll: 1,
                rows: 0,
                cssEase: 'ease-out',
                useTransform: true,
                responsive: [{
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: parseInt(lScreen),
                        }
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: parseInt(mScreen),
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: parseInt(sScreen),
                        }
                    },
                    {
                        breakpoint: parseInt(sScreen),
                        settings: {
                            slidesToShow: 1,
                            adaptiveHeight: true
                        }
                    }
                ],
                smartSpeed: 450
            });
            $(".review-slider-prev").click(function() {
                $(".review_slider_active").slick("slickPrev");
            });

            $(".review-slider-next").click(function() {
                $(".review_slider_active").slick("slickNext");
            });
        });
    };

    if (typeof elementorFrontend !== 'undefined' && typeof elementor !== 'undefined') {
        elementorFrontend.hooks.addAction('frontend/element_ready/extra_addon_book_reviews_slider.default', BookReview);
        elementorFrontend.hooks.addAction('frontend/element_ready/text-editor.default', BookReview);
    } else {
        var $scope = jQuery('.review_slider_active');
        BookReview($scope, jQuery);
    }
});