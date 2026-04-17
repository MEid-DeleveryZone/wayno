

jQuery('.home-banner-slider').slick({
    infinite: true,
    slidesToShow: 1,
    autoplay: true,
    slidesToScroll: 1,
    responsive: [
        {
          breakpoint: 1200,
          settings: {
            arrows: false,
            dots: false
          }
        }
    ]
});      

$('.newly-arrived-slider').slick({
    dots: true,
    infinite: true,
    speed: 300,
    slidesToShow: 6,
    slidesToScroll: 2,
    arrows: false,
    dots: false,
    responsive: [
      {
        breakpoint: 1200,
        settings: {
          slidesToShow: 4,
          slidesToScroll: 1,
          infinite: true,
          dots: false
        }
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1
        }
      }
    ]
});

if($('body').attr('dir') == 'rtl'){
  $(".home-banner-slider").slick('slickSetOption', {rtl: true, autoplay: true, autoplaySpeed: 3000}, true);
}
if($('body').attr('dir') == 'rtl'){
  $(".newly-arrived-slider").slick('slickSetOption', {rtl: true}, true);
}

