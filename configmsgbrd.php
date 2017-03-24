<?php

	DEFINE ('DBUSER', 'root');
	DEFINE ('DBPW', ':Phasr5:');
	DEFINE ('DBHOST', 'localhost');
	DEFINE ('DBNAME', 'msgbrd');

	if ($dbc = mysql_connect (DBHOST, DBUSER, DBPW))
	{
		if (!mysql_select_db(DBNAME))
		{
			trigger_error("Could not select the database" .mysql_error());

			exit();
		}
	} else 
	{
		trigger_error("Could not connect to MySQL");
		exit();
	}

	function escape_data ($data)
	{
		if (function_exists('mysql_real_escape_string'))
		{
			global $dbc;
			$data = mysqli_real_escape_string (trim($data), $dbc);
			$data = string_tags($data);
		}

		return $data;
	}
?>