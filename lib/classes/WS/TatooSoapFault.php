<?php
namespace WS;

/**
 * Tatoo soap fault exception
 *
 * @author Neithan
 */
class TatooSoapFault extends \SoapFault
{
	const NORESPONSE = 'TSF1';
	const ALREADYEXISTS = 'TSF2';
	const MYSQLCONNECT = 'TMS1';
}