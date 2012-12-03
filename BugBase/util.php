<?php
	
	function sendMail($to,$from,$subject,$htmlMessage){
		//create a boundary string. It must be unique
		$random_hash = md5(date('r', time()));
		//define the headers we want passed. Note that they are separated with \r\n
		$headers = "From: ".$from."\r\nReply-To: ".$from;
		//add boundary string and mime type specification
		$headers .= "\r\nContent-Type: multipart/alternative; boundary=\"PHP-alt-".$random_hash."\"";
		//define the body of the message.
		$message="--PHP-alt-".$random_hash."\n";
		$message.="Content-Type: text/html; charset=\"iso-8859-1\"\n";
		$message.="Content-Transfer-Encoding: 7bit\n\n";
			$message.=$htmlMessage."\n\n";
		$message.="--PHP-alt-".$random_hash."--";
		
		//send the email
		$mail_sent = @mail( $to, $subject, $message, $headers );
		//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
		return $mail_sent;
	}
	
	
	function chmod_r($path, $filemode, $dirmode) {
		if (is_dir($path) ) {
			if (!chmod($path, $dirmode)) {
				$dirmode_str=decoct($dirmode);
				print "Failed applying filemode '$dirmode_str' on directory '$path'\n";
				print "  `-> the directory '$path' will be skipped from recursive chmod\n";
				return;
			}
			$dh = opendir($path);
			while (($file = readdir($dh)) !== false) {
				if($file != '.' && $file != '..') {  // skip self and parent pointing directories
					$fullpath = $path.'/'.$file;
					chmod_R($fullpath, $filemode,$dirmode);
				}
			}
			closedir($dh);
		} else {
			if (is_link($path)) {
				print "link '$path' is skipped\n";
				return;
			}
			if (!chmod($path, $filemode)) {
				$filemode_str=decoct($filemode);
				print "Failed applying filemode '$filemode_str' on file '$path'\n";
				return;
			}
		}
	} 
	
	//does string1 contain string 2
	function contains($str1,$str2){
		$pos = strpos($str1, $str2);
		// Note our use of ===.  Simply == would not work as expected
		// because the position of 'a' was the 0th (first) character.
		if ($pos === false) {return false;} 
		else {return true;}
	}
	
	function randCode($len=8) {
		return substr(strtoupper(base_convert(microtime(),10,16)),0,$len);
	}
    
    /* Helper used to generate ticket IDs */
    function randNumber($len=6,$start=false,$end=false) {

        mt_srand ((double) microtime() * 1000000);
        $start=(!$len && $start)?$start:str_pad(1,$len,"0",STR_PAD_RIGHT);
        $end=(!$len && $end)?$end:str_pad(9,$len,"9",STR_PAD_RIGHT);
        
        return mt_rand($start,$end);
    }

	
	
    function encrypt($text, $salt) {

        //if mcrypt extension is not installed--simply return unencryted text and log a warning.
        if(!function_exists('mcrypt_encrypt') || !function_exists('mcrypt_decrypt')) {
            $msg='Cryptography extension mcrypt is not enabled or installed. IMAP/POP passwords are being stored as plain text in database.';
            Sys::log(LOG_WARN,'mcrypt missing',$msg);
            return $text;
        }

        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256,$salt, $text, MCRYPT_MODE_ECB,
                         mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }

    function decrypt($text, $salt) {
        if(!function_exists('mcrypt_encrypt') || !function_exists('mcrypt_decrypt'))
            return $text;

        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB,
                        mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }
?>
