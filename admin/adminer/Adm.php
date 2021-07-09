<?php
function adminer_object() {
include_once "./plugins/plugin.php";
foreach (glob("plugins/*.php") as $filename) {
include_once "./$filename";
}
//~ include "./plugins/drivers/simpledb.php";
$plugins = [
    /*
      Params
      1. DB type - "server" == MySQL, others are sqlite, sqlite2, pgsql, oracle, mssql, firebird, simpledb, mongo, elastic
      2. server - localhost by default
      3. login 
      4. password
      5. DB name
    */
    new FillLoginForm("mysql","localhost","uap","Urxx38?1","apfr")
];
return new AdminerPlugin($plugins);
}
include "./adminer.php";
?>