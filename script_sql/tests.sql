DROP DATABASE IF EXISTS ud2_test;

CREATE DATABASE ud2_test;

USE ud2_test;

-- Crear tablas login y usuarios

DROP TABLE IF EXISTS login;

CREATE TABLE login(
    id_login INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    pass VARCHAR(255) NOT NULL
);

DROP TABLE IF EXISTS users;

CREATE TABLE users(
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    id_login INT,
    nombre VARCHAR(100) NOT NULL,
    isActive TINYINT NOT NULL,
    rol VARCHAR(10) NOT NULL DEFAULT "user",
    FOREIGN KEY (id_login) REFERENCES login(id_login)
);


-- Insertar usuarios
INSERT INTO login (email, pass) VALUES ("juan@gmail.com", "$2y$10$GLHvyCCNtDGzNKlwK4JGgOoLBZdXKVoKxFIHIgV2h.5CGs7rftw82");
INSERT INTO login (email, pass) VALUES ("nacho@gmail.com", "$2y$10$2NSl5fkuyTZqQLd2RCsHYeBxycItEGil6jPzdYFAaT2uMUeeL4xYm");
INSERT INTO login (email, pass) VALUES ("lourdes@gmail.com", "$2y$10$x8tBcof4psz3XRkpP0Xzm.D31.FKheG5qiuOUd3Nd2NUn0Bv6hHwq");
INSERT INTO users (id_login, nombre, isActive, rol) VALUES (1, "Juan", 1, "user");
INSERT INTO users (id_login, nombre, isActive, rol) VALUES (2, "Nacho", 1, "admin");
INSERT INTO users (id_login, nombre, isActive, rol) VALUES (3, "Lourdes", 1, "user");

-- Crear tablas preguntas y respuestas

DROP TABLE IF EXISTS preguntas;

CREATE TABLE preguntas(
    id_pregunta INT AUTO_INCREMENT PRIMARY KEY,
    pregunta VARCHAR(255) NOT NULL,
    multiple TINYINT NOT NULL DEFAULT 0,
    veces_acertada INT NOT NULL DEFAULT 0,
    porcentaje DECIMAL(5,2) NOT NULL DEFAULT 0
);

DROP TABLE IF EXISTS respuestas;

CREATE TABLE respuestas(
    id_respuesta INT AUTO_INCREMENT PRIMARY KEY,
    id_pregunta INT,
    respuesta VARCHAR(255) NOT NULL,
    isCorrecta TINYINT NOT NULL DEFAULT 0,
    FOREIGN KEY (id_pregunta) REFERENCES preguntas(id_pregunta)
);

-- Insertar preguntas
INSERT INTO preguntas (pregunta, multiple) VALUES ("¿En qué lugar se ejecuta el código PHP?", 0);
INSERT INTO preguntas (pregunta, multiple) VALUES ("La ejecución de un bucle finaliza:", 1);
INSERT INTO preguntas (pregunta, multiple) VALUES ("¿Cuáles de estas son marcas para la inserción del código PHP en las páginas HTML?", 0);
INSERT INTO preguntas (pregunta, multiple) VALUES ("¿En qué atributo de un formulario especificamos la página a la que se van a enviar los datos del mismo?", 0);
INSERT INTO preguntas (pregunta, multiple) VALUES ("¿Cuál de estas instrucciones está correctamente escrita en PHP?", 0);
INSERT INTO preguntas (pregunta, multiple) VALUES ("¿Cuál de estas instrucciones PHP imprimirá por pantalla correctamente el mensaje 'Hola Mundo' en letra negrita?", 0);
INSERT INTO preguntas (pregunta, multiple) VALUES ("Dos de las formas de pasar los parámetros entre páginas PHP son:", 0);
INSERT INTO preguntas (pregunta, multiple) VALUES ("¿Cuál de estas instrucciones se utiliza para realizar una consulta a una base de datos MySQL?", 0);
INSERT INTO preguntas (pregunta, multiple) VALUES ("El resultado de la ejecución de código en un servidor web, es una página web que:", 1);
INSERT INTO preguntas (pregunta, multiple) VALUES ("¿Cómo se define una variable de tipo string en PHP?", 0);

-- Insertar respuestas
INSERT INTO respuestas (id_pregunta, respuesta, isCorrecta) VALUES (1, "Servidor", 1), (1, "Cliente (Ordenador propio)", 0), (1, "Indistintamente", 0), (1, "Ninguna es correcta", 0);
INSERT INTO respuestas (id_pregunta, respuesta, isCorrecta) VALUES (2, "cuando se ejecuta la sentencia end.", 0), (2, "cuando se ejecuta la sentencia break.", 1), (2, "cuando se cumple la condición.", 0), (2, "cuando no se cumple la condición.", 1);
INSERT INTO respuestas (id_pregunta, respuesta, isCorrecta) VALUES (3, "&lt;? y ?&gt;", 1), (3, "&lt;php&gt;...&lt;/php&gt;", 0), (3, "<# y #>", 0), (3, "&lt;php ... php&gt;", 0);
INSERT INTO respuestas (id_pregunta, respuesta, isCorrecta) VALUES (4, "name", 0), (4, "file", 0), (4, "action", 1), (4, "Description", 0);
INSERT INTO respuestas (id_pregunta, respuesta, isCorrecta) VALUES (5, "if (a=0) print a", 0), (5, "if (a==0) echo 'hola mundo';", 1), (5, "if (a==0) { echo ok }", 0), (5, "if (a==0): print a;", 0);
INSERT INTO respuestas (id_pregunta, respuesta, isCorrecta) VALUES (6, "print &lt;strong &gt;Hola Mundo &lt;/strong&gt; ;", 0), (6, "print (&lt;strong&gt; Hola Mundo &lt;/strong&gt;);", 0), (6, "print ('&lt;strong&gt; Hola Mundo &lt;/strong&gt;');", 1), (6, "print &lt;b&gt; Hola Mundo &lt;/b&gt; ;", 0);
INSERT INTO respuestas (id_pregunta, respuesta, isCorrecta) VALUES (7, "Require e Include", 0), (7, "Get y Put", 0), (7, "Post y Get", 1), (7, "Into e Include", 0);
INSERT INTO respuestas (id_pregunta, respuesta, isCorrecta) VALUES (8, "mysql_query", 1), (8, "mysql_access", 0), (8, "query_mysqli", 0), (8, "mysql_db_access", 0);
INSERT INTO respuestas (id_pregunta, respuesta, isCorrecta) VALUES (9, "Puede incluir también código en lenguaje JavaScript.", 1), (9, "Puede contener sentencias en lenguaje intermedio.", 0), (9, "Puede ser exactamente igual a una página web estática.", 1), (9, "Se almacena en el servidor web para responder a futuras peticiones.", 0);
INSERT INTO respuestas (id_pregunta, respuesta, isCorrecta) VALUES (10, "char str;", 0), (10, "string str;", 0), (10, "$str_nombre", 0), (10, "En PHP no se define el tipo de las variables explícitamente", 1);


-- Crear tabla Examen
DROP TABLE IF EXISTS examen;

CREATE TABLE examen(
    id_examen INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    nota DECIMAL(4,2) NOT NULL,
    resp_correctas INT NOT NULL,
    porcentaje DECIMAL(5,2) NOT NULL,
    FOREIGN KEY (id_user) REFERENCES users(id_user)
);