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

namespace CliCreate\Commands\Controller;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CliCreate\Commands\CliCreate;

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
        '-space' => 'Using this option will create folders and files according to the path you typed.'
    ];
    
    private $controllerName;
    private $useModel = false;
    private $useBase = true;
    private $nameSpace = false;
    private $templatePath;
    private $appPath;
    
    public function run(array $params = []){
        $this->controllerName = ucfirst(CliCreate::getName($params,"controller"));
        $this->appPath = APPPATH;
        $this->templatePath = dirname(__FILE__)."/template/";
        $this->getOption();
        
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

        //get model
        $useModels = "";
        if($this->useModel){
            $modelList = CliCreate::getAllNamespace($this->appPath,"Models");
            
            $useID = CliCreate::selectTable($modelList);

            $numArr = explode(",",$useID);
            foreach ($numArr as $key => $value) {
                $useModels .= "use {$modelList[$value]};\n" ?? "";
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

     /**
     * 取得可能被使用者輸入的 options 
     *
     * @return Void
     */
    private function getOption(){
        $this->useBase = !empty(CLI::getOption('nobase'))?false:true;
        $this->useModel = !empty(CLI::getOption('usemodel'))?true:false;
        $this->nameSpace = !empty(CLI::getOption('space'))?true:false;
    }

}