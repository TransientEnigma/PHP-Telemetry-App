<?php

/**
 * Connection.php
 *
 * Class to enable PDO instantiation
 *
 *
 * Author: G Conway
 * Email: p2613423@my365.dmu.ac.uk
 * Date: 29/12/2020
 *
 * @author G E Conway <p2613423@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */
namespace Telemetry;


use \PDO;

class TelemetryConnection
{

    /**
     * This class connects to MySQL using the PDO object.
     * This can be included in web pages where a database connection is needed.
     * Customize these to match your MySQL database connection details.
     * This info should be available from within your hosting panel.
     */

    function _construct(){ }

    private string $username = "root";
    private string $password = "1234";
    private string $hostname = "localhost";
    private string $database = "p2430705db";
    private array $options = array();

    public function newConnection(): PDO
    {

      $username = $this->username;
      $password = $this->password;
      $hostname = $this->hostname;
      $database = $this->database;
      $options = $this->options;

        $pdo = new PDO(
            "mysql:host=" . $hostname . ";dbname=" . $database, //DSN
            $username, //Username
            $password, //Password
           $options //Options
        );

        return($pdo);
    }

public function setPDOConnectionSettings($database_connection_settings){

        $this->username = $database_connection_settings['username'];
        $this->password = $database_connection_settings['password'];
        $this->hostname = $database_connection_settings['host'];
        $this->database = $database_connection_settings['database_name'];
        $this->options = $database_connection_settings['options'];


}


}