
  (function ($) {
  
  "use strict";

    // MENU
    $('.navbar-collapse a').on('click',function(){
      $(".navbar-collapse").collapse('hide');
    });

    let audio = new Audio('images/audio/tune.mp3');
      $('.navbar-toggler').on('click',function(){
      audio.play();
    });
    // CUSTOM LINK
    $('.smoothscroll').click(function(){
      var el = $(this).attr('href');
      var elWrapped = $(el);
      var header_height = $('.navbar').height();
  
      scrollToDiv(elWrapped,header_height);
      return false;
  
      function scrollToDiv(element,navheight){
        var offset = element.offset();
        var offsetTop = offset.top;
        var totalScroll = offsetTop-navheight;
  
        $('body,html').animate({
        scrollTop: totalScroll
        }, 300);
      }
    });


    //Scroll back to top

var progressPath = document.querySelector('.progress-wrap path');
var pathLength = progressPath.getTotalLength();
progressPath.style.transition = progressPath.style.WebkitTransition = 'none';
progressPath.style.strokeDasharray = pathLength + ' ' + pathLength;
progressPath.style.strokeDashoffset = pathLength;
progressPath.getBoundingClientRect();
progressPath.style.transition = progressPath.style.WebkitTransition = 'stroke-dashoffset 10ms linear';
var updateProgress = function () {
  var scroll = $(window).scrollTop();
  var height = $(document).height() - $(window).height();
  var progress = pathLength - (scroll * pathLength / height);
  progressPath.style.strokeDashoffset = progress;
}
updateProgress();
$(window).scroll(updateProgress);
var offset = 50;
var duration = 550;
jQuery(window).on('scroll', function() {
  if (jQuery(this).scrollTop() > offset) {
    jQuery('.progress-wrap').addClass('active-progress');
    $(".navbar-collapse").collapse('hide');
  } else {
    jQuery('.progress-wrap').removeClass('active-progress');
  }
});
jQuery('.progress-wrap').on('click', function(event) {
  event.preventDefault();
  jQuery('html, body').animate({scrollTop: 0}, duration);
  return false;
})
  
$(window).on("scroll", function() {
  console.log($(this).scrollTop())
  if($(this).scrollTop() >= 1980){
    // set to new image
    $("#tab-1 img").attr("src","images/robote-3.png");
  } else {
    //back to default
    $("#tab-1 img").attr("src","images/robot2.png");
  }
})

  })(window.jQuery);


  
 /**
   * Animation on scroll
   */
 window.addEventListener('load', () => {
  AOS.init({
    duration: 1000,
    easing: "ease-in-out",
    once: true,
    mirror: false
  });
});


  // scroll indicator

const indicator = document.querySelector('.indicator-scroll');

window.addEventListener('scroll', () =>{
    
 const {scrollTop, clientHeight, scrollHeight} = document.documentElement;

  const scrolled = ( scrollTop / (scrollHeight - clientHeight) * 100);

  indicator.style.width = `${scrolled}%`;
});






    // cursor 

    
    const updateProperties = (elem, state) => {

      elem.style.setProperty('--x', `${ state.x }px`)
      elem.style.setProperty('--y', `${ state.y }px`)
      elem.style.setProperty('--width', `${ state.width }px`)
      elem.style.setProperty('--height', `${ state.height }px`)
      elem.style.setProperty('--radius', state.radius)
      elem.style.setProperty('--scale', state.scale)
    
    }
    
    document.querySelectorAll('.cursor').forEach((cursor) => {
    
      let onElement
    
      const createState = (e) => {
        
        const defaultState = {
          x: e.clientX,
          y: e.clientY,
          width: 42,
          height: 42,
          radius: '100px'
        }
    
        const computedState = {}
        
        if (onElement != null) {
          const { top, left, width, height } = onElement.getBoundingClientRect()
          const radius = window.getComputedStyle(onElement).borderTopLeftRadius
          
          computedState.x = left + width / 2
          computedState.y = top + height / 2
          computedState.width = width
          computedState.height = height
          computedState.radius = radius
        }
    
        return {
          ...defaultState,
          ...computedState
        }
    
      }
    
      document.addEventListener('mousemove', (e) => {
        const state = createState(e)
        updateProperties(cursor, state)
      })
    
        document.querySelectorAll('.btn, .btn-watch-video i, .contact-form-body input, form textarea, .progress-wrap, .submitbtn, .nav-tabs button, .input , .custom-btn, .button-confirm').forEach((elem) => {
        elem.addEventListener('mouseenter', () => onElement = elem)
        elem.addEventListener('mouseleave', () => onElement = undefined)
      })
    
    })

 

