$(function() {
	
	// CHAT
	// Send message
	$('#message-form').submit(function() {
		form = this;
		form.sendButton.disabled = true;
		$('.direct-chat .direct-chat-messages').load($(this).attr("action"), { message : this.message.value }, function() {
			form.sendButton.disabled = false;
			$('#message-form input#message').val("");
			showLastMessage();
		});
		
		return false;
	});

	// Scroll to bottom of direct caht
	function showLastMessage()
	{
		$('.direct-chat .direct-chat-messages').each( function() 
		{
		   // certain browsers have a bug such that scrollHeight is too small
		   // when content does not fill the client area of the element
		   var scrollHeight = Math.max(this.scrollHeight, this.clientHeight);
		   var scrollTop = scrollHeight - this.clientHeight;
		   
			$('.direct-chat .direct-chat-messages').animate({ scrollTop: scrollTop}, 600);	
		});
	}



	// Database 'hook'
	function waitForMessage()
	{
		/* This requests the url "msgsrv.php"
		When it complete (or errors)*/
		$.ajax({
			type: "POST",
			url: window.location.href,

			async: true, /* If set to non-async, browser shows page as "Loading.."*/
			cache: false,
			timeout:50000, /* Timeout in ms */

			success: function(data){ /* called when request to barge.php completes */
				$('.direct-chat .direct-chat-messages').html(data);
				showLastMessage();
				setTimeout(
					waitForMessage, /* Request next message */
					3000 /* ..after 3 seconds */
				);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				setTimeout(
					waitForMessage, /* Try again after.. */
					15000); /* milliseconds (15seconds) */
			}
		});
	};
	if ($('.direct-chat').length > 0) {
		waitForMessage();
	}

	// ADVANCED INFORMATIONS
	// Create
	$(document).on('click', '#show-ai', function () {
		var url = $(this).attr('href');
		var that = this;
		var placeholder = $('#advanced-informatios-place');

		$.post(url, function(data) {
			
			$(placeholder).html(data);

			var div = $(placeholder).children();
			var height = $(div).height();

			$(div).css('opacity', '0');
			$(div).css('height', '0');
			$(div).css('top', '-20px');

			$( div ).animate({
				height: height+"px",
			}, 300 );

			$( div ).animate({
				opacity: 1.0,
				top: 0,
			}, 200 );
		});
		return false;
	});

	// Save
	$(document).on('click', '#replace-ai', function () {
		var url = $(this).attr('href');
		var that = this;
		var placeholder = $('#advanced-informatios-place');
		var div = $(placeholder).children();

		$.post(url, function(data) {
			$( div ).animate({
				opacity: 0.0,
				top: 20,
			}, 300, 'swing', function() {
				$(placeholder).html(data);
				var div = $(placeholder).children();
				$(div).css('opacity', '0');
				$(div).css('top', '-20px');

				$(div).animate({
					opacity: 1.0,
					top: 0,
				}, 200 );
			});
		});
		return false;
	});

	// Hide
	$(document).on('click', '#hide-ai', function () {
		var url = $(this).attr('href');
		var that = this;
		var placeholder = $('#advanced-informatios-place');

		$.post(url, function(data) {
			var div = $(placeholder).children();
		
			$( div ).animate({
				top: '-20px',
				opacity: 0.0,
				
			}, 300);

			$( div ).animate({
				height: 0.0,
			}, 200, 'swing', function() {
				$(placeholder).html();
			} );

		});
		return false;
	});

});