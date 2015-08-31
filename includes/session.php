<?php
/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */
/**
 * Session kezelõ beállítása 
 */
ini_set('session.save_handler', 'user');

class Session_MDB2 {
    // session-lifetime
    var $lifeTime;
    // mysql-handle
    var $dbHandle;
    // Session table
    var $table;

    function Session_MDB2(&$mdb2, $table) {
        $this->dbHandle =& $mdb2;
        $this->table    = $table;
    }

    function open($savePath, $sessName) {
        // get session-lifetime
        $this->lifeTime = get_cfg_var("session.gc_maxlifetime");
        return true;
    }

    function close() {
        $this->gc(ini_get('session.gc_maxlifetime'));
        // close database-connection
    }

    function read($sessID) {
        $mdb2 =& $this->dbHandle; 
        // fetch session-data
        $query = "
            SELECT session_data AS d 
			FROM $this->table 
            WHERE session_id = '$sessID' 
            AND session_expires > ".time()
		;
        $result =& $mdb2->query($query);

        // return data or an empty string at failure
        if($row = $result->fetchRow($result)) {
             return $row[0];
        }
        return "";
    }

    function write($sessID,$sessData) {
        // new session-expire-time
        $newExp = time() + $this->lifeTime;
        // is a session with this id in the database?
        $query = "
			SELECT * 
			FROM $this->table 
            WHERE session_id = '$sessID'
		";
        $result =& $this->dbHandle->query($query);

        $sessData = $this->dbHandle->escape($sessData);
        // if yes,
        if($result->numRows()) {
            // ...update session-data
            $query="
                UPDATE $this->table
                SET session_expires = '$newExp',
                    session_data = '$sessData'
                WHERE session_id = '$sessID'
			";
            $affected = $this->dbHandle->exec($query);

            // if something happened, return true
            if(!PEAR::isError($affected)) {
                return true;
            }
        }
        // if no session-data was found,
        else {
            // create a new row
            $query = "
            	INSERT INTO $this->table 
                (session_id, session_expires, session_data) 
                VALUES 
				('$sessID', '$newExp','$sessData')
			";
            $affected = $this->dbHandle->exec($query);
            // if row was created, return true
            if(!PEAR::isError($affected)) {
                return true;
            }
        }
        // an unknown error occured
        return false;
    }
    
    function destroy($sessID) {
        // delete session-data
        $query = "
			DELETE FROM $this->table 
			WHERE session_id = '$sessID'
		";
        $affected = $this->dbHandle->exec($query);
        // if session was deleted, return true,
        if(!PEAR::isError($affected)) {
            return true;
        }
        // ...else return false
        return false;
    }

    function gc($sessMaxLifeTime) {
        // delete old sessions
        $query = "
			DELETE FROM $this->table 
			WHERE session_expires < ".time()
		;
        $affected = $this->dbHandle->exec($query);
        // return affected rows
        return $affected;
    }
}

$session =& new Session_MDB2($mdb2, 'iShark_Sessions');
session_set_save_handler(
    array(&$session,"open"),
    array(&$session,"close"),
    array(&$session,"read"),
    array(&$session,"write"),
    array(&$session,"destroy"),
    array(&$session,"gc"));

register_shutdown_function('session_write_close');  

// Ezután hagyományosan használható a munkamenet

?>
