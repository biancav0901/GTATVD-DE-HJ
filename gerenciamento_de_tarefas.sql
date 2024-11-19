create database gerenciamento_de_tarefas;
use gerenciamento_de_tarefas;

create table usuarios(
						 usu_id int primary key auto_increment not null,
						 usu_nome varchar(100),
						 usu_email varchar(100));
						 
CREATE TABLE tarefas (
    tarefa_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    tarefa_setor VARCHAR(100),
    tarefa_prioridade VARCHAR(100),
    tarefa_descricao VARCHAR(100),
    tarefa_status VARCHAR(100),
    usu_id INT,
    FOREIGN KEY (usu_id) REFERENCES usuarios(usu_id));
