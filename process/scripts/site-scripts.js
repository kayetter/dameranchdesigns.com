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

// /*---using jquery-modal to open modal content of each block in the design section*/
            $(".block").click(function() {
              myobj = $(this);
              console.log(myobj);
              idName = myobj.attr("id");
              id = '#'+idName + '-modal';
              console.log(id)
                $(id).appendTo("body").modal({fadeDuration: 300, fadeDelay: 1.5});
                console.log("i clicked here");
                return false;
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
// /*----using jqueryui widget to create accordion content for gallery----*/
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
                    "ui-accordion-icons": "accordion-icons"
                },
                animate: 300,
            });
// ============================scroll magic controller and scrolling design and animation=======================
            // init controller
            var controller = new ScrollMagic.Controller({});

            //calculate window height - height of #title-logo
            var winHeight = $(window).height() - 220 + $('#pic1').height();

            //tweenMax timeline for logo rotate, scale and bounce out
            var tweenLogo = new TimelineMax()
                .add(TweenMax.to('#title-logo', .5, {
                    scale: 2,
                    rotation: 180,
                    ease: Back.easeOut
                }))
                .add(TweenMax.to('#title-logo', 1.5, {
                    y: winHeight,
                    x: -20,
                    ease: Bounce.easeOut
                }));

            //new scrollScene for the #title-logo animation
            var sceneLogo = new ScrollMagic.Scene({
                    triggerElement: '#pic1',
                    triggerHook: '.9'
                })
                .setClassToggle("#title-section", "z-index")
                .setTween(tweenLogo)
                // .addIndicators()
                .addTo(controller);

            //pins the pic backgrounds and let's next section move up on the picture
            $(".pic-background").each(function() {
                new ScrollMagic.Scene({
                        triggerElement: this,
                        triggerHook: '0',
                        duration: 800
                    })
                    .setPin(this, {
                        pushFollowers: false
                    })
                    // .addIndicators()
                    .addTo(controller);

            });

            //pins the content sections so that they stay in place for a bit of extra scrolling
            $(".content").each(function() {
                new ScrollMagic.Scene({
                        triggerElement: this,
                        triggerHook: '0',
                        duration: 300
                    })
                    .setPin(this, {
                        pushFollowers: true
                    })
                    // .addIndicators()
                    .addTo(controller);

            });

            // slick carousel call

            $(#carousel).slick()






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
