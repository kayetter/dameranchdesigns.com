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




            // /*on click toggle show hide detail element of blocks*/

            //   $(".summary").click(function(e){

            //     e.preventDefault();

            //     var details= $(this).next('.details'),
            //     dur = 700;
            //     // details.toggleClass("transition, hidden");
            // 		 details.slideToggle(dur, 'linear',  timeOut);
            //
            //      function timeOut() {
            //        setTimeout(function(){
            //          details.slideUp('fast');}, 1000000);
            //        }
            //  });

// ============================scroll magic controller and scrolling design and animation=======================
            // init controller
            var controller = new ScrollMagic.Controller({});

            //calculate window height - height of #title-logo
            var logoPos = $('#title-logo').position();
            var logoPosY = logoPos.top;
            var titleHt = $('#title-section').height();
            var logo2x = $('#title-logo').height() * 2;
            var fallHt = titleHt - logo2x;

            //tweenMax timeline for logo rotate, scale and bounce out
            var tweenLogo = new TimelineMax()
                .add(TweenMax.to('#title-logo', .5, {
                    scale: 2,
                    rotation: 180,
                    ease: Back.easeOut
                }))
                .add(TweenMax.to('#title-logo', 2, {
                    y: fallHt,
                    x: 20,
                    ease: Bounce.easeOut
                }))
                .add(TweenMax.to('#title-logo',.5,{
                  scale: 0,
                  opacity: 0,
                  ease: Back.easeOut
                }));

            //new scrollScene for the #title-logo animation
            var logoScene = new ScrollMagic.Scene({
                    triggerElement: '#title',
                    triggerHook: '.05'
                })
                .setClassToggle("#title-section", "z-index")
                .setTween(tweenLogo)
                .addIndicators()
                .addTo(controller);


            //pins the pic backgrounds and let's next section move up on the picture
            // $(".pic-background").each(function() {
            //     new ScrollMagic.Scene({
            //             triggerElement: this,
            //             triggerHook: '0',
            //             duration: 0
            //         })
            //         .setPin(this, {
            //             pushFollowers: false
            //         })
            //         // .addIndicators()
            //         .addTo(controller);
            //
            // });

            //pins the content sections so that they stay in place for a bit of extra scrolling
            // $(".content").each(function() {
            //     new ScrollMagic.Scene({
            //             triggerElement: this,
            //             triggerHook: '0',
            //             duration: 1
            //         })
            //         .setPin(this, {
            //             pushFollowers: true
            //         })
            //         // .addIndicators()
            //         .addTo(controller);
            //
            // });

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

            if (winWidth < 600) {
                $('.day').css('width', winWidth);
            } 
            console.log($('.nav-elements').width());
            var logoPos = $('#title-logo').position();
            var logoPosY = logoPos.top;
            var titleHt = $('#title-section').height();
            var logo2x = $('#title-logo').height() * 2;
            var fallHt = titleHt - logo2x;

        });
