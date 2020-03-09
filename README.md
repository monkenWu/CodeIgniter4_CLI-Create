# CodeIgniter4_CLI-Create

Cli-Create is based on CodeIgniter4. It will help you generate template files more quickly when developing projects with CodeIgniter4.

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

## Quick Start

### create:controller

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
    
### create:model

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
