CREATE DATABASE controle_estoque; 
-- Cria o banco de dados chamado "controle_estoque"

USE controle_estoque;
-- Seleciona o banco de dados para uso

CREATE TABLE alimentos (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- Campo ID, inteiro, auto-incremento, chave primária
    nome VARCHAR(255) NOT NULL,             -- Nome do alimento, texto até 255 caracteres, obrigatório
    quantidade VARCHAR(100) NOT NULL,       -- Quantidade, texto até 100 caracteres, obrigatório
    tipo VARCHAR(100) NOT NULL,             -- Tipo do alimento, texto até 100 caracteres, obrigatório
    data_fabricacao DATE,                   -- Data de fabricação, formato data, opcional
    data_validade DATE                      -- Data de validade, formato data, opcional
);

ALTER TABLE alimentos
ADD COLUMN unidade VARCHAR(10),             -- Adiciona coluna "unidade" (ex: kg, l, un), texto até 10 caracteres
ADD COLUMN fornecedor VARCHAR(100),         -- Adiciona coluna "fornecedor", texto até 100 caracteres
ADD COLUMN lote VARCHAR(50),                -- Adiciona coluna "lote", texto até 50 caracteres
ADD COLUMN data_entrada DATE,               -- Adiciona coluna "data_entrada", formato data
ADD COLUMN observacoes VARCHAR(500);        -- Adiciona coluna "observacoes", texto até 500 caracteres