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


-- Copiando estrutura do banco de dados para ferramentas
CREATE DATABASE IF NOT EXISTS `ferramentas` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `ferramentas`;

-- Copiando estrutura para tabela ferramentas.emprestimo
CREATE TABLE IF NOT EXISTS `emprestimo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_ferramenta` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_emprestimo` date DEFAULT NULL,
  `data_devolucao` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_emprestimo_usuario` (`id_usuario`),
  KEY `fk_emprestimo_ferramenta` (`id_ferramenta`),
  CONSTRAINT `fk_emprestimo_ferramenta` FOREIGN KEY (`id_ferramenta`) REFERENCES `ferramenta` (`id`),
  CONSTRAINT `fk_emprestimo_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela ferramentas.emprestimo: ~3 rows (aproximadamente)
INSERT INTO `emprestimo` (`id`, `cadastro`, `id_ferramenta`, `id_usuario`, `data_emprestimo`, `data_devolucao`) VALUES
	(5, '2025-09-02 19:24:35', 4, 3, '2025-09-02', '2025-09-03'),
	(6, '2025-09-02 19:32:45', 1, 1, '2025-09-02', '2025-09-03'),
	(7, '2025-09-02 19:36:09', 5, 4, '2025-09-02', '2025-09-03');

-- Copiando estrutura para tabela ferramentas.ferramenta
CREATE TABLE IF NOT EXISTS `ferramenta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `nome` varchar(50) NOT NULL,
  `descricao` varchar(50) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `estado` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela ferramentas.ferramenta: ~3 rows (aproximadamente)
INSERT INTO `ferramenta` (`id`, `cadastro`, `nome`, `descricao`, `status`, `estado`) VALUES
	(1, '2025-09-02 17:44:03', 'Martelo', 'martelo de ferro', 'reservada', 'semi novo'),
	(4, '2025-09-02 18:29:16', 'Alicate', 'alicate grande', 'disponivel', 'semi novo'),
	(5, '2025-09-02 19:35:52', 'Cortador de Grama', 'aparador', 'disponivel', 'novo');

-- Copiando estrutura para tabela ferramentas.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `nome` varchar(60) NOT NULL,
  `email` varchar(25) NOT NULL,
  `senha` varchar(25) NOT NULL,
  `apartamento` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela ferramentas.usuario: ~3 rows (aproximadamente)
INSERT INTO `usuario` (`id`, `cadastro`, `nome`, `email`, `senha`, `apartamento`) VALUES
	(1, '2025-09-02 17:13:28', 'Douglas Marcelo Monquero', 'douglas.monquero@gmail.co', 'd41d8cd98f00b204e9800998e', 'bl03 apt21'),
	(3, '2025-09-02 18:29:46', 'Patricia Alves', 'paty@email.com', '81dc9bdb52d04dc20036dbd83', 'bl03 apt21'),
	(4, '2025-09-02 19:34:38', 'João Ferlini', 'joao@email.com', 'a3590023df66ac92ae35e3316', 'bl01 apt24');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
