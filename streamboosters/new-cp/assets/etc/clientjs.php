$(document).ready(function() {
	// clean up disabled buttons for firefox after soft-refresh (f5)
	$("input[disabled]").each(function(obj) { $(this).removeAttr("disabled") });

	function successResponse(text) {
		$("#responseContainer").css("visibility", "visible");
		$("#responseContainer").removeClass("failed");
		$("#responseContainer").addClass("succeeded");
		$("#response").text(text);
	}
	function failureResponse(text) {
		$("#responseContainer").css("visibility", "visible");
		$("#responseContainer").removeClass("succeeded");
		$("#responseContainer").addClass("failed");
		$("#response").text(text);
	}
	
	function showLoader() {
		$("#responseContainer").css("visibility", "visible");
		$("#responseContainer").removeClass("succeeded");
		$("#responseContainer").removeClass("failed");
		$("#response").html("<img src=\"assets/img/ajax-loader.gif\" />");
	}

		$("#logoutform").click(function() { showLoader();
		var postData = { action: "logout" };
		
		$.post("ajax.php", postData, function(serverResponse) {
			if(serverResponse.errorCode == 0) {
				successResponse("logging out.");
				window.location.reload();
			} else {
				failureResponse(serverResponse.errorMessage);
			}
		}, "json");
	});
			var startChattersCustomInterval, startViewersCustomInterval, startViewersInterval, startChattersInterval;
	
	$("#startViewerForm").submit(function() {
		$(this).children("input[type=submit]").attr("disabled", "disabled");
		$(this).children("input[type=submit]").val("re-sending every 3 minutes...");
		
		showLoader();
		
		var postData = { action: "startViewers" };

		$.post("ajax.php", postData, function(serverResponse) {
			if(serverResponse.errorCode == 0) {
				successResponse("Started viewers.");
			} else {
				failureResponse(serverResponse.errorMessage);
			}
		}, "json");
		
		startViewersInterval = setInterval(function() {
			showLoader();
			var postData = { action: "startViewers" };

			$.post("ajax.php", postData, function(serverResponse) {
				if(serverResponse.errorCode == 0) {
					successResponse("Started viewers.");
				} else {
					failureResponse(serverResponse.errorMessage);
				}
			}, "json");
		}, 3 * 60 * 1000);
		
		return false;
	});
	
	$("#stopViewerForm").submit(function() {showLoader();
		var postData = { action: "stopViewers" };
		
		$.post("ajax.php", postData, function(serverResponse) {
			if(serverResponse.errorCode == 0) {
				successResponse("Stopped viewers.");
			} else {
				failureResponse(serverResponse.errorMessage);
			}
		}, "json");
		
		if(typeof startViewersInterval == "number") {
			clearInterval(startViewersInterval);
		}
		if(typeof startChattersInterval == "number") {
			clearInterval(startChattersInterval);
		}
		$("#startViewerForm").children("input[type=submit]").removeAttr("disabled");
		$("#startViewerForm").children("input[type=submit]").val("Start Viewers");
		
		return false;
	});
	
	
	$("#startViewersCustom").submit(function() {
		$(this).children("input[type=submit]").attr("disabled", "disabled");
		$(this).children("input[type=submit]").val("re-sending every 3 minutes...");
		var count = $("#startViewersCustomCount").val();
		var delay = (parseInt($("#startViewersCustomDelay").val()) * 60 * 1000);
		
		pollViewers(count, delay);
		startViewersCustomInterval = setInterval(function() {
			pollViewers(count, 0);
		}, 3 * 60 * 1000);
	
		return false;
	});
	
	$("#stopViewersCustom").submit(function() {
		var postData = { action: "stopViewers",
						 count: $("#stopViewersCustomCount").val()
						};
		
		$.post("ajax.php", postData, function(serverResponse) {
			if(serverResponse.errorCode == 0) {
				successResponse("Stopped viewers.");
			} else {
				failureResponse(serverResponse.errorMessage);
			}
		}, "json");
		
		if(typeof startViewersCustomInterval == "number") {
			clearInterval(startViewersCustomInterval);
		}
		$("#startViewersCustom").children("input[type=submit]").removeAttr("disabled");
		$("#startViewersCustom").children("input[type=submit]").val("Start viewers");
		
		return false;
	});
	
	
	
	$("#startChattersCustom").submit(function() {
		$(this).children("input[type=submit]").attr("disabled", "disabled");
		$(this).children("input[type=submit]").val("re-sending every 3 minutes...");
		var count = $("#startChattersCustomCount").val();
		var delay = (parseInt($("#startChattersCustomDelay").val()) * 60 * 1000);
		
		pollChatters(count, delay);
		startChattersCustomInterval = setInterval(function() {
			pollChatters(0);
		}, 3 * 60 * 1000);
	
		return false;
	});
	
	$("#stopChattersCustom").submit(function() {
		var postData = { action: "stopChatters",
						 count: $("#stopChattersCustomCount").val(),
						 delay: (parseInt($("#stopChattersCustomDelay").val()) * 60 * 1000)
						};
		
		$.post("ajax.php", postData, function(serverResponse) {
			if(serverResponse.errorCode == 0) {
				successResponse("Stopped chatters.");
			} else {
				failureResponse(serverResponse.errorMessage);
			}
		}, "json");
		
		if(typeof startChattersCustomInterval == "number") {
			clearInterval(startChattersCustomInterval);
		}
		$("#startChattersCustom").children("input[type=submit]").removeAttr("disabled");
		$("#startChattersCustom").children("input[type=submit]").val("Start chatters");
		
		return false;
	});
	
	
	
	
	
	function startViewers(myCount, myDelay) {showLoader();
		var postData = { action: "startViewers",
						 count: myCount,
						 delay: myDelay
						};
		
		$.post("ajax.php", postData, function(serverResponse) {
			if(serverResponse.errorCode == 0) {
				successResponse("Started viewers.");
			} else {
				failureResponse(serverResponse.errorMessage);
			}
		}, "json");
	}
	
	function startChatters(myCount, myDelay) {showLoader();
		var postData = { action: "startChatters",
						 count: myCount,
						 delay: myDelay
						};
		
		$.post("ajax.php", postData, function(serverResponse) {
			if(serverResponse.errorCode == 0) {
				successResponse("Started chatters.");
			} else {
				failureResponse(serverResponse.errorMessage);
			}
		}, "json");
		
	}
	
	function pollViewers(count, delay) {
		var postData = { action: "pollViewers" };
		
		$.post("ajax.php", postData, function(serverResponse) {
			if(serverResponse.errorCode == 0) {
				var startCount = count - serverResponse.errorMessage;
				if(startCount > 0) {
					startViewers(startCount, delay);
				} else {
					successResponse(count + " viewers still running");
				}
			}
		}, "json");
	}
	
	function pollChatters(count) {
		var postData = { action: "pollChatters" };
		
		$.post("ajax.php", postData, function(serverResponse) {
			if(serverResponse.errorCode == 0) {
				var startCount = count - serverResponse.errorMessage;
				if(startCount > 0) {
					startChatters(startCount, 0);
				} else {
					successResponse(count + " chatters still running");
				}
			}
		}, "json");
	}
	
		});