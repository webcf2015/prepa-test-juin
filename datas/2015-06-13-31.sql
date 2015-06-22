-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema prepatestjuin
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema prepatestjuin
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `prepatestjuin` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `prepatestjuin` ;

-- -----------------------------------------------------
-- Table `prepatestjuin`.`rubrique`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `prepatestjuin`.`rubrique` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `lintitle` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `prepatestjuin`.`photo_has_rubrique`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `prepatestjuin`.`photo_has_rubrique` (
  `photo_id` INT UNSIGNED NOT NULL,
  `rubrique_id` INT UNSIGNED NOT NULL,
  INDEX `fk_photo_has_rubrique_rubrique1_idx` (`rubrique_id` ASC),
  INDEX `fk_photo_has_rubrique_photo1_idx` (`photo_id` ASC),
  CONSTRAINT `fk_photo_has_rubrique_photo1`
    FOREIGN KEY (`photo_id`)
    REFERENCES `prepatestjuin`.`photo` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_photo_has_rubrique_rubrique1`
    FOREIGN KEY (`rubrique_id`)
    REFERENCES `prepatestjuin`.`rubrique` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
