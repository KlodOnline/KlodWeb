CREATE SCHEMA klodwebsite;

CREATE TABLE klodwebsite.player ( 
	id                   INT  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	name                 VARCHAR(100)   DEFAULT (NULL)   ,
	hashpass             VARCHAR(255)   DEFAULT (NULL)   ,
	ip                   VARCHAR(255)   DEFAULT (NULL)   ,
	mail                 VARCHAR(255)   DEFAULT (NULL)   ,
	activcode            INT   DEFAULT (NULL)   ,
	paidto               INT      
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE klodwebsite.world ( 
	id                   INT  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
	name                 VARCHAR(100)   DEFAULT (NULL)   ,
	address              VARCHAR(255)   DEFAULT (NULL)   ,
	demo                 VARCHAR(100)   DEFAULT (NULL)   
 ) engine=InnoDB;
