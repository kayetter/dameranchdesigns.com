        //calculates width of tagline
        function textWidth(text) {
            var calc = '<section class = "title-section" style= "height:40px">' + text + '</section>' ;
            $('body').append(calc);
            $('body').find('.title-section:last').wrapInner("<p></p>");
            console.log(calc)
            var width = $('body').find('.title-section p:last').width();
            $('body').find('.title-section:last').remove();
            console.log(width);
            $('.nav-elements').css('width', width + 'px');
        };

        //functions called when document loads
        $(document).ready(function() {

          if ($(window).width() < 380 ){
            $('.nav-elements').css('width', '75%');
          }
          else if ($(window).width() > 380 && $(window).width() < 760) {
              textWidth($('#title p').text());
          }
          else {
            width = $('#title').width();
            $('.nav-elements').css('width', width + 'px');
          }
          console.log($('.nav-elements').width());
        });

        //functions called when document resizes
        $(window).resize(function() {
          if ($(window).width() < 380 ){
            $('.nav-elements').css('width', '75%');
          }
          else if ($(window).width() > 380 && $(window).width() < 760) {
              textWidth($('#title p').text(), 'p');
          }
          else {
            width = $('#title h1').width();
            $('.nav-elements').css('width', width + 'px');
          }
          console.log($('.nav-elements').width());
        });
