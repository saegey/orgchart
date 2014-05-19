<?php
/**
* Provides a wrapper class for Employee database table.
*/

class Employee {
    /**
    * Returns the employees joined for their bossname
    *
    * @param object $dbConnection Database Connection
    *
    * @return array Array of employee records joined for boss name
    */
    public static function all($dbConnection) {
        $sql = "SELECT e1.*, e2.name as bossName FROM employees e1, employees e2 where e1.bossId = e2.id";
        $employees = $dbConnection->fetchAll($sql);
        return $employees;
    }
}

?>