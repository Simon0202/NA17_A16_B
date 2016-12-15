--Recherche d'individu
Select nom, prenom from Habitant where nom='' and pays=''
order by prenom;

--Recensement
Select count(nom) from Habitant where pays='';

--Information sur individu
Select * from Habitant where prenom='';

--Statistiques
select avg(now()-ddn) from Habitant where now()-ddn between [ageMin] and [ageMax];