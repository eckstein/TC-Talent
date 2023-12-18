jQuery(document).ready(function($) {


  //Responsive Header stuff
  $('#search-toggle').click(function() {
    var target = $(this).data('target');
    $(target).slideToggle();
  });

  

	
	 // Set the first thumbnail as active
    $('.talent-gallery .thumbnails img').first().addClass('active');

    $('.talent-gallery .thumbnails img').click(function() {
        var largeImage = $(this).data('large');
        $('.talent-gallery .large-image img').attr('src', largeImage);

        // Remove the active class from all thumbnails
        $('.talent-gallery .thumbnails img').removeClass('active');

        // Add the active class to the clicked thumbnail
        $(this).addClass('active');
    });
	
	
	$('.mobile-filter-toggle').on ('click', function() {
		$('.talent-archive-filters').slideToggle();
		$(this).toggleClass('open');
		$(this).find('i.fa-angle-down').toggleClass('fa-angle-down fa-angle-up');
	})
  
});
