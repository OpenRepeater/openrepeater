$(function() {

	$("#selConfigFile").on('change', function(){
		var fileLabel = $(this).find('option:selected').text();
		var filePath = $(this).val();

	    $.ajax({ 
		    type: 'POST', 
		    dataType: 'text', 
		    url: 'log.php', 
		    data: { request: 'get_file', file_path: filePath },
            success: function (results) {
				results = JSON.parse(results);

				$('#configFileDisplay').html('<pre>' + results.fileContents + '</pre>');
				$('#configFileDate').html(results.fileDate);

				$('#configFileLoc span').html(filePath);
				$('#configFileLoc').fadeIn(500);
            } 
		});	

	});



	// Loop through JSON array of config files and build select menu
	config_files = JSON.parse(configFilesList);

	config_files.sort( function(a, b){
		var aName = a.fileLabel.toLowerCase();
		var bName = b.fileLabel.toLowerCase(); 
		return ((aName < bName) ? -1 : ((aName > bName) ? 1 : 0));
	});

	$.each(config_files, function(index, curFile) {
	    $("#selConfigFile").append('<option value="'+curFile.filePath+'">'+curFile.fileLabel+'</option>');		
	});



	function displayLog(label, path) {
		var tabLabel = label;
		var baseID = label.replace(/\s+/g, '_').replace(/\./g,'_').toLowerCase();
		var tabID = baseID + '_tab';
		var contentID = baseID + '_content';
		var contentBody = '';
   
		var $tabTemplate = $('#tabTemplate').html();
		$tabTemplate = $tabTemplate.replace(/%%TAB_ID%%/g, tabID)
			.replace(/%%TAB_LABEL%%/g, tabLabel)
			.replace(/%%URL_TO_CONTENT%%/g, contentID);

	    $("#logTabs").append($tabTemplate);

		var $tabContent = $('#contentTemplate').html();
		$tabContent = $tabContent.replace(/%%CONTENT_ID%%/g, contentID)
			.replace(/%%TAB_ID%%/g, tabID)
			.replace(/%%CONTENT_BODY%%/g, contentBody);

	    $("#logContent").append($tabContent);

	}

})