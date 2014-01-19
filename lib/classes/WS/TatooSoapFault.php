<?php
namespace WS;

/**
 * Tatoo soap fault exception
 *
 * @author Neithan
 */
class TatooSoapFault extends \SoapFault
{
	const WSDL = 'WSDL';
	const CLIENT = 'client';
}