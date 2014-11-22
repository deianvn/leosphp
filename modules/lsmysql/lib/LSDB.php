<?php

use ls\core\LSLogger as LSLogger;

class LSDB {
    
    private $conn = null;
    
    function connect($host, $userName, $password, $dbName) {
        $this->conn = mysqli_connect($host, $userName, $password);
        $error = mysqli_connect_error();

        if ($error) {
            LSLogger::error('Error connecting to db: ' . $error);
            return false;
        }

        if (mysqli_select_db($this->conn, $dbName) === false) {
            LSLogger::error('Error selecting db: ' . $dbName);
            return false;
        }

        return $this->conn;
    }
    
    function isActive() {
        return $this->conn !== null;
    }

    function disconnect() {
        $ret = mysqli_close($this->conn);
        $this->conn = null;
        return $ret;
    }

    function begin() {
        return mysqli_autocommit($this->conn, false);
    }

    function commit() {
        return mysqli_commit($this->conn);
    }

    function rollback() {
        return mysqli_rollback($this->conn);
    }

    function query($query) {
        LSLogger::debug($query);
        return mysqli_query($this->conn, $query);
    }

    function fileQuery($queryPath) {
        if (!file_exists($queryPath)) {
            LSLogger::error('Query not found: ' . $queryPath);
            exit(0);
        }

        $query = file_get_contents($queryPath);

        return mysqli_query($this->conn, $query);
    }

    function queries($queryName, $automatic = true) {
        $queries = dbLookupQuery($queryName);

        if ($automatic === true) {

            mysqli_multi_query($this->conn, $queries);
            $ret = true;

            do {
                $result = mysqli_store_result($link);

                if (mysqli_errno($this->conn) !== 0) {
                    $ret = false;
                } else if ($result !== false) {
                    mysqli_free_result($result);
                }
            } while (mysqli_next_result($this->conn));

            return $ret;
        } else {
            return mysqli_multi_query($this->conn, $queries);
        }
    }

    function fileQueries($queryPath, $automatic = true) {
        if (!file_exists($queryPath)) {
            LSLogger::error('Query not found: ' . $queryPath);
            header("HTTP/1.0 404 Not Found");
            exit(0);
        }

        $queries = file_get_contents($queryPath);

        if ($automatic === true) {

            mysqli_multi_query($this->conn, $queries);
            $ret = true;

            do {
                $result = mysqli_store_result($this->conn);

                if (mysqli_errno($this->conn) !== 0) {
                    $ret = false;
                } else if ($result !== false) {
                    mysqli_free_result($result);
                }

                if (!mysqli_more_results($this->conn)) {
                    break;
                }
            } while (mysqli_next_result($this->conn));

            return $ret;
        } else {
            return mysqli_multi_query($this->conn, $queries);
        }
    }

    function storeResult() {
        return mysqli_store_result($this->conn);
    }

    function freeResult($result) {
        return mysqli_free_result($result);
    }

    function rowsNumber($result) {
        return mysqli_num_rows($result);
    }

    function rowsAffected() {
        return mysqli_affected_rows($this->conn);
    }

    function fetchRow($result) {
        return mysqli_fetch_row($result);
    }

    function fetchAssoc($result) {
        return mysqli_fetch_assoc($result);
    }

    function lastGeneratedID() {
        return mysqli_insert_id($this->conn);
    }

    function error() {
        return mysqli_error($this->conn);
    }

    function esc($value) {
        return mysqli_real_escape_string($this->conn, $value);
    }
}
