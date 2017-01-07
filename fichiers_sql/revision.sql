--Recherche d'individu
Select nom, prenom from Habitant where nom='' and pays=''
order by prenom;

--Recensement
Select count(nom) from Habitant where pays='';

--Information sur individu
Select * from Habitant where prenom='';

--Statistiques
select avg(now()-ddn) from Habitant where now()-ddn between [ageMin] and [ageMax];

CREATE TABLE Article(
lien VARCHAR(80) PRIMARY KEY REFERENCES Publication(lien),
texte VARCHAR(480),
url_piece_jointe VARCHAR(80)
);

CREATE TABLE Multimedia(
lien VARCHAR(80) PRIMARY KEY REFERENCES Publication(lien),
legende VARCHAR(80),
url VARCHAR(80)
);

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
