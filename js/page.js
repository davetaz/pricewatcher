$(document).ready(function() {
	registerListners();
});

function registerListners() {

	$("#addProduct").click(function(event) {
		var url = prompt("Please enter the url of the product you wish to track, the URL MUST look like the following:", "http://www.marksandspencer.com/cashmilon-open-front-cardigan/p/p22335242");
		if (url != null) {
			$.post("manage.php", { "url": url, "code": getParameterByName('code') })
                	.done(function(data) {
                		console.log(data);
			})
			.fail(function(xhr, textStatus, errorThrown) {
				console.log(xhr.responseText);
			});
		}
		
	});
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
