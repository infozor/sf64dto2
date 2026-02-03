<?php

namespace App\ModuleProcess\Aion;


class Test2
{
    function __construct($output)
    {

        $this->output = $output;

    }

    function do_it()
    {
        $a = 1;

        $db_servername = $_ENV['DB_HOST2'];
        $db_username = $_ENV['DB_USER2'];
        $db_password = $_ENV['DB_PASSWORD2'];
        $db_name = $_ENV['DB_NAME2'];
        $dbPort = $_ENV['DB_PORT2'];

        $conn = new \PDO("pgsql:host=$db_servername;port=$dbPort;dbname=$db_name", $db_username, $db_password);
        $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

        $this->conn = $conn;

        $sqlstr = sprintf('
                    SELECT m."Podr_ID",
                        t."Good_ID",
                        SUM(t."KVO") AS "KVO"
                    FROM
                        "TovarPart" t
                        INNER JOIN "PodrMesto" m ON m."PodrMesto_ID" = t."PodrMesto_ID"
                    WHERE
                        t."Good_ID" = 42
                    GROUP BY
                        m."Podr_ID",
                        t."Good_ID"
                    HAVING
                        SUM(t."KVO") > 0;
				
			');



        $stmt = $this->conn->prepare($sqlstr);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);


        $a = 1;

        return $rows;

        //
    }
    
    function get_schedulle_jobs()
    {
    	
    	$db_servername = $_ENV['DB_HOST1'];
    	$db_username = $_ENV['DB_USER1'];
    	$db_password = $_ENV['DB_PASSWORD1'];
    	$db_name = $_ENV['DB_NAME1'];
    	$dbPort = $_ENV['DB_PORT1'];
    	
    	$conn = new \PDO("pgsql:host=$db_servername;port=$dbPort;dbname=$db_name", $db_username, $db_password);
    	$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    	$conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    	
    	$this->conn = $conn;
    	

    	
    	$sqlstr = sprintf('
                    SELECT id, cron_expr, payload
		             FROM scheduled_jobs
		            WHERE active = true
			');
    	
    	
    	
    	$stmt = $this->conn->prepare($sqlstr);
    	$stmt->execute();
    	$rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    	
    	
    	return $rows;
    	
    	//
    }

}