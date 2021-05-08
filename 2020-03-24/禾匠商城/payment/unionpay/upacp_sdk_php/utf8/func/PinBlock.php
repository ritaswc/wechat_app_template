<?php
	/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
	function  Pin2PinBlock( &$sPin )
	{
			$iTemp = 1;
		$sPinLen = strlen($sPin);
		$sBuf = array();
				$sBuf[0]=intval($sPinLen, 10);
	
		if($sPinLen % 2 ==0)
		{
			for ($i=0; $i<$sPinLen;)
			{
				$tBuf = substr($sPin, $i, 2);
				$sBuf[$iTemp] = intval($tBuf, 16);
				unset($tBuf);
				if ($i == ($sPinLen - 2))
				{
					if ($iTemp < 7)
					{
						$t = 0;
						for ($t=($iTemp+1); $t<8; $t++)
						{
							$sBuf[$t] = 0xff;
						}
					}
				}
				$iTemp++;
				$i = $i + 2;				}
		}
		else
		{
			for ($i=0; $i<$sPinLen;)
			{
				if ($i ==($sPinLen-1))
				{
					$mBuf = substr($sPin, $i, 1) . "f";
					$sBuf[$iTemp] = intval($mBuf, 16);
					unset($mBuf);
					if (($iTemp)<7)
					{
						$t = 0;
						for ($t=($iTemp+1); $t<8; $t++)
						{
							$sBuf[$t] = 0xff;
						}
					}
				}
				else 
				{
					$tBuf = substr($sPin, $i, 2);
					$sBuf[$iTemp] = intval($tBuf, 16);
					unset($tBuf);
				}
				$iTemp++;
				$i = $i + 2;
			}
		}
		return $sBuf;
	}
	
	function FormatPan(&$sPan)
	{
		$iPanLen = strlen($sPan);
		$iTemp = $iPanLen - 13;
		$sBuf = array();
		$sBuf[0] = 0x00;
		$sBuf[1] = 0x00;
		for ($i=2; $i<8; $i++)
		{
			$tBuf = substr($sPan, $iTemp, 2);
			$sBuf[$i] = intval($tBuf, 16);
			$iTemp = $iTemp + 2;		
		}
		return $sBuf;
	}
	
	function Pin2PinBlockWithCardNO(&$sPin, &$sCardNO)
	{
		global $log;
		$sPinBuf = Pin2PinBlock($sPin);
		$iCardLen = strlen($sCardNO);
		if ($iCardLen <= 10)
		{
			return (1);
		}
		elseif ($iCardLen==11){
			$sCardNO = "00" . $sCardNO;
		}
		elseif ($iCardLen==12){
			$sCardNO = "0" . $sCardNO;
		}
		$sPanBuf = FormatPan($sCardNO);
		$sBuf = array();
		
		for ($i=0; $i<8; $i++)
		{
			$sBuf[$i] = vsprintf("%c", ($sPinBuf[$i] ^ $sPanBuf[$i]));
		}
		unset($sPinBuf);
		unset($sPanBuf);
		$sOutput = implode("", $sBuf);			return $sOutput;
	}

?>