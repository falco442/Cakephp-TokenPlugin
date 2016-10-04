<?php

App::uses('TokenAuthenticate', 'TokenAuth.Controller/Component/Auth');
App::uses('CakeTime', 'Utility');

class TokenShell extends AppShell {

	public function getOptionParser() {
	    $parser = parent::getOptionParser();
		$parser->addSubcommand('refresh', array(
		    'help' => __('Refresh token of the users'),
		    'parser' => array(
		        'description' => __(""),
		        'options' => array(
		            'model' => array('help' => __('The Model to use.'), 'required' => true,'default'=>'User','short'=>'m'),
		            'time' => array('help' => __('The period for which to keep tokens'), 'required' => true, 'default'=>'-15 days','short'=>'t'),
		            'colored'=>array('help'=>__('Make shell coloured'),'required'=>false,'default'=>false,'boolean'=>true,'short'=>'c')
		        )
		    )
		));
	    return $parser;
	}

    public function main() {
        $this->out('Hello world.');
    }

    public function refresh(){
    	$parser = $this->getOptionParser();
    	if(!$this->params['colored']){
    		$this->stdout->outputAs(ConsoleOutput::PLAIN);
    	}
    	if(!isset($this->params['model']) || empty($this->params['model'])){
    		$this->error('Model not selected','You have to select a model to use for UserModel');
    	}
    	if(!isset($this->params['time']) || empty($this->params['time'])){
    		$this->error('Timing not selected','You have to select the time for which to keep the tokens alive');
    	}

    	$this->out('<info>Starting to refresh tokens...</info>');


    	$model = $this->params['model'];
    	$this->loadModel($model);
    	$time = CakeTime::format($this->params['time'], '%F %T');

    	if(!CakeTime::isPast($time)){
    		$this->error('Time param','Time param must be set in the past (ex: -2 days)');
    	}

    	$this->out(sprintf("Minimum date for tokens is %s",$time));
    	$result = $this->$model->updateAll(
    		array(
    			$model.'.token'=>null,
    			$model.'.token_created'=>null
    		),
    		array($model.'.token_created <='=>$time)
    	);

    	if($result){
    		$this->out('<success>Operation succeeded</success>');
    	}
    	else{
    		$this->err();
    	}

    }
}