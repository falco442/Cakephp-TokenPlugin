**Note** : This application is in development

___


# Cakephp-TokenPlugin


This is the plugin for make an authentication done with Tokens.

## Requirements

* CakePHP 2.x

# Installation

## Getting plugin

You can install the plugin by manually download, or by composer

```
composer require falco442/cakephp-token-plugin
```

## Preparing  tables

Put into the table you use for authentication model ('users') the fields 'token' (varchar(255)) and 'token_created' (datetime).

## Loading plugin

Load the plugin by calling

```PHP
CakePlugin::loadAll();
```

or

```PHP
CakePlugin::load('TokenAuth');
```

and put the Authentication object in your `AppController.php`:

```PHP
public $components = [
	'...',
	'RequestHandler', 					// suggested if you want REST
	'Auth'=>[
		'authenticate'=>[
			'TokenAuth.Token'
		],
		'unauthorizedRedirect'=>false	// suggested if you want REST
	]
];
```

Keep in mind that you can customize the Authentication object with the same parameters you would have used with FormAuthenticate, like `userModel` and `fields`


# Use

## In Controller

You can set up the login action for your controller; for example, the action `login()` in `UsersController.php`:

```PHP
public function login(){
	$user = $this->Auth->identify($this->request,$this->response);
	$this->set(compact('user'));
	$this->set('_serialize',['user']);
}
```

Since the token authentication is done mainly for API applications, all you need is to retrieve the `$user` object that contains the new token that TokenAuth automatically generates. This token will be used to do all the calls to the actions that you don't want to be publicly accessible.

If you want an action to be public, simply use

```PHP
$this->Auth->allow(array('action-name'));
```

in the `beforeFilter()` method in respective controller.

The non-public routes that a client will call shall be of the form

```
GET /uri.json?token=token-received
```



## Reset token

You can reset token by calling the shell

```
cd cake-root ./Console/cake TokenAuth.token refresh
```

**Note**: 
* the reset token task will take '-15 days' as base token life, but you can customize the shell
* the shell take the model `User` as base, but you can set any model you like

Type in console

```
cd cake-root ./Console/cake TokenAuth.token refresh --help
```

to get some help

# Useful info

Since we use (mainly) token authentication for api web applications, it is useful to set REST in CakePHP (see [http://book.cakephp.org/2.0/en/development/rest.html](this page)).

This is done with simple steps:

* modify the `cake-root/app/Config/routes.php` by adding `Router::parseExtensions('json','xml');` (or with the extensions you desires)
* in `cake-root/app/Controller/AppController.php` add the `RequestHandler` component; it will parse the extension of the format (json, xml, ...)
* **if you want REST** you can map the resources (as [http://book.cakephp.org/2.0/en/development/rest.html#the-simple-setup](this page) says) with the method `Router::mapResources()`, to be put in `cake-root/app/Config/routes.php`