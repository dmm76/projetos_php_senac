-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.32-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para receitas
CREATE DATABASE IF NOT EXISTS `receitas` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `receitas`;

-- Copiando estrutura para tabela receitas.categoria
CREATE TABLE IF NOT EXISTS `categoria` (
  `idCategoria` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL DEFAULT '0',
  `descricao` varchar(50) DEFAULT '0',
  PRIMARY KEY (`idCategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela receitas.categoria: ~5 rows (aproximadamente)
INSERT INTO `categoria` (`idCategoria`, `nome`, `descricao`) VALUES
	(1, 'Bolos', 'receita de Bolo'),
	(2, 'Doces', 'receita de doces'),
	(3, 'Salgados', 'receita de salgados'),
	(4, 'Frios', 'pratos frios'),
	(5, 'Cafés', 'receitas de café');

-- Copiando estrutura para tabela receitas.produto
CREATE TABLE IF NOT EXISTS `produto` (
  `idProduto` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idProduto`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela receitas.produto: ~7 rows (aproximadamente)
INSERT INTO `produto` (`idProduto`, `nome`) VALUES
	(1, 'ovos'),
	(2, 'farinha'),
	(3, 'leite'),
	(4, 'fermento'),
	(5, 'açucar'),
	(6, 'cenoura'),
	(7, 'chocolate meio amargo');

-- Copiando estrutura para tabela receitas.receita
CREATE TABLE IF NOT EXISTS `receita` (
  `idReceita` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL DEFAULT '0',
  `descricao` varchar(50) NOT NULL DEFAULT '0',
  `idCategoria` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idReceita`),
  KEY `fk_receita_categoria` (`idCategoria`),
  CONSTRAINT `fk_receita_categoria` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`idCategoria`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela receitas.receita: ~2 rows (aproximadamente)
INSERT INTO `receita` (`idReceita`, `nome`, `descricao`, `idCategoria`) VALUES
	(1, 'Bolo de Cenoura', 'Bolo de Cenoura com cobertura de chocolate', 1),
	(2, 'Coxinha de Frango', 'coxinha mandioca com recheio de frango', 3);

-- Copiando estrutura para tabela receitas.receita_produto
CREATE TABLE IF NOT EXISTS `receita_produto` (
  `idReceita_produto` int(11) NOT NULL AUTO_INCREMENT,
  `idReceita` int(11) NOT NULL,
  `idProduto` int(11) NOT NULL,
  `quantidade` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`idReceita_produto`),
  KEY `fk_rp_receita` (`idReceita`),
  KEY `fk_rp_produto` (`idProduto`),
  CONSTRAINT `fk_rp_produto` FOREIGN KEY (`idProduto`) REFERENCES `produto` (`idProduto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rp_receita` FOREIGN KEY (`idReceita`) REFERENCES `receita` (`idReceita`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela receitas.receita_produto: ~7 rows (aproximadamente)
INSERT INTO `receita_produto` (`idReceita_produto`, `idReceita`, `idProduto`, `quantidade`) VALUES
	(1, 1, 5, '300gr'),
	(2, 1, 6, '400gr'),
	(3, 1, 7, '400gr'),
	(4, 1, 2, '400gr'),
	(5, 1, 4, '10gr'),
	(6, 1, 3, '200ml'),
	(7, 1, 1, '6');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
