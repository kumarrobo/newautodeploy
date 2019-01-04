<?php

//App::import('Component', 'Email'); // Import EmailComponent to make it available
App::import('Core', 'Controller'); // Import Controller class to base our App's controllers off of
App::import('Controller', 'Crons'); // Import PostsController to make it available
//App::import('Sanitize'); // Import Sanitize class to make it available

class ExecShell extends Shell {
    //var $uses = array('Crons'); // Load Post model for access as $this->Post

    function startup() {
        $this->Crons = new CronsController(); // Create PostsController object
        $this->Crons->constructClasses(); // Set up PostsController
       // $this->Crons->Security->initialize(&$this->Crons); // Initialize component that's attached to PostsController. This is needed if you want to call PostsController actions that use this component
    }

    function main() {
        //plansDetails
        //$this->out($this->Email->delivery); // Should echo 'mail' on the command line
        //$this->out(Sanitize::html('<p>Hello</p>')); // Should echo &lt;p&gt;Hello&lt;/p&gt;  on the command line
        
       // $this->out();
        if($this->args[0] == "getFromApi"){
            $this->out( $this->Crons->plansDetails("getFromApi")); // Should echo 'Index action' on the command line
        }else{
            $this->out( $this->Crons->plansDetails("getFromFile")); // Should echo 'Index action' on the command line
        }
        
        $this->out("\n----------  Process complete ---------- ");
        //var_dump(is_object($this->Posts->Security)); // Should echo 'true'
    }
    function checkVendor() {
        //plansDetails
        //$this->out($this->Email->delivery); // Should echo 'mail' on the command line
        //$this->out(Sanitize::html('<p>Hello</p>')); // Should echo &lt;p&gt;Hello&lt;/p&gt;  on the command line
        
        $this->out("\n********** Cheking Vendor  ( date - ".date("d-m-yyyy H:i:s")." ) ********** ");
        $this->Crons->checkVendors();
        $this->out("\n----------  Process complete ---------- ");
        //var_dump(is_object($this->Posts->Security)); // Should echo 'true'
    }
}

?>