<?php

namespace Blog\App\Database;

final class Connect
{
    private string $host;
    private string $dbname;
    private string $user;
    private string $password;

    /**
     * @param string $host
     * @param string $dbname
     * @param string $user
     * @param string $password
     * @return void
     */

    public function __construct(string $host, string $dbname, string $user, string $password)
    {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @return object
     */

    public function connect(): object
    {
        try {
            $pdo = new \PDO('mysql:dbname=' . $this->dbname . ';host=' . $this->host,
                $this->user,
                $this->password
            );
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (\Exception $e) {
            return $e;
        }
    }
}