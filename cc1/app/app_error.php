<?php 
  class AppError extends ErrorHandler {
  
	
    function error404($params) {    	
      header("HTTP/1.0 404 Not Found");
      $this->controller->redirect(array('controller'=>'users', 'action'=>'er404','404'));
    }
  function error500($params) {
      header("HTTP/1.0 500 Internal Server Error");
      $this->controller->redirect(array('controller'=>'users', 'action'=>'er404','500'));
    }
    
  function error503($params) {
      header("HTTP/1.0 500 Service Temporarily Unavailable");
      $this->controller->redirect(array('controller'=>'users', 'action'=>'er404','503'));
    }  
  }

?>
