ALTER TABLE `customers` 
ADD COLUMN `username` VARCHAR(45) NULL AFTER `city`,
ADD COLUMN `password` VARCHAR(45) NULL AFTER `username`,
ADD UNIQUE INDEX `username_UNIQUE` (`username` ASC);