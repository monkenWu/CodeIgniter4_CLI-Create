<?php 
/**
 * Cli-Create
 *
 * Cli-Create 基於 CodeIgniter4 。它將可以幫助你在使用 CodeIgniter4 開發專案時，更加迅速地產生各式檔案。 
 * Cli-Create is based on CodeIgniter4. It will help you generate template files more quickly when developing projects with CodeIgniter4.
 * @package    CodeIgniter4
 * @subpackage libraries
 * @category   library
 * @version    0.3.0
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
        'entity_name' => 'The entity name. If you selected -entity option. You can type this arguments.'
    ];
    protected $options = [
        '-basic' => 'Creates a basic model file.',
        '-entity' => 'Uses Entity Classes.',
        '-manual' => 'Creates a Manual Model.',
        '-space' => 'Creates folders and files according to the path you typed.'
    ];
    
    private $modelName;
    private $entityName;
    private $nameSpace = false;
    private $appPath;
    private $templatePath;
    private $option;
    private $callNameSpace = false;
    
    public function run(array $params = []){
        $this->modelName = ucfirst(CliCreate::getName($params,"model"));
        $this->option = $this->getOption($params);
        $this->appPath = APPPATH;
        $this->templatePath = CliCreate::getPath(dirname(__FILE__),"template");
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
     * Get options that may be typed by the user. 
     *
     * @return string 判斷 option 後回傳本次使用者希望執行的模式。 
     * After finding out what option selected by user, return the mode that user whants to execute.
     */
    private function getOption(&$params){
        if(in_array("-call",$params)){
            $basic = in_array("-basic",$params) ? true : NULL; 
            $entity = in_array("-entity",$params) ? true : NULL; 
            $manual = in_array("-manual",$params) ? true : NULL; 
            $space = in_array("-space",$params) ? true : NULL;
            foreach ($params as $key => $value) {
                if(strstr($value,'-')) unset($params[$key]); 
            }
            if($space){
                $params = array_values($params);
                $key = count($params)-1;
                $this->callNameSpace = $params[$key];
                unset($params[$key]);
            }
        }else{
            $basic = CLI::getOption('basic');
            $entity = CLI::getOption('entity');
            $manual = CLI::getOption('manual');
            $space = CLI::getOption('space');    
        }
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
     * 讀取基本的 Model 模板，並寫入到新的文件中。
     * Writes the basic Model template into the new file.
     * 
     * @return void
     */
    private function writeBasicModel(){
        $space = "";
        if($this->nameSpace){
            if($this->callNameSpace){
                $space = $this->callNameSpace;
            }else{
                $space = CliCreate::getNameSpace("Models");
            }
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
     * 讀取 Manual Model 模板，並寫入到新的文件中。
     * Writes the Manual Model template into the new file.
     *
     * @return void
     */
    private function writeManual(){
        $space = "";
        if($this->nameSpace){
            if($this->callNameSpace){
                $space = $this->callNameSpace;
            }else{
                $space = CliCreate::getNameSpace("Models");
            }
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
     * 讀取 Model 模板，並寫入到新的文件中。
     * Writes the Model template into the new file.
     *
     * @return void
     */
    private function writeModel(){
        $space = "";
        if($this->nameSpace){
            if($this->callNameSpace){
                $space = $this->callNameSpace;
            }else{
                $space = CliCreate::getNameSpace("Models");
            }
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
     * 讀取 Entity 與 Model 模板，皆寫入到新的文件中。
     * Writes the Entity and Model template into the new file.
     * @return void
     */
    private function writeEntity(){
        //get model namespace
        $space = "";
        $entitySpace = "";
        if($this->nameSpace){
            if($this->callNameSpace){
                $space = $this->callNameSpace;
            }else{
                $space = CliCreate::getNameSpace("Models");
            }
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