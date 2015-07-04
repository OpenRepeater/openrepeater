<?php
include('../sys_info.php');

for ($i = 1; $i < $count; $i++) {
	$usedspace = intval(preg_replace("/[^0-9]/", "", trim($used[$i])));
	$freespace = intval(preg_replace("/[^0-9]/", "", trim($avail[$i])));
}

$javascript = '
var data = [
{ label: "Used",  data: '.$usedspace.', color: "#006838"},
{ label: "Free",  data: '.$freespace.', color: "#8dc63f"}
];
	
//donut chart
if($("#donutchart").length)
{
	$.plot($("#donutchart"), data,
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
}';

header ("Content-type: application/javascript");
echo trim($javascript);
?>