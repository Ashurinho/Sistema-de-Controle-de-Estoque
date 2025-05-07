CREATE DATABASE controle_estoque;

USE controle_estoque;

CREATE TABLE alimentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    quantidade VARCHAR(100) NOT NULL,
    tipo VARCHAR(100) NOT NULL,
    data_fabricacao DATE,
    data_validade DATE
);
