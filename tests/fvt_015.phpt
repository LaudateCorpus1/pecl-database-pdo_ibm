--TEST--
pdo_ibm: Insert/select clob/blob columns with binding to local variable or stream
--SKIPIF--
<?php require_once('skipif.inc'); ?>
--FILE--
<?php
	require_once('fvt.inc');
	class Test extends FVTTest
	{
		public function runTest()
		{
			$this->connect();

			try {
				/* Drop the test table, in case it exists */
				$drop = 'DROP TABLE animals';
				$result = $this->db->exec( $drop );
			} catch( Exception $e ){}

			/* Create the test table */
			$create = 'CREATE TABLE animals (id INTEGER, my_clob clob, my_blob blob)';
			$res = $this->db->exec( $create );

			$stmt = $this->db->prepare('insert into animals (id,my_clob) values (:id,:my_clob)');
			$clob = "test clob data\n";
			print "inserting from php variable\n";
			$stmt->bindValue( ':id' , 0 );
			$stmt->bindParam( ':my_clob' , $clob , PDO::PARAM_LOB , strlen($clob) );
			$stmt->execute();

			$stmt = $this->db->prepare( 'select id,my_clob,my_blob from animals' );
			$res = $stmt->execute();
			$res = $stmt->fetchAll();
			var_dump( $res );

			$res = $this->db->exec( "DELETE FROM animals" );

			print "inserting from php file stream\n";
			$stmt = $this->db->prepare('insert into animals (id,my_clob,my_blob) values (:id,:my_clob,:my_blob)');
			$fpath = dirname(__FILE__) . "/clob.dat";
			$fp1 = fopen( $fpath , "rb" );
			$fp2 = dirname(__FILE__) . "/spook.png";
			$stmt = $this->db->prepare('insert into animals (id,my_clob,my_blob) values (:id,:my_clob,:my_blob)');
			$stmt->bindValue( ':id' , 1 );
			$stmt->bindParam( ':my_clob' , $fp1 , PDO::PARAM_LOB );
			$stmt->bindParam( ':my_blob' , $fp2 , PDO::PARAM_LOB );
			$stmt->execute();

			$stmt = $this->db->prepare( 'select id,my_clob,my_blob from animals' );
			$res = $stmt->execute();
			$res = $stmt->fetchAll();
			var_dump( $res );

			$stmt = $this->db->prepare( 'select id,my_clob,my_blob from animals' );
			$stmt->bindColumn( 1 , $id );
			$stmt->bindColumn( 2 , $clob );
			$stmt->bindColumn( 3 , $blob );
			$res = $stmt->execute();
			while ($stmt->fetch(PDO::FETCH_BOUND)) {
				var_dump( $id );
				var_dump( $clob );
				var_dump( $blob );
			}

			try {
				/* Drop the XML test table, in case it exists */
				$drop = 'DROP TABLE xmlTest';
				$result = $this->db->exec( $drop );
			} catch( Exception $e ){}

			/* Create the XML test table */
			$create = 'CREATE TABLE xmlTest (id INTEGER, my_xml XML)';
			$res = $this->db->exec( $create );

			$stmt = $this->db->prepare('INSERT INTO xmlTest (id,my_xml) VALUES (:id,:my_xml)'); $xml = "<TEST><function><xml/></function></TEST>"; $stmt->bindValue( ':id' , 0 ); $stmt->bindParam( ':my_xml' , $xml , PDO::PARAM_LOB , strlen($xml) );
			$stmt->execute();

			$stmt = $this->db->prepare( 'select id,my_xml from xmlTest' );
			$stmt->bindColumn( 1 , $id );
			$stmt->bindColumn( 2 , $my_xml );
			$res = $stmt->execute();
			while ($stmt->fetch(PDO::FETCH_BOUND)) {
				echo $id . "\n";
				echo $my_xml . "\n";
			}

			print "done\n";
		}
	}

	$testcase = new Test();
	$testcase->runTest();
?>
--EXPECTF--
inserting from php variable
array(1) {
  [0]=>
  array(6) {
    ["ID"]=>
    string(1) "0"
    [0]=>
    string(1) "0"
    ["MY_CLOB"]=>
    string(15) "test clob data
"
    [1]=>
    string(15) "test clob data
"
    ["MY_BLOB"]=>
    NULL
    [2]=>
    NULL
  }
}
inserting from php file stream
array(1) {
  [0]=>
  array(6) {
    ["ID"]=>
    string(1) "1"
    [0]=>
    string(1) "1"
    ["MY_CLOB"]=>
    string(5140) "this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.
this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.
this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.
this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.
this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.
"
    [1]=>
    string(5140) "this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.
this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.
this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.
this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.
this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.
"
    ["MY_BLOB"]=>
    string(%d)%s
    [2]=>
    string(%d)%s
  }
}
string(1) "1"
string(5140) "this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.
this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.
this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.
this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.
this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.  this iss clob data.
"
string(%d)%s
0
<?xml version="1.0" encoding="UTF-8" ?><TEST><function><xml/></function></TEST>
done
