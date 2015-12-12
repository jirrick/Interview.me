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
            url: "/www/candidate/detail/id/3",

            async: true, /* If set to non-async, browser shows page as "Loading.."*/
            cache: false,
            timeout:50000, /* Timeout in ms */

            success: function(data){ /* called when request to barge.php completes */
            	$('.direct-chat .direct-chat-messages').html(data);
            	showLastMessage();
                setTimeout(
                    waitForMessage, /* Request next message */
                    3000 /* ..after 1 seconds */
                );
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                setTimeout(
                    waitForMessage, /* Try again after.. */
                    15000); /* milliseconds (15seconds) */
            }
        });
    };
    waitForMessage();

});