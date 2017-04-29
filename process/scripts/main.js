        //calculates width of tagline
        function textWidth(text) {
            var calc = '<section class = "title-section" style= "height:40px">' + text + '</section>';
            $('body').append(calc);
            $('body').find('.title-section:last').wrapInner("<h2></h2>");
            $('body').find('.title-section:last h2').css('display', 'inline-block');
            var width = $('body').find('.title-section:last h2').width();
            $('body').find('.title-section:last').remove();
            $('.nav-elements').css('width', width + 'px');
        }




        //functions called when document loads
        $(document).ready(function() {
          winWidth = $(window).width();
          aboutWidth = $('.about p').width();

           if (winWidth > 760) {
                textWidth($('#learn-more h2').text());
            } else if (winWidth < 760) {
                $('.nav-elements').css('width', aboutWidth + 'px');
            }

            if (winWidth < 600) {
                $('.day').css('width', winWidth);
            }



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
                .addTo(controller);


/*===============jquery-accordion control ======================*/
            $('.accordion').accordion({
                "transitionSpeed": 400
            });




        }); /*end of document ready function*/

        //functions called when document resizes and orientation change

        $(window).on('resize orientationchange', (function() {
          winWidth = $(window).width();
          aboutWidth = $('.about p').width();

           if (winWidth > 760) {
                textWidth($('#learn-more h2').text());
            } else if (winWidth < 760) {
                $('.nav-elements').css('width', aboutWidth + 'px');
            }

           $('.day').css('width', '600px');
           if (winWidth < 600) {
               $('.day').css('width', winWidth);
           }

            var titleHt = $('#title-section').height();
            var logo2x = $('#title-logo').height() * 2;
            var fallHt = titleHt - logo2x;

        }));
