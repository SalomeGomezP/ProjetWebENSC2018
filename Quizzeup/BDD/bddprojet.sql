-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  ven. 30 mars 2018 à 18:37
-- Version du serveur :  10.1.25-MariaDB
-- Version de PHP :  5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `bddprojet`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrer`
--

CREATE TABLE `administrer` (
  `id_exp` int(10) UNSIGNED NOT NULL,
  `login` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `administrer`
--

INSERT INTO `administrer` (`id_exp`, `login`) VALUES
(19, 'expe');

-- --------------------------------------------------------

--
-- Structure de la table `campagne`
--

CREATE TABLE `campagne` (
  `id_campagne` int(11) UNSIGNED NOT NULL,
  `id_questionnaire` int(11) UNSIGNED NOT NULL,
  `type_campagne` varchar(20) NOT NULL,
  `id_exp` int(11) UNSIGNED NOT NULL,
  `nom_interface` varchar(40) NOT NULL,
  `classe` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `campagne`
--

INSERT INTO `campagne` (`id_campagne`, `id_questionnaire`, `type_campagne`, `id_exp`, `nom_interface`, `classe`) VALUES
(1, 1, 'individuelle', 16, 'InTeRfAcE', ''),
(2, 1, 'comparative', 17, 'Interface1', 'A'),
(3, 1, 'comparative', 17, 'Interface2', 'B'),
(4, 1, 'individuelle', 18, 'dsrg', ''),
(5, 1, 'individuelle', 19, 'Interface', '');

-- --------------------------------------------------------

--
-- Structure de la table `experience`
--

CREATE TABLE `experience` (
  `id_exp` int(10) UNSIGNED NOT NULL,
  `statut` int(1) UNSIGNED NOT NULL,
  `nom_exp` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `nb_partic` int(11) UNSIGNED NOT NULL,
  `derniere_classe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `experience`
--

INSERT INTO `experience` (`id_exp`, `statut`, `nom_exp`, `description`, `nb_partic`, `derniere_classe`) VALUES
(16, 0, 'individuelle', 'la bon interface indiv', 2, 1),
(17, 0, 'comparative', 'pitié faites que ca marche', 2, 1),
(18, 0, 'qer', 'sef', 3, 1),
(19, 0, 'Campagne de test interface ', 'Bonjour, vous allez remplir un questionnaire d\'évaluation d\'une interface.', 4, 1);

-- --------------------------------------------------------

--
-- Structure de la table `experimentateur`
--

CREATE TABLE `experimentateur` (
  `login` varchar(10) NOT NULL,
  `mdp` varchar(12) NOT NULL,
  `mail` varchar(30) NOT NULL,
  `organisme` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `experimentateur`
--

INSERT INTO `experimentateur` (`login`, `mdp`, `mail`, `organisme`) VALUES
('expe', 'test', 'cdussard@ensc.fr', 'CNRS');

-- --------------------------------------------------------

--
-- Structure de la table `question`
--

CREATE TABLE `question` (
  `id_question` int(11) UNSIGNED NOT NULL,
  `extr_g` varchar(30) NOT NULL,
  `extr_d` varchar(30) NOT NULL,
  `id_questionnaire` int(10) UNSIGNED NOT NULL,
  `type_question` varchar(3) NOT NULL,
  `ordre_passation` int(2) UNSIGNED NOT NULL,
  `sens_analyse` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `question`
--

INSERT INTO `question` (`id_question`, `extr_g`, `extr_d`, `id_questionnaire`, `type_question`, `ordre_passation`, `sens_analyse`) VALUES
(1, 'Humain', 'Technique', 1, 'QP', 1, 'inverse'),
(2, 'Simple', 'Compliqué', 1, 'QP', 5, 'inverse'),
(3, 'Pratique', 'Pas pratique', 1, 'QP', 8, 'inverse'),
(4, 'Fastidieux', 'Efficace', 1, 'QP', 10, 'lecture'),
(5, 'Prévisible', 'Imprévisible', 1, 'QP', 12, 'inverse'),
(6, 'Confus', 'Clair', 1, 'QP', 20, 'lecture'),
(7, 'Incontrôlable', 'Maîtrisable', 1, 'QP', 28, 'lecture'),
(8, 'Original', 'Conventionnel', 1, 'QHS', 4, 'inverse'),
(9, 'Sans imagination', 'Créatif', 1, 'QHS', 18, 'lecture'),
(10, 'Audacieux', 'Prudent', 1, 'QHS', 22, 'lecture'),
(11, 'Novateur', 'Conservateur', 1, 'QHS', 23, 'inverse'),
(12, 'Ennuyeux', 'Captivant', 1, 'QHS', 24, 'lecture'),
(13, 'Peu exigeant', 'Challenging', 1, 'QHS', 25, 'lecture'),
(14, 'Nouveau', 'Commun', 1, 'QHS', 27, 'inverse'),
(15, 'M\'isole', 'Me sociabilise', 1, 'QHI', 2, 'lecture'),
(16, 'Professionnel', 'Amateur', 1, 'QHI', 6, 'inverse'),
(17, 'De bon goût', 'De mauvais goût', 1, 'QHI', 11, 'inverse'),
(18, 'Bas de gamme', 'Haut de gamme', 1, 'QHI', 13, 'lecture'),
(19, 'M\'exclut', 'M\'intègre', 1, 'QHI', 14, 'lecture'),
(20, 'Me rapproche des autres', 'Me sépare des autres', 1, 'QHI', 15, 'inverse'),
(21, 'Non présentable', 'Présentable', 1, 'QHI', 16, 'lecture'),
(22, 'Plaisant', 'Déplaisant', 1, 'ATT', 3, 'inverse'),
(23, 'Laid', 'Beau', 1, 'ATT', 7, 'lecture'),
(24, 'Agréable', 'Désagréable', 1, 'ATT', 9, 'inverse'),
(25, 'Rebutant', 'Attirant', 1, 'ATT', 17, 'lecture'),
(26, 'Bon', 'Mauvais', 1, 'ATT', 19, 'inverse'),
(27, 'Repoussant', 'Attrayant', 1, 'ATT', 21, 'lecture'),
(28, 'Motivant', 'Décourageant', 1, 'ATT', 26, 'inverse');

-- --------------------------------------------------------

--
-- Structure de la table `questionnaire`
--

CREATE TABLE `questionnaire` (
  `id_questionnaire` int(10) UNSIGNED NOT NULL,
  `type_questionnaire` varchar(15) NOT NULL,
  `nom_questionnaire` varchar(20) NOT NULL,
  `intruction_passation` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `questionnaire`
--

INSERT INTO `questionnaire` (`id_questionnaire`, `type_questionnaire`, `nom_questionnaire`, `intruction_passation`) VALUES
(1, 'long', 'Attrakdiff', 'Dans le cadre d’un projet sur l’expérience utilisateur, nous souhaiterions évaluer vos impressions sur le site web / produit / système. Ce questionnaire se présente sous forme de paires de mots pour vous assister dans évaluation du système. Chaque paire représente des contrastes. Les échelons entre les deux extrémités vous permettent de décrire l’intensité de la qualité choisie.\r\nNe pensez aux paires de mots et essayez simplement de donner une réponse spontanée. Vous pourrez avoir l’impression que certains termes ne décrivent pas correctement le système. Dans ce cas, assurez-vous de donner tout de même une réponse. Gardez à l’esprit qu’il n’y a pas de bonne ou mauvaise réponse. Seule votre opinion compte ! ');

-- --------------------------------------------------------

--
-- Structure de la table `repondre`
--

CREATE TABLE `repondre` (
  `id_question` int(11) UNSIGNED NOT NULL,
  `login` varchar(10) NOT NULL,
  `reponse` int(1) NOT NULL,
  `id_campagne` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `login` varchar(10) NOT NULL,
  `mdp` varchar(12) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `date_naissance` date NOT NULL,
  `nation` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `genre` int(1) UNSIGNED NOT NULL,
  `mail` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`login`, `mdp`, `date_naissance`, `nation`, `genre`, `mail`) VALUES
('ut1', 'test', '1992-03-06', 'France', 2, 'cdussard@ensc.fr'),
('ut2', 'test', '1988-03-06', 'Irlande', 3, 'sgomez@ensc.fr');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrer`
--
ALTER TABLE `administrer`
  ADD PRIMARY KEY (`id_exp`,`login`),
  ADD KEY `login` (`login`);

--
-- Index pour la table `campagne`
--
ALTER TABLE `campagne`
  ADD PRIMARY KEY (`id_campagne`),
  ADD KEY `id_exp` (`id_exp`),
  ADD KEY `id_questionnaire` (`id_questionnaire`);

--
-- Index pour la table `experience`
--
ALTER TABLE `experience`
  ADD PRIMARY KEY (`id_exp`),
  ADD UNIQUE KEY `id_exp` (`id_exp`);

--
-- Index pour la table `experimentateur`
--
ALTER TABLE `experimentateur`
  ADD PRIMARY KEY (`login`),
  ADD UNIQUE KEY `mail` (`mail`);

--
-- Index pour la table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id_question`),
  ADD UNIQUE KEY `extr_g` (`extr_g`),
  ADD UNIQUE KEY `extr_d` (`extr_d`),
  ADD KEY `id_questionnaire` (`id_questionnaire`);

--
-- Index pour la table `questionnaire`
--
ALTER TABLE `questionnaire`
  ADD PRIMARY KEY (`id_questionnaire`);

--
-- Index pour la table `repondre`
--
ALTER TABLE `repondre`
  ADD PRIMARY KEY (`id_question`,`id_campagne`,`login`),
  ADD KEY `id_campagne` (`id_campagne`),
  ADD KEY `login` (`login`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`login`),
  ADD UNIQUE KEY `mail` (`mail`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `administrer`
--
ALTER TABLE `administrer`
  MODIFY `id_exp` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT pour la table `experience`
--
ALTER TABLE `experience`
  MODIFY `id_exp` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT pour la table `question`
--
ALTER TABLE `question`
  MODIFY `id_question` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT pour la table `questionnaire`
--
ALTER TABLE `questionnaire`
  MODIFY `id_questionnaire` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `administrer`
--
ALTER TABLE `administrer`
  ADD CONSTRAINT `administrer_ibfk_1` FOREIGN KEY (`login`) REFERENCES `experimentateur` (`login`),
  ADD CONSTRAINT `administrer_ibfk_2` FOREIGN KEY (`id_exp`) REFERENCES `experience` (`id_exp`);

--
-- Contraintes pour la table `campagne`
--
ALTER TABLE `campagne`
  ADD CONSTRAINT `campagne_ibfk_1` FOREIGN KEY (`id_exp`) REFERENCES `experience` (`id_exp`),
  ADD CONSTRAINT `campagne_ibfk_2` FOREIGN KEY (`id_questionnaire`) REFERENCES `questionnaire` (`id_questionnaire`);

--
-- Contraintes pour la table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`id_questionnaire`) REFERENCES `questionnaire` (`id_questionnaire`);

--
-- Contraintes pour la table `repondre`
--
ALTER TABLE `repondre`
  ADD CONSTRAINT `repondre_ibfk_1` FOREIGN KEY (`id_question`) REFERENCES `question` (`id_question`),
  ADD CONSTRAINT `repondre_ibfk_2` FOREIGN KEY (`id_campagne`) REFERENCES `campagne` (`id_campagne`),
  ADD CONSTRAINT `repondre_ibfk_3` FOREIGN KEY (`login`) REFERENCES `utilisateur` (`login`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
