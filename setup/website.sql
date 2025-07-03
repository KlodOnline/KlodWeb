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

-- Inserting a local test world
INSERT INTO klodwebsite.world (name, address, demo)
VALUES ('DefaultWorld', 'https://127.0.0.1:2443', 'true');
