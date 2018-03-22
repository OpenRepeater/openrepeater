<?php


//include('../sys_info.php');
require_once('../../includes/classes/System.php');
$classSystem = new System();

$javascript = '';

foreach ( $classSystem->disk_usage() as $driveNum => $driveValues ) {
	$usedspace = intval(preg_replace("/[^0-9]/", "", trim($driveValues['used'])));
	$freespace = intval(preg_replace("/[^0-9]/", "", trim($driveValues['avail'])));

	$javascript .= '
	var data = [
	{ label: "Used",  data: '.$usedspace.', color: "#006838"},
	{ label: "Free",  data: '.$freespace.', color: "#8dc63f"}
	];
		
	//donut chart
	if($("#donutchart'.$driveNum.'").length)
	{
		$.plot($("#donutchart'.$driveNum.'"), data,
		{
				series: {
						pie: {
								innerRadius: 0.5,
								show: true
						}
				},
				legend: {
					show: false
				}
		});
	}
	
	';

}


header ("Content-type: application/javascript");
echo trim($javascript);
?>