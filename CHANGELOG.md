# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## Unreleased
### Added
+ ConnectionManager to handling more that one connection
+ NullConnection, NullStatement classes
### Changed
+ QueryBuilder class name to QueryBuilderFactory, and factory methods
now will be prefixed 'create'

## 0.1.0 [2016-10-01]
### Added
+ Configuration for travis, scrutinizer, phpunit and composer
+ Initial Connection
+ Initial ParameterManager
+ DI using pimple/pimple
+ Initial ParameterManager
+ QueryBuilder for INSERT, UPDATE, SELECT, DELETE and INSERT ... SELECT
+ QueryCompiler
+ Initial StatementInterface and PDOStatement decorator
+ Table, TableFactory and TableRecognizer for representing database's tables