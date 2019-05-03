CREATE TABLE `festival`.`Lieu` ( `id` VARCHAR(5) NOT NULL , `nom` VARCHAR(50) NOT NULL , `adresseRue` VARCHAR(255) NOT NULL , `codePostal` VARCHAR(5) NOT NULL , `ville` VARCHAR(32) NOT NULL , `capacite` INT(5) NOT NULL ) ENGINE = InnoDB;
ALTER TABLE `Lieu` ADD PRIMARY KEY(`id`);
