
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
id_publi INTEGER PRIMARY KEY,
titre VARCHAR(50),
confidentialite VARCHAR(20) CHECK (confidentialite = 'public' OR confidentialite = 'prive' OR confidentialite ='restreint'),
responsable VARCHAR(50) REFERENCES Utilisateur(email)
);

CREATE TABLE Groupe_Lecteurs(
id_lecteur INTEGER(10) PRIMARY KEY,
email_admin VARCHAR(50) NOT NULL REFERENCES Administrateur(email),
id_publi INTEGER NOT NULL REFERENCES Flux(id_publi)
);

CREATE TABLE Groupe_Redacteurs(
id_lecteur INTEGER(10) PRIMARY KEY REFERENCES Groupe_Lecteurs(id_lecteur),
id_redacteur INTEGER(10) UNIQUE
);


CREATE TABLE Article(
lien VARCHAR(50) PRIMARY KEY,
id_lecteur INTEGER(10) NOT NULL REFERENCES Groupe_Lecteurs(id_lecteur),
id_redacteur INTEGER(10) NOT NULL REFERENCES Groupe_Redacteurs(id_redacteur),
titre VARCHAR(50) NOT NULL,
date_publi DATETIME NOT NULL,
etat VARCHAR(20) CHECK (etat = 'vailde' OR etat = 'rejete' OR etat = 'supprime'),
texte VARCHAR(450),
url_piece_jointe VARCHAR(50)
);

CREATE TABLE Multimedia(
lien VARCHAR(50) PRIMARY KEY,
id_lecteur INTEGER(10) NOT NULL REFERENCES Groupe_Lecteurs(id_lecteur),
id_redacteur INTEGER(10) NOT NULL REFERENCES Groupe_Redacteurs(id_redacteur),
titre VARCHAR(50) NOT NULL,
date_publi DATETIME NOT NULL,
etat VARCHAR(20) CHECK (etat = 'vailde' OR etat = 'rejete' OR etat = 'supprime'),
legende VARCHAR(50),
url_piece_jointe VARCHAR(50)
);

CREATE TABLE Lire(
id_publi INTEGER REFERENCES Flux(id_publi),
email VARCHAR(50) REFERENCES Utilisateur(email),
id_lecteur INTEGER(10) REFERENCES Groupe_Lecteurs(id_lecteur),
PRIMARY KEY (id_publi, email),
vote VARCHAR(10) CHECK (vote='like' OR vote='dislike' OR vote='null'),
commentaire VARCHAR(450)
);

CREATE TABLE Administrateur(
email VARCHAR(50) PRIMARY KEY REFERENCES Utilisateur(email),
id_lecteur INTEGER(10) REFERENCES Groupe_Lecteurs(id_lecteur),
id_redacteur INTEGER(10) REFERENCES Groupe_Redacteurs(id_redacteur),
CHECK (id_lecteur IS NOT NULL OR id_redacteur IS NOT NULL)
);

CREATE TABLE Compo_Groupe(
email VARCHAR(50) REFERENCES Utilisateur(email),
id_lecteur INTEGER(10) REFERENCES Groupe_Lecteurs(id_lecteur),
PRIMARY KEY(email, id_lecteur)
);

INSERT INTO Utilisateur (email, nom, prenom, entreprise)
VALUES ('michel.durand@email.com','Durand','Michel','Big Company');
