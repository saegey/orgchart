<?php
/**
 * Provides a way to create organizational data for employees.
 */
class OrgChart {
    //property declaration
    private $originalData = array();
    private $index = array();
    private $calcData = array();

    /**
    * Returns instance of OrgChart
    *
    * @param array $employees Array of employees
    *
    * @return object Instance of OrgChart;
    */

    function __construct($employees) {
        foreach($employees as $employee) {
            $id = $employee['id'];
            $bossId = $this->calcBoss($employee);
            // create array with employee id as key to make retrieval easier
            $this->originalData[$id] = $employee;
            // creates index with bossId as key and direct reports
            $this->index[$bossId][] = $id;
        }
    }

    /**
    * Get method for instance array calcData
    *
    * @return array calcData;
    */

    public function getCalcData() {
        return $this->calcData;
    }

    /**
     * Builds array calcData with subordinate and distance from ceo data.
     */

    public function calc() {
        $this->getChildNodes(0, 0, 0);
    }

    /**
     * Filters calcData array by name [Currently case sensitive]
     *
     * @param string $filter The string to search the name field with
     */

    public function filterByName($filter) {
        if (strlen($filter) > 0) {
            $this->calcData = array_filter($this->calcData, function($item) use ($filter) {
                if (strpos($item['name'], $filter) !== false) {
                    return $item;
                }
            });
        }
    }

    /**
     * Sorts calcData by field and in ascending or descending order
     *
     * @param string $sortField The field to sort the array by.
     * @param string $sortDirection The direction to sort by (asc/desc)
     */

    public function sortBy($sortField, $sortDirection) {
        $this->sortField = $sortField;
        $this->sortDirection = $sortDirection;
        uasort($this->calcData, function($a, $b) {
            if ($a[$this->sortField] == $b[$this->sortField]) {
                return 0;
            } else {
                if ($this->sortDirection === "asc") {
                    return ($a[$this->sortField] > $b[$this->sortField]) ? 1 : -1;
                } else {
                    return ($a[$this->sortField] < $b[$this->sortField]) ? 1 : -1;
                }
            }
        });
    }

    /**
     * Recursively retrieves child nodes to find level
     * and stores left and right position data for nested list
     * to calculate number of subordinates
     *
     * @param integer $bossId The employees bossId or null if the top element.
     * @param integer $level The current level of recursion
     * @param integer $left The left value in a nested listed
     *
     * @return integer The right value of a nested listed incremented by 1.
     */

    private function getChildNodes($bossId, $level, $left) {
        $right = $left + 1;

        if (isset($this->index[$bossId])) {
            foreach ($this->index[$bossId] as $id) {
                $this->calcData[$this->originalData[$id]['id']] = array(
                    "id"       => $this->originalData[$id]['id'],
                    "name"     => $this->originalData[$id]["name"],
                    "bossName" => $this->originalData[$id]["bossName"],
                    "level"    => $level
                );
                $right = $this->getChildNodes($id, $level + 1, $right);
            }
        }
        if ($bossId !== 0 && $this->calcData[$bossId]) {
            $this->calcData[$bossId]["subordinates"] = (($right - 1 ) - $left) / 2;
        }
        return $right + 1; 
    }

    /**
     * Calcs if is the head boss, if not 
     *
     * @param array $employee Employee record.
     *
     * @return integer/string NULL if head boss or bossId
     */

    private static function calcBoss($employee) {
        return $employee['bossId'] === $employee['id'] ? 0 : $employee["bossId"];
    }
}
?>