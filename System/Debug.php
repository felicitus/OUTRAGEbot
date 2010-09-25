<?php
/**
 *	Debug utility file for OUTRAGEbot
 *
 *	@ignore
 *	@package OUTRAGEbot
 *	@copyright David Weston (c) 2010 -> http://www.typefish.co.uk/licences/
 *	@author David Weston <westie@typefish.co.uk>
 *	@version 1.1.1-BETA7 (Git commit: d6e9046fbd12d660ded19c7b71c3e13c577d5adc)
 */


/* The function log array */
$aFunctions = array();


/* Register the tick function */
register_tick_function(function()
{
	global
		$aFunctions;
	
	
	$aBacktraces = debug_backtrace(false);
	
	foreach($aBacktraces as $aBacktrace)
	{
		$sString = "";
		
		if(isset($aBacktrace['class']))
		{
			$sString = "{$aBacktrace['class']}::";
		}
		
		$sString .= "{$aBacktrace['function']}";
		
		if(!isset($aFunctions[$sString]))
		{
			$aFunctions[$sString] = 0;
		}
		
		++$aFunctions[$sString];
	}
});


/* Function to dump the output to the file */
function outputCallRecords()
{
	$aFunctions = arsort($GLOBALS['aFunctions']);
	$sString = "";
	
	foreach($aFunctions as $sKey => $iVal)
	{
		$sString .= "{$sKey} -> {$iVal}\r\n";
	}
	
	file_put_contents("Output.txt", $sString);
	return true;
}


/* Declare the tick count */
declare(ticks = 10);

echo "** Debug analysis loaded.";