<?php

App::uses('FormAuthenticate', 'Controller/Component/Auth');

class TokenAuthenticate extends FormAuthenticate {

	public function authenticate(CakeRequest $request, CakeResponse $response) {
        $user = parent::authenticate($request,$response);
        if($user){
        	$userModel = $this->settings['userModel'];
        	$fields = $this->settings['fields'];
        	list($plugin, $model) = pluginSplit($userModel);

        	$userModelRegistry = ClassRegistry::init($userModel);

        	$conditions = array(
        		$model.'.'.$userModelRegistry->primaryKey => $user[$userModelRegistry->primaryKey]
        	);

			$result = $userModelRegistry->find('first', array(
			    'conditions' => $conditions,
			    'recursive' => 0
			));

			unset($user[$fields['password']]);
			unset($result[$model][$fields['password']]);

			if($result){
				$user['token'] = sha1(CakeText::uuid());
				if(!$userModelRegistry->save($user)){
					return false;
				}
			}
        }
        return $user;
    }

	public function getUser(CakeRequest $request){
		if(!$request->query('token'))
			return false;
		$userModel = $this->settings['userModel'];

		$fields = $this->settings['fields'];
		list($plugin, $model) = pluginSplit($userModel);

		$conditions = array(
		    $model . '.token' => $request->query('token'),
		);

		if (!empty($this->settings['scope'])) {
		    $conditions = array_merge($conditions, $this->settings['scope']);
		}

		$result = ClassRegistry::init($userModel)->find('first', array(
		    'conditions' => $conditions,
		    'recursive' => 0
		));
		if (empty($result) || empty($result[$model])) {
		    return false;
		}
		unset($result[$model][$fields['password']]);
		return $result[$model];
	}
}
