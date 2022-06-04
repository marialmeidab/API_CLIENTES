# API_CLIENTES

Api com os 4 métodos: GET,POST,PUT e DELETE. Para manipular informações de clientes. 

Api utilizando token para autorização. 
Token que utilizei: 6DSTiJuAJ7sclcrtq629a557309a5d 

Tabelas de dados: 

tokens_autorizados | CREATE TABLE `tokens_autorizados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(150) NOT NULL,
  `status` enum('S','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_UNIQUE` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8

clientes | CREATE TABLE `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `cpf` varchar(20) NOT NULL,
  `placa` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 |
