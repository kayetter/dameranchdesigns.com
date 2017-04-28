        //calculates width of tagline
        function textWidth(text) {
            var calc = '<section class = "title-section" style= "height:40px">' + text + '</section>';
            $('body').append(calc);
            $('body').find('.title-section:last').wrapInner("<p></p>");
            var width = $('body').find('.title-section p:last').width();
            $('body').find('.title-section:last').remove();
            $('.nav-elements').css('width', width + 'px');
        }




        //functions called when document loads
        $(document).ready(function() {
          winWidth = $(window).width();
           if (winWidth < 380) {
                $('.nav-elements').css('width', '75%');
            } else if (winWidth > 380 && winWidth < 760) {
                textWidth($('#title p').text());
            } else {
                width = $('#title').width();
                $('.nav-elements').css('width', width + 'px');
            }

            if (winWidth < 600) {
                $('.day').css('width', winWidth);
            }

              //gallery slide modal
            $('#bmb-slide').click(function(){
              $('#bmb-gallery-article').appendTo("body").modal({fadeDuration: 300, fadeDelay: 1.5});
            });



// ============================scroll magic controller and scrolling design and animation=======================
            // init controller
            var controller = new ScrollMagic.Controller({});

            //calculate window height - height of #title-logo
            var titleHt = $('#title-section').height();
            var logo2x = $('#title-logo').height() * 2;
            var fallHt = titleHt;

            //tweenMax timeline for logo rotate, scale and bounce out
            var tweenLogo = new TimelineMax()
                .add(TweenMax.to('#title-logo', .5, {
                    scale: 2,
                    rotation: 180,
                    ease: Back.easeOut
                }))
                .add(TweenMax.to('#title-logo', 2, {
                    y: fallHt,
                    ease: Back.easeOut
                }))
                .add(TweenMax.to('#title-logo',.5,{
                  scale: 0,
                  opacity: 0,
                  ease: SlowMo.easeOut
                }));

            //new scrollScene for the #title-logo animation
            var logoScene = new ScrollMagic.Scene({
                    triggerElement: '#title',
                    triggerHook: '.05'
                })
                .setClassToggle(".class-change", "z-45")
                .setTween(tweenLogo)
                .addIndicators()
                .addTo(controller);


            // slick carousel call

            $('.carousel-slide').slick({
              slidesToShow: 1,
              slidesToScroll: 1,
              fade: true,
              dots: true,
              infinite: true,
              speed: 500}
            );


/*===============jquery-accordion control ======================*/
            $('.accordion').accordion({
                "transitionSpeed": 400
            });




        }); /*end of document ready function*/

        //functions called when document resizes and orientation change

        $(window).on('resize orientationchange', (function() {
          winWidth = $(window).width();
          if (winWidth < 380) {
               $('.nav-elements').css('width', '75%');
           } else if (winWidth > 380 && winWidth < 760) {
               textWidth($('#title p').text());
           } else {
               width = $('#title').width();
               $('.nav-elements').css('width', width + 'px');
           }

           $('.day').css('width', '600px');
           if (winWidth < 600) {
               $('.day').css('width', winWidth);
           }

            var titleHt = $('#title-section').height();
            var logo2x = $('#title-logo').height() * 2;
            var fallHt = titleHt - logo2x;

        }));
