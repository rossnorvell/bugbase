<?php require_once('auth.php');
	require_once('config.php');
	require_once('util.php');
		
/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {    
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);
        
        if ($realSize != $this->getSize()){            
            return false;
        }
        
        $target = fopen($path, "w");        
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);
        
        return true;
    }
    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];            
        } else {
            throw new Exception('Getting content length is not supported.');
        }      
    }   
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {  
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = 7485760;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 7485760){        
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
            
        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $sizeLimit;
        
        $this->checkServerSettings();       

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false; 
        }
    }
    
    private function checkServerSettings(){        
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));        
        
        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';             
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");    
        }        
    }
    
    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }
    
    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE){
        if (!is_writable($uploadDirectory)){
            return array('error' => "Server error. Upload directory isn't writable.");
        }
        
        if (!$this->file){
            return array('error' => 'No files were uploaded.');
        }
        
        $size = $this->file->getSize();
        
        if ($size == 0) {
            return array('error' => 'File is empty');
        }
        
        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large');
        }
        
        $pathinfo = pathinfo($this->file->getName());
        $filename = preg_replace("/(\s){1,}/",'_',$pathinfo['filename']);
		//BugBase Modification
		$r = randCode(16);
		$fn=$filename;
		$filename=$r."_".$filename;
		//--------------------
        //$filename = md5(uniqid());
        $ext = $pathinfo['extension'];

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }
        
        if(!$replaceOldFile){
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename .= rand(10, 99);
            }
        }
        
        if ($this->file->save($uploadDirectory . $filename . '.' . $ext)){
            //BugBase Modifcation
			/* Log the attachment in the database */
			$link = mysql_connect(DB_HOST, DB_USER,DB_PASS) or die("Blibber bobber");
			mysql_select_db(DB_NAME) or die("blabber blobber");
			$qry="SELECT MAX( attach_id ) FROM ost_ticket_attachment";
			$res = mysql_query($qry);
			$row = mysql_fetch_row($res);
			$attach_id = $row[0]+1;/* max +1 for next */
			$ticket=sanitize($_GET['ticket']);
			$filesize=filesize($uploadDirectory . $filename . '.' . $ext);
			//insert the attachment, remember at this point we don't have ref_id though. only uploaded it
			$qry="INSERT INTO ost_ticket_attachment(attach_id,ticket_id,ref_id,ref_type,file_size,file_name,file_key,deleted,created) VALUES('".$attach_id."','".$ticket."','222','M','".$filesize."','".($fn.".".$ext)."','".$r."','0',NOW())";
			$res = mysql_query($qry);
			mysql_close($link);
			//-------------------
			return array('success'=>true,'attach_id'=>$attach_id);
        } else {
            return array('error'=> 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }
        
    }    
}

// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array();
// max file size in bytes
$sizeLimit = 7 * 1024 * 1024;

$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
// new stuff for BugBase
$updated_path = ALT_ATTACHMENTS."/".date('my');//month year;
chmod_r($updated_path."/",0744,0744);//make sure no one can view, only download through download.php
if(!file_exists($updated_path)){ 
	mkdir($updated_path); 
}
$updated_path=$updated_path."/";
//---------------------
$result = $uploader->handleUpload($updated_path);
// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
