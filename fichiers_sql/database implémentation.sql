CREATE TABLE Utilisateur(
email VARCHAR(50) PRIMARY KEY,
nom VARCHAR(20) NOT NULL,
prenom VARCHAR(20) NOT NULL,
date_naissance DATE,
entreprise VARCHAR(50),
genre VARCHAR(20),
pays VARCHAR(20),
metier VARCHAR(20)
)


CREATE TABLE Flux(
id_publi INTEGER PRIMARY KEY,
titre VARCHAR(50),
confidentialite VARCHAR(20) CHECK (confidentialite = 'public' OR confidentialite = 'prive' OR confidentialite ='restreint')
responsable VARCHAR(50) REFERENCES Utilisateur(email)
);

CREATE TABLE Groupe_Lecteurs(
id_lecteur NUMBER(10) PRIMARY KEY,
email_admin VARCHAR(50) NOT NULL REFERENCES Administrateur(email),
id_publi INTEGER NOT NULL REFERENCES Flux(id_publi),
);

CREATE TABLE Groupe_Redacteurs(
id_lecteur NUMBER(10) PRIMARY KEY REFERENCES Groupe_Lecteurs(id_lecteur),
id_redacteur NUMBER(10) UNIQUE
);


CREATE TABLE Article(
lien VARCHAR(50) PRIMARY KEY,
id_lecteur NUMBER(10) NOT NULL REFERENCES Groupe_Lecteurs(id_lecteur),
id_redacteur NUMBER(10) NOT NULL REFERENCES Groupe_Redacteurs(id_redacteur),
titre VARCHAR(50) NOT NULL,
date_publi DATETIME NOT NULL,
etat VARCHAR(20) CHECK (etat = 'vailde' OR etat = 'rejete' OR etat = 'supprime'),
texte TEXT,
url_piece_jointe VARCHAR(50)
--- TEXT fonctionne sous PostGreSQL
);

CREATE TABLE Multimedia(
lien VARCHAR(50) PRIMARY KEY,
id_lecteur NUMBER(10) NOT NULL REFERENCES Groupe_Lecteurs(id_lecteur),
id_redacteur NUMBER(10) NOT NULL REFERENCES Groupe_Redacteurs(id_redacteur),
titre VARCHAR(50) NOT NULL,
date_publi DATETIME NOT NULL,
etat VARCHAR(20) CHECK (etat = 'vailde' OR etat = 'rejete' OR etat = 'supprime'),
legende VARCHAR(50),
url_piece_jointe VARCHAR(50)
);

CREATE TABLE Lire(
id_publi INTEGER REFERENCES Flux(id_publi),
email VARCHAR(50) REFERENCES Utilisateur(email),
id_lecteur NUMBER(10) REFERENCES Groupe_Lecteurs(id_lecteur),
PRIMARY KEY (id_publi,email)
vote VARCHAR(10) CHECK (vote = 'like' OR vote='dislike' OR vote='null'),
commentaire TEXT
)

CREATE TABLE Administrateur(
email VARCHAR(50) PRIMARY KEY REFERENCES Utilisateur(email),
id_lecteur NUMBER(10) REFERENCES Groupe_Lecteurs(id_lecteur),
id_redacteur NUMBER(10) REFERENCES Groupe_Redacteurs(id_redacteur),
CHECK (id_lecteur NOT NULL OR id_redacteur NOT NULL)
--- Bon après avoir revu le sujet y a un truc qui me fait tiquer en voyant le modèle logique de données. Le modèle donné a un paramètre pour groupe_lecteur et un paramètre pour groupe_rédacteur, sauf que tout groupe rédacteur est un groupe lecteur.
--- M'en suis tenu au rapport pour l'instant, doit bien y avoir une raison pour laquelle ça n'est pas corrigé.
);

CREATE TABLE Compo_Groupe(
email VARCHAR(50) REFERENCES Utilisateur(email),
id_lecteur NUMBER(10) REFERENCES Groupe_Lecteurs(id_lecteur),
PRIMARY KEY(email, id_lecteur)
)
