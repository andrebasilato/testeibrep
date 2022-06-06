-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema reconhecimento
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema reconhecimento
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `reconhecimento` DEFAULT CHARACTER SET utf8 ;
USE `reconhecimento` ;

-- -----------------------------------------------------
-- Table `reconhecimento`.`reconhecimento_alunos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reconhecimento`.`reconhecimento_alunos` (
  `idaluno` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(250) NOT NULL,
  `data_cadastro` DATETIME NOT NULL,
  `foto` VARCHAR(100) NOT NULL,
  `tamanho` VARCHAR(15) NOT NULL,
  `extensao` VARCHAR(15) NOT NULL,
  PRIMARY KEY (`idaluno`))
ENGINE = InnoDB
AUTO_INCREMENT = 45
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `reconhecimento`.`reconhecimento_fotos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reconhecimento`.`reconhecimento_fotos` (
  `idfoto` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `tamanho` VARCHAR(45) NOT NULL,
  `extensao` VARCHAR(10) NOT NULL,
  `data_cadastro` DATETIME NOT NULL,
  `face_id` VARCHAR(45) NOT NULL,
  `face_att_age` INT(11) NOT NULL,
  `face_att_gender` VARCHAR(45) NOT NULL,
  `idaluno` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`idfoto`),
  INDEX `fk_fotos_alunos_idx` (`idaluno` ASC),
  CONSTRAINT `fk_fotos_alunos`
    FOREIGN KEY (`idaluno`)
    REFERENCES `reconhecimento`.`reconhecimento_alunos` (`idaluno`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
