CREATE TABLE Utilisateur(
email VARCHAR(50) PRIMARY KEY,
nom VARCHAR(20) NOT NULL,
prenom VARCHAR(20) NOT NULL,
date_naissance DATE,
entreprise VARCHAR(50),
genre VARCHAR(20),
pays VARCHAR(20),
metier VARCHAR(20)
);


CREATE TABLE Flux(
titre VARCHAR(50) PRIMARY KEY,
confidentialite VARCHAR(20) CHECK (confidentialite = 'public' OR confidentialite = 'prive' OR confidentialite ='restreint'),
createur VARCHAR(50) NOT NULL REFERENCES Utilisateur(email)
);

CREATE TABLE Groupe_Lecteurs(
id_lecteur INT PRIMARY KEY,
email_admin VARCHAR(50) NOT NULL REFERENCES Utilisateur(email),
titre VARCHAR(50) NOT NULL REFERENCES Flux(titre)
);

CREATE TABLE Groupe_Redacteurs(
id_lecteur INT PRIMARY KEY REFERENCES Groupe_Lecteurs(id_lecteur),
id_redacteur INT UNIQUE
);

CREATE TABLE Publication(
lien VARCHAR(50) PRIMARY KEY,
id_lecteur INT NOT NULL REFERENCES Groupe_Lecteurs(id_lecteur),
id_redacteur INT NOT NULL REFERENCES Groupe_Redacteurs(id_redacteur),
titre VARCHAR(50) NOT NULL,
date_publi DATE NOT NULL,
etat VARCHAR(20) CHECK (etat = 'valide' OR etat = 'rejete' OR etat = 'supprime')
);

CREATE TABLE Article(
lien VARCHAR(50) PRIMARY KEY REFERENCES Publication(lien),
texte VARCHAR(450),
url_piece_jointe VARCHAR(50)
);

CREATE TABLE Multimedia(
lien VARCHAR(50) PRIMARY KEY REFERENCES Publication(lien),
legende VARCHAR(50),
url VARCHAR(50)
);

CREATE TABLE Lire(
lien_publi VARCHAR(50) REFERENCES Publication(lien),
email VARCHAR(50) REFERENCES Utilisateur(email),
id_lecteur INT REFERENCES Groupe_Lecteurs(id_lecteur),
PRIMARY KEY (lien_publi, email),
vote VARCHAR(10) CHECK (vote='like' OR vote='dislike' OR vote='null'),
commentaire VARCHAR(450)
);

CREATE TABLE Compo_Groupe(
email VARCHAR(50) REFERENCES Utilisateur(email),
id_lecteur INT REFERENCES Groupe_Lecteurs(id_lecteur),
PRIMARY KEY(email, id_lecteur)
);

INSERT INTO Utilisateur (email, nom, prenom, entreprise)
VALUES ('michel.durand@email.com','Durand','Michel','Big Company'),	-- Responsables de groupes
 ('micheld@email.com','Durent','Michel','Big Company'),
 ('paul@email.com','Gerdon','Paul','Small Company'),
 ('fançois@email.com','Klezt','François','Small Company'),
 
 ('inconnu@email.com','n°1','inconnu','Small Company'),	-- éléments de tests pour les appartenances de groupe
 ('inconnu2@email.com','n°2','inconnu','Small Company'),
 ('inconnu3@email.com','n°3','inconnu','Small Company'),
 ('inconnu4@email.com','n°4','inconnu','Small Company'),
 ('inconnu5@email.com','n°5','inconnu','Small Company'),
 ('inconnu6@email.com','n°6','inconnu','Small Company'),
 ('inconnu7@email.com','n°7','inconnu','Small Company'),
 ('inconnu8@email.com','n°8','inconnu','Small Company'),
 ('inconnu9@email.com','n°9','inconnu','Small Company'),
 ('inconnu10@email.com','n°10','inconnu','Small Company'),
 ('inconnu11@email.com','n°11','inconnu','Small Company')
 ;

INSERT INTO Flux
VALUES ('Avions et création','public','michel.durand@email.com'),
('BDD SQL','public','michel.durand@email.com'),
('Programmation objet','public','paul@email.com')
;


INSERT INTO Groupe_Lecteurs
VALUES (1,'michel.durand@email.com','Avions et création'),		-- 1er Groupe Lecteur du premier flux
(2,'michel.durand@email.com','Avions et création'),			-- 1er Groupe Redacteur du premier flux
(3,'micheld@email.com','Avions et création'),					-- 2ème Groupe Lecteur du premier flux
(4,'michel.durand@email.com','BDD SQL'),			-- 1er Groupe Lecteur du second flux
(5,'michel.durand@email.com','BDD SQL'),				-- 1er Groupe Redacteur du second flux
(6,'michel.durand@email.com','Programmation objet'),			-- 1er Groupe Lecteur du troisième flux
(7,'michel.durand@email.com','Programmation objet')				-- 1er Groupe Redacteur du troisième flux
;

INSERT INTO Groupe_Redacteurs
VALUES (2,1), 								-- On assigne Groupe_lecteur n°2 comme groupe_Redacteur
(5,2),										-- On assigne Groupe_lecteur n°5 comme groupe_Redacteur
(7,3)										-- On assigne Groupe_lecteur n°7 comme groupe_Redacteur
;

INSERT INTO Compo_Groupe
VALUES 
('michel.durand@email.com',1),
	('inconnu@email.com',1),
	('inconnu2@email.com',1),
	('inconnu3@email.com',1),
('michel.durand@email.com',2),
	('inconnu4@email.com',1),
('micheld@email.com',3),
	('inconnu4@email.com',3),
('michel.durand@email.com',4),
	('inconnu5@email.com',4),
('michel.durand@email.com',5),
('michel.durand@email.com',6),
	('inconnu6@email.com',6),
	('inconnu7@email.com',6),
('michel.durand@email.com',7)
;

INSERT INTO Publication
VALUES
('www.avions/article.com',1,1,'Vieux avions','20160101','valide'),
('www.avions/article2.com',2,1,'Nouveaux avions','20160101','valide'),
('www.bddpourlavie/extrait.com',4,2,'Le SQL','20160101','valide'),
('www.mauvaissite/extrait.com',5,2,'Langages pour BDD','20160101','rejete'),
('www.cplusplus.com',6,3,'Le C++','20160101','valide'),
('www.cplusplus/videos.com',7,3,'Le C++','20160101','valide')
;

INSERT INTO Article
VALUES
('www.avions/article.com','Dans la vie, il y a deux avions : les vieux et les nouveaux',NULL),
('www.avions/article2.com','Ici on parle des nouveaux avions',NULL),
('www.bddpourlavie/extrait.com','Le SQL sert à manipuler des BDD',NULL),
('www.mauvaissite/extrait.com','Cet article ne vaut rien',NULL),
('www.cplusplus.com','Designs patterns',NULL)
;

INSERT INTO Multimedia
VALUES
('www.cplusplus/videos.com','Designs patterns',NULL)
;

INSERT INTO Lire
VALUES
('www.avions/article.com','michel.durand@email.com',1,'like'),
('www.avions/article.com','inconnu@email.com',1,'like'),
('www.avions/article.com','inconnu2@email.com',1,'like'),
('www.avions/article.com','inconnu3@email.com',1,'dislike'),
('www.cplusplus.com','michel.durand@email.com',7,'dislike')
;