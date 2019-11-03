<html>
<head>
	<style>
		body {
			margin: 0;
			padding: 0;
		}

		a, a:visited, a:active {
			color: green;
		}
		.header {
			display: block;
			overflow: auto;
			background-color: #000000;
			color: #ffffff;
			padding: 15px;
			margin: 0;
		}

		.header h3 {
		    float: left;
		    margin: 0;
		}

		.header span a {
			float: right;
   		}
   		
   		#content_wrap {
			padding: 20px;
		}
		
		.footer_note {
			font-size: 12px;
			color: silver;
   		}
	
   		li {
	   		margin-bottom: 10px;
   		}
	
		div.greyGridTable {
		  border: 2px solid #FFFFFF;
		  width: 100%;
		  text-align: center;
		  border-collapse: collapse;
		}
		.divTable.greyGridTable .divTableCell, .divTable.greyGridTable .divTableHead {
		  border: 1px solid #FFFFFF;
		  padding: 3px 4px;
		}
		.divTable.greyGridTable .divTableBody .divTableCell {
		  font-size: 13px;
		}
		.divTable.greyGridTable .divTableCell:nth-child(even) {
		  background: #EBEBEB;
		}
		.divTable.greyGridTable .divTableHeading {
		  background: #FFFFFF;
		  border-bottom: 4px solid #333333;
		}
		.divTable.greyGridTable .divTableHeading .divTableHead {
		  font-size: 15px;
		  font-weight: bold;
		  color: #333333;
		  text-align: center;
		  border-left: 2px solid #333333;
		}
		.divTable.greyGridTable .divTableHeading .divTableHead:first-child {
		  border-left: none;
		}
		
		.greyGridTable .tableFootStyle {
		  font-size: 14px;
		}
		/* DivTable.com */
		.divTable{ display: table; }
		.divTableRow { display: table-row; }
		.divTableHeading { display: table-header-group;}
		.divTableCell, .divTableHead { display: table-cell; width: 33%;}
		.divTableHeading { display: table-header-group;}
		.divTableFoot { display: table-footer-group;}
		.divTableBody { display: table-row-group;}
	
	
		.myButton {
			-moz-box-shadow:inset 0px 1px 0px 0px #a4e271;
			-webkit-box-shadow:inset 0px 1px 0px 0px #a4e271;
			box-shadow:inset 0px 1px 0px 0px #a4e271;
			background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #89c403), color-stop(1, #77a809));
			background:-moz-linear-gradient(top, #89c403 5%, #77a809 100%);
			background:-webkit-linear-gradient(top, #89c403 5%, #77a809 100%);
			background:-o-linear-gradient(top, #89c403 5%, #77a809 100%);
			background:-ms-linear-gradient(top, #89c403 5%, #77a809 100%);
			background:linear-gradient(to bottom, #89c403 5%, #77a809 100%);
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#89c403', endColorstr='#77a809',GradientType=0);
			background-color:#89c403;
			-moz-border-radius:6px;
			-webkit-border-radius:6px;
			border-radius:6px;
			border:1px solid #74b807;
			display:inline-block;
			cursor:pointer;
			color:#ffffff;
			font-family:Arial;
			font-size:15px;
			font-weight:bold;
			padding:6px 24px;
			text-decoration:none;
			text-shadow:0px 1px 0px #528009;
		}
		.myButton:hover {
			background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #77a809), color-stop(1, #89c403));
			background:-moz-linear-gradient(top, #77a809 5%, #89c403 100%);
			background:-webkit-linear-gradient(top, #77a809 5%, #89c403 100%);
			background:-o-linear-gradient(top, #77a809 5%, #89c403 100%);
			background:-ms-linear-gradient(top, #77a809 5%, #89c403 100%);
			background:linear-gradient(to bottom, #77a809 5%, #89c403 100%);
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#77a809', endColorstr='#89c403',GradientType=0);
			background-color:#77a809;
		}
		.myButton:active {
			position:relative;
			top:1px;
		}

		.myButton.rebuild {
			-moz-box-shadow:inset 0px 1px 0px 0px #e27171;
			-webkit-box-shadow:inset 0px 1px 0px 0px #e27171;
			box-shadow:inset 0px 1px 0px 0px #e27171;
			background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #c40303), color-stop(1, #a80909));
			background:-moz-linear-gradient(top, #c40303 5%, #a80909 100%);
			background:-webkit-linear-gradient(top, #c40303 5%, #a80909 100%);
			background:-o-linear-gradient(top, #c40303 5%, #a80909 100%);
			background:-ms-linear-gradient(top, #c40303 5%, #a80909 100%);
			background:linear-gradient(to bottom, #c40303 5%, #a80909 100%);
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#c40303', endColorstr='#a80909',GradientType=0);
			background-color:#c40303;
			border:1px solid #b80707;
			color:#ffffff;
			text-shadow:0px 1px 0px #800909;
		}

		#locationForm {
			
		}

		#locationForm div {
			margin-bottom: 5px;
   		}

		#locationForm label {
			width: 200px;
			display: block;
			float: left;
		}
		
		#locationForm input {
			width: 300px;
		}

		#locationForm input[type=number] {
			width: 50px;
		}
		
		.message {
			background: yellow;
			margin-bottom: 30px;
			padding: 10;
		}


	</style>
</head>

<body>

<div class="header">
	<h3>ORP Development UI</h3>
	<span><a href="index.php">Dev Menu</a></span>
</div>

<div id="content_wrap">