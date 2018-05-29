;(function($, window, document) {
	$('document').ready(function () {
		$('select#file_id').change(function () {
			$(this).closest('form').submit();
		});

		$('input#new_file').keydown(function(e) {
			if (e.which == 13) {
				$('input#create').click();
			}
		});
	});
})(jQuery, window, document);
