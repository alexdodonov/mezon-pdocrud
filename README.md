# Database support
[![Build Status](https://travis-ci.com/alexdodonov/mezon-pdocrud.svg?branch=master)](https://travis-ci.com/alexdodonov/mezon-pdocrud) [![codecov](https://codecov.io/gh/alexdodonov/mezon-pdocrud/branch/master/graph/badge.svg)](https://codecov.io/gh/alexdodonov/mezon-pdocrud) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexdodonov/mezon-pdocrud/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alexdodonov/mezon-pdocrud/?branch=master)

## Intro
Mezon built-in classes support varios databases using PDO extension of the PHP language.

## Detail
The following databases are supported:

- CUBRID
- MS SQL Server
- Firebird
- IBM
- Informix
- MySQL
- MS SQL Server
- Oracle
- ODBC and DB2
- PostgreSQL
- SQLite
- 4D

PDO objects are wrapped with ProCrud class wich will help you to create simple CRUD routine.

For example:

```PHP
$dataConnection = [
    'dns' => 'mysql:host=localhost;dbname=testdb' , 
    'user' => 'user' ,
    'password' => 'password'
];

$crud = new \Mezon\PdoCrud\PdoCrud();
$crud->connect( $dataConnection );

// fetching fields id and title from table test_table where ids are greater than 12
$crud->prepare('SELECT * FROM test_table WHERE id > :id');

// result stores array of anonimous object
$result = $crud->execSelect(['id' => '12']);
```

## Deleting records

Deleting routine is quite simple:

```PHP
$crud->delete( 
	'table_name' , // table name
	'id > 10' ,    // WHERE statement
	10             // number of records to delete
);
```

## Inserting records

Inserting routine is also very simple:

```PHP
$crud->insert( 
	'table_name' ,                 // table name
	[ 'f1' => 1 , f2 => '2' ] // new values for fields f1 and f2
);
```

## Updating records

Updating routine is also very simple:

```PHP
$crud->update( 
	'table_name' ,                   // table name
	[ 'f1' => 1 , f2 => '2' ] , // new values for fields f1 and f2
	'id > 10'                        // WHERE statement
);
```

## Transaction and thread safety

You can lock tables and work with transactions.

```PHP
$crud->lockTables( [ 'table1' , 'table2' ] , [ 'READ' , 'WRITE' ] );
$crud->startTransaction();

// perform some changes in database

// then commit these changes
$crud->commit();

// or rollback them
// $crud->commit();

$crud->unlockTables();
```