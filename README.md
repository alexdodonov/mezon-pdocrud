# Database support
##Intro##
Mezon built-in classes support varios databases using PDO extension of the PHP language.

##Details##
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
$DataConnection = array(
    'dns' => 'mysql:host=localhost;dbname=testdb' , 
    'user' => 'user' ,
    'password' => 'password'
);

$CRUD = new PdoCrud();
$CRUD->connect( $DataConnection );
// fetching fields id and title from table test_table where ids are greater than 12
$Records = $CRUD->select( 'id , title' , 'test_table' , 'id > 12' );
```

##Deleting records##

Deleting routine is quite simple:

```PHP
$CRUD->delete( 
	'table_name' , // table name
	'id > 10' ,    // WHERE statement
	10             // number of records to delete
);
```

##Inserting records##

Inserting routine is also very simple:

```PHP
$CRUD->insert( 
	'table_name' ,                 // table name
	array( 'f1' => 1 , f2 => '2' ) // new values for fields f1 and f2
);
```

##Updating records##

Updating routine is also very simple:

```PHP
$CRUD->update( 
	'table_name' ,                   // table name
	array( 'f1' => 1 , f2 => '2' ) , // new values for fields f1 and f2
	'id > 10'                        // WHERE statement
);
```

##Transaction and thread safety##

You can lock tables and work with transactions.

```PHP
$CRUD->lock_tables( array( 'table1' , 'table2' ) , array( 'READ' , 'WRITE' ) );
$CRUD->start_transaction();

// perform some changes in database

// then commit these changes
$CRUD->commit();

// or rollback them
// $CRUD->commit();

$CRUD->unlock_tables();
```