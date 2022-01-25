$(document).ready(function(){
indexSlide();
   function indexSlide(){
       $('.index-slider-list').slick({
          slidesToShow: 4,
          slidesToScroll: 1,
          autoplay: true,
          arrows:true,
          autoplaySpeed: 3000,
           responsive: [
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
        autoplay: true,
        arrows:true,
        autoplaySpeed: 3000
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        arrows:true,
        autoplaySpeed: 3000
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
      });
   }
    });