<?php 
/**
 * Cli-Create
 *
 * Cli-Create 基於 CodeIgniter4 。它將可以幫助你在使用 CodeIgniter4 開發專案時，更加迅速地產生各式模板檔案。 
 * Cli-Create is based on CodeIgniter4. It will help you generate template files more quickly when developing projects with CodeIgniter4.
 * @package    CodeIgniter4
 * @subpackage libraries
 * @category   library
 * @version    1.0.0
 * @author    monkenWu <610877102@mail.nknu.edu.tw>
 * @link      https://github.com/monkenWu/Codeigniter4-Easy-create
 *        
 */

namespace monken\Commands;
use CodeIgniter\CLI\CLI;

class CliCreate {
    
    /**
     * 取得使用者名稱 
     * Gets the user name. 
     *
     * @param array $params CLI所取得的 params 陣列 / The params array that CLI has gotten.
     * @param String $type 將影響到提示中的文字 / $type will impact the words in prompt.
     * @return string
     */
    public static function getName(array &$params,String $type){
        $name = array_shift($params);
        if (empty($name)){
			$name = CLI::prompt(
                CLI::color("Names the {$type} file","blue")
            );
		}
		if (empty($name)){  
			CLI::error("You must provide a {$type} file name.");
			exit();
        }
        return $name;
    }

    /**
     * 取得使用者輸入的命名空間
     * Gets the namespace that be entered by user.
     *
     * 將會判斷使用者所輸入的值是否符合規則，並且將所有的路徑強制轉換成第一個字母大寫的形式。
     * It will judge if the value is compliant with the rules that has been entered by user, and change all paths into first letter capitalized.
     * @param String $type Models、Controllers或App下的任何資料夾。 / Any folder that under the Models, Controllers or App.
     * @return string 將回傳取得的命名空間。 / It will return the namespace that has been gotten.
     */
    public static function getNameSpace(String $type){
        $name = CLI::prompt(
            CLI::color("The current namespace is \"App \ {$type}\", and what you typed will be concatenated after this ","blue")
        );
        while(strstr($name, '/')){
            $name = CLI::prompt(
                CLI::color("Namespace cannot be separated by '/', Please retype ","blue")
            );
        }
        if( (str_split($name)[0] != "\\") && ($name != "")){
            $name = "\\".$name;
        }
        $tempArray = explode("\\",$name);
        $returnName = "";
        foreach ($tempArray as $key => $value) {
            if($value != "") $returnName .= "\\".ucfirst($value);
        }
        return $returnName;
    }

    /**
     * 判斷是否有一個以上的true存在。
     * Finds out that if more than one "true" exists.
     *
     * @param Array $boolArr 
     * @return boolean
     */
    public static function isMulti(Array $boolArr){
        $num = 0;
        foreach ($boolArr as $key => $value) {
            if($value) $num++;
        }
        return $num > 1 ? true : false;
    }

    /**
     * 寫入檔案。
     * Writes file.
     *
     * @param String $writePath 寫入檔案的絕對路徑 / Writes the file's absolute path.
     * @param String $fileText 檔案名稱 the file name
     * @return string
     */
    public static function writeFile(String $writePath,String $fileText){
        helper('filesystem');

        if (! write_file($writePath, $fileText)){
            CLI::error("Failed to create file.");
            exit();
        }

        CLI::write('Created successfully: ' .
            CLI::color($writePath, 'green')
        );
    }

    /**
     * 取得模板。
     * Gets the template.
     *
     * @param String $path 絕對路徑 / Absolute path
     * @param String $name 模板名稱 / Template name
     * @return string
     */
    public static function getTemplate(String $path,String $name){
        $filePath = $path.$name;
        $handle = fopen($filePath, "r");
        $template = fread($handle, filesize($filePath));
        fclose($handle);
        if($template){
            return $template;
        }else{
            CLI::error("Template loading error.");
			exit();
        }
    }

    /**
     * 確認檔案是否存在。
     * Finds out that if the file is exists.
     *
     * 若檔案存在，將會詢問使用者是否需要覆寫檔案，或取消寫入。
     * If the file is exists, it will ask user if it need to overwrite the file or just stop writing.
     * @param String $filePath 檔案完整路徑，包含檔名 / The file's fullpath, included the file name.
     * @param String $type 本次操作的分類，將影響到提示文字 / The classification of this operation, it will impact the prompt.
     * @return void
     */
    public static function checkFileEexists(String $filePath,String $type){
        if(file_exists($filePath)){
            $check = CLI::prompt(
                CLI::color("Found the same {$type} file name, Do you need overwrite the file?","yellow"),
                ['y','n']
            );
            if($check == "n"){
                CLI::write("{$type} creation process has ".
                    CLI::color("stopped.", 'yellow')
                );
                exit();
            }
        }
    }

    /**
     * 替換模板檔案的 Tag 。
     * Changes the template file's tag.
     *
     * @param String $template 模板字串。 / Template string
     * @param Array $repalceData 索引陣列， key 值為模板檔案中的 Tag 名稱，Value 為替換值。 / Array, the key is the name of template file's tag, and the value is the words to replace it.
     * @return string 替換後的結果 / The result of replacement.
     */
    public static function replaceText(String $template,Array $repalceData){
        foreach ($repalceData as $key => $value) {
            $template = str_replace("{{$key}}", $value, $template);
        }
        return $template;
    }

    /**
     * 取得檔案路徑。
     * Gets the file path.
     *
     * 將會判斷當前執行的系統是否為windows，將會回傳符合系統要求的絕對路徑。
     * It will check if the operate systeam is windows, then return the absolute path that the systeam need. 
     * @param String $appPath 專案app資料夾路徑 / The project app folder's path 
     * @param String $type Models、Controllers或App下的任何資料夾。 / Any folder that under the Models, Controllers or App.
     * @param String $dir 指定路徑，如路徑不存在將自動建立。路徑以 "\\" 分隔。預設為空。 / Specify the path. If the path doesn't exists, it will create it automatically. The input path need to separated by "\\". Defult value is empty.
     * @param String $fileName 檔案名稱，不須傳入 ".php" 副檔名。預設為空。 / The file name. It is not need the file extension ".php".Default is empty.
     * @return String 回傳絕對路徑。 / Return the absolute path.
     */
    static public function getPath(String $appPath,String $type,String $dir = "",String $fileName = ""){
        $word = "";
        
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $word = "\\";
        }else{
            $word = "/";
        }

        $dirArr = explode("\\",$dir);
        
        //Compares the last character of the string.
        if(substr($appPath,strlen($appPath)-1,1) != $word){
            $appPath .= $word;
        }
        $appPath = $appPath.$type;

        foreach ($dirArr as $key => $value) {
            $temp = $appPath;
            if($key > 0){
                $temp .= $word;
            }
            $temp .= $value;
            if(!is_dir($temp)){
                mkdir($temp,0755);
            }
            $appPath = $temp;
        }

        $fileName .= $fileName != "" ? ".php" : "";

        return $appPath.$word.$fileName;
    }
    
    /**
     * 取得指定資料夾下所有的命名空間。
     * Get all namespaces under the specific folder.
     *
     * @param String $appPath 
     * @param String $type Models、Controllers或App下的任何資料夾。  / Any folder that under the Models, Controllers or App.
     * @return array 命名空間陣列 / The namespace array
     */
    public static function getAllNamespace(String $appPath,String $type){
        $map = directory_map("{$appPath}{$type}/", FALSE, TRUE);
        $nameArr = [];

        $getDeepMap = function($map,$key,&$nameArr) use (&$getDeepMap){
            $nowDir = $key;
            foreach ($map as $key => $value) {
                if(gettype($key) != "integer"){
                    $getDeepMap($map,"{$nowDir}{$key}",$nameArr);
                }
                $path = $nowDir.str_replace(".php","",$value);
                $path = str_replace("/","\\",$path);
                $nameArr[] = $path;
            }
        };

        foreach ($map as $key => $value) {
            if(gettype($key) != "integer"){
                $getDeepMap($value,"App/{$type}/{$key}",$nameArr);
            }else{
                if(!strstr($value,".php")) continue;
                $path = "App/{$type}/".str_replace(".php","",$value);
                $path = str_replace("/","\\",$path);
                $nameArr[] = $path;
            }
        }

        return $nameArr;
    }

    /**
     * 顯示表格，並且回傳使用者所選擇的 ID 。
     * Display the table, and return the id that selected by user.
     *
     * @param Array $namespaceArr 傳入由命名空間組成的陣列 / The array built by namespaces.
     * @return array 使用者所選擇的 id / The id that selected by user.
     */
    public static function selectTable(Array $namespaceArr){

        $thead = ['ID', 'Namespace', 'ID', 'Namespace'];
        $tbody = [];
        foreach ($namespaceArr as $key => $value) {
            if($key%2==0){
                $tbody[] = [$key,$value];
            }else{
                array_push($tbody[count($tbody)-1],$key,$value);
            }
        }

        CLI::table($tbody,$thead);
        $useID = CLI::prompt(
            CLI::color("Please type the ID of the Namespace you want to use.\nIf you want to use muiltiple Namespace, then type muiltiple ID and separated it by \",\" ","blue")
        );

        return $useID;
    }

}