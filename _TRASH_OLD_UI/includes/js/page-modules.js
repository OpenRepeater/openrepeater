function deleteModule (moduleID,moduleName) {
// 	alert(moduleID + moduleName);
	$('#deleteModule .modal-body span').text(moduleName);
	$('#deleteSelectedModule').val(moduleID);
}