-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

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
  UNIQUE INDEX `uq_customer_email` (`Email` ASC),
  UNIQUE INDEX `uq_customer_cpf` (`CPF` ASC))
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
    FOREIGN KEY (`Customer_idCustomer` )
    REFERENCES `lojacriartdb`.`customer` (`idCustomer`))
ENGINE = InnoDB
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
  UNIQUE INDEX `uq_user_email` (`email` ASC),
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
    FOREIGN KEY (`User_idUser` )
    REFERENCES `lojacriartdb`.`user` (`idUser`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- SEED DATA (10+ registros por tabela principal)
-- -----------------------------------------------------

-- 1. Roles
INSERT INTO `role` (`idRole`, `name`, `description`) VALUES
(1, 'user', 'Usuário padrão do sistema com permissões limitadas'),
(2, 'admin', 'Administrador com acesso total ao sistema');

-- 2. Users 
INSERT INTO `user` (`idUser`, `name`, `email`, `passwordHash`, `phone`, `cpf`, `Role_idRole`) VALUES
(1, 'Administrador', 'admin@criarty.com', '$2y$10$bgLrouGfMI86u3cNTN0.lOMlmQJUFPJhVdUe7PrXkCspCA11ly30y', '11988887777', '111.222.333-44', 2);

-- 3. Categories
INSERT INTO `category` (`idCategory`, `Name`, `Description`) VALUES
(1, 'Papelaria Personalizada', 'Cadernos, agendas e blocos exclusivos'),
(2, 'Canecas e Copos', 'Canecas de cerâmica e copos personalizados'),
(3, 'Decoração', 'Quadros, placas decorativas e luminárias'),
(4, 'Vestuário', 'Camisetas e ecobags personalizadas'),
(5, 'Datas Sazonais', 'Produtos para Natal, Páscoa e Dia das Mães');

-- 4. Products (10 itens)
INSERT INTO `product` (`idProduct`, `Name`, `BasePrice`, `Stock`, `MinStock`, `Description`, `Category_idCategory`) VALUES
(1, 'Caderno Capa Dura Personalizado', 45.90, 30, 5, 'Caderno 200 folhas com arte personalizada', 1),
(2, 'Bloco de Notas Criativo', 15.50, 50, 10, 'Bloco pautado com capa kraft', 1),
(3, 'Caneca de Porcelana Branca', 29.90, 40, 8, 'Caneca 325ml para sublimação', 2),
(4, 'Copo Long Drink Acrílico', 6.50, 120, 20, 'Copo personalizado para festas e eventos', 2),
(5, 'Quadro Decorativo MDF', 39.90, 15, 3, 'Quadro decorativo 20x30cm com frase', 3),
(6, 'Luminária LED Acrílica', 89.90, 10, 2, 'Luminária com base de madeira e acrílico gravado', 3),
(7, 'Camiseta 100% Algodão', 49.90, 25, 5, 'Camiseta preta ou branca silkada', 4),
(8, 'Ecobag de Algodão Cru', 22.00, 60, 10, 'Sacola ecológica reutilizável', 4),
(9, 'Kit Presente Dia das Mães', 119.90, 8, 2, 'Kit contendo caneca, bloco e caixa especial', 5),
(10, 'Agenda Executiva 2026', 65.00, 20, 5, 'Agenda diária com capa sintética', 1);

-- 5. Customers (10 clientes)
INSERT INTO `customer` (`idCustomer`, `Name`, `Email`, `passwordHash`, `Phone`, `CPF`) VALUES
(1, 'João Pereira', 'joao.pereira@email.com', 'hash', '11911112222', '123.456.789-01'),
(2, 'Maria Oliveira', 'maria.oliveira@email.com', 'hash', '11922223333', '234.567.890-12'),
(3, 'Pedro Santos', 'pedro.santos@email.com', 'hash', '11933334444', '345.678.901-23'),
(4, 'Luciana Costa', 'luciana.costa@email.com', 'hash', '11944445555', '456.789.012-34'),
(5, 'Fernanda Souza', 'fernanda.souza@email.com', 'hash', '11955556666', '567.890.123-45'),
(6, 'Gabriel Almeida', 'gabriel.almeida@email.com', 'hash', '11966667777', '678.901.234-56'),
(7, 'Juliana Ribeiro', 'juliana.ribeiro@email.com', 'hash', '11977778888', '789.012.345-67'),
(8, 'Bruno Martins', 'bruno.martins@email.com', 'hash', '11988889999', '890.123.456-78'),
(9, 'Camila Barbosa', 'camila.barbosa@email.com', 'hash', '11999990000', '901.234.567-89'),
(10, 'Rafael Dias', 'rafael.dias@email.com', 'hash', '11900001111', '012.345.678-90');

-- 6. Addresses (10 endereços)
INSERT INTO `address` (`idAddress`, `Customer_idCustomer`, `Street`, `Number`, `Neighborhood`, `City`, `State`, `ZipCode`, `isDefault`) VALUES
(1, 1, 'Rua das Flores', '123', 'Centro', 'São Paulo', 'SP', '01001-000', 1),
(2, 2, 'Av. Paulista', '1000', 'Bela Vista', 'São Paulo', 'SP', '01310-100', 1),
(3, 3, 'Rua da Consolação', '500', 'Consolação', 'São Paulo', 'SP', '01302-001', 1),
(4, 4, 'Rua Augusta', '1500', 'Cerqueira César', 'São Paulo', 'SP', '01413-002', 1),
(5, 5, 'Av. Brigadeiro Luís Antônio', '300', 'Paraíso', 'São Paulo', 'SP', '01318-000', 1),
(6, 6, 'Rua 25 de Março', '200', 'Centro', 'São Paulo', 'SP', '01021-200', 1),
(7, 7, 'Rua Teodoro Sampaio', '800', 'Pinheiros', 'São Paulo', 'SP', '05405-000', 1),
(8, 8, 'Av. Rebouças', '2500', 'Pinheiros', 'São Paulo', 'SP', '05401-300', 1),
(9, 9, 'Rua Oscar Freire', '900', 'Jardins', 'São Paulo', 'SP', '01426-001', 1),
(10, 10, 'Rua Haddock Lobo', '400', 'Cerqueira César', 'São Paulo', 'SP', '01414-001', 1);

-- 7. Orders (10 pedidos)
INSERT INTO `order` (`idOrder`, `Status`, `PaymentStatus`, `PaymentMethod`, `TotalAmount`, `Address_idAddress`, `Customer_idCustomer`) VALUES
(1, 'entregue', 'pago', 'Cartão de Crédito', 91.80, 1, 1),
(2, 'enviado', 'pago', 'Pix', 29.90, 2, 2),
(3, 'em_processamento', 'pago', 'Boleto', 79.80, 3, 3),
(4, 'pendente', 'pendente', 'Pix', 89.90, 4, 4),
(5, 'entregue', 'pago', 'Cartão de Crédito', 49.90, 5, 5),
(6, 'cancelado', 'recusado', 'Cartão de Crédito', 45.90, 6, 6),
(7, 'enviado', 'pago', 'Pix', 44.00, 7, 7),
(8, 'em_processamento', 'pago', 'Boleto', 119.90, 8, 8),
(9, 'pendente', 'pendente', 'Pix', 15.50, 9, 9),
(10, 'entregue', 'pago', 'Cartão de Crédito', 65.00, 10, 10);

-- 8. Order Items (10 itens de pedido)
INSERT INTO `orderitems` (`idOrderItems`, `Order_idOrder`, `Product_idProduct`, `Quantity`, `UnitPrice`, `CustomizationDetails`) VALUES
(1, 1, 1, 2, 45.90, 'Nome gravado na capa: João'),
(2, 2, 3, 1, 29.90, 'Com foto temática'),
(3, 3, 5, 2, 39.90, 'Frase motivacional'),
(4, 4, 6, 1, 89.90, 'Base acinzentada'),
(5, 5, 7, 1, 49.90, 'Tamanho M - estampa rock'),
(6, 6, 1, 1, 45.90, 'Sem personalização'),
(7, 7, 8, 2, 22.00, 'Logo minimalista'),
(8, 8, 9, 1, 119.90, 'Caixa com laço vermelho'),
(9, 9, 2, 1, 15.50, 'Capa kraft simples'),
(10, 10, 10, 1, 65.00, 'Ano 2026 dourado');

-- 9. Stock Logs (10 logs de estoque)
INSERT INTO `stocklog` (`idStockLog`, `Product_idProduct`, `User_idUser`, `quantityChange`, `reason`, `notes`) VALUES
(1, 1, 1, 30, 'entrada', 'Lote inicial de cadernos'),
(2, 2, 1, 50, 'entrada', 'Lote inicial de blocos'),
(3, 3, 2, 40, 'entrada', 'Compra de canecas'),
(4, 4, 2, 120, 'entrada', 'Compra copos acrílicos'),
(5, 5, 1, 15, 'entrada', 'Fabricação quadros MDF'),
(6, 6, 2, 10, 'entrada', 'Montagem luminárias LED'),
(7, 7, 3, 25, 'entrada', 'Confecção camisetas'),
(8, 8, 3, 60, 'entrada', 'Recebimento ecobags'),
(9, 9, 1, 8, 'entrada', 'Montagem kits dia das mães'),
(10, 10, 1, 20, 'entrada', 'Lote agendas executivas');

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;