#Fichier de création de la base
CREATE DATABASE IF NOT EXISTS descartes_example;
use descartes_example;

-- Create table of exemple
CREATE TABLE IF NOT EXISTS exemple
(
	id INT NOT NULL AUTO_INCREMENT,
	clef VARCHAR(25) NOT NULL,
	valeur VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
);

-- Create table of activation exemple
CREATE TABLE IF NOT EXISTS exemple_activation
(
	id INT NOT NULL AUTO_INCREMENT,
	exemple_id INT NOT NULL,
	activate BOOLEAN DEFAULT 0,
	PRIMARY KEY (id),
	CONSTRAINT fk_exemple_exemple_activation
        	FOREIGN KEY (exemple_id)
        	REFERENCES exemple(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE
);
