<?php
class Pagination {
    private $resultsPerPage = 100;
    private $results;
    private $page;
    private $numResults;

    /**
    * Constructor for Pagination class
    *
    * @param array $results The result set to paginate
    */

    function __construct($results) {
        $this->results = $results;
        $this->numResults = count($results);
    }

    /**
    * Returns results of result set for page
    *
    * @param integer $page The page of results to get
    *
    * @return array Results for page
    */
    public function getResultsPage($page) {
        // if no page then set page to 1
        if ($page === NULL) {
            $this->page = 1;
        } else {
            $this->page = $page;
        }
        return array_slice(
            $this->results, 
            ($page - 1) * $this->resultsPerPage, 
            $this->resultsPerPage
        );
    }

    /**
    * Returns the number of total pages based on results per page
    *
    * @return integer Total number of pages
    */
    public function numPages() {
        if ($this->numResults > $this->resultsPerPage) {
            return ceil($this->numResults / $this->resultsPerPage);
        } else {
            return 1;
        }
    }
}
?>