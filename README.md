##1. What is SQL?
Structured Query Language, used for manipulating data in relational databases

##2. What is RDBMS?
Relational Database Management System, a database system based on relational model

##3. What is Data Mining?
Data Mining is data extraction for discovering patterns in large scale data

##4. What is an ERD?
Entity Relationship Diagram, used for database design, visualizes tables and its Relationships


##5. What is the difference between Primary Key and Unique Key?
Primary key creates a clustered index, cannot be null and cannot have duplicate ids, while unique key is non clustered, can be null and cannot have duplicate ids as well, unless its null.


##6. How can you store a picture file in the database? What Object type is used?
Object type is BLOB (binary large object), you can also base64 encode the image and store it as a text string, but in my experience, storing images in a database is not performant.


##7. What is Data Warehousing?
A system used for reporting and data analysis, mostly used for long range view and data aggregation


##8. What are indexes in a Database? Give a definition of the types of indexes.
Indexes are used for search/ lookups performance, its a copy of a column or column combination for faster data retrieval and operations.

1. Clustered: Clustered index is a b-tree structure and sorts and stores the rows data of a table based on the order
2. Non clustered: A non clustered index is created using clustered index. Each index row in the non clustered index has non clustered key value and a row locator.
3. Unique: ensures the availability of only non-duplicate values
4. Fulltext: searches for specific characters in string data


##9. How many Triggers are possible in MySQL? (Explain them all)

event: before, after / action: insert, update, delete
all of these triggers happen "before" or "after" the record event update/insert/delete, so for example, you are able to validate some data before inserting, updating or deleting a record, or maybe execute additional inserts or updates after the query is finished, or do additional math on specific columns in tables
you can also define the order of triggers with "follows" or "precedes"


##10. What is Heap table?
Heap table is a table without a clustered index, data in that kind of tables is unsorted and does not have a table seek operator. Heap tables are great for inserts, since it doesnt need to create an index, but not for selects, and of course forwarding records will be an issue, because sql moves the table to a different physical location.


##11. Define the common MySQL storage engines and explain their differences.
Most common MySQL engines are:
InnoDB - has row level locking, has transaction support, foreign keys and relationship constraints
MyISAM - has table level locking, does not have transaction support, no foreign keys and does not have automatic crash recovery
Memory - has table level locking, usually its used for temporary tables and quick lookups
