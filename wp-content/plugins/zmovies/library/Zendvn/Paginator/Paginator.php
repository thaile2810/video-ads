<?php
namespace Zendvn\Paginator;

class Paginator{
	
    public $first = 1;
    public $last = 1;
    public $next;
    public $previous;
    public $current;
    public $pageCount = null;
    public $pagesInRange = array();
    
    private static $defaultItemCountPerPage = 10;
    
    private $count = 10;
    private $itemCountPerPage = null;
    private $currentPageNumber = 1;
    private $pageRange = 10;
    
    
    public function __construct($count, $paginatorParams){
        $this->count = $count;
        $this->setCurrentPageNumber($paginatorParams['currentPageNumber']);
        $this->setPageRange($paginatorParams['pageRange']);
        $this->setItemCountPerPage($paginatorParams['itemCountPerPage']);
    }
	public function create(){
        $currentPageNumber = $this->getCurrentPageNumber();

        $this->last             = $this->pageCount;
        $this->current          = $this->currentPageNumber;

        // Previous and next
        if ($currentPageNumber - 1 > 0) {
            $this->previous = $currentPageNumber - 1;
        }

        if ($currentPageNumber + 1 <= $this->pageCount) {
            $this->next = $currentPageNumber + 1;
        }

        // Pages in range
        $this->getPagesInRange();

        return $this;
	}
	public function getPagesInRange(){
	    
	    if ($this->pageRange > $this->pageCount) {
	        $this->pageRange = $this->pageCount;
	    }
	    
	    $delta = ceil($this->pageRange / 2);
	    
	    if ($this->currentPageNumber - $delta > $this->pageCount - $this->pageRange) {
	        $min = $this->pageCount - $this->pageRange + 1;
	        $max = $this->pageCount;
	    } else {
	        if ($this->currentPageNumber - $delta < 0) {
	            $delta = $this->currentPageNumber;
	        }
	    
	        $offset     = $this->currentPageNumber - $delta;
	        $min = $offset + 1;
	        $max = $offset + $this->pageRange;
	    }
	    for ($min; $min <= $max; $min++){
	        $this->pagesInRange[] = $min;
	    }
	}
	protected function _calculatePageCount(){
	    return (int) ceil($this->count / $this->getItemCountPerPage());
	}
	public function getCurrentPageNumber(){
        return $this->normalizePageNumber($this->currentPageNumber);
    }
	private function setCurrentPageNumber($pageNumber){
	    $this->currentPageNumber = (int) $pageNumber;
	    return $this;
	}
	private function setPageRange($pageRange){
	    $this->pageRange = (int) $pageRange;
	}
	private function setItemCountPerPage($itemCountPerPage = -1){
	    $this->itemCountPerPage = (int) $itemCountPerPage;
	    if ($this->itemCountPerPage < 1) {
	        $this->itemCountPerPage = $this->getTotalItemCount();
	    }
	    $this->pageCount        = $this->_calculatePageCount();
	}
	public function normalizePageNumber($pageNumber){
	    $pageNumber = (int) $pageNumber;
	    if ($pageNumber < 1) {
	        $pageNumber = 1;
	    }
	    $pageCount = $this->count();
	
	    if ($pageCount > 0 && $pageNumber > $pageCount) {
	        $pageNumber = $pageCount;
	    }
	    return $pageNumber;
	}
	public function count(){
	    if (!$this->pageCount) {
	        $this->pageCount = $this->_calculatePageCount();
	    }
	    return $this->pageCount;
	}
	public function getItemCountPerPage(){
	    if (empty($this->itemCountPerPage)) {
	        $this->itemCountPerPage = static::$defaultItemCountPerPage;
	    }
	    return $this->itemCountPerPage;
	}
}