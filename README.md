# CodeIgniter4_CLI-Create

Cli-Create is based on CodeIgniter4. It will help you generate template files more quickly when developing projects with CodeIgniter4.

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

By default, creating a controller file will inherit the BaseController class. If you don't want the default settings, you can use:

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


## create:model

Create a new model file.

* Use
    ```
    $ php spark create:model [model_name] [entity_name] [Options]
    ```

* Description:
    ```
    Create a new controller file.
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
