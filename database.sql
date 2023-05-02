-- phpMyAdmin SQL Dump

-- version 4.5.4.1deb2ubuntu2

-- http://www.phpmyadmin.net

--

-- Client :  localhost

-- Généré le :  Jeu 26 Octobre 2017 à 13:53

-- Version du serveur :  5.7.19-0ubuntu0.16.04.1

-- Version de PHP :  7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */

;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */

;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */

;

/*!40101 SET NAMES utf8mb4 */

;

--

-- Base de données :  `db_beauceron`

--

-- --------------------------------------------------------

--

-- Structure de la table `item`

--

DROP TABLE IF EXISTS item;

CREATE TABLE
    `item` (
        `id` int(11) UNSIGNED NOT NULL,
        `title` varchar(255) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = latin1;

--

-- Contenu de la table `item`

--

INSERT INTO
    `item` (`id`, `title`)
VALUES (1, 'Stuff'), (2, 'Doodads');

--

-- Index pour les tables exportées

--

--

-- Index pour la table `item`

--

ALTER TABLE `item` ADD PRIMARY KEY (`id`);

--

-- AUTO_INCREMENT pour les tables exportées

--

--

-- AUTO_INCREMENT pour la table `item`

--

ALTER TABLE
    `item` MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 3;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */

;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */

;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */

;

-- --------------------------------------------------------

--

-- Structure de la table `actuality`

--

DROP TABLE IF EXISTS actuality;

CREATE TABLE
    `actuality` (
        `id` INT NOT NULL AUTO_INCREMENT,
        `title` VARCHAR(100) NOT NULL,
        `content` TEXT NULL,
        `creation_date` DATETIME NOT NULL DEFAULT NOW(),
        `image_path` VARCHAR(255) NULL,
        PRIMARY KEY (`id`),
        UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE
    ) ENGINE = InnoDB DEFAULT CHARSET = latin1;

--

-- Contenu de la table `Actuality`

--

INSERT INTO
    `actuality` (
        `title`,
        `content`,
        `creation_date`,
        `image_path`
    )
VALUES (
        'Convocation Assemblée Générale 2023',
        'L''assamblée générale du Club Les Amis du Beauceron aura lieux le samedi 06 mai 2023 à 18h00 à la mairie de Thoissey 8 rue de l''hôtel de ville salle des mariages.
     
        L''ordre du jour sera le suivant :
			
			  Ouverture de l''assamblée générale par la présidente.
			
			  Compte rendu moral de l''année 2022 par la présidente.
			  Compte rendu financier de l''année 2022 par le trésorier.
			  Compte rendu des différentes commissions.
			  Approbation des comptes rendus.
			  Quitus au trésorier.
			  Question diverses.
			  Clôture de l''assembée générale.
			
			Pour participer à l''assembée générale, il faut être à jour de cotisation et adhérent depuis plus de 9 mois.',
        NOW(),
        'assemblee-generale.jpg'
    ), (
        'Renouvellement Adhesion & Abonnement 2023',
        'Pensez a renouveller votre adhesion. La numéro 1 - 2023 va bientôt paraître
     
        Vous trouverez les documents suivant pour votre inscription : 
        
          - <a href="lettre adhesion au club 2023.pdf" border="0" alt="">LETTRE DE RENOUVELEMENT</a>
          - <a href="../club/club-adhesion.html" border="0" alt="">BULLETIN ADHESION</a>
          - <a href="RIB Crédit Agricole CAB.pdf" border="0" alt="">RIB bancaire</a>
    ',
        DATE_ADD(NOW(), INTERVAL -2 DAY),
        'adhesion-association.jpg'
    ), (
        'La surdité & l''hémophilie chez le beauceron',
        'La surdité et l''hémophilie chez le beauceron, le beauceron arlequin. Description et génétique.
     Bonjour,
      Message du Docteur Marie ABITBOL concernant ses travaux de recherche sur la surdité, en collaboration avec la Commission d''élevage du CAB :
      Nous sommes heureux d’annoncer que grâce à la collaboration des amoureux du Beauceron, nous avons identifié la mutation de surdité !
      Un test ADN de dépistage vient juste d’être lancé : TEST ADN.

        Cordialement, Marie Abitbol.

        Marie ABITBOL : 
        Pr en génétique Département des Sciences Fondamentales
        VetAgro Sup, Campus vétérinaire de Lyon
        1 avenue Bourgelat, 69280 Marcy l''Etoile
      
      Mail : marie.abitbol@vetagro-sup.fr
      Tel : 0478872566
      et
      INMG-Institut NeuroMyoGène
      Equipe MNCA
      CNRS UMR 5310 - INSERM U1217 - UCBL1 Université de Lyon
      8 avenue Rockefeller, 69008 Lyon 
    ',
        DATE_ADD(NOW(), INTERVAL -5 DAY),
        'perte-audition-chien.webp'
    ), (
        'Titre de champion international de beaute',
        'Titres de champion international de beaute jeune & veteran de la FCI C.I.B.-J & C.I.B.-V.

    Mesdames, Messieurs les Présidents,

    Le Comité Général de la FCI a décidé de créer deux nouveaux titres de Champion International de Beauté
    pour tous les chiens de races (soumises à épreuves de travail ou non) inscrits en classe Jeunes
    (de 9 à 18 mois) ou Vétérans (à partir de 8 ans) lors d’Expositions Internationales de la FCI avec
    octroi du CACIB conformément au Règlement des Expositions Canines de la FCI.
    
    C.I.B.-J Champion International de Beauté Jeune
    C.I.B.-V Champion International de Beauté Vétéran
    
    Les conditions DE BASE exigées pour TOUS les chiens avec pedigree sont les suivantes :
    
    C.I.B.-J Champion International de Beauté Jeune

    Pour les chiens avec pedigree de races reconnues à titre définitif par la FCI selon la Nomenclature des
    Races de la FCI, inscrits dans le livre des origines reconnu par la FCI (cela signifie que les inscriptions
    dans des annexes aux livres des origines ne sont pas valables) (conformément au Règlement du
    Championnat International de la FCI, Introduction)

    • 3 certificats « Jeune » internationaux (c.-à-d. trois « 1er EXCELLENT » en classe Jeunes) obtenus
    dans des Expositions Internationales de la FCI avec octroi du CACIB à partir du 1er août 2022
    • Dans 3 pays différents
    • Sous 3 juges différents

    C.I.B.-V Champion International de Beauté Vétéran
    Pour les chiens avec pedigree de races reconnues à titre définitif par la FCI selon la Nomenclature des
    Races de la FCI, inscrits dans le livre des origines reconnu par la FCI (cela signifie que les inscriptions
    ',
        NOW(),
        NULL
    ), (
        'Postes a pourvoir appel a candidature',
        'DELEGATIONS : 
      - ZONE 19 Départements 18-36-45. 
      - ZONE 24 Départements 11-30-34-48-66
    
    Adresser votre candidature à : 
    Madame RENAUX Danièle
    Résidence Les Fontaines de Caudéran 
    Appt 510 - 166, avenue Pasteur
    33200 BORDEAUX
    ',
        DATE_ADD(NOW(), INTERVAL -8 DAY),
        NULL
    );

DROP TABLE IF EXISTS evenement;

CREATE TABLE
    `evenement`(
        `id` INT NOT NULL AUTO_INCREMENT,
        `title` VARCHAR(255) NOT NULL,
        `date` DATE NOT NULL,
        `place` VARCHAR(255) NOT NULL,
        `description` TEXT NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE = InnoDB DEFAULT CHARSET = latin1;

INSERT INTO
    `evenement` (
        `title`,
        `date`,
        `place`,
        `description`
    )
VALUES (
        'Beau-Dog: Concours de dégustation de hot dogs pour Beaucerons affamés!',
        '2023-03-15',
        'Stade de la Mâchoire Géante, Bordeaux, France',
        'Les Beaucerons les plus gloutons se réunissent pour un concours de dégustation de hot dogs hors du commun! Les crocs acérés, les estomacs inépuisables et les langues pendantes seront de la partie pour cette compétition effrénée. Venez admirer ces champions de l\'appétit canin dévorer des hot dogs à une vitesse stupéfiante, dans une ambiance hilarante et décalée!'
    ), (
        'Les Joutes Canines: Un tournoi d\'adresse et de grâce pour nos Beaucerons chevaliers!',
        '2023-07-12',
        'Château de Chambord, Loir-et-Cher, France',
        'Préparez-vous à un spectacle de joutes canines à couper le souffle, où nos Beaucerons se transformeront en de véritables chevaliers à quatre pattes! Armés de leur élégance naturelle et de leur adresse, nos participants s\'affronteront avec panache pour conquérir le titre de champion de la joute canine. Les mouvements gracieux des Beaucerons sur le terrain vous émerveilleront, et vous serez captivé par la manière dont ils manient leur lance en croquant dans les défis avec brio.'
    ), (
        'Beau-Rock: Concert en plein air pour Beaucerons fans de musique live!',
        '2023-06-28',
        'Parc du Grognement Énergique, Lyon, France',
        'Préparez-vous à secouer la queue et à groover avec les Beaucerons passionnés de musique live! Ce concert en plein air mettra en vedette des groupes de rock spécialement sélectionnés pour leur capacité à faire bouger les pattes et à remuer les oreilles. Une ambiance déjantée, des snacks canins à gogo et du rock à volonté vous attendent pour cette soirée inoubliable!'
    ), (
        'Beau-Ciné: Soirée cinéma en plein air pour Beaucerons cinéphiles!',
        '2023-08-12',
        'Parc du Cinéma Canin, Marseille, France',
        'Installez-vous confortablement avec votre Beauceron et profitez d\'une soirée cinéma en plein air sous les étoiles! Une sélection de films mettant en vedette des chiens et des animaux de compagnie sera projetée sur grand écran, avec des couvertures moelleuses et des snacks canins à déguster. Une soirée de détente et de divertissement pour les Beaucerons cinéphiles et leurs humains!'
    );

/********************TABLE MEMBER LIST****************/

DROP TABLE IF EXISTS member;

CREATE TABLE
    member(
        id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
        lastname VARCHAR(50) NOT NULL,
        firstname VARCHAR(50) NOT NULL,
        job VARCHAR(50) NOT NULL,
        email VARCHAR(50) NOT NULL,
        photo VARCHAR(255)
    );

INSERT INTO
    `member` (
        `lastname`,
        `firstname`,
        `job`,
        `email`,
        `photo`
    )
VALUES (
        'Scofield',
        'Michael',
        'Ingénieur',
        'michael.scofield@gmail.com',
        NULL
    ), (
        'Snow',
        'Jon',
        'Garde de nuit',
        'jon.snow@gmail.com',
        'profildefault.jpg'
    ), (
        'White',
        'Walter',
        'Professeur de physique-chimie',
        'walter.white@gmail.com',
        ''
    );

--

-- Creation de la table `user`

--

DROP TABLE IF EXISTS user;

CREATE TABLE
    `user` (
        `id` INT NOT NULL AUTO_INCREMENT,
        `email` VARCHAR(100) NOT NULL,
        `password` CHAR(60) NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY unique_email (email),
        UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE
    ) ENGINE = InnoDB DEFAULT CHARSET = latin1;

--

-- Contenu de la table `user`

--

INSERT INTO
    `user` (`email`, `password`)
VALUES (
        'admin@wildcodeschool.fr',
        '$2y$10$MlkN4ClnzqN6vjmaCELJ4uKYj0X85PkURYFASzFMTx77NZMB8JEZG'
    );

--

-- Creation de la table `dog`

--

DROP TABLE IF EXISTS dog;

CREATE TABLE
    `dog` (
        `id` INT NOT NULL AUTO_INCREMENT,
        `owner` VARCHAR(45) NOT NULL,
        `owner_city` VARCHAR(45) NULL,
        `owner_email` VARCHAR(45) NOT NULL,
        `name` VARCHAR(45) NOT NULL,
        `gender` CHAR(1) NOT NULL DEFAULT 'M',
        `birthdate` DATE NULL,
        `is_lof` TINYINT NOT NULL DEFAULT 1,
        `identity_number` VARCHAR(45) NOT NULL,
        `image` VARCHAR(255) NULL,
        `description` TEXT NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE
    ) ENGINE = InnoDB DEFAULT CHARSET = latin1;

--

-- Contenu de la table `dog`

--

INSERT INTO
    `dog` (
        `owner`,
        `owner_city`,
        `owner_email`,
        `name`,
        `gender`,
        `birthdate`,
        `is_lof`,
        `identity_number`,
        `image`,
        `description`
    )
VALUES (
        'Mme GERBAC Christine',
        'AIGUILLON (47190)',
        'christine-gerbac@gmail.com',
        'RIRI',
        'F',
        DATE_ADD(NOW(), INTERVAL -31 DAY),
        1,
        '015156574949849',
        'dog-1.jpg',
        'REBECCA-RUAH ARGENTEE DE SAINTE PETRONILLE (Arlequin) Cotation 2 Dys HD-A par SAMSON DES PLAINES DE LA SAINTE VICTOIRE Cotation 1 Dys HD-A. Note 2+1=3'
    ), (
        'M. JOURNAULT Eric',
        'BEDDES (18370)',
        'journault-eric@outlook.com',
        'FIFI',
        'M',
        DATE_ADD(NOW(), INTERVAL -15 DAY),
        0,
        '015897574949849',
        'dog-2.jpg',
        'OPALE LA COTE (Arlequin) Cotation 1 Dys HD-A par PHARO DES CONTES DE L\'ABREUVAGE Cotation 1 Dys HD-A. Note 1+1=2'
    ), (
        'M. COLAS David',
        'ANGERVILLE (91670)',
        'colas-david@gmail.com',
        'LOULOU',
        'M',
        DATE_ADD(NOW(), INTERVAL -15 DAY),
        0,
        '015897574949849',
        NULL,
        'OLYMPE ARGENTEE DU MONT DES CROISETTES (Arelquin) Cotation 2 Dys HD-A par LOOPING DE LA TERRE DES LIONS Cotation 1. Note 2+1=3'
    );