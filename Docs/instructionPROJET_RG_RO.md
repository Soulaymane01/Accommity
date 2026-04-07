# ennoncé du projet

Gestion d’hébergements en ligne
et réservations (type Airbnb)

# Instruction:

Il s’agit de réaliser une conduite de projet UML pour les sujets affectés à chaque groupe, et de développer une application Web en langage PHP.

Le cahier des charges fonctionnel avec les règles de gestion et d’organisation, peut être réalisé par des recherche sur internet, dans des documents, ou à travers des entretiens avec des gens qui travaillent dans le domaine du sujet affecté.

Il est demandé pour ce projet de suivre soigneusement les étapes listées dans le document fourni : Conduite_Projet_UML.

Afin de bien gérer le temps de travail, réaliser le diagramme de GANTT au début, et affecter à chaque étape une durée de temps.

Le rapport, à remettre dans les délais, doit montrer avec détails et précisions les différents diagrammes UML, en respectant les étapes de la conduite de projet. Le rapport doit également inclure les différentes impressions d’écran montrant l’application Web développée.

`Attention : toutes les données et méthodes gérées par l’application doivent être auparavant trouvées dans la partie conception.`

--

# CONDUITE DU PROJET UML:

La conduite d’un projet UML se fait sur base des étapes suivantes :

```
1- Collecte des données et règles de fonctionnement (entretiens, documentations, archives, sites internet, etc.), et leur organisation sous formes de RG (Règles de Gestion : RG1 : .. RG2 :.. , etc.) et de RO (Règles d’Organisation : RO1 :.. RO2 :.. , etc.).
2- Définition du Système d’Information (SI)
3- Proposition de classes et d’un diagramme préliminaire de classes.
4- Décomposition du SI en des sous-systèmes. Réalisation du diagramme de paquetage.
5- Proposer une maquette préliminaire de l’application.
6- Pour chaque paquetage, réaliser un diagramme des activités.
7- Pour chaque paquetage, réaliser un diagramme des cas d’utilisation.
8- Pour chaque cas d’utilisation, développer un diagramme de séquence.
9- Itérer depuis l’étape 3 à l’étape 8, jusqu’à converger vers une solution et des diagrammes optimales.
10- Les objets trouvés dans les diagrammes de séquence, donneront le diagramme de classe pour chaque paquetage.
11- Rassembler les différents diagrammes de classes dans un seul diagramme général.
12- Trouver des classes, dans le diagramme de classe général, dont les objets pouvant changer d’états. Et réaliser pour ces classes (en général quelques-unes) le diagramme d’états-transitions.
13- Faire une liste avec 2 colonnes, dans la première : nom de la fonction (trouvée dans le diagramme d’activité, de séquence ou d’états-transition), dans la deuxième : le diagramme où la fonction a été trouvée.
14- Réaliser le Mapping O/R et faire le MLD correspondant au diagramme de classe général.
15- Réaliser la maquette finale de l’application (avec menus, sous-menus, boutons, etc.) et montrer dans cette maquette où seront utilisées les fonctions de l’étape 11.
16- Proposer l’architecture de l’application, le (s) langage (s) de développement (s), le système de gestion de données. S’appuyer sur les diagrammes de composants et de déploiement. Ainsi que sur le diagramme des classes participantes, et sur le diagramme de séquence système.
```

--

# les regles de gestions et organisation:

## Règle de gestion et Règles:

```
Utilisateurs


RG01
Tout utilisateur doit créer un compte avec une adresse e-mail unique et un mot de passe avant d'accéder aux fonctionnalités de la plateforme.
RG02
Un utilisateur peut être soit Voyageur, soit Hôte, soit les deux simultanément
RG03
Un hôte doit vérifier son identité (pièce d'identité, numéro de téléphone) avant de pouvoir publier un hébergement.
RG04
Un voyageur doit compléter son profil (photo, description, vérifications) pour augmenter sa crédibilité auprès des hôtes.
RG05
L'administrateur peut supprimer tout compte en cas de violation des conditions d'utilisation.

Annonces


RG06
Un hôte peut créer plusieurs annonces, chacune correspondant à un hébergement distinct (appartement, maison, chambre, etc.).
RG07
Chaque annonce doit obligatoirement contenir : titre, description, type de logement, adresse, capacité d'accueil, équipements, photos, tarif par nuit et mode de réservation (réservation instantanée ou demande de réservation). Le règlement intérieur est facultatif mais recommandé.
RG08
Les dates déjà réservées sont automatiquement bloquées dans le calendrier de disponibilité de l'hébergement.
RG9
L'hôte définit le mode de réservation de son annonce :
— réservation instantanée
— demande de réservation
RG 10
Le prix par nuit est fixé librement par l'hôte.
RG11
Un hébergement doit appartenir à une catégorie géographique (ville, région, pays) pour permettre la recherche filtrée.


Réservations


RG12
Un voyageur peut soumettre une demande de réservation en sélectionnant les dates d'arrivée et de départ et le nombre de voyageurs. Ce nombre ne peut pas dépasser la capacité maximale.
RG13
Une réservation passe par les statuts suivants :
— reservation instantanée : Confirmée → En cours → Terminée, ou Annulée.
— demande de réservation : En attente → Confirmée → En cours → Terminée, ou Refusée(cad hote a refusé la demande) / Annulée(cad confirmé mais apres l’un des deux a annulée la réservation) / Expirée (si l’hote n’a pas répondu apres 24h de la demande).


RG 14 :  si l'annonce est en mode réservation instantanée, la réservation est automatiquement confirmée dès validation du paiement. Aucune action de l'hôte n'est requise. (remboursemen en cas d'annulation

RG 15 :si l'annonce est en mode demande de réservation, la demande est placée en En attente. L'hôte doit l'accepter ou la refuser dans un délai de 24 heures. Passé ce délai, la demande expire automatiquement et le voyageur est notifié.(remboursement en cas d’annulation ou d'expiration demande)




RG16
Un voyageur ne peut pas effectuer deux réservations actives se chevauchant pour un même hébergement.
RG17
L'annulation d'une réservation est régie par la politique d'annulation définie par l'hôte (flexible, modérée, stricte). Le remboursement est calculé automatiquement selon cette politique.
RG18
Un voyageur ne peut pas réserver son propre hébergement.
RG19
Une réservation confirmée bloque automatiquement les dates correspondantes dans le calendrier de disponibilité.


Paiements


RG20
Le paiement est prélevé au moment de la réservation, et non à la date d'arrivée.




RG22
Le versement à l'hôte est effectué 24 heures après l'arrivée officielle du voyageur, afin de réduire les risques de litiges.
RG23
En cas d'annulation par le voyageur, le remboursement est traité automatiquement selon la politique de l'hôte (flexible, modérée, stricte).
RG24
En cas d'annulation par l'hôte, le voyageur est intégralement remboursé.




RG26
Un reçu de paiement doit être généré et envoyé par e-mail à chaque transaction.

Évaluations


RG27
Un voyageur ne peut déposer un avis sur un hébergement qu'après la fin effective du séjour.
RG28
Le système d'évaluation est bilatéral : le voyageur note l'hôte et le logement, et l'hôte note le voyageur.




RG30
La note globale d'un hébergement est la moyenne pondérée de plusieurs critères : propreté, communication, emplacement, rapport qualité/prix, exactitude.
RG31
L'administrateur peut supprimer un avis inapproprié (contenu offensant, faux) après investigation.




regle d’organisation:


Utilisateurs
RO01
Chaque utilisateur dispose d'un tableau de bord personnel regroupant ses réservations, ses annonces, ses messages et ses paiements.

Annonces
RO02
Un logement peut avoir différents statuts : En cours de vérification, Publié, Suspendu, supprimer, rejeté.
RO03
L'hôte définit la disponibilité du logement via un calendrier. Ce calendrier est mis à jour automatiquement à chaque confirmation ou annulation de réservation.
RO04
Un hébergement ayant une note inférieure à un seuil défini peut être automatiquement suspendu en attente de révision.

Réservations
RO05
Le système génère automatiquement un récapitulatif de réservation incluant les détails du séjour, les montants et les coordonnées des parties.
RO06
Une notification automatique est envoyée (e-mail et/ou SMS) à chaque changement de statut de la réservation.
RO07
Un rappel automatique est envoyé à l'hôte 48 heures avant le check-in et au voyageur 24 heures avant.

Paiements
RO08
Un historique complet des transactions est accessible à chaque utilisateur depuis son espace personnel.

Administration
RO09
L'administrateur dispose d'un back-office complet pour gérer les utilisateurs, les annonces, les réservations, les paiements et les litiges.
RO10
tous signalement soit coté hote ou voyageur en vers avis (hote->voy : avis ou l’invers) genere auto ticket de litige, assigné à l’admin, valable après la fin du séjour.
RO11
Un avis ne peut être soumis qu'une seule fois par réservation.
RO12
Un avis peut être modifié ou supprimé par son auteur dans un délai défini après sa soumission.


```

# paquet de notre projet:

```
Liste des Paquetages
PK_Utilisateurs : Gère l'inscription, l'authentification, les profils (Voyageur/Hôte) et la vérification des identités.
PK_Annonces : Gère la création, la description des hébergements, les catégories géographiques et le calendrier de disponibilité.
PK_Reservations : Gère le cycle de vie d'une réservation (instantanée ou sur demande), les statuts et les contraintes de dates.
PK_Paiements : Gère les transactions,les remboursements et les reçus.
PK_Evaluations : Gère le système d'avis bilatéraux et le calcul des notes moyennes.
PK_Administration : Gère le back-office, la modération des comptes et des avis, et le traitement des litiges.
PK_Notifications (Service Transverse)Envoi d'alertes de changement de statut (RO06), rappels automatiques avant check-in/out (RO07) et génération de reçus par e-mail (RG26).

Relations exactes entre les paquetages
En UML, une flèche ----use----> signifie qu'un paquetage a besoin des classes ou des données de l'autre pour fonctionner. Voici le schéma simplifié et rigoureux de votre architecture :
Le noyau des données
PK_Annonces ----use----> PK_Utilisateurs (L'annonce a besoin d'un Hôte pour exister.)
PK_Notifications ----use----> PK_Utilisateurs (Les notifications ont besoin des coordonnées (email/tel) de l'utilisateur.)
Le moteur de réservation (Le "Hub")
PK_Reservations ----use----> PK_Annonces (On ne peut pas réserver sans sélectionner un hébergement.)
PK_Reservations ----use----> PK_Utilisateurs (Une réservation doit identifier le Voyageur qui la crée.)
PK_Reservations ----use----> PK_Notifications (Dès qu'une réservation change de statut, elle appelle le service de notification.)
Les modules satellites
PK_Paiements ----use----> PK_Reservations (Le paiement se base sur le montant et l'ID de la réservation confirmée.)
PK_Paiements ----use----> PK_Notifications (Pour envoyer le reçu de paiement par e-mail selon la règle RG26.)
PK_Evaluations ----use----> PK_Reservations (L'avis ne peut être déposé que si le séjour est terminé.)
La supervision
PK_Administration ----use----> (Tous les autres) (L'admin doit pouvoir modifier/supprimer un compte, supprimer une annonce ou gérer un litige de paiement.)

```
