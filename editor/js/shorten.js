$(window).on('load', function(){
	$( "#url" ).click(function() {
		$.ajax({
			url: 'https://www.googleapis.com/urlshortener/v1/url?key=AIzaSyBt37_aZdCkvEZUbkqueWIgsY0iLGoNKYc',
			type: 'POST',
			contentType: 'application/json; charset=utf-8',
			data: '{ longUrl: "' + $('#url').val() +'"}',
			success: function(response) {
				$('#url').val(response.id);
				$('#url').select();
				$('#url').unbind( "click" );
			}
		 });
	});
});