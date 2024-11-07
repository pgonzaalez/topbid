DROP DATABASE subhasta;
CREATE DATABASE subhasta;
USE subhasta;


CREATE TABLE subhasta (
    id_subhasta INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL, 
    date_hora DATETIME NOT NULL,
    lloc VARCHAR(100) NOT NULL,
    descripcio VARCHAR(250) NOT NULL,
    percentatge INT DEFAULT 10,
    inici ENUM('senseIniciar', 'iniciat','finalitzat') DEFAULT 'senseIniciar'
);


CREATE TABLE usuari (
    id_usuari INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    contrasenya VARCHAR(50),
    dni VARCHAR(50) UNIQUE,
    rol ENUM('subhastador', 'venedor', 'comprador') DEFAULT 'venedor'
);

CREATE TABLE product (
    id_producte INT AUTO_INCREMENT PRIMARY KEY,
    usuari_id INT ,
    imatge VARCHAR(255),
    nom VARCHAR(45) NOT NULL,
    descripcio_curta VARCHAR(100) NOT NULL,
    descripcio_llarga VARCHAR(255) NOT NULL,
    preu DECIMAL(10, 2) NOT NULL,
    observacions TEXT,
    status ENUM('pendent','pendent-assignacio', 'rebutjat','asignat','venut','retirat') DEFAULT 'pendent',
    FOREIGN KEY (usuari_id) REFERENCES usuari(id_usuari)
);

CREATE TABLE product_likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    UNIQUE KEY (product_id, user_id),
    FOREIGN KEY (product_id) REFERENCES product(id_producte)
);
CREATE TABLE subhasta_producte (
    id_subhasta INT,
    id_producte INT PRIMARY KEY,
    preu_final DECIMAL(10, 2) default 0,
    comprador_id INT default 4,
    FOREIGN KEY (id_subhasta) REFERENCES subhasta(id_subhasta),
    FOREIGN KEY (id_producte) REFERENCES product(id_producte),
    FOREIGN KEY (comprador_id) REFERENCES usuari(id_usuari)
);

CREATE TABLE notificacions (
    id_notificacio INT PRIMARY KEY AUTO_INCREMENT,
    producte_id INT,
    id_usuari INT,
    data DATETIME,
    llegida BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (producte_id) REFERENCES product(id_producte),
    FOREIGN KEY (id_usuari) REFERENCES usuari(id_usuari)
);

CREATE TABLE missatges (
    id_missatge INT PRIMARY KEY AUTO_INCREMENT,
    emisor INT,
    receptor INT,
    producte_id INT,
    missatge VARCHAR(250),
    data DATETIME,
    llegit BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (emisor) REFERENCES usuari(id_usuari),
    FOREIGN KEY (receptor) REFERENCES usuari(id_usuari),
    FOREIGN KEY (producte_id) REFERENCES product(id_producte)
);

CREATE TABLE compres (
    id_compra INT AUTO_INCREMENT PRIMARY KEY,
    id_subhasta INT,
    id_producte INT,
    comprador_id INT,
    propietari_id INT,
    preu_inicial DECIMAL(10, 2),
    preu_final DECIMAL(10, 2),
    guany_comprador DECIMAL(10, 2),
    guany_propietari DECIMAL(10, 2),
    total_guany DECIMAL(10, 2),
    FOREIGN KEY (id_subhasta) REFERENCES subhasta(id_subhasta),
    FOREIGN KEY (id_producte) REFERENCES product(id_producte),
    FOREIGN KEY (comprador_id) REFERENCES usuari(id_usuari),
    FOREIGN KEY (propietari_id) REFERENCES usuari(id_usuari)
);


INSERT INTO usuari (username, contrasenya, rol) VALUES 
('venedor1', 'venedor1', 'venedor'),
('venedor2', 'venedor2', 'venedor'),
('subhastador', 'subhastador', 'subhastador');

INSERT INTO usuari (username, rol) VALUES
('desert','comprador');

INSERT INTO product (usuari_id, imatge, nom, descripcio_curta, descripcio_llarga, preu, observacions, status) VALUES 
(1, 'views/images/productes/appleIphone13.png', 'Iphone 13 Pro Max', 'Iphone 13 Pro Max de color daurat', 'Iphone 13 Pro Max de color daurat de fàbrica', 599, 'Li tinc molt afecte', 'pendent'),
(1, 'views/images/productes/camisetaBarca.jpg', 'Samarreta 1ª equipació FC Barcelona 2024-2025', 'Samarreta d\'home 1ª equipació FC Barcelona 2024-2025 Stadium Nike', 'Samarreta d\'home 1ª equipació FC Barcelona 2024-2025 Stadium Nike', 100, 'És edició limitada', 'pendent'),
(2, 'views/images/productes/CharizardCard.png', 'Carta Pokemon Charizard', 'Carta Pokemon Charizard Tipus Edició Especial', 'Carta Pokemon Charizard Tipus Edició Especial', 1500, 'Edició del 2015', 'asignat'),
(2, 'views/images/productes/play5.jpg', 'PlayStation 5', 'Consola PlayStation 5 amb un comandament', 'Consola PlayStation 5 amb un comandament i tots els cables necessaris', 499, 'Nova, sense obrir', 'asignat'),
(2, 'views/images/productes/macbook-pro.jpg', 'MacBook Pro 2021', 'MacBook Pro 2021 amb xip M1', 'MacBook Pro 2021 amb xip M1, 16GB RAM, 512GB SSD', 1299, 'Usat, en bon estat', 'asignat'),
(1, 'views/images/productes/bicicleta.jpg', 'Bicicleta de Muntanya', 'Bicicleta de muntanya amb suspensió', 'Bicicleta de muntanya amb suspensió davantera i posterior, frens de disc', 350, 'Poc ús, com nova', 'pendent-assignacio'),
(2, 'views/images/productes/rolex.png', 'Rellotge Rolex', 'Rellotge Rolex Submariner', 'Rellotge Rolex Submariner, acer inoxidable, resistent a l\'aigua', 7500, 'Amb certificat d\'autenticitat', 'pendent-assignacio'),
(1, 'views/images/productes/guitarra-electrica.jpg', 'Guitarra Fender Stratocaster', 'Guitarra elèctrica Fender Stratocaster', 'Guitarra elèctrica Fender Stratocaster, color vermell, inclou funda', 1200, 'Usada, en bon estat', 'pendent-assignacio'),
(1, 'views/images/productes/canon.jpg', 'Càmera Canon EOS R5', 'Càmera Canon EOS R5 amb lent 24-105mm', 'Càmera Canon EOS R5 amb lent 24-105mm, 45MP, gravació 8K', 3500, 'Nova, sense ús', 'asignat'),
(2, 'views/images/productes/televisor-samsung.jpg', 'Televisor Samsung 65"', 'Televisor Samsung 65" 4K UHD', 'Televisor Samsung 65" 4K UHD, Smart TV, HDR', 899, 'Nou, en caixa original', 'asignat'),
(2, 'views/images/productes/cuadro.jpg', 'Pintura de Picasso', 'Pintura original de Picasso', 'Pintura original de Pablo Picasso', 100000, 'És un quadre de Picasso veritable', 'asignat'),
(2, 'views/images/productes/jabulani.jpg', 'Pilota Jabulani', 'Pilota Jabulani del mundial de 2010', 'Pilota del mundial de Sud-àfrica de 2010', 100, 'És la pilota original', 'pendent'),
(1, 'views/images/productes/samsung-galaxy-s21.jpg', 'Samsung Galaxy S21', 'Samsung Galaxy S21 color negre', 'Samsung Galaxy S21 color negre, 128GB', 799, 'Nou, sense obrir', 'asignat'),
(1, 'views/images/productes/nike-air-max.jpg', 'Nike Air Max 2021', 'Sabatilles Nike Air Max 2021', 'Sabatilles Nike Air Max 2021, talla 42', 150, 'Noves, en caixa original', 'asignat'),
(1, 'views/images/productes/lenovo-thinkpad.jpg', 'Lenovo ThinkPad X1', 'Portàtil Lenovo ThinkPad X1', 'Portàtil Lenovo ThinkPad X1, 16GB RAM, 1TB SSD', 1800, 'Usat, en bon estat', 'pendent-assignacio'),
(1, 'views/images/productes/sony-alpha.jpg', 'Càmera Sony Alpha 7 III', 'Càmera Sony Alpha 7 III amb lent 28-70mm', 'Càmera Sony Alpha 7 III amb lent 28-70mm, 24MP', 2000, 'Nova, sense ús', 'pendent-assignacio'),
(1, 'views/images/productes/tesla-model-s.jpg', 'Tesla Model S', 'Cotxe elèctric Tesla Model S', 'Cotxe elèctric Tesla Model S, 2021, 5000km', 75000, 'Com nou, únic propietari', 'asignat'),
(2, 'views/images/productes/gucci-bag.jpg', 'Bossa Gucci', 'Bossa Gucci de cuir', 'Bossa Gucci de cuir, color negre', 1200, 'Nova, amb etiqueta', 'asignat'),
(2, 'views/images/productes/roomba.jpg', 'Roomba i7', 'Aspiradora Roomba i7', 'Aspiradora Roomba i7, amb base d\'autobuidatge', 600, 'Nova, sense obrir', 'asignat'),
(2, 'views/images/productes/ps5-controller.jpg', 'Comandament PS5', 'Comandament sense fil per a PS5', 'Comandament sense fil per a PS5, color blanc', 70, 'Nou, en caixa original', 'pendent-assignacio'),
(2, 'views/images/productes/airpods-pro.jpg', 'AirPods Pro', 'Auriculars Apple AirPods Pro', 'Auriculars Apple AirPods Pro, amb estoig de càrrega', 250, 'Nous, sense obrir', 'pendent-assignacio'),
(2, 'views/images/productes/fitbit.jpg', 'Fitbit Charge 4', 'Rellotge intel·ligent Fitbit Charge 4', 'Rellotge intel·ligent Fitbit Charge 4, color negre', 130, 'Nou, en caixa original', 'pendent-assignacio'),
(1, 'views/images/productes/lego-star-wars.jpg', 'LEGO Star Wars', 'Set LEGO Star Wars Millennium Falcon', 'Set LEGO Star Wars Millennium Falcon, 7541 peces', 800, 'Nou, sense obrir', 'asignat'),
(1, 'views/images/productes/ikea-sofa.jpg', 'Sofà IKEA', 'Sofà IKEA de 3 places', 'Sofà IKEA de 3 places, color gris', 400, 'Usat, en bon estat', 'asignat'),
(2, 'views/images/productes/coffee-machine.jpg', 'Màquina de cafè Nespresso', 'Màquina de cafè Nespresso Vertuo', 'Màquina de cafè Nespresso Vertuo, color negre', 150, 'Nova, sense obrir', 'asignat');

INSERT INTO product_likes (product_id, user_id) VALUES 
(1, 1),
(2, 1),
(3, 2),
(4, 2),
(5, 1),
(6, 2),
(7, 1),
(8, 2),
(9, 1),
(10, 2),
(11, 1),
(12, 2),
(13, 1);

INSERT INTO subhasta (nom, date_hora, lloc, descripcio) VALUES 
('Subhasta de productes exòtics', '2024-12-12 12:00:00', 'Barcelona', 'Subhasta de productes de diferents categories'),
('Subhasta de tecnologia', '2024-12-15 14:00:00', 'Madrid', 'Subhasta de gadgets i dispositius electrònics'),
('Subhasta d\'objectes miscel·lanis', '2024-12-20 16:00:00', 'València', 'Subhasta d\'obres d\'art i antiguitats'),
('Subhasta de vehicles', '2024-12-25 18:00:00', 'Sevilla', 'Subhasta de cotxes, motos i altres vehicles'),
('Subhasta de moda', '2024-12-30 20:00:00', 'Bilbao', 'Subhasta de roba, accessoris i articles de moda');

INSERT INTO subhasta_producte (id_subhasta, id_producte) VALUES 
(1, 3),
(1, 11),
(2, 4),
(2, 5),
(2, 9),
(2, 10),
(2, 13),
(3, 25),
(3, 24),
(3, 23),
(3, 19),
(4, 17),
(5, 18),
(5, 14);


