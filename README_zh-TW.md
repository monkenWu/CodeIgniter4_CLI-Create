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

## create:controller -rest

與傳統的控制器不同， Codeigniter 內建一種快速建構 RESTFul API 的方法。當然，CLI-Create 程式庫也可以幫助你快速地創建這些檔案，並自動為你產生所需的設定。

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
    -d      控制器和路由的名稱是不同的。  
    -o      選擇你想要創建的函數。
    -w      Websafe ，創建有善於 html 表單的APIs
    -space  根據你所輸入的路徑創建資料夾與檔案
    ```
    
### 新建一個普通的 RESTFul 控制器

當你不需要任何設定時，可以直接執行以下指令。


```
php spark create:controller [controller_name] -rest
```

![](https://i.imgur.com/SoTvvte.png)

現在，在 app/Controllers 路徑下，你可以看到新的 RESTFul 控制器檔案，就像這樣：

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

然後，打開 app / Config / Routes.php 你會看到這個控制器所需的路由設定，已經在你的設定檔案中被註冊了：

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

> 請注意，自動寫入設定檔案的功能，是通過檔案中的註解位置定位到要寫入的確切位置。請不要修改正式設定檔案中的任何註解，以免寫入錯誤，造成程式錯誤。

### 使用不同的路由名稱和控制器名稱創建 RESTFul API

你可能會經常使用這個選項，因為有的時候，我們的路由不會與控制器的存放位置（或命名空間）有關聯，那麼使用這個選項將會非常合適。

```
php spark create:controller [controller_name] -rest -d -space
```

-d 選項可以自訂義路由， -space 選項可以自訂義控制器的命名空間。我們認為需要用這兩個選項才能滿足這個需求，所以請用這個範例來進行演示。

![](https://i.imgur.com/v9wi0WX.png)

-d 這個選項可以與任何選項混用。

現在，在 app/Controllers 路徑下，你可以看到新的 RESTFul 控制器檔案，就像這樣：

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

然後，打開 app / Config / Routes.php 你會看到這個控制器所需的路由設定，已經在你的設定檔案中被註冊了：

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

### 新建 WebSafe 的 RESTFul API

codeigniter 允許你在設定路由時添加 web safe 屬性，它將會自動生成友善於 HTML 表單的更新與刪除方法。

```
php spark create:controller [controller_name] -rest -w
```

-w 這個選項可以與任何選項混用。

![](https://i.imgur.com/ae0gkXi.png)

然後，打開 app / Config / Routes.php 你會看到路由的設定中出現了 websafe 屬性。

```php
//CliCreate-add-in-2020-04-10 05:54:38
$routes->resource('user',[
    'controller' =>'\App\Controllers\User',
    'websafe' => 1,
]);
```

### Create RESTFul API and limit the routes made

你可以限制所要生成的路由為何，只有符合你所選擇的路由才會被創建，其他的路由將會被忽略。

```
php spark create:controller [controller_name] -rest -o
```

![](https://i.imgur.com/NlzcKem.png)


-o 這個選項可以與任何選項混用。

現在，在 app/Controllers 路徑下，你可以看到新的 RESTFul 控制器檔案，就像這樣：

![](https://i.imgur.com/oReZWr7.png)

然後，打開 app / Config / Routes.php 你會看到你所允許創建的路由將會被紀錄在路由設定中。

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

同時創建新的模型檔案以及控制器檔案。

* 使用規則
	1. 不使用 create:model 指令的 options
    ```
    $ php spark create:model [controller_name] [controller_options] -model [model_name]
    ```
	2. 使用 create:model 指令的 option
    ```
    $ php spark create:model [controller_name] [controller_options] -model=[model_options] [model_name] [entity_name] 
    ```

* 簡介:
    ```
    同時新建控制器以及模型檔案。
    ```
* 說明:
	1. controller_name : 控制器名稱。
	2. controller_options : 控制器可使用的選項。
	3. model_options : 可用選項，與 [create:model](#create:model) 指令提供的選項相同，若你想使用的選項不只一個，請注意，它們必須以逗號分隔且去除 「-」 且串接在 「=」 後面。
    1. model_name : 模型名稱。
    2. entity_name : 實體名稱。如果你使用了 entity 這個選項，你就可以輸入這個引數。

### 同時新建控制器與模型

Cli-Create 提供了讓你同時創建控制器以及模型的方法，你可以使用一行 spark 指令就完成這件事。

```
php spark create:controller [controller_name] -model [model_name]
```

![](https://i.imgur.com/u9jhIon.png)

現在，打開 app/Controllers/Blog.php 你應該可以看見新的控制器檔案，並且已經寫入了正確的模型命名空間：

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

然後，打開 app/Models/BlogModel.php 你會看到這個控制器所需的模型已經被同時建立了：

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

#### 當然，你也可以在使用這個指令時，同時使用 [create:controller](#create:controller) 所提供的所有選項，就像這樣：

```
php spark create:controller [controller_name] -space -nobase [model_name]
```

![](https://i.imgur.com/vCI18wX.png)

現在，打開 app/Controllers/Notice.php你應該可以看見新的「 -nobase 」控制器檔案：

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

然後，打開 app/Models/NoticeModel.php 你會看到這個控制器所需的模型已經被同時建立了：

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

### 使用模型選項

[create:model](#create:model) 指令提供多種不同的模型讓你自由選擇，你當然可以在創建模型與控制器的同時，指定一些你所希望的選項。

```
php spark create:controller [controller_name] -model=basic [model_name]
```

![](https://i.imgur.com/MJU0OlJ.png)

> 你所使用的選項都必須緊接在「=」之後。這些選項你你可以參考 [create:model](#create:model) 提到的所有選項。


現在，打開 app/Controllers/Blog.php你應該可以看見新控制器檔案，並且所需的模型已經被宣告了：

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

然後，打開 app/Models/BlogModel.php 你會看到這個控制器所需的「-basic」模型已經被同時建立了：

```php
<?php namespace App\Models;

use CodeIgniter\Model;

class BlogModel extends Model
{
    protected $DBGroup = 'default';
}
```

### 使用多個模型選項

若你需要同時使用多個的模型選項，你只需要以逗號間隔他們即可，就像這樣：

```
php spark create:controller -space [controller_name] -model=basic,space [model_name]
```

![](https://i.imgur.com/8lovROQ.png)


這個範例比較複雜，新建控制器的同時也新建模型與實體，並且自訂命名空間。

### 同時新建 RESTFul 控制器與模型

你也可以在 [create:controller -rest](#create:controller--rest) 中同時創建模型。

```
php spark create:controller -rest [controller_name] -model [model_name]
```

![](https://i.imgur.com/UvLY3oL.png)

現在，打開 app/Controllers/System/Api/User.php 你應該可以看見新的 RESTFul 控制器檔案，並且所需的模型已經被宣告了：

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
