(function ($) {
  "use strict";

  /* 1. Proloder */
  $(window).on("load", function () {
    $("#preloader-active").delay(450).fadeOut("slow");
    $("body").delay(450).css({
      overflow: "visible",
    });
  });

  /* 2. sticky And Scroll UP */
  $(window).on("scroll", function () {
    var scroll = $(window).scrollTop();
    if (scroll < 200) {
      $(".header-sticky").removeClass("sticky-bar");
      $("#back-top").fadeOut(500);
    } else {
      $(".header-sticky").addClass("sticky-bar");
      $("#back-top").fadeIn(500);
    }
  });

  // Scroll Up
  $("#back-top a").on("click", function () {
    $("body,html").animate({
        scrollTop: 0,
      },
      100
    );
    return false;
  });


  /* 4. MainSlider-1 */
  // h1-hero-active
  function mainSlider() {
    var BasicSlider = $(".slider-active");

    if (BasicSlider.length) {
      BasicSlider.on("init", function (e, slick) {
        var $firstAnimatingElements = $(".single-slider:first-child").find(
          "[data-animation]"
        );
        doAnimations($firstAnimatingElements);
      });
      BasicSlider.on(
        "beforeChange",
        function (e, slick, currentSlide, nextSlide) {
          var $animatingElements = $(
            '.single-slider[data-slick-index="' + nextSlide + '"]'
          ).find("[data-animation]");
          doAnimations($animatingElements);
        }
      );
      BasicSlider.slick({
        autoplay: false,
        autoplaySpeed: 4000,
        dots: false,
        fade: true,
        arrows: false,
        prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-angle-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fas fa-angle-right"></i></button>',
        responsive: [{
            breakpoint: 1024,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              infinite: true,
            },
          },
          {
            breakpoint: 991,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              arrows: false,
            },
          },
          {
            breakpoint: 767,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              arrows: false,
            },
          },
        ],
      });
    }

    function doAnimations(elements) {
      var animationEndEvents =
        "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend";
      elements.each(function () {
        var $this = $(this);
        var $animationDelay = $this.data("delay");
        var $animationType = "animated " + $this.data("animation");
        $this.css({
          "animation-delay": $animationDelay,
          "-webkit-animation-delay": $animationDelay,
        });
        $this.addClass($animationType).one(animationEndEvents, function () {
          $this.removeClass($animationType);
        });
      });
    }
  }

  setTimeout(() => {}, 500);

  $(document).ready(function () {
    // console.log("ready!");
    var content_box_owl = $("#content-box-owl");
    // content_box_owl.owlCarousel();
    // content_box_owl.trigger('destroy.owl.carousel');

    content_box_owl.owlCarousel({
      autoplay: true,
      margin: 10,
      loop: true,
      autoWidth: true,
      items: 1,
    });

    $(".brand-active-carousel").owlCarousel({
      items: 5, // Display 4 items at a time
      autoplay: true,
      loop: true, // Enable looping
      margin: 30, // Space between items
      nav: false, // Enable navigation buttons
      dots: false, // Enable dots
      responsive: {
        // Make it responsive for all screen sizes
        0: {
          items: 1, // 1 item for small screens
        },
        600: {
          items: 2, // 2 items for medium screens
        },
        1000: {
          items: 5, // 4 items for large screens
        },
      },
    });

    $(".items-active").slick({
      dots: false,
      infinite: true,
      autoplay: true,
      speed: 400,
      arrows: true,
      prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
      nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
      slidesToShow: 3,
      slidesToScroll: 1,
      responsive: [{
          breakpoint: 1024,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            infinite: true,
            dots: false,
          },
        },
        {
          breakpoint: 992,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
            dots: false,
          },
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
          },
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
          },
        },
      ],
    });

    /*counterUp*/
    $(".home-counter").counterUp({
      delay: 10,
      time: 3000,
    });

    /* 3. slick Nav */
    // mobile_menu
    var menu = $("ul#navigation");
    if (menu.length) {
      menu.slicknav({
        prependTo: ".mobile_menu",
        closedSymbol: "+",
        openedSymbol: "-",
      });
    }

    $("#testimonials").owlCarousel({
      autoplay: true,
      rewind: true,
      margin: 20,
      loop: true,
      dots: false,
      responsiveClass: true,
      autoHeight: true,
      autoplayTimeout: 5000,
      smartSpeed: 800,
      nav: false,
      responsive: {
        0: {
          items: 1
        },

        600: {
          items: 3
        },

        1024: {
          items: 3
        },

        1366: {
          items: 3
        }
      }
    });

  });

  mainSlider();

  // 4. Single Img slder

  /* 5. Gallery Active */
  var client_list = $(".location-active");
  if (client_list.length) {
    client_list.owlCarousel({
      slidesToShow: 3,
      slidesToScroll: 1,
      loop: true,
      autoplay: true,
      speed: 3000,
      smartSpeed: 2000,
      nav: true,
      navText: [
        '<i class="ti-arrow-left"></i>',
        '<i class="ti-arrow-right"></i>',
      ],
      dots: false,
      margin: 0,

      autoplayHoverPause: true,
      responsive: {
        0: {
          nav: false,
          items: 1,
        },
        576: {
          nav: false,
          items: 1,
        },
        768: {
          nav: true,
          items: 2,
        },
        992: {
          nav: true,
          items: 3,
        },
      },
    });
  }

  // Brand Active
  // $(".brand-active").slick({
  //   dots: false,
  //   infinite: true,
  //   autoplay: true,
  //   speed: 400,
  //   arrows: false,
  //   slidesToShow: 5,
  //   slidesToScroll: 1,
  //   responsive: [
  //     {
  //       breakpoint: 1024,
  //       settings: {
  //         slidesToShow: 4,
  //         slidesToScroll: 3,
  //         infinite: true,
  //         dots: false,
  //       },
  //     },
  //     {
  //       breakpoint: 992,
  //       settings: {
  //         slidesToShow: 3,
  //         slidesToScroll: 1,
  //         infinite: true,
  //         dots: false,
  //       },
  //     },
  //     {
  //       breakpoint: 768,
  //       settings: {
  //         slidesToShow: 2,
  //         slidesToScroll: 1,
  //       },
  //     },
  //     {
  //       breakpoint: 480,
  //       settings: {
  //         slidesToShow: 1,
  //         slidesToScroll: 1,
  //       },
  //     },
  //   ],
  // });

  /* 4. Testimonial Active*/
  var testimonial = $(".h1-testimonial-active");
  if (testimonial.length) {
    testimonial.slick({
      dots: true,
      infinite: true,
      speed: 1000,
      autoplay: true,
      loop: true,
      arrows: true,
      prevArrow: '<button type="button" class="slick-prev"><i class="ti-arrow-top-left"></i></button>',
      nextArrow: '<button type="button" class="slick-next"><i class="ti-arrow-top-right"></i></button>',
      slidesToShow: 3,
      slidesToScroll: 3,
      responsive: [{
          breakpoint: 1024,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
            dots: true,
            arrows: true,
          },
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
          },
        },
        {
          breakpoint: 500,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            dots: false,
          },
        },
      ],
    });
  }

  /* 6. Nice Selectorp  */
  var nice_Select = $("select");
  if (nice_Select.length) {
    nice_Select.niceSelect();
  }

  /* 7. data-background */
  $("[data-background]").each(function () {
    $(this).css(
      "background-image",
      "url(" + $(this).attr("data-background") + ")"
    );
  });

  /* 10. WOW active */
  // new WOW().init();

  // 11. ---- Mailchimp js --------//
  function mailChimp() {
    $("#mc_embed_signup").find("form").ajaxChimp();
  }
  // mailChimp();

  // 12 Pop Up Img
  var popUp = $(".single_gallery_part, .img-pop-up");
  if (popUp.length) {
    popUp.magnificPopup({
      type: "image",
      gallery: {
        enabled: true,
      },
    });
  }

  // 12 Pop Up Video
  var popUp = $(".popup-video");
  if (popUp.length) {
    popUp.magnificPopup({
      type: "iframe",
    });
  }

  // $(".counter").counterUp({
  //   delay: 10,
  //   time: 3000,
  // });

  // Modal Activation
  $(".search-switch").on("click", function () {
    $(".search-model-box").fadeIn(400);
  });

  $(".search-close-btn").on("click", function () {
    $(".search-model-box").fadeOut(400, function () {
      $("#search-input").val("");
    });
  });
})(jQuery);