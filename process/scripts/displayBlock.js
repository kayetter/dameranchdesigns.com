
$(document).ready(function(){

$('.block').hover(
  function() {
    $( this ).children('.details').removeClass( "display-none", 1000);
   console.log('added class');
 },
  function() {
     $( this ).children('.details').addClass( "display-none", 1000);
   console.log('removed class')}
);

});
