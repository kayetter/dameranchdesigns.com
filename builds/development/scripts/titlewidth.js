(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
        //calculates width of tagline
        function textWidth(text) {
            var calc = '<section class = "title-section" style= "height:40px">' + text + '</section>';
            $('body').append(calc);
            $('body').find('.title-section:last').wrapInner("<p></p>");
            console.log(calc)
            var width = $('body').find('.title-section p:last').width();
            $('body').find('.title-section:last').remove();
            console.log(width);
            $('.nav-elements').css('width', width + 'px');
        }


        //functions called when document loads
        $(document).ready(function() {

            if ($(window).width() < 380) {
                $('.nav-elements').css('width', '75%');
            } else if ($(window).width() > 380 && $(window).width() < 760) {
                textWidth($('#title p').text());
            } else {
                width = $('#title').width();
                $('.nav-elements').css('width', width + 'px');
            }
            console.log($('.nav-elements').width());


            /*on click toggle show hide detail element of blocks*/
            $(".summary").click(function(e){
              e.preventDefault();
              var details=$(this).next('.details'),
              dur=700;
              // details.toggleClass("transition, hidden");
							 details.slideToggle(dur, 'linear',  timeOut);

               function timeOut() {
                 setTimeout(function(){
                   details.slideUp('fast');}, 100000);
                 }
           });

          $('.accordion').accordion({

            collapsible: true,
            classes: {
              "ui-accordion": "accordion-panel",
              "ui-accordion-header": "accordion-header",
              "ui-accordion-header-collapsed": "accordion-header-collapsed",
              "ui-accordion-content": "accordion-content",
              "ui-accordion-content-active": "accordion-content-active",
              "ui-accordion-header-icon": "accordion-header-icon",
              "ui-accordion-icons": "accordion-icons"},
            animate: 300,
            active: 0
              });









        }); /*end of document ready function*/

        //functions called when document resizes
        $(window).resize(function() {
            if ($(window).width() < 380) {
                $('.nav-elements').css('width', '75%');
            } else if ($(window).width() > 380 && $(window).width() < 760) {
                textWidth($('#title p').text(), 'p');
            } else {
                width = $('#title h1').width();
                $('.nav-elements').css('width', width + 'px');
            }
            console.log($('.nav-elements').width());
        });

},{}]},{},[1])