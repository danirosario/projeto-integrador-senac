-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema lojacriartdb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema lojacriartdb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `lojacriartdb` DEFAULT CHARACTER SET utf8mb3 ;
USE `lojacriartdb` ;

-- -----------------------------------------------------
-- Table `lojacriartdb`.`customer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacriartdb`.`customer` (
  `idCustomer` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(100) NOT NULL,
  `Email` VARCHAR(100) NOT NULL,
  `passwordHash` VARCHAR(255) NULL DEFAULT NULL,
  `Phone` VARCHAR(20) NULL DEFAULT NULL,
  `CPF` VARCHAR(14) NOT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isActive` TINYINT(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idCustomer`),
  UNIQUE INDEX `uq_customer_email` (`Email` ASC) ,
  UNIQUE INDEX `uq_customer_cpf` (`CPF` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `lojacriartdb`.`address`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacriartdb`.`address` (
  `idAddress` INT NOT NULL AUTO_INCREMENT,
  `Customer_idCustomer` INT NOT NULL,
  `Street` VARCHAR(120) NOT NULL,
  `Number` VARCHAR(10) NULL DEFAULT NULL,
  `Complement` VARCHAR(60) NULL DEFAULT NULL,
  `Neighborhood` VARCHAR(60) NULL DEFAULT NULL,
  `City` VARCHAR(60) NOT NULL,
  `State` CHAR(2) NOT NULL,
  `ZipCode` VARCHAR(9) NOT NULL,
  `isDefault` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idAddress`),
  INDEX `fk_Address_Customer1_idx` (`Customer_idCustomer` ASC),
  CONSTRAINT `fk_Address_Customer1`
    FOREIGN KEY (`Customer_idCustomer`)
    REFERENCES `lojacriartdb`.`customer` (`idCustomer`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `lojacriartdb`.`category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacriartdb`.`category` (
  `idCategory` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NOT NULL,
  `Description` TEXT NULL DEFAULT NULL,
  `isActive` TINYINT(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idCategory`),
  UNIQUE INDEX `uq_category_name` (`Name` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `lojacriartdb`.`order`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacriartdb`.`order` (
  `idOrder` INT NOT NULL AUTO_INCREMENT,
  `OrderDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Status` ENUM('pendente', 'em_processamento', 'enviado', 'entregue', 'cancelado') NOT NULL DEFAULT 'pendente',
  `PaymentStatus` ENUM('pendente', 'pago', 'recusado', 'estornado') NOT NULL DEFAULT 'pendente',
  `PaymentMethod` VARCHAR(30) NULL DEFAULT NULL,
  `TotalAmount` DECIMAL(10,2) NOT NULL,
  `Address_idAddress` INT NULL DEFAULT NULL,
  `Customer_idCustomer` INT NOT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idOrder`),
  INDEX `fk_Order_Customer1_idx` (`Customer_idCustomer` ASC),
  INDEX `fk_Order_Address1_idx` (`Address_idAddress` ASC),
  CONSTRAINT `fk_Order_Address1`
    FOREIGN KEY (`Address_idAddress`)
    REFERENCES `lojacriartdb`.`address` (`idAddress`),
  CONSTRAINT `fk_Order_Customer1`
    FOREIGN KEY (`Customer_idCustomer`)
    REFERENCES `lojacriartdb`.`customer` (`idCustomer`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `lojacriartdb`.`product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacriartdb`.`product` (
  `idProduct` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(100) NOT NULL,
  `BasePrice` DECIMAL(10,2) NOT NULL,
  `Stock` INT NOT NULL DEFAULT '0',
  `MinStock` INT NOT NULL DEFAULT '5',
  `Description` TEXT NULL DEFAULT NULL,
  `ImageURL` VARCHAR(255) NULL DEFAULT NULL,
  `isActive` TINYINT(1) NOT NULL DEFAULT '1',
  `Category_idCategory` INT NOT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idProduct`),
  INDEX `fk_Product_Category1_idx` (`Category_idCategory` ASC),
  CONSTRAINT `fk_Product_Category1`
    FOREIGN KEY (`Category_idCategory`)
    REFERENCES `lojacriartdb`.`category` (`idCategory`))
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `lojacriartdb`.`orderitems`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacriartdb`.`orderitems` (
  `idOrderItems` INT NOT NULL AUTO_INCREMENT,
  `Order_idOrder` INT NOT NULL,
  `Product_idProduct` INT NOT NULL,
  `Quantity` INT NOT NULL,
  `UnitPrice` DECIMAL(10,2) NOT NULL,
  `CustomizationDetails` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`idOrderItems`),
  INDEX `fk_OrderItems_Order_idx` (`Order_idOrder` ASC),
  INDEX `fk_OrderItems_Product_idx` (`Product_idProduct` ASC),
  CONSTRAINT `fk_OrderItems_Order`
    FOREIGN KEY (`Order_idOrder`)
    REFERENCES `lojacriartdb`.`order` (`idOrder`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_OrderItems_Product`
    FOREIGN KEY (`Product_idProduct`)
    REFERENCES `lojacriartdb`.`product` (`idProduct`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `lojacriartdb`.`product_orderitems`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacriartdb`.`product_orderitems` (
  `Product_idProduct` INT NOT NULL,
  `OrderItems_idOrderItems` INT NOT NULL,
  PRIMARY KEY (`Product_idProduct`, `OrderItems_idOrderItems`),
  INDEX `fk_Product_has_OrderItems_OrderItems1_idx` (`OrderItems_idOrderItems` ASC),
  INDEX `fk_Product_has_OrderItems_Product1_idx` (`Product_idProduct` ASC),
  CONSTRAINT `fk_Product_has_OrderItems_OrderItems1`
    FOREIGN KEY (`OrderItems_idOrderItems`)
    REFERENCES `lojacriartdb`.`orderitems` (`idOrderItems`),
  CONSTRAINT `fk_Product_has_OrderItems_Product1`
    FOREIGN KEY (`Product_idProduct`)
    REFERENCES `lojacriartdb`.`product` (`idProduct`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `lojacriartdb`.`role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacriartdb`.`role` (
  `idRole` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`idRole`),
  UNIQUE INDEX `uq_role_name` (`name` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `lojacriartdb`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacriartdb`.`user` (
  `idUser` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(60) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `passwordHash` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) NULL DEFAULT NULL,
  `cpf` VARCHAR(14) NULL DEFAULT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isActive` TINYINT(1) NOT NULL DEFAULT '1',
  `Role_idRole` INT NOT NULL,
  PRIMARY KEY (`idUser`),
  UNIQUE INDEX `uq_user_email` (`email` ASC) ,
  UNIQUE INDEX `uq_user_cpf` (`cpf` ASC),
  INDEX `fk_User_Role1_idx` (`Role_idRole` ASC),
  CONSTRAINT `fk_User_Role1`
    FOREIGN KEY (`Role_idRole`)
    REFERENCES `lojacriartdb`.`role` (`idRole`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `lojacriartdb`.`stocklog`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacriartdb`.`stocklog` (
  `idStockLog` INT NOT NULL AUTO_INCREMENT,
  `Product_idProduct` INT NOT NULL,
  `User_idUser` INT NULL DEFAULT NULL,
  `quantityChange` INT NOT NULL,
  `reason` ENUM('entrada', 'saida', 'ajuste', 'venda', 'devolucao') NOT NULL,
  `notes` VARCHAR(255) NULL DEFAULT NULL,
  `changedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idStockLog`),
  INDEX `fk_StockLog_Product1_idx` (`Product_idProduct` ASC),
  INDEX `fk_StockLog_User1_idx` (`User_idUser` ASC),
  CONSTRAINT `fk_StockLog_Product1`
    FOREIGN KEY (`Product_idProduct`)
    REFERENCES `lojacriartdb`.`product` (`idProduct`),
  CONSTRAINT `fk_StockLog_User1`
    FOREIGN KEY (`User_idUser`)
    REFERENCES `lojacriartdb`.`user` (`idUser`))
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8mb4;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
