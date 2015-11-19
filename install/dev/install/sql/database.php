<?php
   class MyDB extends SQLite3
   {
      function __construct()
      {
         $this->open('/var/lib/openrepeater/db/openrepeater.db');
      }
   }
   $dbConnection = new MyDB();
?>
