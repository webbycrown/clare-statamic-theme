/* =====================================
    Template Name: Clare E Commerce
    Author Name: WebbyCrown
    Description: Clare E Commerce - HTML5 Template.
    Version:1.0
========================================*/

/*======================================
[ JS Table of contents ]
Home one js
01. General Open JS
    + Mobile menu
    + Mobile menu dropdown
    + all categories menu js
    + Cookie popup js
    + mini cart popup js
    + Countdown js
    + Plus Minus button js
    + Page scroll
    + Search Bar
 
02. Slider Open JS
    + Hero slider
    + hero slider home 2 slider
    + What we do slider
    + Testimonial slider
03. Tabs Open JS
04. Accordion Open JS
05. Isotope JS
06. All popup JS
07. Preloader JS

========================



========================================*/

(function ($) {
  function clareSetCookie(name, value, days) {
    var expires = "";
    if (days) {
      var date = new Date();
      date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
      expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + encodeURIComponent(value || "") + expires + "; path=/; SameSite=Lax";
  }
  function clareGetCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(";");
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) === " ") c = c.substring(1);
      if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length));
    }
    return "";
  }
  function clareDismissed(name) {
    try {
      if (window.localStorage && localStorage.getItem(name)) return true;
    } catch (e) {}
    return !!clareGetCookie(name);
  }
  function clareDismiss(name, value) {
    value = value || "1";
    try {
      if (window.localStorage) localStorage.setItem(name, value);
    } catch (e) {}
    clareSetCookie(name, value, 365);
  }

  eCommerce = {
    init: function () {
      this.general_open();
      this.all_slider();
      this.tabs_open();
      this.accordion_open();
      this.Isotope_js();
      this.all_popup_js();
      this.Preloader_js();
    },

        /*======================================
         01. General Open JS
        ========================================*/

        general_open: function () {

          /* Page scroll to Header sticky */
          $(window).scroll(function() {
            if ($(this).scrollTop() > 0){  
              $('.site-header').addClass("sticky");
            }
            else{
              $('.site-header').removeClass("sticky");
            }
          });

          /* Mobile menu*/
          $("body").on('click', '.header-button .toggle-menu, .mobile-menu-close a', function(){
                $('.mobile-menu').toggleClass('open');
                $(this).toggleClass('active');
                $('body, html').toggleClass('menu-open');
            });

        /* Mobile menu dropdown */
        if( $(window).width() <= 991 ) {
              $(".mobile-menu .menu > li").each(function (i) {
                  if ($(this).has("ul").length)
                  {
                      $(this).find('ul').addClass("sub-menu");
                      $(this).find('> a').after('<span class="caret-arrow"></span>');
                      $(this).find('> .sub-menu').css('display', 'none');
                  }
              });
              $('.mobile-menu .menu li .caret-arrow').click(function () {
                  var catSubUl = $(this).next('.sub-menu');
                  var catSubli = $(this).closest('li');
                  if (catSubUl.is(':hidden'))
                  {
                      //$("#window > ul > li .sub-menu").slideUp();
                      catSubUl.slideDown();
                      //$('.caret').removeClass('active');
                      $(this).addClass('active');
                      catSubli.addClass('active');
                  }
                  else
                  {
                      catSubUl.slideUp();
                      $(this).removeClass('active');
                      catSubli.removeClass('active');
                  }
              });
          }

          //TOGGLING NESTED ul
          $(".drop-down .selected").click(function() {
            $(".drop-down .search-categories").toggle();
          });

          //SELECT OPTIONS AND HIDE OPTION AFTER SELECTION
          $(".drop-down .search-categories li").click(function() {
            var text = $(this).text();
            var slug = $(this).attr("data-slug") || "";
            $(".drop-down .selected span").html(text);
            $(this).closest("form").find(".clare-search-category").val(slug);
            $(".drop-down .search-categories").hide();
          }); 


          //HIDE OPTIONS IF CLICKED ANYWHERE ELSE ON PAGE
          $(document).bind('click', function(e) {
            var $clicked = $(e.target);
            if (! $clicked.parents().hasClass("drop-down"))
              $(".drop-down .search-categories").hide();
          });

          /* all categories menu js — click to open, click again to close */
          $(document).on("click", ".all-categories .dropdown-toggle", function(e){
            e.preventDefault();
            e.stopPropagation();
            var $wrap = $(this).closest(".all-categories");
            var open = $wrap.hasClass("is-open");
            $(".all-categories").removeClass("is-open");
            if (!open) {
              $wrap.addClass("is-open");
            }
          });
          $(document).on("click", function(e){
            if (!$(e.target).closest(".all-categories").length) {
              $(".all-categories").removeClass("is-open");
            }
          });

          /* Shop filter accordion — click Categories / Colours / Sizes / Price to close */
          $(document).on("click", ".shop-filter-form .widget-title", function(){
            var $widget = $(this).closest(".sidebar-widget");
            var $body = $widget.find(".sidebar-widget-body");
            $widget.toggleClass("is-closed");
            $body.stop(true, true).slideToggle(200);
          });

          /* Cookie popup js — close once, stay closed in this browser */
          if (!clareDismissed("clare_cookie")) {
            $("#clare-cookie-popup, .cookie-popup").addClass("open");
          } else {
            $("#clare-cookie-popup, .cookie-popup").removeClass("open");
          }
          $(document).on("click", ".cookie-popup .accept-all-btn, .cookie-popup .clare-cookie-close", function(){
            clareDismiss("clare_cookie", $(this).data("clare-cookie") || "1");
            $('.cookie-popup').removeClass("open");
          });

          /* Shop filters apply as soon as a choice is made */
          $(document).on("change", ".shop-filter-form input", function(){
            this.form.submit();
          });
          /* Product inquiry panel — Ask a question */
          function clareAskOpen($panel) {
            if (!$panel.length) return;
            $panel.addClass("is-open");
            $(".clare-ask-toggle").addClass("is-active").attr("aria-expanded", "true");
          }
          function clareAskClose($panel) {
            $panel.removeClass("is-open");
            $(".clare-ask-toggle").removeClass("is-active").attr("aria-expanded", "false");
          }
          $(document).on("click", ".clare-ask-toggle", function(e){
            e.preventDefault();
            var $panel = $("#product-inquiry");
            if ($panel.hasClass("is-open")) {
              clareAskClose($panel);
              return;
            }
            clareAskOpen($panel);
            $panel.get(0).scrollIntoView({ behavior: "smooth", block: "nearest" });
            $panel.find("input[name='name']").trigger("focus");
          });
          $(document).on("click", ".clare-ask-close", function(){
            clareAskClose($("#product-inquiry"));
          });
          if ($(".product-inquiry-ok, .product-inquiry-err").length || window.location.hash === "#product-inquiry") {
            clareAskOpen($("#product-inquiry"));
          }

          $(document).on("change", ".shop-ordering .orderby", function(){
            var form = document.getElementById("shop-filter-form");
            if (!form) {
              this.form.submit();
              return;
            }
            var input = form.querySelector('input[name="orderby"]');
            if (!input) {
              input = document.createElement("input");
              input.type = "hidden";
              input.name = "orderby";
              form.appendChild(input);
            }
            input.value = this.value;
            form.submit();
          });

          /* Header search suggestions — products and blog posts */
          var searchIndex = window.clareSearchIndex || [];
          $(document).on("input", "[data-clare-search]", function(){
            var box = $(this).closest("form").find("[data-clare-suggest]");
            var raw = String(this.value || "").trim();
            var q = raw.toLowerCase();
            if (!box.length || q.length < 1) {
              box.removeClass("open").empty();
              return;
            }
            var hits = searchIndex.filter(function(item){
              var title = String(item.title || "").toLowerCase();
              var excerpt = String(item.excerpt || "").toLowerCase();
              return title.indexOf(q) !== -1 || excerpt.indexOf(q) !== -1;
            }).slice(0, 6);
            var html = hits.map(function(item){
              var meta = item.meta || item.price || (item.type === "blog" ? "Blog" : "");
              return '<li><a href="' + item.url + '">' + item.title + '<span class="meta">' + meta + '</span></a></li>';
            }).join("");
            html += '<li><a href="/blog?q=' + encodeURIComponent(raw) + '">Search blog for “' + raw.replace(/</g, "") + '”<span class="meta">Journal</span></a></li>';
            box.html(html).addClass("open");
          });
          $(document).on("click", function(e){
            if (!$(e.target).closest(".clare-search-wrap").length) {
              $("[data-clare-suggest]").removeClass("open");
            }
          });


          /* mini cart popup js */
          $(document).on("click", ".header-button .cart-icon, .mini-cart-close a", function(){
            $('.mini-cart-dropdown').toggleClass("open");
            $('body').toggleClass("minicart-open");
          });

          /* Filter sidebar popup js */
          $(document).on("click", ".filter-shop-loop .filter-mobile-btn, .sidebar-inner .filter-close", function(){
            $('.sidebar').toggleClass("open");
          });

          
          /* countdown js */
          if ($('.product-countdown').length>0){
            const second = 1000,
                  minute = second * 60,
                  hour = minute * 60,
                  day = hour * 24;

            //I'm adding this section so I don't have to keep updating this pen every year :-)
            //remove this if you don't need it
            let today = new Date(),
                dd = String(today.getDate()).padStart(2, "0"),
                mm = String(today.getMonth() + 1).padStart(2, "0"),
                yyyy = today.getFullYear(),
                nextYear = yyyy + 1,
                dayMonth = "09/30/",
                birthday = dayMonth + yyyy;
            
            today = mm + "/" + dd + "/" + yyyy;
            if (today > birthday) {
              birthday = dayMonth + nextYear;
            }
            //end
            
            const countDown = new Date(birthday).getTime(),
                x = setInterval(function() {    

                  const now = new Date().getTime(),
                        distance = countDown - now;

                  document.getElementById("days").innerText = Math.floor(distance / (day)),
                    document.getElementById("hours").innerText = Math.floor((distance % (day)) / (hour)),
                    document.getElementById("minutes").innerText = Math.floor((distance % (hour)) / (minute)),
                    document.getElementById("seconds").innerText = Math.floor((distance % (minute)) / second);

                  //do something later when date is reached
                  if (distance < 0) {
                    document.getElementById("headline").innerText = "It's my birthday!";
                    document.getElementById("countdown").style.display = "none";
                    document.getElementById("content").style.display = "block";
                    clearInterval(x);
                  }
                  //seconds
                }, 0)
              }

          /* Plus Minus button js */

          var buttonPlus  = $(".quantity .plus");
          var buttonMinus = $(".quantity .minus");

          var incrementPlus = buttonPlus.click(function() {
            var $n = $(this)
            .parent(".quantity")
            .find(".input-qty");
            $n.val(Number($n.val())+1 );
          });

          var incrementMinus = buttonMinus.click(function() {
            var $n = $(this)
            .parent(".quantity")
            .find(".input-qty");
            var amount = Number($n.val());
            if (amount > 1) {
              $n.val(amount-1);
            }
          });

          /* Page scroll */
          $(".scroll a").click(function (event) {
            $('.scroll a').removeClass("active");
            event.preventDefault();
            var full_url = this.href;
            var parts = full_url.split("#");
            var trgt = parts[1];
            var target_offset = $("#" + trgt).offset();
            var target_top = target_offset.top;
            $('html, body').animate({scrollTop: target_top - 100 }, 0);
            $(this).addClass("active");
          });

          /* Search Popup */
          $(document).on("click", ".search-icon, .close-search", function(){
            $('.search-bar').toggleClass("open");
          });

          /* Contact form over AJAX — stay on the page */
          $(document).on("submit", "form.js-clare-contact", function(e){
            e.preventDefault();
            var $form = $(this);
            var $msg = $form.find(".clare-form-msg");
            var $btn = $form.find("button[type='submit']");
            var label = $btn.text();
            $msg.removeClass("is-ok is-err").empty().attr("hidden", true);
            $form.addClass("is-sending");
            $btn.prop("disabled", true).text("Sending…");

            $.ajax({
              url: $form.attr("action"),
              type: "POST",
              data: $form.serialize(),
              headers: { "X-Requested-With": "XMLHttpRequest", "Accept": "application/json" },
              success: function(){
                $msg.addClass("is-ok").text("Message sent. We will get back to you shortly.").removeAttr("hidden");
                $form.find(".form-group").hide();
                $btn.hide();
              },
              error: function(xhr){
                var data = xhr.responseJSON || {};
                var errors = data.errors || [];
                if (!errors.length && data.error) {
                  errors = Object.keys(data.error).map(function(key){ return data.error[key]; });
                }
                $msg.addClass("is-err").text(errors[0] || "Please check your name, email, and message.").removeAttr("hidden");
              },
              complete: function(){
                $form.removeClass("is-sending");
                $btn.prop("disabled", false).text(label);
              }
            });
          });

          /* Cart inquiry over AJAX — email admin with cart lines */
          $(document).on("submit", "form.js-clare-cart-inquiry", function(e){
            e.preventDefault();
            var $form = $(this);
            var $msg = $form.find(".cart-inquiry-msg");
            var $btn = $form.find("button[type='submit']");
            var label = $btn.text();
            $msg.removeClass("is-ok is-err").empty();
            $form.addClass("is-sending");
            $btn.prop("disabled", true).text("Sending…");

            $.ajax({
              url: $form.attr("action"),
              type: "POST",
              data: $form.serialize(),
              headers: { "X-Requested-With": "XMLHttpRequest", "Accept": "application/json" },
              success: function(){
                $msg.addClass("is-ok").text("Inquiry sent. We will reply to this email shortly.");
                $form.find("[name='message']").val("");
              },
              error: function(xhr){
                var data = xhr.responseJSON || {};
                var errors = data.errors || [];
                if (!errors.length && data.error) {
                  errors = Object.keys(data.error).map(function(key){ return data.error[key]; });
                }
                $msg.addClass("is-err").text(errors[0] || "Please check your name, email, and message.");
              },
              complete: function(){
                $form.removeClass("is-sending");
                $btn.prop("disabled", false).text(label);
              }
            });
          });

          /* Newsletter subscribe over AJAX — stay on the page */
          $(document).on("submit", "form.newsletter-form, form.js-clare-subscribe", function(e){
            e.preventDefault();
            var $form = $(this);
            var $wrap = $form.closest(".clare-subscribe");
            var $msg = $wrap.find(".clare-subscribe-msg");
            var $btn = $form.find("button[type='submit']");
            var label = $btn.text();
            $msg.removeClass("is-ok is-err").empty();
            $form.addClass("is-sending");
            $btn.text("Sending…");

            $.ajax({
              url: $form.attr("action"),
              type: "POST",
              data: $form.serialize(),
              headers: { "X-Requested-With": "XMLHttpRequest", "Accept": "application/json" },
              success: function(){
                clareDismiss("clare_newsletter", "1");
                var $popup = $form.closest("#newsletter-popup");
                if ($popup.length) {
                  $popup.find(".clare-popup-copy").hide();
                  $popup.find(".clare-subscribe-success").removeAttr("hidden");
                } else {
                  $msg.addClass("is-ok").text("You're on the list. We'll send catalog notes to this address.");
                  $form[0].reset();
                }
              },
              error: function(xhr){
                var data = xhr.responseJSON || {};
                var errors = data.errors || [];
                if (!errors.length && data.error) {
                  errors = Object.keys(data.error).map(function(key){ return data.error[key]; });
                }
                $msg.addClass("is-err").text(errors[0] || "Please enter a valid email address.");
              },
              complete: function(){
                $form.removeClass("is-sending");
                $btn.text(label);
              }
            });
          });

          $(document).on("click", ".clare-popup-close", function(){
            clareDismiss("clare_newsletter", "1");
            if ($.magnificPopup && $.magnificPopup.instance) {
              $.magnificPopup.close();
            }
          });
          
        },

        

        /*======================================
         02. Slider Open JS
        ========================================*/
      all_slider: function () {

      /*Trending Collection slider*/
      var swiper = new Swiper(".trending-collection-slider .swiper", {
        slidesPerView: 1,
        spaceBetween: 0,
        navigation: {
          nextEl: ".trending-collection-section .swiper-button-next",
          prevEl: ".trending-collection-section .swiper-button-prev",
        },
        breakpoints: {
          640: {
            slidesPerView: 2,
          },
          768: {
            slidesPerView: 3,
          },
          1024: {
            slidesPerView: 4,
          },
        },
      });

      
            /*hero slider*/
      var swiper = new Swiper(".hero-slider-section .swiper", {
        slidesPerView: 1,
        spaceBetween: 0,
        
        pagination: {
          el: ".hero-slider-section .swiper-pagination",
          clickable: true,
        },
      });

            /*Season Collection slider*/
      var swiper = new Swiper(".season-collection-slider .swiper", {
        slidesPerView: 1,
        spaceBetween: 15,
        navigation: {
          nextEl: ".season-collection-section .swiper-button-next",
          prevEl: ".season-collection-section .swiper-button-prev",
        },
        breakpoints: {
          640: {
            slidesPerView: 2,
          },
          768: {
            slidesPerView: 3,
            spaceBetween: 25,
          },
          1024: {
            slidesPerView: 3,
            spaceBetween: 32,
          },
        },
      });

             /*new arrival slider*/
      var swiper = new Swiper(".new-arrival-slider .swiper", {
        slidesPerView: 1,
        spaceBetween: 15,
        navigation: {
          nextEl: ".new-arrival-slider .swiper-button-next",
          prevEl: ".new-arrival-slider .swiper-button-prev",
        },
        breakpoints: {
          640: {
            slidesPerView: 2,
          },
          768: {
            slidesPerView: 3,
            spaceBetween: 20,
          },
          1024: {
            slidesPerView: 4,
            spaceBetween: 20,
          },
        },
      });

            /*testimonial slider*/
      var swiper = new Swiper(".testimonial-slider .mySwiper", {
        spaceBetween: 0,
        slidesPerView: 1,
        effect: "fade",
      });
      var swiper2 = new Swiper(".testimonial-slider .mySwiper2", {
        spaceBetween: 0,
        pagination: {
          el: ".testimonial-slider .swiper-pagination",
          clickable: true,
        },
        thumbs: {
          swiper: swiper,
        },
      });

            /*product gallery vertical*/
      var swiper = new Swiper(".product-gallery-vertical .mySwiper", {
        spaceBetween: 5,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
        direction: "vertical",
      });
      var swiper2 = new Swiper(".product-gallery-vertical .mySwiper2", {
        spaceBetween: 0,
        navigation: {
          nextEl: ".product-gallery-vertical .swiper-button-next",
          prevEl: ".product-gallery-vertical .swiper-button-prev",
        },
        thumbs: {
          swiper: swiper,
        },
      });

            /*product gallery horizontal*/
      var swiper = new Swiper(".product-gallery-horizontal .mySwiper", {
        spaceBetween: 5,
        slidesPerView: 3,
        freeMode: true,
        watchSlidesProgress: true,

        breakpoints: {
          640: {
            slidesPerView: 3,
          },
          768: {
            slidesPerView: 4,
          },
          1024: {
            slidesPerView: 5,
          },
        },
      });
      var swiper2 = new Swiper(".product-gallery-horizontal .mySwiper2", {
        spaceBetween: 0,
        navigation: {
          nextEl: ".product-gallery-horizontal .swiper-button-next",
          prevEl: ".product-gallery-horizontal .swiper-button-prev",
        },
        thumbs: {
          swiper: swiper,
        },
      });

      
    },

    
    /*======================================
     03. Tabs Open JS
    ========================================*/
    tabs_open: function() {

      $('.wc-tabs li, .tab-link-title').click(function(){
        var tab_id = $(this).attr('data-tab');
        $('.wc-tabs li, .tab-link-title').removeClass('active');
        $('.tabs-entry-content').removeClass('active');
        $(this).addClass('active');
        $("#"+tab_id).addClass('active');
      });

    },

    /*======================================
     04. Accordion Open JS
    ========================================*/
    accordion_open: function() {

      $("body").on("click",".accordion .accordion-title",function(){
        $(".accordion-content").slideUp(),
        $(this).hasClass("active")?($(this).next(".accordion-content").slideUp(),
          $(this).removeClass("active")):(
          $(".accordion .accordion-title").removeClass("active"),
          $(this).addClass("active"),
          $(this).next(".accordion-content").slideDown())
        });

    },

    /*======================================
     05. Isotope JS
    ========================================*/
    Isotope_js: function() {
      // init Isotope

      if (typeof $.fn.imagesLoaded !== "function") {
        return;
      }
      $('.marquee-animation').imagesLoaded( function() {
        var $grid_masonary = $('.grid-masonary').isotope({
          itemSelector: '.grid-item',
          masonry: {
            horizontalOrder: false,
          }
        });
      });
      
      
    },

    /*======================================
     06. All popup JS
    ========================================*/
    all_popup_js: function() {
      
      /* Newsletter Popup JS — once closed, cookie keeps it off */
      $('.newsletter-popup-link').magnificPopup({
        type: 'inline',
        preloader: false,
        focus: 'input[name="email"]',
        callbacks: {
          close: function() {
            clareDismiss("clare_newsletter", "1");
          }
        }
      });
      if (!clareDismissed("clare_newsletter")) {
        setTimeout(function() {
          if (clareDismissed("clare_newsletter")) return;
          $('body').find('.newsletter-popup-link').trigger('click');
        }, 2000);
      }

      // product quick view Popup
      $('.quick-view-link').magnificPopup({
        type: 'inline',
        preloader: false,
        focus: '#name',
      });
      // product quick view Popup
      $('.video-play-icon').magnificPopup({
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: false,
      });
      
      
    },

    /*=====================================
    07. Preloader JS
    ======================================*/  
    Preloader_js: function() {
      //After 2s preloader is fadeOut
      $('.preloader').delay(2000).fadeOut('slow');
      setTimeout(function() {
      //After 2s, the no-scroll class of the body will be removed
        $('body').removeClass('no-scroll');
      }, 2000); //Here you can change preloader time
    },

    


  };
  eCommerce.init();

})(jQuery);