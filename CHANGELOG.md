# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

Unreleased
---------

### Added
+ StatementInterface and PDO implementation
+ QueryBuilderFacade as abstraction between QueryBuilder 
and other library components

### Changed
+ now Query contains only SQL and parameters
+ ConnectionInterface no longer be responsible for fetching data
+ prefix for service's names
+ now one instance UnderQuery is associated with only one connection

### Removed
+ TableComponentTrait (logic moved to AbstractQueryBuilder)
+ ConnectionManagerInterface

0.5.0 [2016-10-26]
-----------------

### Added
+ container-interop/container-interop
+ implementation ContainerInterface for Pimple
+ humbug/humbug with configuration
+ ParameterCollection and QueryParameter to handling external parameters
+ ReferenceCollection to handling internal parameters

### Changed
+ QueryBuilderFactory renamed to UnderQuery (it better shows entry point)
+ PhuriaSQLBuilder::__constructor() now expects ContainerInterface
+ documentation moved from README.md to readthedocs.org
+ project name from Phuria SQL Builder to UnderQuery 

### Removed
+ ParameterManager

0.4.0 [2016-10-17]
-----------------

### Added
+ Query::execute() for the proper execute INSERT, DELETE AND UPDATE queries
+ ConnectionInterface::execute() 

0.3.1 [2016-10-17]
-----------------

### Changed
+ Fixed UPDATE query joins

0.3.0 [2016-10-17]
-----------------

### Added
+ Code coverage annotations in tests
+ Added AbstractQueryBuilder::setParameter()

### Changed
+ Unit test looks like integration tests have been moved
+ Query::__constructor() now requires a connection
+ Now ConnectionInterface will be responsible for fetching data
+ ConnectionInterface methods like query(), prepare() have been replaced
by fetch(), fetchScalar(), fetchAll()
+ ParameterManagerInterface::createOrGetParameter() renamed to getParameter()

### Removed
+ StatementInterface

0.2.0 [2016-10-16]
--------------------

### Added
+ ConnectionManager to handling more that one connection
+ NullConnection, NullStatement classes
+ QueryBuilderFactory::registerConnection()
+ QueryFactory to solve the problem with a choice of connection

### Changed
+ QueryBuilder class name to QueryBuilderFactory, and factory methods
now will be prefixed 'create'
+ QueryBuilder::buildQuery() now accepts as a parameter connection name

0.1.0 [2016-10-01]
-----------------

### Added
+ Configuration for travis, scrutinizer, phpunit and composer
+ Initial Connection
+ Initial ParameterManager
+ DI using pimple/pimple
+ QueryBuilder for INSERT, UPDATE, SELECT, DELETE and INSERT ... SELECT
+ QueryCompiler
+ Initial StatementInterface and PDOStatement decorator
+ Table, TableFactory and TableRecognizer for representing database's tables