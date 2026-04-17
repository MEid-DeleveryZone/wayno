!(function (e) {
    "use strict";
    if (
        (e(window).on("load", function () {
            setTimeout(function () {
                e(".loader_skeleton").fadeOut("slow"), e("body").css({ overflow: "" });
            }, 500),
                e(".loader_skeleton").remove("slow");
        }),
            e("#preloader").fadeOut("slow", function () {
                e(this).remove();
            }),
            e(window).on("scroll", function () {
                e(this).scrollTop() > 600 ? e(".tap-top").fadeIn() : e(".tap-top").fadeOut();
            }),
            e(".tap-top").on("click", function () {
                return e("html, body").animate({ scrollTop: 0 }, 600), !1;
            }),
            e(window).on("load", function () {
                e("#ageModal").modal("show");
            }),
            e(window).width() > "1200" &&
            e("#hover-cls").hover(function () {
                e(".sm").addClass("hover-unset");
            }),
            e(window).width() > "1200" &&
            e("#sub-menu > li").hover(
                function () {
                    e(this).children().hasClass("has-submenu") && e(this).parents().find("nav").addClass("sidebar-unset");
                },
                function () {
                    e(this).parents().find("nav").removeClass("sidebar-unset");
                }
            ),
            e(".bg-top").parent().addClass("b-top"),
            e(".bg-bottom").parent().addClass("b-bottom"),
            e(".bg-center").parent().addClass("b-center"),
            e(".bg_size_content").parent().addClass("b_size_content"),
            e(".bg-img").parent().addClass("bg-size"),
            e(".bg-img.blur-up").parent().addClass("blur-up lazyload"),
            jQuery(".bg-img").each(function () {
                var s = e(this),
                    i = s.attr("src");
                s.parent().css({ "background-image": "url(" + i + ")", "background-size": "cover", "background-position": "center", display: "block" }), s.hide();
            }),
            e(".filter-button").click(function () {
                e(this).addClass("active").siblings(".active").removeClass("active");
                var s = e(this).attr("data-filter");
                "all" == s
                    ? e(".filter").show("1000")
                    : (e(".filter")
                        .not("." + s)
                        .hide("3000"),
                        e(".filter")
                            .filter("." + s)
                            .show("3000"));
            }),
            e("#formButton").click(function () {
                e("#form1").toggle();
            }),
            e(".heading-right h3").click(function () {
                e(".offer-box").toggleClass("toggle-cls");
            }),
            e(".toggle-nav").on("click", function () {
                e(".sm-horizontal").css("right", "0px");
            }),
            e(".mobile-back").on("click", function () {
                e(".sm-horizontal").css("right", "-410px");
            }),
            jQuery(window).width() < "750"
                ? (jQuery(".footer-title h4").append('<span class="according-menu"></span>'),
                    jQuery(".footer-title").on("click", function () {
                        jQuery(".footer-title").removeClass("active"), jQuery(".footer-contant").slideUp("normal"), 1 == jQuery(this).next().is(":hidden") && (jQuery(this).addClass("active"), jQuery(this).next().slideDown("normal"));
                    }),
                    jQuery(".footer-contant").hide())
                : jQuery(".footer-contant").show(),
            e(window).width() < "1183"
                ? (jQuery(".menu-title h5").append('<span class="according-menu"></span>'),
                    jQuery(".menu-title").on("click", function () {
                        jQuery(".menu-title").removeClass("active"), jQuery(".menu-content").slideUp("normal"), 1 == jQuery(this).next().is(":hidden") && (jQuery(this).addClass("active"), jQuery(this).next().slideDown("normal"));
                    }),
                    jQuery(".menu-content").hide())
                : jQuery(".menu-content").show(),
            e("button.add-button").click(function () {
                e(this).next().addClass("open"), e(".qty-input").val("1");
            }),
            e(".quantity-right-plus").on("click", function () {
                var s = e(this).siblings(".qty-input"),
                    i = parseInt(s.val());
                isNaN(i) || s.val(i + 1);
            }),
            e(".quantity-left-minus").on("click", function () {
                var s = e(this).siblings(".qty-input");
                if ("1" == e(s).val()) {
                    var i = e(this).parents(".cart_qty");
                    e(i).removeClass("open");
                }
                var o = parseInt(s.val());
                !isNaN(o) && o > 0 && s.val(o - 1);
            }),
            e(".collection-wrapper .qty-box .quantity-right-plus").on("click", function () {
                var s = e(".qty-box .input-number"),
                    i = parseInt(s.val(), 10);
                isNaN(i) || s.val(i + 1);
            }),
            e(".collection-wrapper .qty-box .quantity-left-minus").on("click", function () {
                var s = e(".qty-box .input-number"),
                    i = parseInt(s.val(), 10);
                !isNaN(i) && i > 1 && s.val(i - 1);
            }),
            e(window).width() > 767)
    ) {
        function s(s) {
            e(window).on("wheel", { $slider: s }, i);
        }
        function i(e) {
            e.preventDefault();
            var s = e.data.$slider;
            e.originalEvent.deltaY > 0 ? s.slick("slickNext") : s.slick("slickPrev");
        }
        (o = e(".full-slider"))
            .on("init", function () {
                s(o);
            })
            .slick({ dots: !0, nav: !1, vertical: !0, infinite: !1 });
    } else {
        var o;
        function s(s) {
            e(window).on("wheel", { $slider: s }, i);
        }
        function i(e) {
            e.preventDefault();
            var s = e.data.$slider;
            e.originalEvent.deltaY > 0 ? s.slick("slickNext") : s.slick("slickPrev");
        }
        (o = e(".full-slider"))
            .on("init", function () {
                s(o);
            })
            .slick({ dots: !0, nav: !1, vertical: !1, infinite: !1 });
    }
        if($('body').attr('dir') == 'rtl'){
            e(".slide-1").slick({rtl: true, autoplay: true, autoplaySpeed: 3000});
        }else{
            e(".slide-1").slick({autoplay: true, autoplaySpeed: 3000});
        }
        e(".slide-2").slick({ infinite: !0, slidesToShow: 2, slidesToScroll: 2, responsive: [{ breakpoint: 991, settings: { slidesToShow: 1, slidesToScroll: 1 } }] }),
        e(".slide-3").slick({
            infinite: !0,
            speed: 300,
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: !0,
            autoplaySpeed: 5e3,
            responsive: [
                { breakpoint: 1200, settings: { slidesToShow: 2, slidesToScroll: 2 } },
                { breakpoint: 767, settings: { slidesToShow: 1, slidesToScroll: 1 } },
            ],
        }),
        e(".team-4").slick({
            infinite: !0,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: !0,
            autoplaySpeed: 3e3,
            responsive: [
                { breakpoint: 1200, settings: { slidesToShow: 3, slidesToScroll: 3 } },
                { breakpoint: 991, settings: { slidesToShow: 2, slidesToScroll: 2 } },
                { breakpoint: 586, settings: { slidesToShow: 2, slidesToScroll: 1 } },
            ],
        }),
        e(".slide-4").slick({
            infinite: !1,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: !0,
            autoplaySpeed: 5e3,
            responsive: [
                { breakpoint: 1200, settings: { slidesToShow: 3, slidesToScroll: 3 } },
                { breakpoint: 991, settings: { slidesToShow: 2, slidesToScroll: 2 } },
                { breakpoint: 586, settings: { slidesToShow: 1, slidesToScroll: 1 } },
            ],
        }),
        e(".product-4").slick({
            infinite: !0,
            speed: 300,
            slidesToShow: 5,
            slidesToScroll: 1,
            autoplay: !0,
            autoplaySpeed: 3e3,
            responsive: [
                { breakpoint: 1200, settings: { slidesToShow: 3, slidesToScroll: 3 } },
                { breakpoint:  991, settings: { slidesToShow: 2, slidesToScroll: 2 } },
                { breakpoint:  400, settings: { slidesToShow: 1, slidesToScroll: 2 } },
            ],
        }),
        e(".recent-orders").slick({
            infinite: !0,
            speed: 300,
            slidesToShow: 2.1,
            slidesToScroll: 2,
            autoplay: !0,
            autoplaySpeed: 3e3,
            responsive: [
                { breakpoint: 1200, settings: { slidesToShow: 3, slidesToScroll: 3 } },
                { breakpoint:  991, settings: { slidesToShow: 2, slidesToScroll: 2 } },
                { breakpoint:  400, settings: { slidesToShow: 1, slidesToScroll: 2 } },
            ],
        }),
        e('.suppliers-slider').slick({
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: 5,
            slidesToScroll: 1,
            arrows: false,
            dots: false,
            responsive: [
              {
                breakpoint: 991,
                settings: {
                  slidesToShow: 3,
                  slidesToScroll: 1,
                  infinite: true,
                  dots: false
                }
              },
              {
                breakpoint: 767,
                settings: {
                  slidesToShow: 2,
                  slidesToScroll: 1,
                  dots: false
                }
              },
              {
                breakpoint: 480,
                settings: {
                  slidesToShow:1,
                  slidesToScroll: 1,
                  dots: false
                }
              }
            ]
        });
        e(".brand-slider").slick({
            infinite: !0,
            speed: 300,
            slidesToShow: 4,
            arrows:false,
            slidesToScroll: 1,
            autoplay: !0,
            autoplaySpeed: 3e3,
            responsive: [
                {
                  breakpoint: 1367,
                  settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    arrows: false,
                    infinite: true
                  }
                },
                {
                  breakpoint: 991,
                  settings: {
                    slidesToShow: 2,
                    arrows: false,
                    slidesToScroll: 1 
                  }
                },
                {
                  breakpoint: 767,
                  settings: {
                    slidesToShow: 2,
                    arrows: false,
                    slidesToScroll: 1
                  }
                },
                {
                  breakpoint: 480,
                  settings: {
                    slidesToShow: 1,
                    arrows:false,
                    slidesToScroll: 1
                  }
                }
              ],
        }),
        e(".tools-product-4").slick({
            infinite: !0,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 4,
            autoplay: !0,
            autoplaySpeed: 5e3,
            responsive: [
                { breakpoint: 1200, settings: { slidesToShow: 3, slidesToScroll: 3 } },
                { breakpoint: 767, settings: { slidesToShow: 2, slidesToScroll: 2 } },
            ],
        }),
        e(".product_4").slick({
            infinite: !0,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 4,
            autoplay: !0,
            autoplaySpeed: 5e3,
            responsive: [
                { breakpoint: 1430, settings: { slidesToShow: 3, slidesToScroll: 3 } },
                { breakpoint: 1200, settings: { slidesToShow: 2, slidesToScroll: 2 } },
                { breakpoint: 991, settings: { slidesToShow: 3, slidesToScroll: 3 } },
                { breakpoint: 768, settings: { slidesToShow: 2, slidesToScroll: 2 } },
            ],
        }),
        e(".product-3").slick({ infinite: !0, speed: 300, slidesToShow: 3, slidesToScroll: 3, autoplay: !0, autoplaySpeed: 5e3, responsive: [{ breakpoint: 991, settings: { slidesToShow: 2, slidesToScroll: 2 } }] }),
        e(".slide-5").slick({
            dots: !1,
            infinite: !0,
            speed: 300,
            slidesToShow: 5,
            slidesToScroll: 5,
            responsive: [
                { breakpoint: 1367, settings: { slidesToShow: 4, slidesToScroll: 4 } },
                { breakpoint: 1024, settings: { slidesToShow: 3, slidesToScroll: 3, infinite: !0 } },
                { breakpoint: 600, settings: { slidesToShow: 3, slidesToScroll: 3 } },
                { breakpoint: 480, settings: { slidesToShow: 3, slidesToScroll: 3 } },
            ],
        }),
        e(".slide-6").slick({
            dots: !1,
            infinite: !0,
            speed: 300,
            slidesToShow: 8,
            slidesToScroll: 6,
            responsive: [
                { breakpoint: 1367, settings: { slidesToShow: 5, slidesToScroll: 5, infinite: !0 } },
                { breakpoint: 1024, settings: { slidesToShow: 4, slidesToScroll: 4, infinite: !0 } },
                { breakpoint: 767, settings: { slidesToShow: 3, slidesToScroll: 3, infinite: !0 } },
                { breakpoint: 480, settings: { slidesToShow: 2, slidesToScroll: 2 } },
            ],
        }),
        e(".brand-6").slick({
            dots: !1,
            infinite: !0,
            speed: 300,
            slidesToShow: 6,
            slidesToScroll: 6,
            responsive: [
                { breakpoint: 1367, settings: { slidesToShow: 5, slidesToScroll: 5, infinite: !0 } },
                { breakpoint: 1024, settings: { slidesToShow: 4, slidesToScroll: 4, infinite: !0 } },
                { breakpoint: 767, settings: { slidesToShow: 3, slidesToScroll: 3, infinite: !0 } },
                { breakpoint: 480, settings: { slidesToShow: 2, slidesToScroll: 2 } },
                { breakpoint: 360, settings: { slidesToShow: 1, slidesToScroll: 1 } },
            ],
        }),
        e(".product-slider-5").slick({
            dots: !1,
            infinite: !0,
            speed: 300,
            slidesToShow: 5,
            slidesToScroll: 5,
            responsive: [
                { breakpoint: 1200, settings: { slidesToShow: 3, slidesToScroll: 3 } },
                { breakpoint: 991, settings: { slidesToShow: 2, slidesToScroll: 2 } },
            ],
        }),
        e(".product-5").slick({
            dots: !1,
            infinite: !0,
            speed: 300,
            slidesToShow: 5,
            slidesToScroll: 5,
            responsive: [
                { breakpoint: 1367, settings: { slidesToShow: 4, slidesToScroll: 4 } },
                { breakpoint: 1024, settings: { slidesToShow: 3, slidesToScroll: 3, infinite: !0 } },
                { breakpoint: 768, settings: { slidesToShow: 2, slidesToScroll: 2 } },
            ],
        }),
        e(".slide-7").slick({
            dots: !1,
            infinite: !0,
            speed: 300,
            slidesToShow: 7,
            slidesToScroll: 7,
            responsive: [
                { breakpoint: 1367, settings: { slidesToShow: 6, slidesToScroll: 6 } },
                { breakpoint: 1024, settings: { slidesToShow: 5, slidesToScroll: 5, infinite: !0 } },
                { breakpoint: 600, settings: { slidesToShow: 4, slidesToScroll: 4 } },
                { breakpoint: 480, settings: { slidesToShow: 3, slidesToScroll: 3 } },
            ],
        }),
        e(".slide-8").slick({ infinite: !0, slidesToShow: 8, slidesToScroll: 8, responsive: [{ breakpoint: 1200, settings: { slidesToShow: 6, slidesToScroll: 6 } }] }),
        e(".center").slick({
            centerMode: !0,
            centerPadding: "60px",
            slidesToShow: 3,
            responsive: [
                { breakpoint: 768, settings: { arrows: !1, centerMode: !0, centerPadding: "40px", slidesToShow: 3 } },
                { breakpoint: 480, settings: { arrows: !1, centerMode: !0, centerPadding: "40px", slidesToShow: 1 } },
            ],
        }),
        e(".product-slick").slick({ slidesToShow: 1, slidesToScroll: 1, arrows: !0, fade: !0, asNavFor: ".slider-nav" }),
        e(".slider-nav").slick({ vertical: !1, slidesToShow: 3, slidesToScroll: 1, asNavFor: ".product-slick", arrows: !1, dots: !1, focusOnSelect: !0 }),
        e(".product-right-slick").slick({ slidesToShow: 1, slidesToScroll: 1, arrows: !0, fade: !0, asNavFor: ".slider-right-nav" }),
        e(window).width() > 575
            ? e(".slider-right-nav").slick({ vertical: !0, verticalSwiping: !0, slidesToShow: 3, slidesToScroll: 1, asNavFor: ".product-right-slick", arrows: !1, infinite: !0, dots: !1, centerMode: !1, focusOnSelect: !0 })
            : e(".slider-right-nav").slick({
                vertical: !1,
                verticalSwiping: !1,
                slidesToShow: 3,
                slidesToScroll: 1,
                asNavFor: ".product-right-slick",
                arrows: !1,
                infinite: !0,
                centerMode: !1,
                dots: !1,
                focusOnSelect: !0,
                responsive: [{ breakpoint: 576, settings: { slidesToShow: 3, slidesToScroll: 1 } }],
            }),
        e(window).width() < 1199 &&
        (e(".header-2 .navbar .sidebar-bar, .header-2 .navbar .sidebar-back, .header-2 .mobile-search img").on("click", function () {
            e("#mySidenav").hasClass("open-side") ? e(".header-2 #main-nav .toggle-nav").css("z-index", "99") : e(".header-2 #main-nav .toggle-nav").css("z-index", "1");
        }),
            e(".sidebar-overlay").on("click", function () {
                e(".header-2 #main-nav .toggle-nav").css("z-index", "99");
            }),
            e(".header-2 #search-overlay .closebtn").on("click", function () {
                e(".header-2 #main-nav .toggle-nav").css("z-index", "99");
            }),
            e(".layout3-menu .mobile-search .ti-search, .header-2 .mobile-search .ti-search").on("click", function () {
                e(".layout3-menu #main-nav .toggle-nav, .header-2 #main-nav .toggle-nav").css("z-index", "1");
            })),
        e("#tab-1").css("display", "Block"),
        e(".default").css("display", "Block"),
        e(".tabs li a").on("click", function () {
            event.preventDefault(), e(".tab_product_slider").slick("unslick"), e(".product-4").slick("unslick"), e(this).parent().parent().find("li").removeClass("current"), e(this).parent().addClass("current");
            var s = e(this).attr("href");
            e("#" + s).show(),
                e(this)
                    .parent()
                    .parent()
                    .parent()
                    .find(".tab-content")
                    .not("#" + s)
                    .css("display", "none"),
                e(".product-4").slick({
                    arrows: !0,
                    dots: !1,
                    infinite: !1,
                    speed: 300,
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    responsive: [
                        { breakpoint: 1200, settings: { slidesToShow: 3, slidesToScroll: 3 } },
                        { breakpoint: 991, settings: { slidesToShow: 2, slidesToScroll: 2 } },
                        { breakpoint: 420, settings: { slidesToShow: 1, slidesToScroll: 1 } },
                    ],
                });
        }),
        e(".tabs li a").on("click", function () {
            event.preventDefault(), e(".tab_product_slider").slick("unslick"), e(".product-5").slick("unslick"), e(this).parent().parent().find("li").removeClass("current"), e(this).parent().addClass("current");
            var s = e(this).attr("href");
            e("#" + s).show(),
                e(this)
                    .parent()
                    .parent()
                    .parent()
                    .find(".tab-content")
                    .not("#" + s)
                    .css("display", "none"),
                e(".product-5").slick({
                    arrows: !0,
                    dots: !1,
                    infinite: !1,
                    speed: 300,
                    slidesToShow: 5,
                    slidesToScroll: 1,
                    responsive: [
                        { breakpoint: 1367, settings: { slidesToShow: 4, slidesToScroll: 4 } },
                        { breakpoint: 1024, settings: { slidesToShow: 3, slidesToScroll: 3, infinite: !0 } },
                        { breakpoint: 768, settings: { slidesToShow: 2, slidesToScroll: 2 } },
                        { breakpoint: 576, settings: { slidesToShow: 1, slidesToScroll: 1 } },
                    ],
                });
        }),
        e("#tab-1").css("display", "Block"),
        e(".default").css("display", "Block"),
        e(".tabs li a").on("click", function () {
            event.preventDefault(), e(".tab_product_slider").slick("unslick"), e(".product-3").slick("unslick"), e(this).parent().parent().find("li").removeClass("current"), e(this).parent().addClass("current");
            var s = e(this).attr("href");
            e("#" + s).show(),
                e(this)
                    .parent()
                    .parent()
                    .parent()
                    .parent()
                    .find(".tab-content")
                    .not("#" + s)
                    .css("display", "none"),
                e(".product-3").slick({ arrows: !0, dots: !1, infinite: !1, speed: 300, slidesToShow: 3, slidesToScroll: 1, responsive: [{ breakpoint: 991, settings: { slidesToShow: 2, slidesToScroll: 2 } }] });
        }),
        e(".collapse-block-title").on("click", function (s) {
            s.preventDefault;
            var i = e(this).parent(),
                o = e(this).next(".collection-collapse-block-content");
            i.hasClass("open") ? (i.removeClass("open"), o.slideUp(300)) : (i.addClass("open"), o.slideDown(300));
        }),
        e(".color-selector ul li").on("click", function (s) {
            e(".color-selector ul li").removeClass("active"), e(this).addClass("active");
        }),
        e(".list-layout-view").on("click", function (s) {
            e(".collection-grid-view").css("opacity", "0"),
                e(".product-wrapper-grid").css("opacity", "0.2"),
                e(".shop-cart-ajax-loader").css("display", "block"),
                e(".product-wrapper-grid").addClass("list-view"),
                e(".product-wrapper-grid").children().children().removeClass(),
                e(".product-wrapper-grid").children().children().addClass("col-lg-12"),
                setTimeout(function () {
                    e(".product-wrapper-grid").css("opacity", "1"), e(".shop-cart-ajax-loader").css("display", "none");
                }, 500);
        }),
        e(".grid-layout-view").on("click", function (s) {
            e(".collection-grid-view").css("opacity", "1"),
                e(".product-wrapper-grid").removeClass("list-view"),
                e(".product-wrapper-grid").children().children().removeClass(),
                e(".product-wrapper-grid").children().children().addClass("col-lg-3");
        }),
        e(".product-2-layout-view").on("click", function (s) {
            e(".product-wrapper-grid").hasClass("list-view") || (e(".product-wrapper-grid").children().children().removeClass(), e(".product-wrapper-grid").children().children().addClass("col-lg-6"));
        }),
        e(".product-3-layout-view").on("click", function (s) {
            e(".product-wrapper-grid").hasClass("list-view") || (e(".product-wrapper-grid").children().children().removeClass(), e(".product-wrapper-grid").children().children().addClass("col-lg-4"));
        }),
        e(".product-4-layout-view").on("click", function (s) {
            e(".product-wrapper-grid").hasClass("list-view") || (e(".product-wrapper-grid").children().children().removeClass(), e(".product-wrapper-grid").children().children().addClass("col-lg-3"));
        }),
        e(".product-6-layout-view").on("click", function (s) {
            e(".product-wrapper-grid").hasClass("list-view") || (e(".product-wrapper-grid").children().children().removeClass(), e(".product-wrapper-grid").children().children().addClass("col-lg-2"));
        }),
        e(".sidebar-popup").on("click", function (s) {
            e(".open-popup").toggleClass("open"), e(".collection-filter").css("left", "-15px");
        }),
        e(".filter-btn").on("click", function (s) {
            e(".collection-filter").css("left", "-15px");
        }),
        e(".filter-back").on("click", function (s) {
            e(".collection-filter").css("left", "-365px"), e(".sidebar-popup").trigger("click");
        }),
        e(".account-sidebar").on("click", function (s) {
            e(".dashboard-left").css("left", "0");
        }),
        e(".filter-back").on("click", function (s) {
            e(".dashboard-left").css("left", "-365px");
        }),
        e(function () {
            e(".product-load-more .col-grid-box").slice(0, 8).show(),
                e(".loadMore").on("click", function (s) {
                    s.preventDefault(), e(".product-load-more .col-grid-box:hidden").slice(0, 4).slideDown(), 0 === e(".product-load-more .col-grid-box:hidden").length && e(".loadMore").text("no more products");
                });
        }),
        e(".product-box button .ti-shopping-cart").on("click", function () {
            e.notify(
                { icon: "fa fa-check", title: "Success!", message: "Item Successfully added to your cart" },
                {
                    element: "body",
                    position: null,
                    type: "success",
                    allow_dismiss: !0,
                    newest_on_top: !1,
                    showProgressbar: !0,
                    placement: { from: "top", align: "right" },
                    offset: 20,
                    spacing: 10,
                    z_index: 1031,
                    delay: 5e3,
                    animate: { enter: "animated fadeInDown", exit: "animated fadeOutUp" },
                    icon_type: "class",
                    template:
                        '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert"><button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button><span data-notify="icon"></span> <span data-notify="title">{1}</span> <span data-notify="message">{2}</span><div class="progress" data-notify="progressbar"><div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div><a href="{3}" target="{4}" data-notify="url"></a></div>',
                }
            );
        }),
        e(".product-box a .ti-heart , .product-box a .fa-heart").on("click", function () {
            e.notify(
                { icon: "fa fa-check", title: "Success!", message: "Item Successfully added in wishlist" },
                {
                    element: "body",
                    position: null,
                    type: "info",
                    allow_dismiss: !0,
                    newest_on_top: !1,
                    showProgressbar: !0,
                    placement: { from: "top", align: "right" },
                    offset: 20,
                    spacing: 10,
                    z_index: 1031,
                    delay: 5e3,
                    animate: { enter: "animated fadeInDown", exit: "animated fadeOutUp" },
                    icon_type: "class",
                    template:
                        '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert"><button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button><span data-notify="icon"></span> <span data-notify="title">{1}</span> <span data-notify="message">{2}</span><div class="progress" data-notify="progressbar"><div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div><a href="{3}" target="{4}" data-notify="url"></a></div>',
                }
            );
        });
})(jQuery),
    $("#ltr_btn").click(function () {
        $("body").addClass("ltr"), $("body").removeClass("rtl");
    }),
    $("#rtl_btn").click(function () {
        $("body").addClass("rtl"), $("body").removeClass("ltr");
    }),
    $(".setting_buttons li").click(function () {
        $(this).addClass("active").siblings().removeClass("active");
    }),
    $(".color-box li").click(function () {
        $(this).addClass("active").siblings().removeClass("active");
    })
    
var body_event = $("body");
function openNav() {
    document.getElementById("mySidenav").classList.add("open-side");
}
function closeNav() {
    document.getElementById("mySidenav").classList.remove("open-side");
}
function openSetting() {
    document.getElementById("setting_box").classList.add("open-setting"), document.getElementById("setting-icon").classList.add("open-icon");
}
function closeSetting() {
    document.getElementById("setting_box").classList.remove("open-setting"), document.getElementById("setting-icon").classList.remove("open-icon");
}
function openCart() {
    document.getElementById("cart_side").classList.add("open-side");
}
function closeCart() {
    document.getElementById("cart_side").classList.remove("open-side");
}
body_event.on("click", ".theme-layout-version", function () {
    if ($(".theme-layout-version").text() == 'Dark') {
        localStorage['theme_color'] = 'dark';
        $("body").addClass("dark"),
        window.sessionStorage.setItem("theme", "dark");
        $(".theme-layout-version").text("Light");
    } else {
        localStorage['theme_color'] = '';
        $("body").removeClass("dark"),
        window.sessionStorage.setItem("theme", "light");
        $(".theme-layout-version").text("Dark");
    }
    $.ajax({
        url: url1,
        type: "POST",
        dataType: 'json',
        data: { 'theme_color': localStorage['theme_color'] },
        success: function (data) {
        }
    });
    // return (
    //     // $(this).toggleClass("dark"),
    //     $(".theme-layout-version").hasClass("dark") ? ($(".theme-layout-version").text("Light"), $("body").addClass("dark"), localStorage['theme_color'] = 'dark') :
    //         ($("#theme-dark").remove(), $(".theme-layout-version").text("Dark")),
    //     !1
    // );
}),
    $(function () {
        $("#main-menu").smartmenus({ subMenusSubOffsetX: 1, subMenusSubOffsetY: -8 }), $("#sub-menu").smartmenus({ subMenusSubOffsetX: 1, subMenusSubOffsetY: -8 });
    }),
    jQuery(".setting-title h4").append('<span class="according-menu"></span>'),
    jQuery(".setting-title").on("click", function () {
        jQuery(".setting-title").removeClass("active"), jQuery(".setting-contant").slideUp("normal"), 1 == jQuery(this).next().is(":hidden") && (jQuery(this).addClass("active"), jQuery(this).next().slideDown("normal"));
    }),
    jQuery(".setting-contant").hide(),

    $(window).on("load", function () {
        $('[data-toggle="tooltip"]').tooltip();
        if(localStorage['theme_color'] == "dark"){
            $(".theme-layout-version").text("Light");
        }
        $.ajax({
            url: url2,
            type: "GET",
            dataType: 'json',
            // data: { 'theme_color': localStorage['theme_color'] },
            success: function (data) {
                if(data.client_preferences.show_dark_mode == 2){
                    if(localStorage['theme_color'] == 'dark'){
                        $('<div class="sidebar-btn dark-light-btn" id="dark-light-btn-toggle"><div class="dark-light"><div class="theme-layout-version">Light</div></div></div>').appendTo($("body"));
                    }
                    else{
                        $('<div class="sidebar-btn dark-light-btn" id="dark-light-btn-toggle"><div class="dark-light"><div class="theme-layout-version">Dark</div></div></div>').appendTo($("body"));
                    }
                    $("#dark-light-btn-toggle").removeClass('d-none');
                }
                else{
                    // localStorage['theme_color'] = '';
                    // $("body").removeClass("dark"),
                    // window.sessionStorage.setItem("theme", "light");
                    // $(".theme-layout-version").text("Dark");
                    $("#dark-light-btn-toggle").addClass('d-none');
                }
            }
        });
    });
