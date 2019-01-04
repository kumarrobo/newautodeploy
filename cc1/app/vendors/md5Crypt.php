<?php
	/*
		Date	:- 	09/10/06
		Author	:- 	Shaishav Shah
		Filename:- 	md5Crypt.php
		Purpose	:- 	The file server as the library for the values to encrypt or decrypt the encrypted values.
	*/
	class Md5Crypt
	{
		/* Function to shuffles the encrypted/decrypted string */
		function keyED($strTxt,$strEncryptKey)
		{
			/* To calculate the md5 hash of a string md5() is invoked */
			$strEncryptKey = md5($strEncryptKey);
			/* Initialize character and temporary string memory variables */
			$ctrPointer=0;
			$strTmp = "";
			/* For loop to shuffle encrypted/decrypted string */
			for ($cntPointer=0;$cntPointer<strlen($strTxt);$cntPointer++)
			{
				/* If the string character is equal to the encrypted key then set the string character value to zero */
				if ($ctrPointer==strlen($strEncryptKey))
				{
					/* To assign zero value to the memory variable */
					$ctrPointer=0;
				}
				/* To append the converted string to a temporary variable */
				$strTmp.= substr($strTxt,$cntPointer,1) ^ 
				substr($strEncryptKey,$ctrPointer,1);
				/* To increment the string character */
				$ctrPointer++;
			}
			/* To return the temporary string value */
			return $strTmp;
		}

		/* Function to encrypt the string value */
		function Encrypt($strTxt,$strKey)
		{
			/* To get a rand value srand() and microtime */
			srand((double)microtime()*1000000);
			$strEncryptKey = md5(rand(0,32000));
			/* Initialize character and temporary string memory variables */
			$ctrPointer=0;
			$strTmp = "";
			/* For loop to convert string passed as parameter to unique value */
			for ($cntPointer=0;$cntPointer<strlen($strTxt);$cntPointer++)
			{
				/* If the string character is equal to the encrypted key then set the string character value to zero */
				if ($ctrPointer==strlen($strEncryptKey))
				{
					/* To assign zero value to the memory variable */
					$ctrPointer=0;
				}
				/* To append the converted string to a temporary variable */
				$strTmp.= substr($strEncryptKey,$ctrPointer,1) .
				(substr($strTxt,$cntPointer,1) ^ substr($strEncryptKey,$ctrPointer,1));
				/* To increment the string character */
				$ctrPointer++;
			}
			/* To encrypt the string value base64_encode() and keyED() is invoked and encrypt value is returned  */
			return base64_encode($this->keyED($strTmp,$strKey));
		}
		
		/* Function to decrypt the encrypted string value */
		function Decrypt($strTxt,$strKey)
		{
			/* To decrypt and shuffle the string value base64_encode() and keyED() is invoked  */
			$strTxt = $this->keyED(base64_decode($strTxt),$strKey);
			$strTmp = "";
			/* For loop to convert strings every character */
			for ($ctrPointer=0;$ctrPointer<strlen($strTxt);$ctrPointer++)
			{
				$strMd5 = substr($strTxt,$ctrPointer,1);
				/* To increment the string character */
				$ctrPointer++;
				/* To append the converted string to a temporary variable */
				$strTmp.= (substr($strTxt,$ctrPointer,1) ^ $strMd5);
			}
			/* To return the decrypted string */
			return $strTmp;
		}
	}
?>