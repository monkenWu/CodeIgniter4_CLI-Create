<?php 
/**
 * Cli-Create
 *
 * Cli-Create 基於 CodeIgniter4 。它將可以幫助你在使用 CodeIgniter4 開發專案時，更加迅速地產生各式檔案。 
 * Cli-Create is based on CodeIgniter4. It will help you generate template files more quickly when developing projects with CodeIgniter4.
 * @package    CodeIgniter4
 * @subpackage libraries
 * @category   library
 * @version    1.0.0
 * @author    monkenWu <610877102@mail.nknu.edu.tw>
 * @link      https://github.com/monkenWu/Codeigniter4-Easy-create
 *        
 */

namespace monken\Commands\Controller;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use monken\Commands\CliCreate;

class CreateController extends BaseCommand{

    protected $group       = 'Cli-Create';
    protected $name        = 'create:controller';
    protected $description = 'Create a new controller file.';
    protected $usage = 'create:controller [controller_name] [Options]';
    protected $arguments = [
		'controller_name' => 'The controller name.',
    ];
    protected $options = [
        '-nobase' => 'Do not extends BaseControllers Class.',
        '-usemodel' => 'Choose models.',
        '-space' => 'Create folders and files according to the path you typed.',
        '-rest' => 'Generate files related to Resource Routes',
        // '-rest -p' => 'Generate files related to Presenter Routes, then use the "-rest -p" options.',
        '-rest -d' => 'The names of controller and router are different.',
        '-rest -o' => 'Select options to create the function what you want. ',
        '-rest -w' => "Generate update and delete methods that work with HTML forms"
    ];
    
    private $controllerName;
    private $routerName;
    private $useModel = false;
    private $useBase = true;
    private $nameSpace = false;
    private $differntNames = false;
    private $selectFunctions = false;
    private $useWebsafe = false;
    private $templatePath;
    private $appPath;
    
    public function run(array $params = []){
        $userNameInput = CliCreate::getName($params,"controller");
        $this->controllerName = ucfirst($userNameInput);
        $this->appPath = APPPATH;
        $this->templatePath = CliCreate::getPath(dirname(__FILE__),"template");
        $option = $this->getOption();
        if($this->differntNames){
            $this->routerName = CliCreate::getName($params,"router");
        }else{
            $this->routerName = $userNameInput;
        }

        if($option == "rest"){
            $this->writeRest();
        }else if($option == "restP"){
            //$this->writeRestP();
        }else{
            $this->writeBasic();
        }

        return;
    }

     /**
     * 取得可能被使用者輸入的 options 
     * Get options that may be typed by the user.
     *
     * @return Void
     */
    private function getOption(){
        $isMulti = CliCreate::isMulti([
            !empty(CLI::getOption('nobase')),
            !empty(CLI::getOption('usemodel')),
            !empty(CLI::getOption('rest'))
        ]);
        if($isMulti){
            CLI::error("If you use the -rest option, you can no longer use the -nobase and -usemodel options.");
			exit();
        }

        $this->useBase = !empty(CLI::getOption('nobase'))?false:true;
        $this->useModel = !empty(CLI::getOption('usemodel'))?true:false;
        $this->nameSpace = !empty(CLI::getOption('space'))?true:false;
        $rest = !empty(CLI::getOption('rest'))?true:false;
        $restP = !empty(CLI::getOption('p'))?true:false;
        $restD = !empty(CLI::getOption('d'))?true:false;
        $restO = !empty(CLI::getOption("o"))?true:false;
        $restW = !empty(CLI::getOption("w"))?true:false;

        if(!$rest && $restP){
            CLI::error("The -p option must be used after -rest.");
			exit();
        }
        if(!$rest && $restD){
            CLI::error("The -d option must be used after -rest.");
			exit();
        }
        $this->differntNames = $restD;
        if(!$rest && $restO){
            CLI::error("The -O option must be used after -rest.");
			exit();
        }
        $this->selectFunctions = $restO;
        if(!$rest && $restW){
            CLI::error("The -W option must be used after -rest.");
			exit();
        }
        $this->useWebsafe = $restW;
    
        if($rest){
            if($restP){
                return "restP";
            }
            return "rest";
        }else{
            return "basic";
        }
    }

    private function writeBasic(){
        $useController = "";
        $extendsController = "BaseController";
        if(!$this->useBase){
            $useController = "\nuse CodeIgniter\Controller;";
            $extendsController = "Controller";
        }
        
        //get namespace
        $space = "";
        if($this->nameSpace){
            $space = CliCreate::getNameSpace("Controllers");
        }
        if($space != ""){
            $extendsController = "\App\Controllers\BaseController";
        }

        //get model
        $useModels = "";
        if($this->useModel){
            $modelList = CliCreate::getAllNamespace($this->appPath,"Models");
            
            $useID = CliCreate::selectTable($modelList,"Namespace");

            $numArr = explode(",",$useID);
            foreach ($numArr as $key => $value) {
                $useModels .= "use {$modelList[($value-1)]};\n" ?? "";
            }
        }

        $templateData = [
            "name" => $this->controllerName,
            "namespace" => $space,
            "useController" => $useController,
            "extendsController" => $extendsController,
            "useModels" => $useModels
        ];
        $template =  CliCreate::getTemplate($this->templatePath,"controller");
        $replaceTemplate = CliCreate::replaceText($template,$templateData);
        $writePath = CliCreate::getPath($this->appPath,"Controllers",$space,$this->controllerName);
        CliCreate::checkFileEexists($writePath,"Controller");
        CliCreate::writeFile($writePath,$replaceTemplate);
        return;
    }

    private function writeRest(){
        //get Model Namespace
        $modelList = CliCreate::getAllNamespace($this->appPath,"Models");
        $modelID = CliCreate::selectTable($modelList,"Namespace",false);
        $useModel = $modelList[((int)$modelID-1)];
        //get want use RestFul functions
        $functions = "";
        $onlySetting = "";
        $resurceFunctions = [
            "index","show","create","update","new","edit","delete"
        ];
        $functionsPath = CliCreate::getPath($this->templatePath,"rest","\\resourceFunctions");
        if($this->selectFunctions){
            $useID = CliCreate::selectTable($resurceFunctions,"function");
            $useArr = explode(",",$useID);
            $onlySetting .= "[";
            foreach ($useArr as $key => $value) {
                $id = (int)$value - 1;
                $functions .= CliCreate::getTemplate($functionsPath,$resurceFunctions[($id)]);
                $functions .= "\r\n\r\n";
                $onlySetting .= $key == 0 ? "'{$resurceFunctions[$id]}'" : ",'{$resurceFunctions[$id]}'"; 
            }
            $onlySetting .= "]";  
        }else{
            foreach ($resurceFunctions as $key => $value) {
                $functions .= CliCreate::getTemplate($functionsPath,$value);
                $functions .= "\r\n\r\n";
            }     
        }
        //get namespace
        $space = "";
        if($this->nameSpace){
            $space = CliCreate::getNameSpace("Controllers");
        }
        //create controller
        $templateData = [
            "name" => $this->controllerName,
            "namespace" => $space,
            "useModel" => $useModel,
            "functions" => $functions
        ];
        $template =  CliCreate::getTemplate($this->templatePath,"resourceController");
        $replaceTemplate = CliCreate::replaceText($template,$templateData);
        $writePath = CliCreate::getPath($this->appPath,"Controllers",$space,$this->controllerName);
        CliCreate::checkFileEexists($writePath,"Controller");
        CliCreate::writeFile($writePath,$replaceTemplate);

        //add router setting
        $routerSetPath = CliCreate::getPath($this->appPath,"Config","","Routes");
        $routerSetString = CliCreate::getTemplate($routerSetPath,"");
        $setBefore = strstr($routerSetString,"Route Definitions",true);
        $setAfter = strstr($routerSetString,"Route Definitions");
        $writeBefore = strstr($setAfter,"/**",true);
        $writeAfter = strstr($setAfter,"/**");
        $time = date('Y-m-d H:i:s');
        $ctrlNameSpace = $space == "" ? "\\{$this->controllerName}" : "{$space}\\$this->controllerName";
        $only = $this->selectFunctions ? "    'only' => {$onlySetting},\n":"";
        $websafe = $this->useWebsafe ? "    'websafe' => 1,\n" : "";
        $writeAfter = "//CliCreate-add-in-{$time}
\$routes->resource('{$this->routerName}',[
    'controller' =>'\App\Controllers{$ctrlNameSpace}',\n{$only}{$websafe}]);\n
{$writeAfter}
";
        $dataString = $setBefore.$writeBefore.$writeAfter;
        CliCreate::writeFile($routerSetPath,$dataString);
        return;
    }

    // private function writeRestP(){
    //     //get namespace
    //     $space = "";
    //     if($this->nameSpace){
    //         $space = CliCreate::getNameSpace("Controllers");
    //     }
    // }

}