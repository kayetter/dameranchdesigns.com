
$(document).ready(function(){

$('.block').hover(
  function() {
    $( this ).children('.details').removeClass( "no-show", 1000);
   console.log('removed class');
 }
  function() {
     $( this ).children('.details').addClass( "no-show", 1000);
   console.log('added class')}
);

});
