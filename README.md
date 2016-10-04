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


# Reset token

You can reset token by calling the shell

```
cd cake-root ./Console/cake TokenAuth.token refresh
```

**Note**: 
* the reset token task will take '-15days' as base token life, but you can customize the shell
* the shell take the model `User` as base, but you can set any model you like

Type in console

```
cd cake-root ./Console/cake TokenAuth.token refresh --help
```

to get some help