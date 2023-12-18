jQuery(document).ready(function($) {
  var popup = $('#quick-view-wrap-overlay');
  var popupContent = $('.quick-view-content');
  var closePopup = $('.quick-view-close');

  // Attach the event handler to the parent element using event delegation
  $(document).on('click', '.talent-card-quick-view-link', function(e) {
	  e.preventDefault();
    var postId = $(this).data('postid');
    $.ajax({
      type: 'POST',
      url: tctalent_ajax.ajaxurl, 
      data: {
        action: 'talent_quick_view', 
        post_id: postId
      },
      success: function(response) {
        popupContent.html(response);
        popup.show();
      }
    });
  });

  // Close the popup and clear its content when the close button is clicked
  closePopup.on('click', function() {
    popup.hide();
    popupContent.html('');
  });

  // Close the popup and clear its content when clicking outside the content area
  popup.on('click', function(event) {
    if (!$(event.target).closest('.quick-view-modal').length) {
      popup.hide();
      popupContent.html('');
    }
  });
});
