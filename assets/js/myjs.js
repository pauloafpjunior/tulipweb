// Modal
$(document).ready(function(){
    $('.modal').modal();
});

$(document).ready(function(){
  $('.collapsible').collapsible();
});


$(document).ready(function(){
  $('input.autocomplete').autocomplete({
    data: {
      "Apple": null,
      "Microsoft": null,
      "Google": "save"
    },
  });
});

ClassicEditor
  .create( document.querySelector( '#editor' ) )
  .catch( error => {
    console.error( error );
} );
