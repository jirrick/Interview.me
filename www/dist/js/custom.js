// Show answers and evaluation buttons
jQuery.showAnswers = function showAnswers(question_id) {
    $("button[id=evaluation-q" + question_id + "]").css("display", "none");
    $("button[id=correct-q" + question_id + "]").css("display", "");
    $("button[id=wrong-q" + question_id + "]").css("display", "");
    $("span[id=correct-q" + question_id + "]").css("display", "");
}

// Evaluates question
jQuery.evaluateQuestion = function evaluateQuestion(question_id, isCorrect) {
    $.ajax(
    {
        type: "POST",
        url: this.href,
        data: 'question_id='+question_id+'&isCorrect='+isCorrect,
        success: function(newElement) {
            //hide buttons
            $("button[id=evaluation-q" + question_id + "]").css("display", "");
            $("button[id=correct-q" + question_id + "]").css("display", "none");
            $("button[id=wrong-q" + question_id + "]").css("display", "none");
            $("span[id=correct-q" + question_id + "]").css("display", "none");
            
            //nastavi stav vlevo od moznosti
            var status = $("span[id=status-q" + question_id + "]");
            status.removeClass("positive-positive positive-negative");
            if (isCorrect) {
                status.addClass("positive-positive");
            } else {
                status.addClass("positive-negative");
            }
            
            //nastavi stavy u moznosti
            var substatus = $("span[id=subcorrect-q" + question_id + "]");
            substatus.removeClass("positive-positive positive-negative");
            if (isCorrect) {
                substatus.addClass("positive-positive");
            } else {
                substatus.addClass("positive-negative");
            }
        }
    }
    );

}

// Retrieve new element's html from controller
jQuery.ajaxAddField = function ajaxAddField(par, requrl) {
    var parent = $("form[name=" + par + "]")
    var count = parseInt(parent.find("#count").val());
       
    $.ajax(
    {
        type: "POST",
        url: requrl,
        data: "count=" + count,
        success: function(newElement) {
            // Insert new element before the Add button
            parent.find("#addElement-label").before(newElement);      
            // Store new id
            count = count + 1;
            parent.find("#count").val(count);
        }
    }
    );
}

//Remove element from from
jQuery.removeField = function removeField(par) {
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
	
	function nl2br() {
		$('.nl2br').each(function(){
			var html = $(this).html();
			$(this).html(html.replace(/\n/g, "<br />"));
		});
	}

	nl2br();

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

	$(document).on('click', '#advanced-informations-form #saveButton', function () {
		// Shows loading
		$('#advanced-informations-form div.box').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
		$('#base-advanced-informations div.box').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');

		// Binds submit action
		$('#advanced-informations-form').submit(function() {
			
			var placeholder = $('#advanced-informations-place');
			form = this;

			// Loads values from form
			var formValues = {};
			for ( var i = 0, il = form.elements.length; i < il; i++ ) {
				var e = form.elements[i];
				if (e.value.length > 0) {
					if ($(e).is('#perzonalista_informace')) {
						var selectedArray = [];
						for ( var j = 0, jl = e.selectedOptions.length; j < jl; j++ ) {
							var o = e.selectedOptions[j];
							selectedArray.push(o.value);
						}
						formValues[e.name+"[]"] = selectedArray;
					}
					else {
						formValues[e.name] = e.value;
					}
				}
				else {
					formValues[e.name] = null;
				}
			}
			delete formValues['saveButton'];
			delete formValues[''];

			// Calls save action in controller
			$.post($(this).attr("action"), { 'formValues' : formValues }, function(data) {
				replaceAdvancedInformations(data);
			});

			// Refreshes base advanced informations
			$.get($(this).attr("action").replace('save-advanced-informations', 'base-advanced-informations'), null , function(data) {
				// Removes loading
				$('#base-advanced-informations div.box .overlay').remove();

				// Shows new content
				var baseAi = $('#base-advanced-informations-ajax-place');
				if ($(baseAi).length > 0) {
					$(baseAi).html(data);
				}
			});
			
			return false;
		});
	});

	// Show
	function showAdvancedInformations(html) {
		var placeholder = $('#advanced-informations-place');

		$(placeholder).html(html);
		$( ".date-picker" ).datepicker({ dateFormat: 'yy-mm-dd' });
		$('#perzonalista_informace').select2();
		nl2br();

		var div = $(placeholder).children();
		var height = $(div).height();

		$(div).css('opacity', '0');
		$(div).css('height', '0');
		$(div).css('top', '-20px');

		$( div ).animate({
			height: height+"px",
		}, 300, 'swing', function(){
			$( div ).css('height', 'auto');
		} );

		$( div ).animate({
			opacity: 1.0,
			top: 0,
		}, 200 );
	}

	$(document).on('click', '.show-ai', function () {
		var url = $(this).attr('href');
		var that = this;

		$(this).children().html('Hide Advanced Informations');
		$(this).removeClass('show-ai');
		$(this).addClass('hide-ai');

		$.post(url, function(data) {
			showAdvancedInformations(data);
		});
		return false;
	});

	// Replace
	function replaceAdvancedInformations(html) {
		var placeholder = $('#advanced-informations-place');
		var div = $(placeholder).children();

		$( div ).animate({
			opacity: 0.0,
			top: 20,
		}, 300, 'swing', function() {
			$(placeholder).html(html);
			$( ".date-picker" ).datepicker({ dateFormat: 'yy-mm-dd' });
			$('#perzonalista_informace').select2();
			nl2br();

			var div = $(placeholder).children();
			$(div).css('opacity', '0');
			$(div).css('top', '-20px');

			$(div).animate({
				opacity: 1.0,
				top: 0,
			}, 200 );
		});
	}
	$(document).on('click', '.replace-ai', function () {
		var url;
		if ($(this).is('input')) {
			url = $('#advanced-informatins-form').attr('action');
		}
		else if ($(this).is('a')) {
			url = $(this).attr('href');
		}
		else {
			return true;
		}
		var that = this;

		$.post(url, function(data) {
			replaceAdvancedInformations(data);
		});
		return false;
	});

	// Hide
	function hideAdvancedInformations() {
		var placeholder = $('#advanced-informations-place');
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
	}

	$(document).on('click', '.hide-ai', function () {

		$(this).children().html('Show Advanced Informations');
		$(this).removeClass('hide-ai');
		$(this).addClass('show-ai');

		hideAdvancedInformations();
		return false;
	});

	// USERS

	function replaceUserPlace(url) {
		var askPermissionUrl = url.replace(/\/user\/.*$/, '/user/is-admin');
		$.get(askPermissionUrl, function(result) {
			if (result == '0') {
				alert("You aren't permitted to do this action!");
			}
			else {
				$.post(url, function(data) {
					$('#users-list-ajax-place').html(data);

					$('#users-list').DataTable( {
						responsive: false,
						columnDefs: [
							{ responsivePriority: 1, targets: 0 },
							{ responsivePriority: 2, targets: 2 },
							{ responsivePriority: 1, targets: -1 },
							{ bSortable: false, aTargets: -1 }
					] });
				});
			}
		});
	}

	$(document).on('click', '.replace-users-list', function(){
		var url = $(this).attr('href');
		replaceUserPlace(url);
		return false;
	});

	$(document).on('click', '.delete-user', function(){
		if (confirm("Are you sure?")) {
			var url = $(this).attr('href');
			replaceUserPlace(url);
		}
		return false;
        });
	});