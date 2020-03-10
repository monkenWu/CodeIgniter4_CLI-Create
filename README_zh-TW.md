# CodeIgniter4_CLI-Create 中文文件

Cli-Create 基於 CodeIgniter4 。它將可以幫助你在使用 CodeIgniter4 開發專案時，更加迅速地產生各式模板檔案。

## 安裝

### 需求
1. CodeIgniter Framework 4.*
2. Composer

### Composer 安裝

使用 Mac/Linux 打開終端機，或者是使用 Windows 打開命令提示字元。將終端機或命令提示字元導航至 CodeIgniter4 專案根目錄：

```
composer require monken/cli-create
```
### 使用程式庫

使用 Mac/Linux 打開終端機，或者是使用 Windows 打開命令提示字元。將終端機或命令提示字元導航至 CodeIgniter4 專案根目錄：

```
php spark list
```

如果你看見下列訊息，就代表安裝成功。

```
CodeIgniter CLI Tool - Version 4.0.2 - Server-Time: 2020-03-09 12:04:21pm


Cli-Create
  create:controller  Create a new controller file.
  create:model       Create a new model file.
```

# 使用指南

## create:controller

創建新的控制器檔案。

* 使用規則
    ```
    $ php spark create:controller [controller_name] [Options]
    ```

* 簡介:
    ```
    創建新的控制器檔案。
    ```
* 引數:
    1. controller_name : 控制器名稱。

* 選項:
    ```
    -nobase      不繼承 BaseControllers 類別.
    -usemodel    選擇欲使用的模型。
    -space       根據你輸入的路徑創建文件夾和文件。
    ```

### 新建一個普通的控制器

使用以下指令 :

```
$ php spark create:contorller [controller_name]
```

現在，打開「 app/Controllers 」你應該可以看見新的控制器檔案：

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

### 使用 "-nobase" 選項

創建控制器檔案預設會繼承 BaseController 類別，如果你不想要這個預設的設定，你可以使用：

```
$ php spark create:controller [controller_name] -nobase
```

「 -nobase 」這個選項可以與任何選項混用。

現在，打開「 app/Controllers 」你應該可以看見新的控制器檔案：

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

### 使用 "-usemodel" 選項

創建控制器檔案時，如果已經有可以直接使用的模型檔案，你可以透過這個指令直接選擇你需要使用的模型。你所選擇的模型的命名空間將會自動地加入到你所創建的控制器檔案。

```
$ php spark create:controller [controller_name] -usemodel
```

「 -usemodel 」 這個選項可以與任何選項混用。

![](https://i.imgur.com/3KmcLhR.png)

現在，打開「 app/Controllers 」你應該可以看見新的控制器檔案：

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

### 自定義命名空間

創建控制器檔案時，如果你需要自定義命名空間，你就可以使用下列選項：

```
$ php spark create:controller [controller_name] -space
```

「 -space 」 這個選項可以與任何選項混用。

![](https://i.imgur.com/iNA6VEW.png)

> 在 Codeigniter 中的命名空間通常映射自實際檔案位置。所以使用這個指令時，將會自動創建符合你所輸入的命名空間規則的資料夾。

現在，打開「 app/Controllers/System/Admin 」你應該可以看見新的「 -nobase 」控制器檔案：

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

創建新的模型檔案。

* 使用規則
    ```
    $ php spark create:model [model_name] [entity_name] [Options]
    ```

* 簡介:
    ```
    Create a new controller file.
    ```
* 引數:
    1. model_name : 模型名稱。
    2. entity_name : 實體名稱。如果你使用了 -entity 這個選項，你就可以輸入這個引數。
* 選項:
    ```
    -basic    新建基本的模型。
    -entity   新建模型與實體。
    -manual   新建手動模型。
    -space    根據你鍵入的路徑創建文件夾和文件。
    ```

### 新建一個普通模型

CodeIgniter\Model 提供一些設定的選項，通過設定這些選項模型類別將可以更好地運作。

你可以使用以下指令 :

```
$ php spark create:model [model_name]
```

現在，打開「 app/Models 」你應該可以看見新的模型檔案：

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

### 新建一個基本模型

使用命令：

```
$ php spark create:model [model_name] -basic
```

現在，打開「 app/Models 」你應該可以看見新的基本模型：

```php
<?php namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $DBGroup = 'group_name';
}
```

這個空的類別提供了資料庫連接、查詢產生器等功能，也可以呼叫許多 CodeIgniter\Model 定義的便利方法。

你也可以利用設定文件中定義的資料庫相關設定，替換掉 group_name ，以利用你所設定的連線資料。

### 新建手動模型

如果你不需要任何 CodeIgniter\Model 所提供的特殊方法，你也可以創建普通的模型檔案。若是使用手動模型，你可能需要額外獲取實作後的資料庫連接物件。

使用下列指令創建：

```
$ php spark create:model [model_name] -manual
```

現在，打開「 app/Models 」你應該可以看見新的手動模型：

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

### 新建一個實體模型

CodeIgniter 支援將實體類別作為第一類物件，並且這是一個可選的功能。它通常是資料庫模式中的一個部分，若是它符合你的需求，可以與模型一起使用。

使用以下指令建立：

```
$ php spark create:model [model_name] [entity_name] -entity
```
現在，打開「 app/Models 」你應該可以看見新的實體模型：

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

現在，打開「 app/Entitsies 」你應該可以看見新的實體檔案：

```php
<?php namespace App\Entities;

use CodeIgniter\Entity;

class User extends Entity
{
    //
}
```

### 自定義命名空間

創建控制器檔案時，如果你需要自定義命名空間，你就可以使用下列選項：

```
$ php spark create:model [model_name] -space
```

「 -space 」 這個選項可以與任何選項混用。

![](https://i.imgur.com/cXC9hW2.png)

> 在 Codeigniter 中的命名空間通常映射自實際檔案位置。所以使用這個指令時，將會自動創建符合你所輸入的命名空間規則的資料夾。

現在，打開「 app/Models/Api/System 」你應該可以看見新的「 -nobase 」控制器檔案：

```php
<?php namespace App\Models\Api\System;

use CodeIgniter\Model;

class User extends Model
{
    //...
}
```
