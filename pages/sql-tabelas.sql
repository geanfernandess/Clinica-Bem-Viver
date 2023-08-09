CREATE TABLE clinica_pessoa
(
   codigo_pessoa int PRIMARY KEY auto_increment,
   nome varchar(50),
   sexo varchar(30),
   email varchar(50) UNIQUE,
   telefone char(15),
   cep char(10),
   logradouro varchar(100),
   cidade varchar(50),
   estado varchar(50)
) ENGINE=InnoDB;

CREATE TABLE clinica_pessoa_paciente
(
   peso float,
   altura int,
   tipoSanguineo varchar(10),
   codigo_paciente int PRIMARY KEY,
   FOREIGN KEY (codigo_paciente) REFERENCES clinica_pessoa(codigo_pessoa) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE clinica_pessoa_funcionario
(
   funcaoFuncionario varchar(50),
   dataContrato date,
   salario float,
   senhaHash varchar(255),
   codigo_funcionario int PRIMARY KEY,
   FOREIGN KEY (codigo_funcionario) REFERENCES clinica_pessoa(codigo_pessoa) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE clinica_pessoa_funcionario_medico
(
   especialidade varchar(50),
   crm varchar(50),
   codigo_medico int PRIMARY KEY,
   FOREIGN KEY (codigo_medico) REFERENCES clinica_pessoa_funcionario(codigo_funcionario) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE clinica_agenda
(
   codigo_agenda int PRIMARY KEY auto_increment,
   dataConsulta date,
   horarioConsulta int,
   nome varchar(50),
   sexo varchar(30),
   email varchar(50),
   codigo_medico int,
   FOREIGN KEY (codigo_medico) REFERENCES clinica_pessoa_funcionario_medico(codigo_medico) ON DELETE CASCADE
) ENGINE=InnoDB;


CREATE TABLE clinica_base_enderecos
(
   CEP varchar(9),
   logradouro varchar(100),
   cidade varchar(50),
   estado varchar(50)
) ENGINE=InnoDB;

INSERT INTO clinica_base_enderecos VALUES ('38400-100', 'Joao Naves', 'Uberlandia', 'MINAS GERAIS');
INSERT INTO clinica_base_enderecos VALUES ('38400-200', 'Floriano Peixoto', 'Uberlandia', 'MINAS GERAIS');
INSERT INTO clinica_base_enderecos VALUES ('38400-300', 'Afonso Pena', 'Uberlandia', 'MINAS GERAIS');
