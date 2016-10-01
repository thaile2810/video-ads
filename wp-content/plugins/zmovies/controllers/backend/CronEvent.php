<?php
namespace Zmovies\Controller\Backend;

class CronEvent{
	
	public function __construct(){
		global $zController;
		
		$action = $zController->getParams('action');
		switch ($action){
		    case 'zvideo-run-cron':
		        $this->runCron();
		        break;
            case 'add-cron-event':
                $this->addCronEvent();
                break;
            case 'add-php-cron-event':
                $this->addPhpCronEvent();
                break;
            case 'zvideo-delete-cron':
                $this->deleteCron();
                break;
		    default:
		        $this->display();
		        break;
		}
		
	}
	
	public function display(){
	    global $zController;
	    
	    $zController->getView('/cron-event/display.php','/backend');
	}
	public function runCron(){
	    global $zController;
	    $model = $zController->getModel('CronEvent');
	    $model->runCron();
	}
	public function deleteCron(){
	    global $zController;
	    $model = $zController->getModel('CronEvent');
	    $model->deleteCron();
	}
	public function addCronEvent(){
	    global $zController;
	     
	    $zController->getView('/cron-event/form-cron-event.php','/backend');
	}
	public function addPhpCronEvent(){
	    global $zController;
	     
	    $zController->getView('/cron-event/form-php-cron-event.php','/backend');
	}	
    
}