<?php include('header.php'); ?>

<ul>
<li><a href="dbupdate.php">Update Database Structure</a> - If you have the old DB structure, this should fix it. It will check for certain fields and if they don't exist, only then will it make the modifications to the database. This is typically a run once script.</li>
<li><a href="ports_add.php">Add Special Ports</a> - This is primarily to add new port types that are not supported by the current UI. This would include hidraw devices (i.e. the DMK URI or USB RIM Lite) or USB-to-Serial adapters for control of PTT and COS. You MUST edit the array in this file before you run it. After You run it, do not update ports via the main UI.</li>
<li><a href="ports_advanced.php">Advanced SVXLink Settings</a> - This provides a means to add other SVXLink settings for the Logic, RX, and TX sections. This page will allow you to view/add/remove settings for those sections on a port by port basis. These settings will be stored in the port options in the database and available upon rebuild. They will also be supported by backup/restore.</li>
<li><a href="linkGroups.php">Add/Update LinkGroup Settings</a> - This is for adding settings for the LinkGroups to the DB You MUST edit this file first.</li>
<li><a href="location.php">Add Location Section</a> - This is for adding settings for the location information to share with Echolink & APRS for proper reporting. You MUST edit this file first.</li>
<li><a href="macros.php">Add Macros Section</a> - This adds macro settings into the database from an array. You MUST edit this file first.</li>
</ul>

<h4>Needs Work...</h4>
<ul>
<li><a href="../expert/shell.php">Web Based SSH/Shell Access</a> - This feature will allow a web based SSH access for advanced users/developers. It utilizes the Shell In A Box package and will be embedded into the UI. Currently having some issues with it loading initially. Seems that once I SSH from a normal client, then it will work on a reload. Also it is not currently allowing a root login. I have to add a secondary user to log in. These could very well be configuration issues that need addressed.</li>
</ul>

<?php include('footer.php'); ?>