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
                   details.slideUp('fast');}, 1000000);
                 }
           });

          $('.accordion').accordion({
            active: false,
            heightStyle: "content",
            header: "h3",
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
              });

              // init controller
              var ctrl = new ScrollMagic.Controller({globalSceneOptions: {triggerHook: "onLeave"}});

              $(".section").each(function() {

                new ScrollMagic.Scene({
                  triggerElement: this
                  })
                  .setPin(this)
                  .addIndicators()
                  .addTo(ctrl);

                });

              // // build scenes
              // new ScrollMagic.Scene({triggerElement: ".section"})
              //         .setTween("#parallax1 article", {y: "80%", ease: Linear.easeNone})
              //         .addIndicators()
              //         .addTo(controller);

              // new ScrollMagic.Scene({triggerElement: "#parallax2"})
              //         .setTween("#parallax2 > div", {y: "80%", ease: Linear.easeNone})
              //         .addIndicators()
              //         .addTo(controller);
              //
              // new ScrollMagic.Scene({triggerElement: "#parallax3"})
              //         .setTween("#parallax3 > div", {y: "80%", ease: Linear.easeNone})
              //         .addIndicators()
              //         .addTo(controller);








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