jQuery.fn.preventDoubleSubmission = function() {
  $(this).on('submit',function(e){
    var $form = $(this);
    if ($form.data('submitted') === true) {
      // Previously submitted - don't submit again
      e.preventDefault();
    } else if (typeof $form.data('submitted') === 'undefined') {
      // Mark it so that the next submit can be ignored
      $form.data('submitted', true);
	  //e.preventDefault();
    }else
		$form.data('submitted', true);
  });

  // Keep chainability
  return this;
};