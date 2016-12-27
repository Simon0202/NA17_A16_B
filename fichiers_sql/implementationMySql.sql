DROP TABLE Utilisateur, Flux, Groupe_Utilisateur, Droits_Groupes_Flux, Publication, Article, Multimedia, Lire, Compo_Groupe ;

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

CREATE TABLE Groupe_Utilisateur(
nom VARCHAR(20) PRIMARY KEY,
email_admin VARCHAR(50) NOT NULL REFERENCES Utilisateur(email)
);

CREATE TABLE Droits_Groupes_Flux(
flux VARCHAR(50) REFERENCES flux(titre),
nom VARCHAR(20) REFERENCES Groupe_Utilisateur(nom),
redacteur BOOLEAN,
PRIMARY KEY (flux, nom)
);

CREATE TABLE Publication(
lien VARCHAR(50) PRIMARY KEY,
flux VARCHAR(50) NOT NULL REFERENCES  Flux(titre),
titre VARCHAR(50) NOT NULL,
date_publi DATE NOT NULL,
etat VARCHAR(20) CHECK (etat = 'valide' OR etat = 'rejete' OR etat = 'supprime'),
last_edit VARCHAR(50) REFERENCES Utilisateur(email)
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
nom VARCHAR(20) REFERENCES Groupe_Utilisateur(nom),
PRIMARY KEY (lien_publi, email),
vote VARCHAR(10) CHECK (vote='like' OR vote='dislike' OR vote='null'),
commentaire VARCHAR(450)
);

CREATE TABLE Compo_Groupe(
email VARCHAR(50) REFERENCES Utilisateur(email),
nom VARCHAR(20) REFERENCES Groupe_Utilisateur(nom),
PRIMARY KEY(email, nom)
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


INSERT INTO Groupe_Utilisateur
VALUES ('LecteurFlux1','michel.durand@email.com'),		-- 1er Groupe Lecteur du premier flux
('RedacteurFlux1','michel.durand@email.com'),			-- 1er Groupe Redacteur du premier flux
('Lecteur2Flux1','micheld@email.com'),					-- 2ème Groupe Lecteur du premier flux
('LecteurFlux2','michel.durand@email.com'),			-- 1er Groupe Lecteur du second flux
('RedacteurFlux2','michel.durand@email.com'),				-- 1er Groupe Redacteur du second flux
('LecteurFlux3','michel.durand@email.com'),			-- 1er Groupe Lecteur du troisième flux
('RedacteurFlux3','michel.durand@email.com')				-- 1er Groupe Redacteur du troisième flux
;

INSERT INTO Droits_Groupes_Flux
VALUES ('Avions et création','LecteurFlux1',FALSE),		-- 1er Groupe Lecteur du premier flux
('Avions et création','RedacteurFlux1',TRUE),				-- 1er Groupe Redacteur du premier flux
('Avions et création','Lecteur2Flux1',FALSE),				-- 2ème Groupe Lecteur du premier flux
('BDD SQL','LecteurFlux2',FALSE),						-- 1er Groupe Lecteur du second flux
('BDD SQL','RedacteurFlux2',TRUE),							-- 1er Groupe Redacteur du second flux
('Programmation objet','LecteurFlux3',FALSE),			-- 1er Groupe Lecteur du troisième flux
('Programmation objet','RedacteurFlux3',TRUE)				-- 1er Groupe Redacteur du troisième flux
;

INSERT INTO Compo_Groupe
VALUES 
('michel.durand@email.com','LecteurFlux1'),
	('inconnu@email.com','LecteurFlux1'),
	('inconnu2@email.com','LecteurFlux1'),
	('inconnu3@email.com','LecteurFlux1'),
('michel.durand@email.com','RedacteurFlux1'),
	('inconnu4@email.com','LecteurFlux1'),
('micheld@email.com','Lecteur2Flux1'),
	('inconnu4@email.com','Lecteur2Flux1'),
('michel.durand@email.com','LecteurFlux2'),
	('inconnu5@email.com','LecteurFlux2'),
('michel.durand@email.com','RedacteurFlux2'),
('michel.durand@email.com','LecteurFlux3'),
	('inconnu6@email.com','LecteurFlux3'),
	('inconnu7@email.com','RedacteurFlux3'),
('michel.durand@email.com','RedacteurFlux3')
;

INSERT INTO Publication
VALUES
('www.avions/article.com','Avions et création','Vieux avions','20160101','valide','michel.durand@email.com'),
('www.avions/article2.com','Avions et création','Nouveaux avions','20160101','valide','michel.durand@email.com'),
('www.bddpourlavie/extrait.com','BDD SQL','Le SQL','20160101','valide','michel.durand@email.com'),
('www.mauvaissite/extrait.com','BDD SQL','Langages pour BDD','20160101','rejete','michel.durand@email.com'),
('www.cplusplus.com','Programmation objet','Le C++','20160101','valide','michel.durand@email.com'),
('www.cplusplus/videos.com','Programmation objet','Le C++','20160101','valide','michel.durand@email.com')
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
('www.avions/article.com','michel.durand@email.com','LecteurFlux1','like'),
('www.avions/article.com','inconnu@email.com','LecteurFlux1','like'),
('www.avions/article.com','inconnu2@email.com','LecteurFlux1','like'),
('www.avions/article.com','inconnu3@email.com','LecteurFlux1','dislike'),
('www.cplusplus.com','michel.durand@email.com','RedacteurFlux3','dislike')
;
