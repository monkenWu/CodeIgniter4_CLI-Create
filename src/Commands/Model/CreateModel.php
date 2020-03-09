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
 * @link      https://github.com/monkenWu/Codeigniter4-Cli-Create
 *        
 */

namespace monken\Commands\Model;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use monken\Commands\CliCreate;

class CreateModel extends BaseCommand{

    protected $group       = 'Cli-Create';
    protected $name        = 'create:model';
    protected $description = 'Create a new model file.';
    protected $usage = 'create:model [model_name] [entity_name] [Options]';
    protected $arguments = [
        'model_name' => 'The model name',
        'entity_name' => 'The entity name.If you selected -entity option.You well type this arguments.'
    ];
    protected $options = [
        '-basic' => 'Create a basic model file.',
        '-entity' => 'Use Entity Classes.',
        '-manual' => 'Create a Manual Model.',
        '-space' => 'Using this option will create folders and files according to the path you typed.'
    ];
    
    private $modelName;
    private $entityName;
    private $nameSpace = false;
    private $appPath;
    private $templatePath;
    private $option;
    
    public function run(array $params = []){
        $this->option = $this->getOption();
        $this->modelName = ucfirst(CliCreate::getName($params,"model"));
        $this->appPath = APPPATH;
        $this->templatePath = dirname(__FILE__)."/template/";
        if($this->option == "basic"){
            $this->writeBasicModel();
        }else if($this->option == "entity"){
            $this->entityName = ucfirst(CliCreate::getName($params,"entity"));
            $this->writeEntity();
        }else if($this->option == "manual"){
            $this->writeManual();
        }else {
            $this->writeModel();
        }
        return;
    }

    /**
     * 取得可能被使用者輸入的 options 
     *
     * @return string 判斷 option 後回傳本次的所執行的模式。
     */
    private function getOption(){
        $basic = CLI::getOption('basic');
        $entity = CLI::getOption('entity');
        $manual = CLI::getOption('manual');
        $space = CLI::getOption('space');
        $isMulti = CliCreate::isMulti([
            !empty($basic),!empty($entity),!empty($manual)
        ]);

        if($isMulti){
            CLI::error("Only one option can be selected in -basic , -entity or -manual.");
			exit();
        }

        if(!empty($space)){
            $this->nameSpace = true;
        }

        if(!empty($basic)){
            return "basic";
        }else if(!empty($entity)){
            return "entity";
        }else if(!empty($manual)){
            return "manual";
        }else{
            return "null";
        }
    }

    /**
     * 寫入基本的 Model檔案
     *
     * @return void
     */
    private function writeBasicModel(){
        $space = "";
        if($this->nameSpace){
            $space = CliCreate::getNameSpace("Models");
        }
        $sendData = [
            "name" => $this->modelName,
            "namespace" => $space
        ];
        $template =  CliCreate::getTemplate($this->templatePath,"basicModel");
        $replaceTemplate = CliCreate::replaceText($template,$sendData);
        $writePath = CliCreate::getPath($this->appPath,"Models",$space,$this->modelName);
        CliCreate::checkFileEexists($writePath,"Model");
        CliCreate::writeFile($writePath,$replaceTemplate);
    }

    /**
     * 寫入 Manual Model 檔案。
     *
     * @return void
     */
    private function writeManual(){
        $space = "";
        if($this->nameSpace){
            $space = CliCreate::getNameSpace("Models");
        }
        $sendData = [
            "name" => $this->modelName,
            "namespace" => $space
        ];
        $template =  CliCreate::getTemplate($this->templatePath,"manualModel");
        $replaceTemplate = CliCreate::replaceText($template,$sendData);
        $writePath = CliCreate::getPath($this->appPath,"Models",$space,$this->modelName);
        CliCreate::checkFileEexists($writePath,"Model");
        CliCreate::writeFile($writePath,$replaceTemplate);
    }

    /**
     * 寫入 Model 檔案。
     *
     * @return void
     */
    private function writeModel(){
        $space = "";
        if($this->nameSpace){
            $space = CliCreate::getNameSpace("Models");
        }
        $sendData = [
            "name" => $this->modelName,
            "namespace" => $space
        ];
        $template =  CliCreate::getTemplate($this->templatePath,"model");
        $replaceTemplate = CliCreate::replaceText($template,$sendData);
        $writePath = CliCreate::getPath($this->appPath,"Models",$space,$this->modelName);
        CliCreate::checkFileEexists($writePath,"Model");
        CliCreate::writeFile($writePath,$replaceTemplate);
    }

    /**
     * 同時寫入 Entity 與 Model 檔案。
     *
     * @return void
     */
    private function writeEntity(){
        //get model namespace
        $space = "";
        $entitySpace = "";
        if($this->nameSpace){
            $space = CliCreate::getNameSpace("Models");
            $entitySpace = CliCreate::getNameSpace("Entities");
        }

        $sendData = [
            "name" => $this->modelName,
            "entityNames" => $this->entityName,
            "namespace" => $space,
            "entityNamespace" => $entitySpace
        ];
        $template =  CliCreate::getTemplate($this->templatePath,"entityModel");
        $replaceTemplateModel = CliCreate::replaceText($template,$sendData);

        $sendData = [
            "name" => $this->entityName,
            "namespace" => $entitySpace
        ];
        $template =  CliCreate::getTemplate($this->templatePath,"entity");
        $replaceTemplateEntity = CliCreate::replaceText($template,$sendData);

        $writeModelPath = CliCreate::getPath($this->appPath,"Models",$space,$this->modelName);
        $writeEntityPath = CliCreate::getPath($this->appPath,"Entities",$entitySpace,$this->entityName);
        CliCreate::checkFileEexists($writeModelPath,"Model");
        CliCreate::checkFileEexists($writeEntityPath,"Entities",$this->entityName,"Entitiy");

        CliCreate::writeFile($writeModelPath,$replaceTemplateModel);
        CliCreate::writeFile($writeEntityPath,$replaceTemplateEntity);
    }

}