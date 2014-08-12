<?php

class PayboardVars {
    
    private $vars = array();

    public static function getInstance(){
        global $payboardVars;

        if($payboardVars == null)
                $payboardVars = new PayboardVars();

        return $payboardVars;
    }

    public function __set($key, $value){
            $this->vars[$key] = $value;
    }

    public function __get($key){
            return $this->vars[$key];
    }
}

?>
