<?php
namespace Hiland\Utils\DataBase;

class DAOHelper
{

    public static function Instance($driver = 'mysqli')
    {
        $dbHostName = C('DBHOSTNAME');
        if (empty($dbHostName)) {
            $dbHostName = DAOConfig::DBHOSTNAME;
        }

        $dbHostPort = C('DBHOSTPORT');
        if (empty($dbHostPort)) {
            $dbHostPort = DAOConfig::DBHOSTPORT;
        }

        $dbHost = $dbHostName . ':' . $dbHostPort;

        $db_user = C('DBHOSTUSER');
        if (empty($db_user)) {
            $db_user = DAOConfig::DBHOSTUSER;
        }

        $db_pwd = C('DBHOSTPASS');
        if (empty($db_pwd)) {
            $db_pwd = DAOConfig::DBHOSTPASS;
        }

        $db_database = C('DBHOSTDATABASE');
        if (empty($db_database)) {
            $db_database = DAOConfig::DBHOSTDATABASE;
        }

        $dbCoding = C('DBCODING');
        if (empty($dbCoding)) {
            $dbCoding = DAOConfig::DBCODING;
        }

        switch ($driver) {
            case 'mysql':
                return new Mysql($dbHostName, $dbHostPort, $db_user, $db_pwd, $db_database, 'pconn', $dbCoding);
                break;
            case 'mysqli':
            default:
                return new Mysqli($dbHostName, $dbHostPort, $db_user, $db_pwd, $db_database, '', $dbCoding);
                break;
        }
    }
}

?>