<?php
require_once(dirname(__FILE__).'/../config.default.php');

/**
 * handles the MySQL queries
 * if the query is a select, it returns an array if there is only one value, otherwise it returns the value
 * if the query is an update, replace or delete from, it returns the number of affected rows
 * if the query is an insert, it returns the last insert id
 * @author Neithan
 * @param string $sql
 * @param bool $noTransform (default = false) if set to "true" the query function always returns a multidimension array
 * @return array|string|int|float
 */
function query($sql, $noTransform = false, $raw = false)
{
	$mysql = connect();

	$sql = ltrim($sql);
	if ($GLOBALS['debug'] == true)
	{
		$res = $mysql->query($sql);
	}
	else
	{
		$res = @$mysql->query($sql);
	}

	if (!$res && $GLOBALS['debug'])
	{
		$backtrace = debug_backtrace();
		$html = '<br />Datenbank Fehler '.$mysql->error.'<br /><br />';
		$html .= $sql.'<br />';
		$html .= '<table>';
		foreach ($backtrace as $part)
		{
			$html .= '<tr><td width="100">';
			$html .= 'File: </td><td>'.$part['file'];
			$html .= ' in line '.$part['line'];
			$html .= '</td></tr><tr><td>';
			$html .= 'Function: </td><td>'.$part['function'];
			$html .= '</td></tr><tr><td>';
			$html .= 'Arguments: </td><td>';
			foreach ($part['args'] as $args)
				$html .= $args.', ';
			$html = substr($html, 0, -2);
			$html .= '</td></tr>';
		}
		$html .= '</table>';
		die($html);
	}

	if ($res || is_object($res))
	{
		if (substr($sql,0,6) == "SELECT" || substr($sql, 0, 4) == 'SHOW')
		{
			$out = array();

			if ($res->num_rows > 1 || ($noTransform && $res->num_rows > 0))
			{
				if (method_exists('mysqli_result', 'fetch_all'))
					$out = $res->fetch_all(MYSQLI_ASSOC);
				else
					while ($row = $res->fetch_assoc())
						$out[] = $row;
			}
			elseif ($res->num_rows == 1 && !$noTransform)
			{
				$out = $res->fetch_assoc();

				if (count($out) == 1)
					$out = current($out);
			}
			else
				$out = false;

			return $out;
		}

		if (substr($sql,0,6) == "INSERT" && $noTransform == false)
		    return $mysql->insert_id;
		elseif (substr($sql,0,6) == "INSERT" && $noTransform == true)
			return $mysql->affected_rows;

		if (substr($sql,0,6) == "UPDATE")
			return $mysql->affected_rows;

		if (substr($sql,0,7) == "REPLACE")
			return $mysql->affected_rows;

		if (substr($sql,0,11) == "DELETE FROM")
			return $mysql->affected_rows;

		if ($raw)
			return $mysql->affected_rows;
	}
	else
		return false;
}

function query_raw($sql)
{
	return query($sql, false, true);
}

/**
 * Escapes and wraps the given value. If it's an array, all elements will be
 * escaped separately
 * @param mixed $value
 * @return String
 */
function sqlval($value, $wrap = true)
{
	$mysql = connect();

	if (is_array($value))
	{
		foreach ($value as &$row)
			$row = sqlval($row, $wrap);
		unset($row);

		return $value;
	}
	else
	{
		$escapedString = '';

		if ($wrap)
			$escapedString .= '"';

		$escapedString .= $mysql->real_escape_string($value);

		if ($wrap)
			$escapedString .= '"';

		return $escapedString;
	}
}

/**
 * Handles the MySQL connection.
 * Should only be used in sqlval() and query()
 * @author Neithan
 * @global array $lang
 * @staticvar mysqli $mysql
 * @return mysqli
 */
function connect()
{
	static $mysql;

	if (!is_object($mysql))
	{
		$mysql = new mysqli($GLOBALS['db']['server'], $GLOBALS['db']['user'], $GLOBALS['db']['password']);

		if ($mysql->connect_error)
		{
			if ($GLOBALS['debug'])
				throw new SoapFault('mysql', $mysql->connect_error);
			else
				throw new SoapFault('mysql', 'could not connect to database');
		}
		else
		{
			$mysql->set_charset($GLOBALS['db']['charset']);
			$mysql->select_db($GLOBALS['db']['database']);
		}
	}

	return $mysql;
}