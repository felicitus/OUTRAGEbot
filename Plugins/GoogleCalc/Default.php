<?php
/**
 *      Youtube example made by MaVe.
 *      @ignore
 *      @copyright None
 *      @package OUTRAGEbot
 */


class GoogleCalc extends Plugins
{
	public
		$pTitle   = "GoogleCalc",
		$pAuthor  = "Westie",
		$pVersion = "1.0";

	private
		$rHandler = "";

	
	/* Called when the plugin is loaded into memory. */
	public function onConstruct()
	{
		$this->rHandler = $this->addHandler('Command', 'getCalc', 'calc');
	}


	/* Called when someone does ~calc (or w/e) */
	public function getCalc($sNickname, $sChannel, $sArguments)
	{
		if(!$sArguments)
		{
			return $this->Notice($sNickname, "USAGE: ~calc [Calculation]");
		}

		$sWebsite = file_get_contents("http://www.google.co.uk/search?hl=en&q=".urlencode($sArguments)."&btnG=Google+Search&meta=&aq=f&oq=");
		$bMatch = preg_match('/<h2 class=r style="font-size:138%">(.*?)<\/h2>/', $sWebsite, $aPatterns);
		$sMessage = "";

		if(!$bMatch)
		{
			$sMessage = "{$sNickname}: Invalid calculation!";
		}
		else
		{
			$sMessage = "{$sNickname}: ".strip_tags($aPatterns[1]);
		}

		$this->Message($sChannel, $sMessage);
	}
}