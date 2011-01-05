<?php
/**
 *	OUTRAGEbot development
 */
 

class ModuleWhois
{
	static
		$pTempObject = null;
	
	
	/**
	 *	Called when the module is loaded.
	 */
	static function initModule()
	{
		Core::introduceFunction("getWhoisData", array(__CLASS__, "sendWhoisRequest"));
	}
	
	
	/**
	 *	The command handler
	 */
	static function sendWhoisRequest($sNickname)
	{
		/* Send the request, and sort out the handler */
		self::$pTempObject = (object) array
		(
			'away' => false,
			'helper' => false,
			'user' => new stdClass(),
			'server' => new stdClass(),
			'ircop' => false,
			'idleTime' => 0,
			'signonTime' => 0,
		);
		
		$pInstance = Core::getCurrentInstance();
		$pSocket = $pInstance->getCurrentSocket();
		
		$pSocket->Output("WHOIS {$sNickname}");
		$pSocket->executeCapture(array(__CLASS__, "parseWhoisLine"));
		
		return self::$pTempObject;
	}
	
	
	/**
	 *	Parses the input
	 */
	static function parseWhoisLine($sString)
	{
		$pMessage = Core::getMessageObject($sString);
		
		switch($pMessage->Numeric)
		{
			case "301":
			{
				self::$pTempObject->away = $pMessage->Payload;
				
				return false;
			}
			
			case "310":
			{
				self::$pTempObject->helper = true;
				
				return false;
			}
			
			case "311":
			{
				self::$pTempObject->user = (object) array
				(
					'nick' => $pMessage->Parts[3],
					'username' => $pMessage->Parts[4],
					'address' => $pMessage->Parts[5],
					'info' => $pMessage->Payload,
				);
				
				return false;
			}
			
			case "312":
			{
				self::$pTempObject->server = (object) array
				(
					'address' => $pMessage->Parts[4],
					'name' => $pMessage->Payload,
				);
				
				return false;
			}
			
			case "313":
			{
				self::$pTempObject->ircop = true;
				
				return false;
			}
			
			case "317":
			{
				self::$pTempObject->idleTime = $pMessage->Parts[4];
				self::$pTempObject->signonTime = $pMessage->Parts[5];
				
				return false;
			}
			
			case "318":
			{
				return true;
			}
			
			case "319":
			{
				self::$pTempObject->channels = explode(' ', $pMessage->Payload);
				
				return false;
			}
		}
	}
}