<?php

class DB {

    const DBHOST = 'localhost';
    const DBNAME = 'rsl';
    const DBUSER = 'rsl';
    const DBPASS = 'rsl123rsl123';

    const DBHOSTSCHOOL = 'localhost';
    const DBNAMESCHOOL = 'bg24c_u005';
    const DBUSERSCHOOL = 'bg24c_u005';
    const DBPASSSCHOOL = '258503';

    private $db;
    private $home=false;

    public function __construct() {
        if ($this->home) {
            $this->db = new mysqli(self::DBHOST, self::DBUSER, self::DBPASS, self::DBNAME);
        } else {
            $this->db = new mysqli(self::DBHOSTSCHOOL, self::DBUSERSCHOOL, self::DBPASSSCHOOL, self::DBNAMESCHOOL);
        }
        $this->db->query('SET NAMES "utf8mb4"');
        $this->db->query('SET CHARACTER SET "utf8mb4"');
    }

    public function getDB() {
        return $this->db;
    }

}