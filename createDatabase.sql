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
