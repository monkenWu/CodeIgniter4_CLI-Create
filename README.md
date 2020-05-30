# CodeIgniter4_CLI-Create

Cli-Create is based on CodeIgniter4. It will help you generate template files more quickly when developing projects with CodeIgniter4.

[中文使用說明](https://hackmd.io/@monkenWu/ByZF1n4HL)

[Guide](https://hackmd.io/@monkenWu/HJndHeESU)

## Install

### Prerequisites
1. CodeIgniter Framework 4.*
2. Composer

### Composer Install

```
composer require monken/cli-create
```
### Use Library

Open Terminal in Mac/Linux or go to Run > “cmd” in Windows and navigate to CodeIgniter4 project’s root:

```
php spark list
```

Now, if you see the following message, the installation is successful.

```
CodeIgniter CLI Tool - Version 4.0.2 - Server-Time: 2020-03-09 12:04:21pm


Cli-Create
  create:controller  Create a new controller file.
  create:model       Create a new model file.
```

# Guide

## create:controller

Create a new controller file.

* Use
    ```
    $ php spark create:controller [controller_name] [Options]
    ```

* Description:
    ```
    Create a new controller file.
    ```
* Arguments:
    1. controller_name : The controller name.

* Options:
    ```
    -nobase      Do not extends BaseControllers Class.
    -usemodel    Choose models.
    -space       Create folders and files according to the path you typed.
    ```

### Create a normal Controller

You can use :

```
$ php spark create:contorller [controller_name]
```

Now, in "app/Controllers" You can see the new Contorller File like this :

```php
<?php namespace App\Controllers;


class User extends BaseController
{
    public function index()
    {
        echo 'Hello World!';
    }
}
```

### Use "-nobase" Option

By default, creating a controller file will extends the BaseController class. If you don't want the default settings, you can use:

```
$ php spark create:controller [controller_name] -nobase
```

The "-nobase" option can be declared after other options.

Now, in "app/Controllers" You can see the new Contorller File like this :

```php
<?php namespace App\Controllers;

use CodeIgniter\Controller;

class User extends Controller
{
    public function index()
    {
        echo 'Hello World!';
    }
}
```

### Use "-usemodel" Option

When creating a Controller file, if you need to directly select the Model that will be used, you can use this option.

```
$ php spark create:controller [controller_name] -usemodel
```

The "-usemodel" option can be declared after other options.

![](https://i.imgur.com/3KmcLhR.png)

Now, in "app/Controllers" You can see the new Contorller File like this :

```php
<?php namespace App\Controllers;

use App\Models\Api\InfoModel;
use App\Models\UserModel;

class Member extends BaseController
{
    public function index()
    {
        echo 'Hello World!';
    }
}
```

### Custom namespace

When creating a Controller file, if you need a custom namespace, you can use the following options:

```
$ php spark create:controller [controller_name] -space
```

The "-space" option can be declared after other options.

![](https://i.imgur.com/iNA6VEW.png)

> The namespace in Codeigniter usually maps the actual file storage path, so using this command will automatically create folders according to the value you entered.

Now, in “app/Controllers/System/Admin” You can see the new "-nobase" Controller File like this :

```php
<?php namespace App\Controllers\System\Admin;

use CodeIgniter\Controller;

class Login extends Controller
{
    public function index()
    {
        echo 'Hello World!';
    }
}

```

## create:controller -rest

Unlike traditional controllers, Codeigniter provides a way to quickly build a RESTFul API. Of course, the CLI-Create library can help you quickly create these files and automatically generate the required settings for you.

* Use
    ```
    $ php spark create:controller [controller_name] -rest [Options]
    ```

* Description:
    ```
    Create a new RESTful controller file.
    ```
* Arguments:
    1. controller_name : The controller name.

* Options:
    ```
    -d      The names of controller and router are different.  
    -o      Select options to create the function what you want.
    -w      Generate update and delete methods that work with HTML forms
    -space  Create folders and files according to the path you typed.
    ```

### Create a normal RESTFul Controller

When you do not need any settings, you can directly execute the following command:

```
php spark create:controller [controller_name] -rest
```

![](https://i.imgur.com/SoTvvte.png)

Now, in “app/Controllers” You can see the new RESTFul Controller File like this :

```php
<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class User extends ResourceController
{
    
    protected $modelName = 'App\Models\User';
    protected $format    = 'json';

    public function index(){
        return $this->respond([
            "status" => true,
            "msg" => "index method successful."
        ]);
    }
    
    /*****/
```

Then, go to "app / Config / Routes.php" and you will see that the routing settings for this Controller have been registered in your configuration file :

```php
/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

//CliCreate-add-in-2020-04-10 05:42:27
$routes->resource('user',[
    'controller' =>'\App\Controllers\User',
]);
```

> Please note that the function of automatically writing to the configuration file is to find the relative position through the comments in the file. Please do not change the comment in the official configuration file, so as not to write the wrong place and cause a program error.

### Create RESTFul API with different route name and controller name

You may use this option frequently. Sometimes, our router will not be associated with the location where the Controllers are stored, then it is very suitable to use this option.

```
php spark create:controller [controller_name] -rest -d -space
```

The "-d" option allows you to customize the router, and "-space" allows you to customize the controller's namespace. We believe that this requirement needs to be met using these two options, so please use this example to demo.


![](https://i.imgur.com/v9wi0WX.png)


The “-d” option can be declared after other options.

Now, in “app/Controllers/System/Api” You can see the new RESTFul Controller File like this :

```php

<?php namespace App\Controllers\System\Api;

use CodeIgniter\RESTful\ResourceController;

class User extends ResourceController
{
    
    protected $modelName = 'App\Models\User';
    protected $format    = 'json';

    public function index(){
        return $this->respond([
            "status" => true,
            "msg" => "index method successful."
        ]);
    }

    /*********/

```

Then, go to "app / Config / Routes.php" and you will see that the routing settings for this Controller have been registered in your configuration file :

```php
/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

//CliCreate-add-in-2020-04-10 05:49:09
$routes->resource('api/user',[
    'controller' =>'\App\Controllers\System\Api\User',
]);

```


### Create "WebSafe" RESTFul API

codeigniter allows you to add the "web safe" attribute when setting routes to have it generate update and delete methods that work with HTML forms

```
php spark create:controller [controller_name] -rest -w
```

The “-w” option can be declared after other options.

![](https://i.imgur.com/ae0gkXi.png)

Then, go to "app / Config / Routes.php" and you will see the websafe attribute appear in the router's settings.

```php
//CliCreate-add-in-2020-04-10 05:54:38
$routes->resource('user',[
    'controller' =>'\App\Controllers\User',
    'websafe' => 1,
]);
```

### Create RESTFul API and limit the routes made

You can restrict the routes generated with the option. Only routes that match one of these methods will be created. The rest will be ignored.

```
php spark create:controller [controller_name] -rest -o
```

![](https://i.imgur.com/NlzcKem.png)


The “-o” option can be declared after other options.

Now, in “app/Controllers/System/Api” You can see the new RESTFul Controller File like this :

![](https://i.imgur.com/oReZWr7.png)

Then, go to "app / Config / Routes.php" and you will see that the routes you allow to be created are recorded in the router setting :

```php
/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

//CliCreate-add-in-2020-04-10 05:59:28
$routes->resource('user',[
    'controller' =>'\App\Controllers\User',
    'only' => ['index','show','create','update','delete'],
]);

```

## create:controller -model

Create a new model for use with the  new controller.

* USE
	1. Use the options provided by the "create: model" directive.
    ```
    $ php spark create:model [controller_name] [controller_options] -model [model_name]
    ```
	3. The options provided using the "Create: Model" command are not used.
    ```
    $ php spark create:model [controller_name] [controller_options] -model=[model_options] [model_name] [entity_name] 
    ```

* Description:
    ```
   	Create new controller and model.
    ```
* Arguments:
	1. controller_name : controller name
	2. controller_options : Options provid by the controller.
	3. model_options : Options provid by the [create:model](#create:model) .If you want to use more than one option, please note that they must be separated by a comma, with "-" removed, and immediately after "=".
    1. model_name : model name.
    2. entity_name : entity name.If you use the entity option, you can type this argument.

### Create new controller and model

Cli-Create provides a way for you to create a controller and a model at the same time. You can accomplish this with a single line of "spark" command.

```
php spark create:controller [controller_name] -model [model_name]
```

![](https://i.imgur.com/u9jhIon.png)

Now, open app / Controllers / Blog.php and you should see the new controller file, and the correct model namespace has been written:

```php
<?php namespace App\Controllers;

use App\Models\BlogModel;

class Blog extends BaseController
{
    public function index()
    {
        echo 'Hello World!';
    }
}
```

Then, open app / Models / BlogModel.php and you will see that the models required by this controller have been created at the same time:

```php
<?php namespace App\Models;

use CodeIgniter\Model;

class BlogModel extends Model
{
    protected $table      = '';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['Field1', 'Field2'];

  /*********/

```

#### Of course, you can also use all the options provided by "[create:controller](#create:controller)" when using this command, like this:

```
php spark create:controller [controller_name] -space -nobase [model_name]
```

![](https://i.imgur.com/vCI18wX.png)

Now, open app / Controllers / Notice.php and you should see the new "-nobase" controller file:

```php
<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\NoticeModel;

class Notice extends Controller
{
    public function index()
    {
        echo 'Hello World!';
    }
}
```

Then, open app / Models / NoticeModel.php and you will see that the models required by this controller have been created at the same time:

```php
<?php namespace App\Models;

use CodeIgniter\Model;

class NoticeModel extends Model
{
    protected $table      = '';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['Field1', 'Field2'];

  /*********/

```

### Use model options

The [create:model](#create:model) command provides a variety of different models for you to choose freely. Of course, you can specify some options you want while creating the model and controller.

```
php spark create:controller [controller_name] -model=basic [model_name]
```

![](https://i.imgur.com/MJU0OlJ.png)

> The options you use must be immediately after "=". You can refer to all the options mentioned in [create:model](#create:model) for these options.

Now, open app / Controllers / Blog.php and you should see the new controller file, and the required model has been declared:

```php
<?php namespace App\Controllers;

use App\Models\BlogModel;

class Blog extends BaseController
{
    public function index()
    {
        echo 'Hello World!';
    }
}

```

Then, open app / Models / BlogModel.php and you will see that the "-basic" model required by this controller has been created at the same time:

```php
<?php namespace App\Models;

use CodeIgniter\Model;

class BlogModel extends Model
{
    protected $DBGroup = 'default';
}
```

### Use multiple model options

If you need to use multiple model options at the same time, you only need to separate them with a comma, like this:

```
php spark create:controller -space [controller_name] -model=basic,space [model_name]
```

![](https://i.imgur.com/8lovROQ.png)


This example is more complicated. create new controller, new model and entitiey, and customize the namespace.

### Create RESTFul controller and model

You can also create models in "[create:controller -rest](#create:controller--rest)" at the same time.

```
php spark create:controller -rest [controller_name] -model [model_name]
```

![](https://i.imgur.com/UvLY3oL.png)

This example is more complicated. create new RESTFul controller, new model and entitiey, and customize controller namespace.

```php
<?php namespace App\Controllers\System\Api;

use CodeIgniter\RESTful\ResourceController;

class User extends ResourceController
{
    
    protected $modelName = 'App\Models\UserModel;
';
    protected $format    = 'json';

    public function index(){
        return $this->respond([
            "status" => true,
            "msg" => "index method successful."
        ]);
    }
/********/
```

## create:model

Create a new model file.

* Use
    ```
    $ php spark create:model [model_name] [entity_name] [Options]
    ```

* Description:
    ```
    Create a new model file.
    ```
* Arguments:
    1. model_name : The model name
    2. entity_name : The entity name. If you selected -entity option. You can type this arguments.
* Options:
    ```
    -basic    Creates a basic model file.
    -entity   Uses Entity Classes.
    -manual   Creates a Manual Model.
    -space    Creates folders and files according to the path you typed.
    ```

### Create a normal model

The model class has a few configuration options that can be set to allow the class’ methods to work seamlessly for you.

You can use :

```
$ php spark create:model [model_name]
```

Now, in "app/Models" You can see the new Model File like this :

```php
<?php namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['Field1', 'Field2'];

    protected $useTimestamps = false;
```

### Create a basic model

Use Command:

```
$ php spark create:model [model_name] -basic
```

Now, in “app/Models” You can see the new basic Model File like this :

```php
<?php namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $DBGroup = 'group_name';
}
```

This empty class provides convenient access to the database connection, the Query Builder, and a number of additional convenience methods.

You would replace “group_name” with the name of a defined database group from the database configuration file.

### Create a manual model

If you do not need to extend any special class to create a model for your application. All you need is to get an instance of the database connection.

You can use this:

```
$ php spark create:model [model_name] -manual
```

Now, in “app/Models” You can see the new Manual Model File like this :

```php
<?php namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class User
{
    protected $db;

    public function __construct(ConnectionInterface &$db)
    {
        $this->db =& $db;
    }
}
```

### Create a entity model

CodeIgniter supports Entity classes as a first-class citizen in it’s database layer, while keeping them completely optional to use. They are commonly used as part of the Repository pattern, but can be used directly with the Model if that fits your needs better.

You can use this command :

```
$ php spark create:model [model_name] [entity_name] -entity
```
Now, in “app/Models” You can see the new Manual Model File like this :

```php
<?php namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table         = 'users';
    protected $allowedFields = [
        'Filed1', 'Filed2'
    ];
    protected $returnType    = 'App\Entities\User';
    protected $useTimestamps = true;
}
```

And in “app/Entitsies” You can see the new Manual Model File like this :

```php
<?php namespace App\Entities;

use CodeIgniter\Entity;

class User extends Entity
{
    //
}
```

### Custom namespace

When creating a Model FILE, if you need a custom namespace, you can use the following options:

```
$ php spark create:model [model_name] -space
```

The "-space" option can be declared after other options.

![](https://i.imgur.com/cXC9hW2.png)

> The namespace in Codeigniter usually maps the actual file storage path, so using this command will automatically create folders according to the value you entered.

Now, in “app/Models/Api/System” You can see the new Manual Model File like this :

```php
<?php namespace App\Models\Api\System;

use CodeIgniter\Model;

class User extends Model
{
    //...
}
```
