<?php
/*
Description: Implement ADODB methods with PDO
Version: 0.0.2
Author: Tomasz Mrozinski
Author URI: mrozinski.net
*/

class PDO_ADODB extends PDO
{

    /**
     * @var null
     */
    private $result = null;

    /**
     * PDO_ADODB constructor.
     * @param $dsn
     * @param $username
     * @param $passwd
     * @param null $options
     */
    public function __construct($dsn, $username, $passwd, $options = null)
    {
        //enable exceptions for PDO by default
        parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        parent::__construct($dsn, $username, $passwd, $options);
    }

    /**
     * @param $sql
     * @return mixed
     */
    public function GetAll($sql)
    {
        $this->prepareAndExecute($sql);
        return $this->result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param $sql
     * @return mixed
     */
    public function GetRow($sql)
    {
        $this->checkSqlLimit($sql);
        $this->prepareAndExecute($sql);
        return $this->result->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param $sql
     * @return mixed
     */
    public function GetOne($sql)
    {
        $this->checkSqlLimit($sql);
        $this->prepareAndExecute($sql . ' LIMIT 1');
        return $this->result->fetchColumn(0);
    }

    /**
     * @param $sql
     * @return mixed
     */
    public function GetCol($sql)
    {
        $this->prepareAndExecute($sql);
        return $this->result->fetchColumn();
    }

    /**
     * @param $sql
     * @return mixed
     */
    public function GetAssoc($sql)
    {
        $this->prepareAndExecute($sql);
        return $this->result->fetchAll(PDO::FETCH_COLUMN | PDO::FETCH_GROUP);
    }

    /**
     * @param $sql
     * @return bool
     */
    public function Execute($sql): bool
    {
        $this->result = $this->prepare($sql);
        return $this->result->execute();
    }

    /**
     * @return string
     */
    public function ErrorMsg(): string
    {
        return implode(', ', $this->errorInfo());
    }

    /**
     * @return string
     */
    public function Insert_ID(): string
    {
        return $this->lastInsertId();
    }

    /**
     * @param $sql
     */
    private function prepareAndExecute($sql): void
    {
        $this->result = $this->prepare($sql);
        $this->result->execute();
    }

    /**
     * @param $sql
     */
    private function checkSqlLimit($sql): void
    {
        if (stristr(strtolower($sql), 'limit 1')) {
            throw new PDOException('You cannot use "LIMIT 1" in sql.');
        }
    }
}