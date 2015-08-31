<?php

    require_once 'MDB2.php';

    /**
     * Közös felhasználói adatbázis használata esetén a közös db nevét kell megadni,
     * ellenkező esetben pedig a saját adatbázist.
     */
    define("DB_MAIN",     "01393_kallay");
    define("DB_USERS",    "01393_kallay");
    define("DB_SESSIONS", "01393_kallay");

	//MDB2 beallitasok
	/**
	 * adatbazis tipusok
	 *
	 * fbsql  -> FrontBase
	 * ibase  -> InterBase (requires PHP 5)
	 * mssql  -> Microsoft SQL Server (NOT for Sybase. Compile PHP --with-mssql)
	 * mysql  -> MySQL
	 * mysqli -> MySQL (supports new authentication protocol) (requires PHP 5)
	 * oci8   -> Oracle 7/8/9
	 * pgsql  -> PostgreSQL
	 * querysim -> QuerySim
	 * sqlite -> SQLite
	 */
	$dsn = array(
		'phptype'  => 'mysql',
		'username' => '01393_kallay',
		'password' => 'kur14_k4ll4y',
		'hostspec' => 'mysql.e-tiger.net',
		'database' => DB_MAIN,
	);

	$options = array(
    	'debug' => 2,
	);

    $mdb2 =& MDB2::connect($dsn, $options);

    if (PEAR::isError($mdb2)) {
	   echo '<font color="red">Kritikus hiba!<br>Nem lehet csatlakozni az adatb�zishoz!<br></font>';
	   die($mdb2->getMessage());
    }

	$mdb2->loadModule('Extended');
    $mdb2->setFetchMode(MDB2_FETCHMODE_ASSOC);

?>