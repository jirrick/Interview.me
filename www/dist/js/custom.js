$(function() {
	
	// CHAT
	// Send message
	$('#message-form').submit(function() {
		form = this;
		form.sendButton.disabled = true;
		$('.direct-chat .direct-chat-messages').load($(this).attr("action"), { message : this.message.value }, function() {
			form.sendButton.disabled = false;
			$(this).each( function() 
			{
			   // certain browsers have a bug such that scrollHeight is too small
			   // when content does not fill the client area of the element
			   var scrollHeight = Math.max(this.scrollHeight, this.clientHeight);
			   this.scrollTop = scrollHeight - this.clientHeight;
			});

			$('#message-form input#message').val("");
		});
		
		return false;
	});

	// Scroll to bottom of direct caht
	$('.direct-chat .direct-chat-messages').each( function() 
	{
	   // certain browsers have a bug such that scrollHeight is too small
	   // when content does not fill the client area of the element
	   var scrollHeight = Math.max(this.scrollHeight, this.clientHeight);
	   this.scrollTop = scrollHeight - this.clientHeight;
	});

});