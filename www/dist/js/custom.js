// Retrieve new element's html from controller
function ajaxAddField(par, requrl) {
    var parent = $("form[name=" + par + "]")
    var id = (parseInt(parent.find("#count").val()) + 1);
        
    $.ajax(
    {
        type: "POST",
        url: requrl,
        data: "newid=" + id,
        success: function(newElement) {
        // Insert new element before the Add button
        parent.find("#addElement-label").before(newElement);      
        // Store id
        parent.find("#count").val(id);
        }
    }
    );
    
    // add extra field when there are no options (cant be only one option)
    if (id++ == 1) {
        $.ajax(
        {
            type: "POST",
            url: requrl,
            data: "newid=" + id,
            success: function(newElement) {
            // Insert new element before the Add button
            parent.find("#addElement-label").before(newElement);      
            // Store id
            parent.find("#count").val(id);
            }
        }
        );
    }
}

//Remove element from from
function removeField(par) {
    var parent = $("form[name=" + par + "]")   
    // Get the last used id
    var id = parseInt(parent.find("#count").val());
    
    if (id > 0) {
        // Remove old shit
        parent.find("[id|=odpoved" + id.toString() + "]").remove();
        parent.find("[id|=check" + id.toString() + "]").remove();
        // Decrement and store id
        parent.find("#count").val(--id);
    }
}

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
    if ($('.direct-chat').length > 0) {
  		waitForMessage();
	}
    
});