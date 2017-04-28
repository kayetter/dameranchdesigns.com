
        //functions called when document loads
        $(document).ready(function() {

              //gallery slide modal
            $('#bmb-slide').click(function(){
              $('#bmb-gallery-article').appendTo("body").modal({fadeDuration: 300, fadeDelay: 1.5});
            });

            // slick carousel call

            $('.carousel-div').slick({
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


        }));
