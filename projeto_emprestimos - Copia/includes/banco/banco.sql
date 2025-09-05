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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela ferramentas.emprestimo: ~6 rows (aproximadamente)
INSERT INTO `emprestimo` (`id`, `cadastro`, `id_ferramenta`, `id_usuario`, `data_emprestimo`, `data_devolucao`) VALUES
	(5, '2025-09-02 19:24:35', 4, 3, '2025-09-02', '2025-09-03'),
	(6, '2025-09-02 19:32:45', 1, 1, '2025-09-02', '2025-09-03'),
	(7, '2025-09-02 19:36:09', 5, 4, '2025-09-02', '2025-09-03'),
	(8, '2025-09-03 19:42:15', 4, 4, '2025-09-03', '2025-09-06'),
	(9, '2025-09-03 19:49:32', 1, 1, '2025-09-03', '2025-09-10'),
	(10, '2025-09-03 19:57:19', 1, 4, '2025-09-03', '2025-09-10');

-- Copiando estrutura para tabela ferramentas.ferramenta
CREATE TABLE IF NOT EXISTS `ferramenta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `nome` varchar(50) NOT NULL,
  `descricao` varchar(50) DEFAULT NULL,
  `status` enum('disponível','reservada','emprestada') NOT NULL DEFAULT 'disponível',
  `estado` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela ferramentas.ferramenta: ~7 rows (aproximadamente)
INSERT INTO `ferramenta` (`id`, `cadastro`, `nome`, `descricao`, `status`, `estado`) VALUES
	(1, '2025-09-02 17:44:03', 'Martelo', 'martelo de ferro', 'emprestada', 'semi novo'),
	(4, '2025-09-02 18:29:16', 'Alicate', 'alicate grande panis', 'reservada', 'semi novo'),
	(5, '2025-09-02 19:35:52', 'Cortador de Grama', 'aparador', 'emprestada', 'novo'),
	(6, '2025-09-03 17:46:26', 'Marreta', 'marreta de demolição', 'reservada', 'usada'),
	(7, '2025-09-03 19:54:31', 'Chave de fenda pequena', 'chave de fenda pequena', 'reservada', 'Otimo'),
	(8, '2025-09-03 19:54:58', 'Chave de fenda média', 'chave de fenda média', 'disponível', 'Otimo'),
	(9, '2025-09-03 19:55:12', 'Chave de fenda grande', 'chave de fenda média', 'disponível', 'Otimo');

-- Copiando estrutura para tabela ferramentas.reserva
CREATE TABLE IF NOT EXISTS `reserva` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ferramenta` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_reserva` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('ativa','atendida','cancelada') NOT NULL DEFAULT 'ativa',
  PRIMARY KEY (`id`),
  KEY `fk_res_user` (`id_usuario`),
  KEY `idx_fila` (`id_ferramenta`,`status`,`data_reserva`),
  CONSTRAINT `fk_res_ferr` FOREIGN KEY (`id_ferramenta`) REFERENCES `ferramenta` (`id`),
  CONSTRAINT `fk_res_user` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela ferramentas.reserva: ~10 rows (aproximadamente)
INSERT INTO `reserva` (`id`, `id_ferramenta`, `id_usuario`, `data_reserva`, `status`) VALUES
	(1, 4, 1, '2025-09-03 16:23:22', 'cancelada'),
	(2, 5, 1, '2025-09-03 16:23:23', 'cancelada'),
	(3, 6, 1, '2025-09-03 16:23:24', 'cancelada'),
	(4, 1, 1, '2025-09-03 16:23:25', 'atendida'),
	(5, 4, 1, '2025-09-03 16:24:06', 'ativa'),
	(6, 6, 1, '2025-09-03 16:24:08', 'ativa'),
	(7, 5, 1, '2025-09-03 16:24:13', 'ativa'),
	(8, 1, 4, '2025-09-03 16:57:03', 'atendida'),
	(9, 7, 4, '2025-09-03 16:57:04', 'ativa'),
	(10, 4, 4, '2025-09-03 16:57:05', 'ativa');

-- Copiando estrutura para tabela ferramentas.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `nome` varchar(60) NOT NULL,
  `email` varchar(40) NOT NULL,
  `senha` varchar(40) NOT NULL,
  `apartamento` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela ferramentas.usuario: ~4 rows (aproximadamente)
INSERT INTO `usuario` (`id`, `cadastro`, `nome`, `email`, `senha`, `apartamento`) VALUES
	(1, '2025-09-02 17:13:28', 'Douglas Marcelo Monquero', 'douglas@email.com', 'e10adc3949ba59abbe56e057f20f883e', 'bl03 apt212'),
	(3, '2025-09-02 18:29:46', 'Patricia Alves', 'paty@email.com.br', 'd41d8cd98f00b204e9800998e', 'bl03 apt21'),
	(4, '2025-09-02 19:34:38', 'João Ferlini', 'joao@email.com.br', '81dc9bdb52d04dc20036dbd8313ed055', 'bl01 apt24'),
	(7, '2025-09-03 18:59:12', 'Carlos Massa', 'carlos.massa@email.com', '81dc9bdb52d04dc20036dbd83', 'bl01 apt55');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
