CREATE TABLE UF (
	uf_cod INT NOT NULL,
	uf_nome VARCHAR(80) NOT NULL,
	PRIMARY KEY (uf_cod)
);

CREATE TABLE MUNICIPIO (
	municipio_cod INT NOT NULL,
	municipio_nome VARCHAR(120) NOT NULL,
	municipio_uf_cod INT NOT NULL,
	PRIMARY KEY (municipio_cod),
	FOREIGN KEY(municipio_uf_cod) REFERENCES UF(uf_cod)
);



CREATE TABLE ENDERECO (
	endereco_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	endereco_latitude CHAR(15) NOT NULL,
	endereco_longitude CHAR(15) NOT NULL,
	endereco_logradouro VARCHAR(120) NOT NULL,
	endereco_bairro VARCHAR(60) NOT NULL,
	endereco_municipio_cod INT NOT NULL,
	PRIMARY KEY(endereco_id),
	FOREIGN KEY (endereco_municipio_cod) REFERENCES MUNICIPIO(municipio_cod)
);




CREATE TABLE SINTOMAS (
	sintomas_id INT UNSIGNED AUTO_INCREMENT,
	sintomas_fratura CHAR(1) DEFAULT 'N',
	sintomas_infarto CHAR(1) DEFAULT 'N',
	sintomas_falta_ar CHAR(1) DEFAULT 'N',
	sintomas_tontura CHAR(1) DEFAULT 'N',
	sintomas_queda CHAR(1) DEFAULT 'N',
	sintomas_convulsao CHAR(1) DEFAULT 'N',
	sintomas_acidente_carro CHAR(1) DEFAULT 'N',
	sintomas_avc CHAR(1) DEFAULT 'N',
	PRIMARY KEY(sintomas_id)
);



CREATE TABLE PACIENTE (
	paciente_id INT UNSIGNED AUTO_INCREMENT,
	paciente_nome VARCHAR(60),
	paciente_sobrenome VARCHAR(90),
	paciente_sexo CHAR(1),
	paciente_idade VARCHAR(4),
	paciente_rg CHAR(10),
	paciente_cpf CHAR(11),
	paciente_telefone VARCHAR(15),
	paciente_sintomas_id INT UNSIGNED,
	PRIMARY KEY(paciente_id),
	FOREIGN KEY(paciente_sintomas_id) REFERENCES SINTOMAS(sintomas_id)
);




CREATE TABLE OCORRENCIA (
	ocorrencia_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	ocorrencia_date TIMESTAMP NOT NULL,
	ocorrencia_status VARCHAR(20) NOT NULL,
	ocorrencia_tipo_atendimento VARCHAR(20) NOT NULL,
	ocorrencia_grau_risco VARCHAR(15) NOT NULL,

	ocorrencia_endereco_id INT UNSIGNED,
	PRIMARY KEY(ocorrencia_id),
	FOREIGN KEY (ocorrencia_endereco_id) REFERENCES ENDERECO(endereco_id)
);


CREATE TABLE PACIENTE_OCORRENCIA(
	paciente_ocorrencia_id INT UNSIGNED AUTO_INCREMENT,
	paciente_ocorrencia_paciente_id INT UNSIGNED NOT NULL,
	paciente_ocorrencia_ocorrencia_id INT UNSIGNED NOT NULL,
	PRIMARY KEY(paciente_ocorrencia_id),
	FOREIGN KEY(paciente_ocorrencia_paciente_id) REFERENCES PACIENTE(paciente_id),
	FOREIGN KEY(paciente_ocorrencia_ocorrencia_id) REFERENCES OCORRENCIA(ocorrencia_id)
);



CREATE TABLE POSTO_SAMU (
	posto_samu_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	posto_samu_cod INT NOT NULL,
	posto_samu_nome VARCHAR(45) NOT NULL,
	posto_samu_qtd_ambulancia INT(4) NOT NULL,
	posto_samu_cap_ambulancia INT(4) NOT NULL,

	posto_samu_endereco_id INT UNSIGNED,
	PRIMARY KEY(posto_samu_id),
	FOREIGN KEY (posto_samu_endereco_id) REFERENCES ENDERECO(endereco_id)
);




CREATE TABLE SAMU (
	samu_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	samu_co_unidade VARCHAR(45) NOT NULL,
	samu_co_placa VARCHAR(45) NOT NULL,
	samu_posto_samu_id INT UNSIGNED NOT NULL,
	PRIMARY KEY(samu_id),
	FOREIGN KEY(samu_posto_samu_id) REFERENCES POSTO_SAMU(posto_samu_id)
);




CREATE TABLE HOSPITAL (
	hospital_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	hospital_nome_fantasia VARCHAR(80) NOT NULL,
	hospital_cnes CHAR(7) NOT NULL,
	hospital_endereco_id INT UNSIGNED,
	PRIMARY KEY(hospital_id),
	FOREIGN KEY(hospital_endereco_id) REFERENCES ENDERECO(endereco_id)
);



CREATE TABLE HOSPITAL_CLININCA (
	hospital_clinica_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	hospital_clinica_descricao VARCHAR(45) NOT NULL,
	hospital_clinica_qtd_leito_ocupad INT(4) NOT NULL,
	hospital_clinica_qtd_leito_total INT(4) NOT NULL,
	hospital_clinica_hospital_id INT UNSIGNED NOT NULL,
	PRIMARY KEY(hospital_clinica_id),
	FOREIGN KEY(hospital_clinica_hospital_id) REFERENCES HOSPITAL(hospital_id)
);


CREATE TABLE UPA (
	upa_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	upa_nome_fantasia VARCHAR(100) NOT NULL,
	upa_cnes CHAR(7) NOT NULL,
	upa_qtd_leito_ocupad INT(4) NOT NULL,
	upa_qtd_leito_total INT(4) NOT NULL,
	upa_endereco_id INT UNSIGNED,
	PRIMARY KEY(upa_id),
	FOREIGN KEY(upa_endereco_id) REFERENCES ENDERECO(endereco_id)
);



CREATE TABLE SAMU_OCORRENCIA (
	samu_ocorrencia_id INT UNSIGNED NOT NULL AUTO_INCREMENT,

	samu_ocorrencia_data_hora_origem DATETIME NOT NULL,
	samu_ocorrencia_data_hora_destino DATETIME NOT NULL,

	samu_ocorrencia_ocorrencia_id INT UNSIGNED NOT NULL,
	samu_ocorrencia_samu_id INT UNSIGNED NOT NULL,
	samu_ocorrencia_hospital_clinica_id INT UNSIGNED,
	samu_ocorrencia_upa_id INT UNSIGNED,

	PRIMARY KEY(samu_ocorrencia_id),
	FOREIGN KEY(samu_ocorrencia_ocorrencia_id) REFERENCES OCORRENCIA(ocorrencia_id),
	FOREIGN KEY(samu_ocorrencia_samu_id) REFERENCES SAMU(samu_id),
	FOREIGN KEY(samu_ocorrencia_hospital_clinica_id) REFERENCES HOSPITAL_CLININCA(hospital_clinica_id),
	FOREIGN KEY(samu_ocorrencia_upa_id) REFERENCES UPA(upa_id)
);




DELIMITER $$
/*DROP PROCEDURE IF EXISTS end_hospital$$*/
CREATE PROCEDURE `end_hospital`(IN `latitude` CHAR(15), IN `longitude` CHAR(15), IN `logradouro` VARCHAR(120), IN `bairro` VARCHAR(60), IN `municipio_cod` INT(11), 
	IN `hosp_id` INT(10)) 
BEGIN 
	IF (SELECT endereco_latitude FROM ENDERECO WHERE endereco_latitude = latitude AND endereco_longitude = longitude)
	THEN
	UPDATE HOSPITAL SET hospital_endereco_id = (SELECT endereco_id FROM ENDERECO WHERE endereco_latitude = latitude AND endereco_longitude = longitude) WHERE hospital_id = hosp_id;
	ELSE
	INSERT INTO ENDERECO(endereco_latitude, endereco_longitude,	endereco_logradouro, endereco_bairro, endereco_municipio_cod) 
	VALUES (latitude, longitude, logradouro, bairro, municipio_cod); 
	UPDATE HOSPITAL SET hospital_endereco_id = (SELECT endereco_id FROM ENDERECO WHERE endereco_latitude = latitude AND endereco_longitude = longitude) WHERE hospital_id = hosp_id;
	END IF;
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS end_paciente$$
CREATE PROCEDURE `end_paciente`(IN `latitude` CHAR(15), IN `longitude` CHAR(15), IN `logradouro` VARCHAR(120), IN `bairro` VARCHAR(60), IN `municipio_cod` INT(11), 
	IN `pac_id` INT(10)) 
BEGIN 
	IF (SELECT endereco_latitude FROM ENDERECO WHERE endereco_latitude = latitude AND endereco_longitude = longitude)
	THEN
	UPDATE PACIENTE SET paciente_endereco_id = (SELECT endereco_id FROM ENDERECO WHERE endereco_latitude = latitude AND endereco_longitude = longitude) WHERE paciente_id = pac_id;
	ELSE
	INSERT INTO ENDERECO(endereco_latitude, endereco_longitude,	endereco_logradouro, endereco_bairro, endereco_municipio_cod) 
	VALUES (latitude, longitude, logradouro, bairro, municipio_cod); 
	UPDATE PACIENTE SET paciente_endereco_id = (SELECT endereco_id FROM ENDERECO WHERE endereco_latitude = latitude AND endereco_longitude = longitude) WHERE paciente_id = pac_id;
	END IF;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS end_upa$$
CREATE PROCEDURE `end_upa`(IN `latitude` CHAR(15), IN `longitude` CHAR(15), IN `logradouro` VARCHAR(120), IN `bairro` VARCHAR(60), IN `municipio_cod` INT(11), 
	IN `up_id` INT(10)) 
BEGIN 
	IF (SELECT endereco_latitude FROM ENDERECO WHERE endereco_latitude = latitude AND endereco_longitude = longitude)
	THEN
	UPDATE UPA SET upa_endereco_id = (SELECT endereco_id FROM ENDERECO WHERE endereco_latitude = latitude AND endereco_longitude = longitude) WHERE upa_id = up_id;
	ELSE
	INSERT INTO ENDERECO(endereco_latitude, endereco_longitude,	endereco_logradouro, endereco_bairro, endereco_municipio_cod) 
	VALUES (latitude, longitude, logradouro, bairro, municipio_cod); 
	UPDATE UPA SET upa_endereco_id = (SELECT endereco_id FROM ENDERECO WHERE endereco_latitude = latitude AND endereco_longitude = longitude) WHERE upa_id = up_id;
	END IF;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS end_posto_samu$$
CREATE PROCEDURE `end_posto_samu`(IN `latitude` CHAR(15), IN `longitude` CHAR(15), IN `logradouro` VARCHAR(120), IN `bairro` VARCHAR(60), IN `municipio_cod` INT(11), 
	IN `post_samu_id` INT(10)) 
BEGIN 
	IF (SELECT endereco_latitude FROM ENDERECO WHERE endereco_latitude = latitude AND endereco_longitude = longitude)
	THEN
	UPDATE POSTO_SAMU SET posto_samu_endereco_id = (SELECT endereco_id FROM ENDERECO WHERE endereco_latitude = latitude AND endereco_longitude = longitude) WHERE posto_samu_id = post_samu_id;
	ELSE
	INSERT INTO ENDERECO(endereco_latitude, endereco_longitude,	endereco_logradouro, endereco_bairro, endereco_municipio_cod) 
	VALUES (latitude, longitude, logradouro, bairro, municipio_cod); 
	UPDATE POSTO_SAMU SET posto_samu_endereco_id = (SELECT endereco_id FROM ENDERECO WHERE endereco_latitude = latitude AND endereco_longitude = longitude) WHERE posto_samu_id = post_samu_id;
	END IF;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS end_ocorrencia$$
CREATE PROCEDURE `end_ocorrencia`(IN `latitude` CHAR(15), IN `longitude` CHAR(15), IN `logradouro` VARCHAR(120), IN `bairro` VARCHAR(60), IN `municipio_cod` INT(11), 
	IN `ocorrenc_id` INT(10)) 
BEGIN 
	IF (SELECT endereco_latitude FROM ENDERECO WHERE endereco_latitude = latitude AND endereco_longitude = longitude)
	THEN
	UPDATE OCORRENCIA SET ocorrencia_endereco_id = (SELECT endereco_id FROM ENDERECO WHERE endereco_latitude = latitude AND endereco_longitude = longitude) WHERE ocorrencia_id = ocorrenc_id;
	ELSE
	INSERT INTO ENDERECO(endereco_latitude, endereco_longitude,	endereco_logradouro, endereco_bairro, endereco_municipio_cod) 
	VALUES (latitude, longitude, logradouro, bairro, municipio_cod); 
	UPDATE OCORRENCIA SET ocorrencia_endereco_id = (SELECT endereco_id FROM ENDERECO WHERE endereco_latitude = latitude AND endereco_longitude = longitude) WHERE ocorrencia_id = ocorrenc_id;
	END IF;
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS pac_ocorrenc$$
CREATE PROCEDURE `pac_ocorrenc` (IN `nome` VARCHAR(60), IN `sobrenome` VARCHAR(90), IN `sexo` CHAR(1), IN `idade` VARCHAR(3), IN `rg` CHAR(10), IN `cpf` CHAR(11), IN `telefone` CHAR(11), IN `ocorrenc_id` INT(10))
BEGIN
	INSERT INTO `PACIENTE`(`paciente_nome`, `paciente_sobrenome`, `paciente_sexo`, `paciente_idade`, `paciente_rg`, `paciente_cpf`, `paciente_telefone`) 
	VALUES (nome, sobrenome, sexo, idade, rg, cpf, telefone);
	INSERT INTO PACIENTE_OCORRENCIA(paciente_ocorrencia_paciente_id, paciente_ocorrencia_ocorrencia_id) 
	VALUES (LAST_INSERT_ID(), ocorrenc_id);
END$$
DELIMITER ;



DELIMITER $$
DROP FUNCTION IF EXISTS calcularDistancia$$
CREATE FUNCTION `calcularDistancia`(`pac_id` INT, `hosp_id` INT) RETURNS FLOAT DETERMINISTIC 
BEGIN 
	DECLARE lat1 FLOAT DEFAULT 0;
	DECLARE lng1 FLOAT DEFAULT 0;
	DECLARE lat2 FLOAT DEFAULT 0;
	DECLARE lng2 FLOAT DEFAULT 0;
	SET @PI = 3.14159265;
	SELECT endereco_longitude, endereco_latitude INTO lng1, lat1 FROM ENDERECO, PACIENTE WHERE paciente_id = pac_id AND paciente_endereco_id = endereco_id;
	SELECT endereco_longitude, endereco_latitude INTO lng2, lat2 FROM ENDERECO, HOSPITAL WHERE hospital_id = hosp_id AND hospital_endereco_id = endereco_id;
	SET @lng1 = (lng1 * @PI)/180;
	SET @lat1 = (lat1 * @PI)/180;
	SET @lng2 = (lng2 * @PI)/180;
	SET @lat2 = (lat2 * @PI)/180;
	SET @D = 6378.137 * ACOS(COS(lat1) * COS(lat2) * COS(lng2 - lng1) + SIN(lat1) * SIN(lat2)); 
	RETURN @D;
END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS incUpa$$
CREATE TRIGGER incUpa AFTER INSERT ON SAMU_OCORRENCIA FOR EACH ROW 
BEGIN
	UPDATE UPA SET upa_qtd_leito_ocupad = (upa_qtd_leito_ocupad + 1) WHERE NEW.samu_ocorrencia_upa_id = upa_id;
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS incHosp$$
CREATE TRIGGER incHosp AFTER INSERT ON SAMU_OCORRENCIA FOR EACH ROW 
BEGIN
	UPDATE UPA SET upa_qtd_leito_ocupad = (upa_qtd_leito_ocupad + 1) WHERE NEW.samu_ocorrencia_hospital_clinica_id = hospital_clinica_id;
END$$
DELIMITER ;



/*INSERCAO UF */
LOAD DATA LOCAL infile '/home/acl/UF.csv' INTO TABLE UF FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\n' IGNORE 1 ROWS
(@col1, @col2, @col3) set uf_cod=@col1, uf_nome=@col3;


/*INSERCAO MUNICIPIO */
LOAD DATA LOCAL infile '/home/acl/uf-mun.csv' INTO TABLE MUNICIPIO CHARACTER SET LATIN1 FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\n' IGNORE 3 ROWS
(@col1, @col2, @col3, @col4) set municipio_uf_cod=@col2, municipio_cod=@col3, municipio_nome=@col4;



/* INSERT INTO */

INSERT INTO HOSPITAL (hospital_nome_fantasia, hospital_cnes) VALUES
("HOSPITAL GERAL DO ESTADO","0004294"),
("HOSPITAL GERAL ROBERTO SANTOS","0003859"),
("HOSPITAL JULIANO MOREIRA","0004286"),
("HOSPITAL MANOEL VICTORINO","2493845"),
("HOSPITAL PROFESSOR CARVALHO LUZ","0004987"),
("HOSPITAL UNIVERSITARIO PROFESSOR EDGARD SANTOS","0003816"),
("UNIDADE MOVEL DO HOSPITAL DA MULHER","9401520"),
("HOSPITAL ESPECIALIZADO OCTAVIO MANGABEIRA","0004065"),
("HOSPITAL GERAL ERNESTO SIMOES FILHO","0004073"),
("HOSPITAL ESPECIALIZADO MARIO LEAL","0005436"),
("HOSPITAL ESPECIALIZADO DOM RODRIGO DE MENEZES","2799073"),
("HOSPITAL ELADIO LASSERRE","2799073"),
("HOSPITAL DO SUBURBIO","0003980"),
("HOSPITAL ANA NERY","6595197");





INSERT INTO UPA(upa_nome_fantasia, upa_cnes, upa_qtd_leito_ocupad, upa_qtd_leito_total) VALUES
("UPA 24H VALE DOS BARRIS","7633149","0","15"),
("UPA 24H BROTAS","7986076","0","12"),
("UPA 24H PIRAJA SANTO INÁCIO","9030158","0","10"),
("UPA 24H SAN MARTIN","7521316","0","20"),
("UPA 24H PROF ADROALDO ALBERGARIA","0004774","0","15"),
("UPA 24H VALERIA","4746469","0","10"),
("UPA 24H DR HELIO MACHADO","0004340","0","15");




/*HOSPITAL GERAL DO ESTADO*/
INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'CIRURGIA GERAL', 0, 25, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL GERAL DO ESTADO' LIMIT 1;

INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'PRONTO-ATENDIMENTO', 0, 71, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL GERAL DO ESTADO' LIMIT 1;

INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'CLINICA MEDICA', 0, 50, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL GERAL DO ESTADO' LIMIT 1;

INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'QUEIMADOS', 0, 30, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL GERAL DO ESTADO' LIMIT 1;



/*HOSPITAL ANA NERY*/
INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'CARDIOLOGIA', 0, 40, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL ANA NERY' LIMIT 1;

INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'PRONTO ATENDIMENTO CARDIOLOGICO', 0, 45, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL ANA NERY' LIMIT 1;

INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'CLINICA MEDICA', 0, 35, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL ANA NERY' LIMIT 1;

INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'UTI', 0, 20, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL ANA NERY' LIMIT 1;



/*UNIDADE MOVEL DO HOSPITAL DA MULHER*/
INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'HOSPITAL DIA', 0, 22, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'UNIDADE MOVEL DO HOSPITAL DA MULHER' LIMIT 1;

INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'CLINICA MEDICA', 0, 10, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'UNIDADE MOVEL DO HOSPITAL DA MULHER' LIMIT 1;

INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'CLINICA CIRURGICA', 0, 85, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'UNIDADE MOVEL DO HOSPITAL DA MULHER' LIMIT 1;

INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'UTI', 0, 10, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'UNIDADE MOVEL DO HOSPITAL DA MULHER' LIMIT 1;



/*HOSPITAL DO SUBURBIO*/
INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'INTERNACAO HOSPITALAR', 0, 253, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL DO SUBURBIO' LIMIT 1;

INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'TERAPIA INTENSIVA', 0, 60, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL DO SUBURBIO' LIMIT 1;

INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'UTI PEDIATRICA', 0, 10, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL DO SUBURBIO' LIMIT 1;

INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'UTI ADULTO', 0, 50, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL DO SUBURBIO' LIMIT 1;



/*HOSPITAL ESPECIALIZADO MARIO LEAL*/
INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'PRONTO ATENDIMENTO', 0, 8, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL ESPECIALIZADO MARIO LEAL' LIMIT 1;

INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'INTERNACAO INTEGRAL', 0, 30, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL ESPECIALIZADO MARIO LEAL' LIMIT 1;



/*HOSPITAL ESPECIALIZADO OCTAVIO MANGABEIRA*/
INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'INTERNAMENTO ISOLADO TUBERCULOSE', 0, 11, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL ESPECIALIZADO OCTAVIO MANGABEIRA ' LIMIT 1;

INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'INTERNAMENTO TUBERCULOSE', 0, 168, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL ESPECIALIZADO OCTAVIO MANGABEIRA ' LIMIT 1;




/*HOSPITAL GERAL ERNESTO SIMOES FILHO*/
INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'URGENCIA', 0, 30, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL GERAL ERNESTO SIMOES FILHO' LIMIT 1;

INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'EMERGENCIA', 0, 40, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL GERAL ERNESTO SIMOES FILHO' LIMIT 1;

INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'ORTOPEDIA', 0, 30, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL GERAL ERNESTO SIMOES FILHO' LIMIT 1;

INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'UTI', 0, 20, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL GERAL ERNESTO SIMOES FILHO' LIMIT 1;



/*HOSPITAL GERAL ROBERTO SANTOS*/
INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'EMERGENCIA', 0, 60, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL GERAL ROBERTO SANTOS' LIMIT 1;

INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'UTI', 0, 20, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL GERAL ROBERTO SANTOS' LIMIT 1;

INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'PEDIATRIA', 0, 50, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL GERAL ROBERTO SANTOS' LIMIT 1;

INSERT INTO HOSPITAL_CLININCA (hospital_clinica_descricao, hospital_clinica_qtd_leito_ocupad, hospital_clinica_qtd_leito_total, 
hospital_clinica_hospital_id)
SELECT 'NEONATAL', 0, 30, hospital_id FROM HOSPITAL WHERE hospital_nome_fantasia = 'HOSPITAL GERAL ROBERTO SANTOS' LIMIT 1;



/*Insercao dos Enderecos dos Hospitais*/


CALL end_hospital("-12.995371", "-38.489318", "Av. Vasco da Gama", "Brotas", 27408, 1);
CALL end_hospital("-12.952698", "-38.449701", "Av. Edgard Santos", "Cabula", 27408, 2);
CALL end_hospital("-12.952970", "-38.449712", "Av. Edgard Santos", "Narandiba", 27408, 3);
CALL end_hospital("-12.971986", "-38.503155", "Praça Conselheiro Almeida Couto", "Nazaré", 27408, 4);
CALL end_hospital("-12.993416", "-38.520213", "R. Dr. Augusto Viana", "Canela", 27408, 5);
CALL end_hospital("-12.937225", "-38.506989", "Rua Barão de Cotegipe", "Roma", 27408, 6);
CALL end_hospital("-12.959218", "-38.485268", "Praça Conselheiro João Alfredo", "Pau Miúdo", 27408, 7);
CALL end_hospital("-12.960362", "-38.486152", "Praça Conselheiro João Alfredo", "Pau Miúdo", 27408, 8);
CALL end_hospital("-12.957854", "-38.488028", "Rua Conde de Porto Alegre", "Iapi", 27408, 9);
CALL end_hospital("-12.922499", "-38.413743", "R. Barão do Rio Branco", "Nova Brasília", 27408, 10);
CALL end_hospital("-12.889628", "-38.422079", "","Águas Claras", 27408, 11);
CALL end_hospital("-12.865028", "-38.456746", "R. Manoel Lino", "Periperi", 27408, 12);
CALL end_hospital("-12.957283", "-38.495826", "R. Saldanha Marinho", "Caixa d'Agua", 27408, 13);




/*Insercao dos Enderecos das UPAS*/
CALL end_upa("-12.988471", "-38.514345", "Av. Vale dos Barris", "Barris", 27408, 1);
CALL end_upa("-12.986450", "-38.480001", "R. Jardim Madalena", "Campinas", 27408, 2);
CALL end_upa("-12.926520", "-38.460979", "R. Direta de Santo Inácio", "Jardim Santo Inácio", 27408, 3);
CALL end_upa("-12.946836", "-38.481067", "R. do Forno", "Fazenda Grande do Retiro", 27408, 4);
CALL end_upa("-12.863300", "-38.458961", "R. das Pedrinhas", "Periperi", 27408, 5);
CALL end_upa("-12.864475", "-38.436766", "R. do Lavrador", "Valéria", 27408, 6);
CALL end_upa("-12.948989", "-38.366293", "R. da Cacimba", "Itapuã", 27408, 7);




/*INSERCAO EM POSTO_SAMU*/
INSERT INTO POSTO_SAMU(posto_samu_cod, posto_samu_nome, posto_samu_qtd_ambulancia, posto_samu_cap_ambulancia) VALUES
("1","POSTO A", "5", "15"),
("2","POSTO B", "10", "20"),
("3","POSTO C", "15", "20");



CALL end_posto_samu("-12.952970", "-38.449712", "Av. Edgard Santos", "Narandiba", 27408, 1);
CALL end_posto_samu("-12.995371", "-38.489318", "Av. Vasco da Gama", "Brotas", 27408, 2);
CALL end_posto_samu("-12.955508", "-38.4740769", "R. dos Afrades", "Cabula", 27408, 3);



/*INSERCAO EM SAMU*/
LOAD DATA LOCAL infile '/home/acl/samu-list.csv' INTO TABLE SAMU FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\n' IGNORE 1 ROWS
(@col1, @col2, @col3) set samu_co_unidade=@col1, samu_co_placa=@col2, samu_posto_samu_id=@col3;







INSERT INTO `SAMU_OCORRENCIA`()
VALUES
 	("2019-05-24 16:25:16","2019-05-24 16:58:09",33,"EMERGENCIA","ALTO",3,19,7),
	("2019-05-25 13:35:19","2019-05-25 15:09:28",24,"EMERGENCIA","BAIXO",4,14,2),
	("2019-05-23 08:55:28","2019-05-23 09:11:15",31,"EMERGENCIA","ALTO",6,21,1),
	("2019-05-23 13:45:18","2019-05-23 14:30:25",22,"EMERGENCIA","MEDIO",5,6,4);








/*VER OS ENDERECOS DOS HOSPITAIS QUE COMEÇAM COM AV */
SELECT hospital_nome_fantasia, endereco_logradouro FROM HOSPITAL, ENDERECO WHERE hospital_endereco_id = endereco_id AND endereco_logradouro LIKE 'av%';

/*VER clinicaS DE CADA HOSPITAL */
SELECT hospital_nome_fantasia, hospital_clinica_descricao FROM HOSPITAL_CLININCA, HOSPITAL WHERE hospital_clinica_hospital_id = hospital_id;

/*VER DISTANCIA DOS HOSPITAIS DE UM PACIENTE*/
SELECT paciente_id, hospital_id, CALL calcularDistancia(paciente_id, hospital_id) FROM PACIENTE, HOSPITAL WHERE paciente_id = 3;







/*INSERCAO UF */
/*LOAD DATA LOCAL infile '/home/acl/UF.csv' IGNORE INTO TABLE UF CHARACTER SET UTF8 FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\n' IGNORE 1 ROWS
(@col1, @col3) set uf_cod=@col1, uf_nome=@col3;*/


/*INSERCAO MUNICIPIO */
/*LOAD DATA LOCAL infile '/home/acl/uf-mun.csv' IGNORE INTO TABLE MUNICIPIO CHARACTER SET UTF8 FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\n' IGNORE 1 LINES 
(@col1, @col2, @col3, @col4, @col5) set municipio_cod=@col3, municipio_nome=@col4, municipio_uf_cod=@col2;*/