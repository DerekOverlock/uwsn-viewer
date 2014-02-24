SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `uwsn_viewer` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `uwsn_viewer` ;

-- -----------------------------------------------------
-- Table `uwsn_viewer`.`User`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `uwsn_viewer`.`User` (
  `UID` INT NOT NULL AUTO_INCREMENT,
  `FirstName` VARCHAR(45) NOT NULL,
  `LastName` VARCHAR(45) NOT NULL,
  `Email` VARCHAR(200) NOT NULL,
  `Password` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`UID`),
  UNIQUE INDEX `Email_UNIQUE` (`Email` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `uwsn_viewer`.`Node`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `uwsn_viewer`.`Node` (
  `NodeID` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NULL,
  `Latitude` FLOAT(10,6) NULL,
  `Longitude` FLOAT(10,6) NULL,
  `SerialNumber` VARCHAR(45) NULL,
  `OwnedBy` VARCHAR(200) NULL,
  PRIMARY KEY (`NodeID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `uwsn_viewer`.`NodeReading`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `uwsn_viewer`.`NodeReading` (
  `NodeReadID` INT NOT NULL AUTO_INCREMENT,
  `NodeID` INT NOT NULL,
  `Current` DECIMAL(10,2) NOT NULL,
  `Temp` DECIMAL(10,2) NOT NULL,
  `Timestamp` DATETIME NOT NULL,
  PRIMARY KEY (`NodeReadID`),
  INDEX `FK_NodeID_idx` (`NodeID` ASC),
  CONSTRAINT `FK_NodeReading_NodeID`
    FOREIGN KEY (`NodeID`)
    REFERENCES `uwsn_viewer`.`Node` (`NodeID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `uwsn_viewer`.`NodeImage`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `uwsn_viewer`.`NodeImage` (
  `NodeImageID` INT NOT NULL AUTO_INCREMENT,
  `NodeID` INT NOT NULL,
  `Image` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`NodeImageID`),
  INDEX `FK_NodeID_idx` (`NodeID` ASC),
  CONSTRAINT `FK_NodeImage_NodeID`
    FOREIGN KEY (`NodeID`)
    REFERENCES `uwsn_viewer`.`Node` (`NodeID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `uwsn_viewer`.`NodeImageNote`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `uwsn_viewer`.`NodeImageNote` (
  `NodeImageNoteID` INT NOT NULL,
  `NodeImageID` INT NOT NULL,
  `UID` INT NOT NULL,
  `Notes` TEXT NOT NULL,
  `Timestamp` DATETIME NOT NULL,
  PRIMARY KEY (`NodeImageNoteID`),
  INDEX `FK_NodeImageNote_NoteImageID_idx` (`NodeImageID` ASC),
  INDEX `FK_NodeImageNote_UID_idx` (`UID` ASC),
  CONSTRAINT `FK_NodeImageNote_NoteImageID`
    FOREIGN KEY (`NodeImageID`)
    REFERENCES `uwsn_viewer`.`NodeImage` (`NodeImageID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_NodeImageNote_UID`
    FOREIGN KEY (`UID`)
    REFERENCES `uwsn_viewer`.`User` (`UID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
