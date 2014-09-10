SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `dbminicms` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `dbminicms` ;

-- -----------------------------------------------------
-- Table `dbminicms`.`rolle`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbminicms`.`rolle` ;

CREATE  TABLE IF NOT EXISTS `dbminicms`.`rolle` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `navn` VARCHAR(145) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbminicms`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbminicms`.`users` ;

CREATE  TABLE IF NOT EXISTS `dbminicms`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(145) NULL ,
  `email` VARCHAR(145) NOT NULL ,
  `password` VARCHAR(45) NOT NULL ,
  `avatar` VARCHAR(145) NULL ,
  `adresse` TEXT NULL ,
  `rolle_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) ,
  INDEX `fk_users_rolle_idx` (`rolle_id` ASC) ,
  CONSTRAINT `fk_users_rolle`
    FOREIGN KEY (`rolle_id` )
    REFERENCES `dbminicms`.`rolle` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `dbminicms`.`rolle`
-- -----------------------------------------------------
START TRANSACTION;
USE `dbminicms`;
INSERT INTO `dbminicms`.`rolle` (`id`, `navn`) VALUES (NULL, 'Super Admin');
INSERT INTO `dbminicms`.`rolle` (`id`, `navn`) VALUES (NULL, 'Admin');
INSERT INTO `dbminicms`.`rolle` (`id`, `navn`) VALUES (NULL, 'Moderator');
INSERT INTO `dbminicms`.`rolle` (`id`, `navn`) VALUES (NULL, 'User');

COMMIT;

-- -----------------------------------------------------
-- Data for table `dbminicms`.`users`
-- -----------------------------------------------------
START TRANSACTION;
USE `dbminicms`;
INSERT INTO `dbminicms`.`users` (`id`, `name`, `email`, `password`, `avatar`, `adresse`, `rolle_id`) VALUES (NULL, 'Bjarke', 'bbr@rts.dk', '1234', NULL, NULL, 1);
INSERT INTO `dbminicms`.`users` (`id`, `name`, `email`, `password`, `avatar`, `adresse`, `rolle_id`) VALUES (NULL, 'Henning', 'hlarsen@rts.dk', '123', NULL, NULL, 2);

COMMIT;
