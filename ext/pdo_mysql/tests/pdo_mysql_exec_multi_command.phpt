--TEST--
MySQL PDO::exec() - Bad error handling with multiple commands
--SKIPIF--
<?php
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'skipif.inc');
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'mysql_pdo_test.inc');
MySQLPDOTest::skip();
?>
--FILE--
<?php

    require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'mysql_pdo_test.inc');
    $db = MySQLPDOTest::factory();
    MySQLPDOTest::createTestTable($db, MySQLPDOTest::detect_transactional_mysql_engine($db));

    /** @var PDO $db */
    $res = $db->exec("INSERT INTO test(id, label) VALUES (41, 'x'); INSERT INTO test_bad(id, label) VALUES (42, 'y')");

    if( $res === false ){
        print 'OK';
    }
    else{
        var_dump($res);
        print 'Failed, because the command ran!';
    }

?>
--CLEAN--
<?php
require dirname(__FILE__) . '/mysql_pdo_test.inc';
$db = MySQLPDOTest::factory();
@$db->exec('DROP TABLE IF EXISTS test');
?>
--EXPECTF--
Warning: PDO::exec(): SQLSTATE[42S02]: Base table or view not found: 1146 Table '%s.test_bad' doesn't exist in %s on line %d
OK

