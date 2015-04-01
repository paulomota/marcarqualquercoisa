ALTER TABLE `db_marcar`.`users` 
ADD COLUMN `dt_nascimento` DATE NULL AFTER `updated_at`,
ADD COLUMN `sexo` VARCHAR(45) NULL AFTER `dt_nascimento`,
ADD COLUMN `status_relacionamento` VARCHAR(45) NULL AFTER `sexo`,
ADD COLUMN `location` VARCHAR(45) NULL AFTER `status_relacionamento`;


ALTER TABLE `db_marcar`.`users` 
CHANGE COLUMN `password` `password` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL ;
