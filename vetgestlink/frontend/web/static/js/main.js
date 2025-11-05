(function ($)
  { "use strict"
  
/* 1. Proloder */
    $(window).on('load', function () {
      $('#preloader-active').delay(450).fadeOut('slow');
      $('body').delay(450).css({
        'overflow': 'visible'
      });
    });

/* 2. sticky And Scroll UP */
    $(window).on('scroll', function () {
      var scroll = $(window).scrollTop();
      if (scroll < 400) {
        $(".header-sticky").removeClass("sticky-bar");
        $('#back-top').fadeOut(500);
      } else {
        $(".header-sticky").addClass("sticky-bar");
        $('#back-top').fadeIn(500);
      }
    });
  // Scroll Up
    $('#back-top a').on("click", function () {
      $('body,html').animate({
        scrollTop: 0
      }, 800);
    });
  

/* 3. slick Nav */
// mobile_menu
    var menu = $('ul#navigation');
    if(menu.length){
      menu.slicknav({
        prependTo: ".mobile_menu",
        closedSymbol: '+',
        openedSymbol:'-'
      });
    }

/* 4. MainSlider-1 */
    // h1-hero-active
    function mainSlider() {
      var BasicSlider = $('.slider-active');
      var slideCount = BasicSlider.find('.single-slider').length;
      // Remove listeners antigos para evitar múltiplas inicializações
      BasicSlider.off('init');
      BasicSlider.off('beforeChange');
      // Verifica se todos os slides têm conteúdo
      var allSlidesHaveContent = true;
      BasicSlider.find('.single-slider').each(function() {
        if ($(this).children().length === 0 && $(this).text().trim() === '') {
          allSlidesHaveContent = false;
        }
      });
      if (BasicSlider.length && slideCount > 1 && allSlidesHaveContent) {
        BasicSlider.on('init', function () {
          var $firstAnimatingElements = $('.single-slider:first-child').find('[data-animation]');
          doAnimations($firstAnimatingElements);
        });
        BasicSlider.on('beforeChange', function (e, slick, currentSlide, nextSlide) {
          var $animatingElements = $('.single-slider[data-slick-index="' + nextSlide + '"]').find('[data-animation]');
          doAnimations($animatingElements);
        });
        if (typeof BasicSlider.slick === 'function' && !BasicSlider.hasClass('slick-initialized')) {
          BasicSlider.slick({
            autoplay: true,
            autoplaySpeed: 4000,
            dots: true,
            fade: true,
            arrows: false,
            prevArrow: '<button type="button" class="slick-prev"><i class="ti-angle-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="ti-angle-right"></i></button>',
            responsive: [
              {
                breakpoint: 1024,
                settings: {
                  slidesToShow: 1,
                  slidesToScroll: 1,
                  infinite: true,
                }
              },
              {
                breakpoint: 991,
                settings: {
                  slidesToShow: 1,
                  slidesToScroll: 1,
                  arrows: false
                }
              },
              {
                breakpoint: 767,
                settings: {
                  slidesToShow: 1,
                  slidesToScroll: 1,
                  arrows: false
                }
              }
            ]
          });
        }
      } else {
        // Se não há slides suficientes, remove Slick se já estiver inicializado
        if (BasicSlider.hasClass('slick-initialized')) {
          BasicSlider.slick('unslick');
        }
        BasicSlider.addClass('single-slide-no-slick');
        if (!allSlidesHaveContent) {
          console.warn('Um ou mais slides estão vazios. Slick não foi inicializado.');
        } else if (slideCount === 1) {
          console.info('Só há um slide, Slick não foi inicializado.');
        } else {
          console.warn('No element found with the class .slider-active or no slides present.');
        }
      }
      function doAnimations(elements) {
        var animationEndEvents = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        elements.each(function () {
          var $this = $(this);
          var $animationDelay = $this.data('delay');
          var $animationType = 'animated ' + $this.data('animation');
          $this.css({
            'animation-delay': $animationDelay,
            '-webkit-animation-delay': $animationDelay
          });
          $this.addClass($animationType).one(animationEndEvents, function () {
            $this.removeClass($animationType);
          });
        });
      }
    }
    mainSlider();

    
/* 5. Testimonial Active*/
  var testimonial = $('.h1-testimonial-active');
    if(testimonial.length){
    testimonial.slick({
        dots: false,
        infinite: true,
        speed: 1000,
        autoplay:false,
        arrows: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="ti-angle-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="ti-angle-right"></i></button>',
        slidesToShow: 1,
        slidesToScroll: 1,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              infinite: true,
              dots: false,
              arrows: true
            }
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              dots: false,
              arrows: true
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              dots: false,
              arrows: true
            }
          }
        ]
      });
      // Custom arrow fade logic
      var arrowTimeout;
      testimonial.on('mouseenter', function() {
        testimonial.find('.slick-arrow').fadeIn(200);
        clearTimeout(arrowTimeout);
      });
      testimonial.on('mouseleave', function() {
        clearTimeout(arrowTimeout);
        arrowTimeout = setTimeout(function() {
          testimonial.find('.slick-arrow').fadeOut(200);
        }, 2000); // 2 segundos
      });
      // Garante que as setas apareçam ao focar
      testimonial.on('focusin', function() {
        testimonial.find('.slick-arrow').fadeIn(200);
        clearTimeout(arrowTimeout);
      });
      testimonial.on('focusout', function() {
        clearTimeout(arrowTimeout);
        arrowTimeout = setTimeout(function() {
          testimonial.find('.slick-arrow').fadeOut(200);
        }, 2000);
      });
      // Inicialmente, mostra as setas
      testimonial.find('.slick-arrow').show();
    }


/* 6. Nice Selectorp  */
  var nice_Select = $('select');
    if(nice_Select.length){
      nice_Select.niceSelect();
    }

/* 7. data-background */
    $("[data-background]").each(function () {
      $(this).css("background-image", "url(" + $(this).attr("data-background") + ")")
      });


/* 10. WOW active */
    new WOW().init();


    
// 11. ---- Mailchimp js --------//  
    function mailChimp() {
      $('#mc_embed_signup').find('form').ajaxChimp();
    }
    mailChimp();



// 12 Pop Up Img
    var popUp = $('.single_gallery_part, .img-pop-up, .popup-video');
      if (popUp.length) {
        popUp.magnificPopup({
          type: 'image',
          gallery: { enabled: true },
          iframe: { enabled: true }
        });
      }




// REMOVE a duplicidade de inicialização do Slick no $(document).ready

})(jQuery);
