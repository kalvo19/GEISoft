-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Temps de generació: 10-01-2016 a les 15:59:30
-- Versió del servidor: 5.5.46-0ubuntu0.14.04.2
-- Versió de PHP: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de dades: `import_16_17`
--

-- --------------------------------------------------------

--
-- Estructura de la taula `accions_alumnes_log`
--

CREATE TABLE IF NOT EXISTS `accions_alumnes_log` (
  `idaccions_alumnes_log` int(11) NOT NULL,
  `accions` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`idaccions_alumnes_log`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Bolcant dades de la taula `accions_alumnes_log`
--

INSERT INTO `accions_alumnes_log` (`idaccions_alumnes_log`, `accions`) VALUES
(1, 'LOGIN'),
(2, 'LOGOUT');

-- --------------------------------------------------------

--
-- Estructura de la taula `accions_families_log`
--

CREATE TABLE IF NOT EXISTS `accions_families_log` (
  `idaccions_families` int(11) NOT NULL,
  `accions` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`idaccions_families`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Bolcant dades de la taula `accions_families_log`
--

INSERT INTO `accions_families_log` (`idaccions_families`, `accions`) VALUES
(1, 'LOGIN'),
(2, 'LOGOUT');

-- --------------------------------------------------------

--
-- Estructura de la taula `accions_professors_log`
--

CREATE TABLE IF NOT EXISTS `accions_professors_log` (
  `idaccions_professors_log` int(11) NOT NULL,
  `accions` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`idaccions_professors_log`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Bolcant dades de la taula `accions_professors_log`
--

INSERT INTO `accions_professors_log` (`idaccions_professors_log`, `accions`) VALUES
(0, 'NO ENTRA EN CLASSE'),
(1, 'LOGIN'),
(2, 'LOGOUT'),
(3, 'ENTRA EN CLASSE'),
(4, 'ENTRA EN GUARDIA'),
(5, 'PASA LLISTA'),
(6, 'PASA LLISTA GUARDIA'),
(7, 'NO ENTRA EN CLASSE'),
(8, 'ENTRA AL CENTRE'),
(9, 'SURT DEL CENTRE');

-- --------------------------------------------------------

--
-- Estructura de la taula `alumnes`
--

CREATE TABLE IF NOT EXISTS `alumnes` (
  `idalumnes` int(11) NOT NULL AUTO_INCREMENT,
  `codi_alumnes_saga` bigint(20) DEFAULT NULL,
  `activat` varchar(1) NOT NULL DEFAULT 'S',
  `historic` varchar(1) NOT NULL DEFAULT 'N',
  `acces_alumne` varchar(1) NOT NULL DEFAULT 'N',
  `acces_familia` varchar(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`idalumnes`),
  UNIQUE KEY `codi_alumnes_saga_UNIQUE` (`codi_alumnes_saga`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `alumnes_families`
--

CREATE TABLE IF NOT EXISTS `alumnes_families` (
  `idalumnes` int(11) NOT NULL,
  `idfamilies` int(11) NOT NULL,
  PRIMARY KEY (`idalumnes`,`idfamilies`),
  KEY `fk_alumnes_families_1` (`idalumnes`),
  KEY `fk_alumnes_families_2` (`idfamilies`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de la taula `alumnes_grup_materia`
--

CREATE TABLE IF NOT EXISTS `alumnes_grup_materia` (
  `idalumnes_grup_materia` int(10) NOT NULL AUTO_INCREMENT,
  `idalumnes` int(11) DEFAULT NULL,
  `idgrups_materies` int(11) DEFAULT NULL,
  PRIMARY KEY (`idalumnes_grup_materia`),
  UNIQUE KEY `idalumnes_grup_materia` (`idalumnes_grup_materia`),
  KEY `idalumnes` (`idalumnes`),
  KEY `idgrups_materies` (`idgrups_materies`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `carrecs`
--

CREATE TABLE IF NOT EXISTS `carrecs` (
  `idcarrecs` int(11) NOT NULL AUTO_INCREMENT,
  `nom_carrec` varchar(45) DEFAULT NULL,
  `descripcio` mediumtext,
  PRIMARY KEY (`idcarrecs`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Bolcant dades de la taula `carrecs`
--

INSERT INTO `carrecs` (`idcarrecs`, `nom_carrec`, `descripcio`) VALUES
(1, 'TUTOR', 'TUTOR DE GRUP'),
(2, 'COORDINADOR', 'COORDINADOR DE CICLE O ETAPA'),
(3, 'ADMINISTRADOR', 'ADMINISTRADOR'),
(4, 'SUPERADMINISTRADOR', 'SUPERADMINISTRADOR'),
(5, 'RESPONSABLE DE FALTES', 'RESPONSABLE DE FALTES');

-- --------------------------------------------------------

--
-- Estructura de la taula `carrecs_permisos`
--

CREATE TABLE IF NOT EXISTS `carrecs_permisos` (
  `idcarrecs` int(11) NOT NULL,
  `idpermisos` int(11) NOT NULL,
  `grau` int(11) DEFAULT NULL,
  PRIMARY KEY (`idcarrecs`,`idpermisos`),
  KEY `fk_carrecs_permisos_1` (`idpermisos`),
  KEY `fk_carrecs_permisos_2` (`idcarrecs`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de la taula `ccc_limit_acumulatiu`
--

CREATE TABLE IF NOT EXISTS `ccc_limit_acumulatiu` (
  `limit_acumulatiu` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de la taula `ccc_limit_comunicacio`
--

CREATE TABLE IF NOT EXISTS `ccc_limit_comunicacio` (
  `idlimit_comunicacio` int(11) NOT NULL,
  `carrec` int(11) DEFAULT NULL,
  PRIMARY KEY (`idlimit_comunicacio`),
  KEY `fk_limit_comunicacio_1_idx` (`carrec`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de la taula `ccc_motius`
--

CREATE TABLE IF NOT EXISTS `ccc_motius` (
  `idccc_motius` int(11) NOT NULL AUTO_INCREMENT,
  `nom_motiu` varchar(2048) CHARACTER SET utf32 COLLATE utf32_spanish2_ci NOT NULL,
  PRIMARY KEY (`idccc_motius`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=9 ;

--
-- Bolcant dades de la taula `ccc_motius`
--

INSERT INTO `ccc_motius` (`idccc_motius`, `nom_motiu`) VALUES
(0, 'Per assignar'),
(1, 'Faltes injustificades de puntualitat i assistncia a classe'),
(2, 'Actes d incorreció o desconsideració amb els companys o cap als membres de la comunitat educativa.'),
(3, 'Actes injustificats que alterin el desenvolupament normal de les activitats del centre.'),
(4, 'Actes d indisciplina, injúries o ofenses contra els companys o els membres de la comunitat educativa.'),
(5, 'Deteriorament intencionat del material del centre.'),
(6, 'Agressió física o amenaces contra els companys o membres de la comunitat educativa.'),
(7, 'Suplantació de personalitat en actes de la vida docent.'),
(8, 'Actuacions i les incitacions a actuacions perjudicials per a la salut.');

-- --------------------------------------------------------

--
-- Estructura de la taula `ccc_taula_principal`
--

CREATE TABLE IF NOT EXISTS `ccc_taula_principal` (
  `idccc_taula_principal` int(11) NOT NULL AUTO_INCREMENT,
  `idalumne` int(11) DEFAULT NULL,
  `idgrup` int(11) NOT NULL DEFAULT '0',
  `idprofessor` int(11) DEFAULT NULL,
  `idmateria` int(11) NOT NULL,
  `idfranges_horaries` int(11) NOT NULL,
  `idespais` int(11) DEFAULT NULL,
  `id_falta` int(11) DEFAULT NULL,
  `id_motius` int(11) NOT NULL DEFAULT '0',
  `data` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `descripcio_breu` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `descripcio_detallada` longtext COLLATE utf8_bin,
  `expulsio` varchar(1) COLLATE utf8_bin NOT NULL DEFAULT 'N',
  `id_tipus_sancio` int(11) DEFAULT NULL,
  `data_inici_sancio` date DEFAULT NULL,
  `data_fi_sancio` date DEFAULT NULL,
  PRIMARY KEY (`idccc_taula_principal`),
  KEY `fk_ccc_taula_principal_1_idx` (`idalumne`),
  KEY `fk_ccc_taula_principal_2_idx` (`idprofessor`),
  KEY `fk_ccc_taula_principal_3_idx` (`idespais`),
  KEY `fk_ccc_taula_principal_4_idx` (`id_falta`),
  KEY `fk_ccc_taula_principal_5_idx` (`id_tipus_sancio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `ccc_tipus`
--

CREATE TABLE IF NOT EXISTS `ccc_tipus` (
  `idccc_tipus` int(11) NOT NULL AUTO_INCREMENT,
  `nom_falta` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `valor` int(11) DEFAULT NULL,
  `limit_acumulacio_comunicacio` int(11) DEFAULT NULL,
  `comentari` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`idccc_tipus`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=9 ;

--
-- Bolcant dades de la taula `ccc_tipus`
--

INSERT INTO `ccc_tipus` (`idccc_tipus`, `nom_falta`, `valor`, `limit_acumulacio_comunicacio`, `comentari`) VALUES
(5, 'LLEU', 15, 20, 'Falta de tipus lleu.'),
(6, 'GREU', 20, 30, 'Falta de tipus greu.'),
(7, 'MOLT GREU', 30, 40, 'Falta de tipus molt greu.'),
(8, 'MOLT SUPER GREU', 40, 50, 'Falta de tipus molt super greu');

-- --------------------------------------------------------

--
-- Estructura de la taula `ccc_tipus_comunicacio_carrec`
--

CREATE TABLE IF NOT EXISTS `ccc_tipus_comunicacio_carrec` (
  `idccc_tipus_comunicacio_carrec` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipus` int(11) DEFAULT NULL,
  `id_carrec` int(11) DEFAULT NULL,
  PRIMARY KEY (`idccc_tipus_comunicacio_carrec`),
  KEY `fk_ccc_tipus_comunicacio_carrec_1_idx` (`id_tipus`),
  KEY `fk_ccc_tipus_comunicacio_carrec_2_idx` (`id_carrec`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `ccc_tipus_mesura`
--

CREATE TABLE IF NOT EXISTS `ccc_tipus_mesura` (
  `idccc_tipus_mesura` int(11) NOT NULL AUTO_INCREMENT,
  `ccc_nom` varchar(45) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`idccc_tipus_mesura`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6 ;

--
-- Bolcant dades de la taula `ccc_tipus_mesura`
--

INSERT INTO `ccc_tipus_mesura` (`idccc_tipus_mesura`, `ccc_nom`) VALUES
(0, 'Per determinar'),
(1, 'Expulsió cautelar'),
(3, 'Neteja pati'),
(4, 'Expulsió definitiva'),
(5, 'Expulsió provisional');

-- --------------------------------------------------------

--
-- Estructura de la taula `comentaris_de_grup`
--

CREATE TABLE IF NOT EXISTS `comentaris_de_grup` (
  `idcomentaris_de_grup` int(11) NOT NULL,
  `data` date DEFAULT NULL,
  `idprofessors` int(11) DEFAULT NULL,
  `idagrupaments` int(11) DEFAULT NULL,
  PRIMARY KEY (`idcomentaris_de_grup`),
  KEY `fk_comentaris_de_grup_1` (`idagrupaments`),
  KEY `fk_comentaris_de_grup_2` (`idprofessors`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de la taula `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `idmoduls_addicionals` tinyint(4) NOT NULL AUTO_INCREMENT,
  `mod_ccc` tinyint(4) NOT NULL DEFAULT '1',
  `mod_ass_servei` tinyint(4) NOT NULL DEFAULT '1',
  `mod_reg_prof` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idmoduls_addicionals`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Bolcant dades de la taula `config`
--

INSERT INTO `config` (`idmoduls_addicionals`, `mod_ccc`, `mod_ass_servei`, `mod_reg_prof`) VALUES
(1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de la taula `contacte_alumne`
--

CREATE TABLE IF NOT EXISTS `contacte_alumne` (
  `idcontacte_alumne` int(11) NOT NULL AUTO_INCREMENT,
  `id_alumne` int(11) DEFAULT NULL,
  `id_tipus_contacte` int(11) DEFAULT NULL,
  `Valor` varchar(145) DEFAULT NULL,
  PRIMARY KEY (`idcontacte_alumne`),
  KEY `fk_contacte_alumne_1` (`id_tipus_contacte`),
  KEY `fk_contacte_alumne_2` (`id_alumne`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `contacte_families`
--

CREATE TABLE IF NOT EXISTS `contacte_families` (
  `idcontacte_families` int(11) NOT NULL AUTO_INCREMENT,
  `id_families` int(11) DEFAULT NULL,
  `id_tipus_contacte` int(11) DEFAULT NULL,
  `Valor` varchar(145) DEFAULT NULL,
  PRIMARY KEY (`idcontacte_families`),
  KEY `fk_contacte_families_1` (`id_tipus_contacte`),
  KEY `fk_contacte_families_2` (`id_families`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `contacte_professor`
--

CREATE TABLE IF NOT EXISTS `contacte_professor` (
  `idcontacte_professor` int(11) NOT NULL AUTO_INCREMENT,
  `id_professor` int(11) DEFAULT NULL,
  `id_tipus_contacte` int(11) DEFAULT NULL,
  `Valor` varchar(145) DEFAULT NULL,
  PRIMARY KEY (`idcontacte_professor`),
  KEY `fk_contacte_professor_1` (`id_tipus_contacte`),
  KEY `fk_contacte_professor_2` (`id_professor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2368 ;

--
-- Bolcant dades de la taula `contacte_professor`
--

INSERT INTO `contacte_professor` (`idcontacte_professor`, `id_professor`, `id_tipus_contacte`, `Valor`) VALUES
(2345, 417, 21, 'admin'),
(2346, 417, 1, 'Administrador Tutoria'),
(2347, 417, 23, 'Administrador'),
(2348, 417, 22, 'Tutoria'),
(2349, 417, 19, 'admin@tutoria.cat'),
(2350, 417, 20, '42a44cdb0bddac0b342e64674123bab1'),
(2351, 417, 12, '625 418 436  '),
(2352, 418, 21, 'vlino'),
(2353, 418, 1, 'Víctor Lino Martínez'),
(2354, 418, 23, 'Víctor'),
(2355, 418, 22, 'Lino Martínez'),
(2356, 418, 19, 'victor.lino@copernic.cat'),
(2357, 418, 20, '3190389c1de99f4bb8c0da83265c3f94'),
(2358, 418, 12, '625401274'),
(2359, 0, 1, 'Professor sense determinar'),
(2360, 0, 3, 'NO_PROF'),
(2361, 0, 4, 'Sense'),
(2362, 0, 5, 'Determinar'),
(2363, 0, 6, 'Professor'),
(2364, 0, 12, '666666666'),
(2365, 0, 19, 'no_prof@geisoft.cat'),
(2366, 0, 20, '123456'),
(2367, 0, 21, 'no_prof');

-- --------------------------------------------------------

--
-- Estructura de la taula `dades_centre`
--

CREATE TABLE IF NOT EXISTS `dades_centre` (
  `iddades_centre` int(11) NOT NULL,
  `nom` varchar(300) COLLATE utf8_bin NOT NULL,
  `adreca` varchar(300) COLLATE utf8_bin NOT NULL,
  `cp` varchar(8) COLLATE utf8_bin NOT NULL,
  `poblacio` varchar(200) COLLATE utf8_bin NOT NULL,
  `tlf` varchar(40) COLLATE utf8_bin NOT NULL,
  `fax` varchar(40) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `prof_env_sms` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Bolcant dades de la taula `dades_centre`
--

INSERT INTO `dades_centre` (`iddades_centre`, `nom`, `adreca`, `cp`, `poblacio`, `tlf`, `fax`, `email`, `prof_env_sms`) VALUES
(0, 'XXX', 'XXXX', 'XXXX', 'XXXXX', 'XXXX', 'XXXX', 'XXXXX', 1),
(1, 'XXXXX', 'XXXXX', 'XXXXX', 'XXXXX', 'XXXXX', 'XXXXX', 'XXXXX', 1);

-- --------------------------------------------------------

--
-- Estructura de la taula `dies_franges`
--

CREATE TABLE IF NOT EXISTS `dies_franges` (
  `id_dies_franges` int(11) NOT NULL AUTO_INCREMENT,
  `iddies_setmana` int(11) NOT NULL,
  `idfranges_horaries` int(11) NOT NULL,
  `idperiode_escolar` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_dies_franges`,`iddies_setmana`,`idfranges_horaries`),
  KEY `fk_dies_franges_1` (`iddies_setmana`),
  KEY `fk_dies_franges_2` (`idfranges_horaries`),
  KEY `fk_dies_franges_3` (`idperiode_escolar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `dies_setmana`
--

CREATE TABLE IF NOT EXISTS `dies_setmana` (
  `iddies_setmana` int(11) NOT NULL,
  `dies_setmana` varchar(15) DEFAULT NULL,
  `laborable` varchar(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`iddies_setmana`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de la taula `equivalencies`
--

CREATE TABLE IF NOT EXISTS `equivalencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grup_gp` varchar(100) NOT NULL,
  `grup_ga` varchar(60) NOT NULL,
  `grup_saga` varchar(60) NOT NULL,
  `prof_gp` varchar(60) NOT NULL,
  `prof_ga` varchar(60) NOT NULL,
  `nom_prof_gp` varchar(60) NOT NULL,
  `codi_prof_gp` varchar(60) NOT NULL,
  `pla_saga` varchar(60) NOT NULL,
  `materia_saga` varchar(60) NOT NULL,
  `materia_gp` varchar(60) NOT NULL,
  `altres` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `espais_centre`
--

CREATE TABLE IF NOT EXISTS `espais_centre` (
  `idespais_centre` int(11) NOT NULL AUTO_INCREMENT,
  `descripcio` varchar(65) NOT NULL,
  `activat` varchar(1) DEFAULT NULL,
  `codi_espai` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`idespais_centre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `families`
--

CREATE TABLE IF NOT EXISTS `families` (
  `idfamilies` int(11) NOT NULL AUTO_INCREMENT,
  `activat` varchar(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`idfamilies`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `fases`
--

CREATE TABLE IF NOT EXISTS `fases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fase` varchar(50) NOT NULL,
  `estat` int(11) NOT NULL,
  `comentaris` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Bolcant dades de la taula `fases`
--

INSERT INTO `fases` (`id`, `fase`, `estat`, `comentaris`) VALUES
(1, 'geisoft', 0, 'Utilitzem o no geisoft'),
(2, 'sincro', 0, 'Està sincronitzada la gestió centralitzada i gassist '),
(3, 'carrega', 0, '0:primera absoluta;1:primera relativa o successives;2:continuar prèvia'),
(4, 'aprofitar_saga', 1, 'Aprofites el fitxer de saga'),
(5, 'aprofitar_horaris', 0, 'Aprofites el fitxer d horaris ho'),
(6, 'segona_carrega', 0, 'És una segona càrrega o posterior'),
(7, 'modalitat_fitxer', 0, '0:ESO/BAT/CAS; 1:CCFF; 2:DUAL'),
(8, 'app_horaris', 0, '0: gpuntis,1:ghc peñalara;2:Kronowin;3:HorW (Sevilla);4: cap aplicacio'),
(9, 'professorat', 0, 'professorat'),
(10, 'alumnat', 0, 'alumnat'),
(11, 'grups', 0, 'grups'),
(12, 'alumne_grups', 0, ''),
(13, 'grups_sense_amteries', 0, 'Grups sense matèries. Càrrega només SAGA'),
(14, 'materies', 0, 'materies'),
(16, 'dies_espais_franges', 0, 'dies, espais i franges'),
(17, 'dies_setmana', 0, 'Dies de la setmana'),
(18, 'franges', 0, 'Franges horàries'),
(19, 'espais', 0, 'espais de centre'),
(20, 'lessons', 0, 'Horaris'),
(21, 'historic', 0, 'Gestió de l històric'),
(22, 'assig_alumnes', 0, 'Assignació d alumnes a grups/matèries');

-- --------------------------------------------------------

--
-- Estructura de la taula `franges_horaries`
--

CREATE TABLE IF NOT EXISTS `franges_horaries` (
  `idfranges_horaries` int(11) NOT NULL AUTO_INCREMENT,
  `idtorn` int(4) NOT NULL,
  `activada` varchar(1) DEFAULT 'S',
  `esbarjo` varchar(1) NOT NULL,
  `hora_inici` time DEFAULT NULL,
  `hora_fi` time DEFAULT NULL,
  PRIMARY KEY (`idfranges_horaries`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `franges_tmp`
--

CREATE TABLE IF NOT EXISTS `franges_tmp` (
  `id_xml_horaris` int(11) NOT NULL,
  `id_taula_franges` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de la taula `grups`
--

CREATE TABLE IF NOT EXISTS `grups` (
  `idgrups` int(11) NOT NULL AUTO_INCREMENT,
  `idtorn` int(4) NOT NULL,
  `codi_grup` varchar(80) DEFAULT NULL,
  `nom` varchar(80) DEFAULT NULL,
  `Descripcio` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idgrups`),
  UNIQUE KEY `codi_grup_UNIQUE` (`codi_grup`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3978 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `grups_materies`
--

CREATE TABLE IF NOT EXISTS `grups_materies` (
  `idgrups_materies` int(11) NOT NULL AUTO_INCREMENT,
  `id_grups` int(11) DEFAULT NULL,
  `id_mat_uf_pla` int(11) DEFAULT NULL,
  `data_inici` date DEFAULT NULL,
  `data_fi` date DEFAULT NULL,
  PRIMARY KEY (`idgrups_materies`),
  KEY `fk_agrupaments_1` (`id_mat_uf_pla`),
  KEY `fk_agrupaments_2` (`id_grups`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `grups_sortides`
--

CREATE TABLE IF NOT EXISTS `grups_sortides` (
  `id_sortida` int(11) NOT NULL,
  `id_grup` int(11) NOT NULL,
  PRIMARY KEY (`id_sortida`,`id_grup`),
  KEY `fk_grups_sortides_1` (`id_grup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de la taula `guardies`
--

CREATE TABLE IF NOT EXISTS `guardies` (
  `idguardies` int(11) NOT NULL AUTO_INCREMENT,
  `idprofessors` int(11) DEFAULT NULL,
  `id_dies_franges` int(11) DEFAULT NULL,
  `idespais_centre` int(11) NOT NULL,
  PRIMARY KEY (`idguardies`),
  KEY `fk_guardies_1` (`id_dies_franges`),
  KEY `fk_guardies_2` (`idprofessors`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `guardies_signades`
--

CREATE TABLE IF NOT EXISTS `guardies_signades` (
  `idguardia_signada` int(11) NOT NULL AUTO_INCREMENT,
  `idprofessors` int(11) NOT NULL,
  `idgrups` int(11) NOT NULL,
  `id_mat_uf_pla` int(11) NOT NULL,
  `idfranges_horaries` int(11) NOT NULL,
  `data` date NOT NULL,
  PRIMARY KEY (`idguardia_signada`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `HIST_CCC`
--

CREATE TABLE IF NOT EXISTS `HIST_CCC` (
  `idccc` int(11) NOT NULL AUTO_INCREMENT,
  `alumne` int(11) DEFAULT NULL,
  `grup` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `professor` int(11) DEFAULT NULL,
  `materia` varchar(80) COLLATE utf8_bin NOT NULL,
  `motiu` varchar(80) COLLATE utf8_bin NOT NULL,
  `data` date DEFAULT NULL,
  `descripcio_breu` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `descripcio_detallada` longtext COLLATE utf8_bin,
  `data_inici_sancio` date DEFAULT NULL,
  `data_fi_sancio` date DEFAULT NULL,
  PRIMARY KEY (`idccc`),
  KEY `fk_ccc_taula_principal_1_idx` (`alumne`),
  KEY `fk_ccc_taula_principal_2_idx` (`professor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `HIST_dates`
--

CREATE TABLE IF NOT EXISTS `HIST_dates` (
  `idhist_dates` int(11) NOT NULL AUTO_INCREMENT,
  `Dates_historics` date NOT NULL,
  PRIMARY KEY (`idhist_dates`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `HIST_faltes_alumnes`
--

CREATE TABLE IF NOT EXISTS `HIST_faltes_alumnes` (
  `id_hist_alumnes` int(11) NOT NULL AUTO_INCREMENT,
  `id_alumne` int(11) NOT NULL,
  `id_professor` int(11) NOT NULL,
  `dia` date NOT NULL,
  `franja` varchar(20) NOT NULL,
  `materia` varchar(60) NOT NULL,
  `grup_curs` varchar(50) NOT NULL,
  `tipus_falta` varchar(50) NOT NULL,
  `comentari` varchar(1545) NOT NULL,
  PRIMARY KEY (`id_hist_alumnes`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `HIST_grups_sortides`
--

CREATE TABLE IF NOT EXISTS `HIST_grups_sortides` (
  `id_sortida` int(11) NOT NULL,
  `id_grup` int(11) NOT NULL,
  PRIMARY KEY (`id_sortida`,`id_grup`),
  KEY `fk_grups_sortides_1` (`id_grup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de la taula `HIST_incidencia_professor`
--

CREATE TABLE IF NOT EXISTS `HIST_incidencia_professor` (
  `idincidencia_professor` int(11) NOT NULL,
  `idprofessors` int(11) NOT NULL,
  `grup` varchar(80) NOT NULL,
  `mat_uf_pla` varchar(80) NOT NULL,
  `tipus_incidencia` varchar(50) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `comentari` varchar(1545) DEFAULT NULL,
  `franges_horaries` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`idincidencia_professor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de la taula `HIST_sortides`
--

CREATE TABLE IF NOT EXISTS `HIST_sortides` (
  `idsortides` int(11) NOT NULL,
  `data_inici` date DEFAULT NULL,
  `data_fi` date DEFAULT NULL,
  `hora_inici` time DEFAULT NULL,
  `hora_fi` time DEFAULT NULL,
  `lloc` varchar(55) COLLATE utf8_bin NOT NULL,
  `descripcio` varchar(256) COLLATE utf8_bin NOT NULL,
  `tancada` varchar(1) COLLATE utf8_bin NOT NULL DEFAULT 'N',
  PRIMARY KEY (`idsortides`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de la taula `HIST_sortides_alumne`
--

CREATE TABLE IF NOT EXISTS `HIST_sortides_alumne` (
  `idsortides_alumne` int(11) NOT NULL,
  `id_sortida` int(11) NOT NULL,
  `id_alumne` int(11) NOT NULL,
  PRIMARY KEY (`idsortides_alumne`),
  KEY `alumnes_sortida` (`id_alumne`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de la taula `HIST_sortides_professor`
--

CREATE TABLE IF NOT EXISTS `HIST_sortides_professor` (
  `idprofessorat_sortides` int(11) NOT NULL,
  `id_sortida` int(11) NOT NULL,
  `id_professorat` int(11) NOT NULL,
  `responsable` varchar(1) COLLATE utf8_bin NOT NULL DEFAULT 'N',
  PRIMARY KEY (`idprofessorat_sortides`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de la taula `incidencia_alumne`
--

CREATE TABLE IF NOT EXISTS `incidencia_alumne` (
  `idincidencia_alumne` int(11) NOT NULL AUTO_INCREMENT,
  `idalumne_agrupament` int(11) DEFAULT '0',
  `idalumnes` int(11) NOT NULL,
  `idgrups` int(11) NOT NULL,
  `id_mat_uf_pla` int(11) NOT NULL,
  `idprofessors` int(11) DEFAULT NULL,
  `id_tipus_incidencia` int(11) DEFAULT NULL,
  `id_tipus_incident` int(11) NOT NULL DEFAULT '0',
  `data` date DEFAULT NULL,
  `comentari` varchar(1545) DEFAULT NULL,
  `idfranges_horaries` int(11) NOT NULL,
  PRIMARY KEY (`idincidencia_alumne`),
  KEY `fk_incidencia_alumne_2` (`idalumne_agrupament`),
  KEY `fk_incidencia_alumne_3` (`idprofessors`),
  KEY `fk_incidencia_alumne_1` (`id_tipus_incidencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `incidencia_professor`
--

CREATE TABLE IF NOT EXISTS `incidencia_professor` (
  `idincidencia_professor` int(11) NOT NULL AUTO_INCREMENT,
  `idprofessors` int(11) NOT NULL,
  `idgrups` int(11) NOT NULL,
  `id_mat_uf_pla` int(11) NOT NULL,
  `id_tipus_incidencia` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `comentari` varchar(1545) DEFAULT NULL,
  `comentari_tasca` varchar(256) DEFAULT NULL,
  `idfranges_horaries` int(11) DEFAULT NULL,
  PRIMARY KEY (`idincidencia_professor`),
  KEY `fk_incidencia_professor_1` (`id_tipus_incidencia`),
  KEY `fk_incidencia_professor_3` (`idfranges_horaries`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `log_alumnes`
--

CREATE TABLE IF NOT EXISTS `log_alumnes` (
  `idlog_alumnes` int(11) NOT NULL AUTO_INCREMENT,
  `data` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `id_alumne` int(11) DEFAULT NULL,
  `id_accio` int(11) DEFAULT NULL,
  PRIMARY KEY (`idlog_alumnes`),
  KEY `fk_log_alumnes_1` (`id_accio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `log_families`
--

CREATE TABLE IF NOT EXISTS `log_families` (
  `idlog_families` int(11) NOT NULL AUTO_INCREMENT,
  `data` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `id_familia` int(11) DEFAULT NULL,
  `id_accio` int(11) DEFAULT NULL,
  PRIMARY KEY (`idlog_families`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `log_professors`
--

CREATE TABLE IF NOT EXISTS `log_professors` (
  `idlog_professors` int(11) NOT NULL AUTO_INCREMENT,
  `data` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `id_professor` int(11) DEFAULT NULL,
  `id_accio` int(11) DEFAULT NULL,
  PRIMARY KEY (`idlog_professors`),
  KEY `fk_log_professors_1` (`id_accio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `materia`
--

CREATE TABLE IF NOT EXISTS `materia` (
  `idmateria` int(11) NOT NULL AUTO_INCREMENT,
  `codi_materia` varchar(30) DEFAULT NULL,
  `nom_materia` varchar(75) DEFAULT NULL,
  `descripcio` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idmateria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `missatges_tutor`
--

CREATE TABLE IF NOT EXISTS `missatges_tutor` (
  `idmissatges_tutor` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idprofessor` int(11) NOT NULL DEFAULT '0',
  `idalumne` int(11) NOT NULL DEFAULT '0',
  `idgrup` int(11) NOT NULL DEFAULT '0',
  `data` date NOT NULL,
  `hora` time NOT NULL,
  `missatge` longtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`idmissatges_tutor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `moduls`
--

CREATE TABLE IF NOT EXISTS `moduls` (
  `idmoduls` int(11) NOT NULL AUTO_INCREMENT,
  `idplans_estudis` int(11) NOT NULL,
  `nom_modul` varchar(80) DEFAULT NULL,
  `hores_finals` smallint(6) NOT NULL,
  `horeslliuredisposicio` smallint(6) DEFAULT NULL,
  `codi_modul` varchar(30) NOT NULL,
  PRIMARY KEY (`idmoduls`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `moduls_materies_ufs`
--

CREATE TABLE IF NOT EXISTS `moduls_materies_ufs` (
  `id_mat_uf_pla` int(11) NOT NULL AUTO_INCREMENT,
  `idplans_estudis` int(11) NOT NULL,
  `hores_finals` smallint(6) DEFAULT NULL,
  `Curs` smallint(6) DEFAULT NULL,
  `codi_materia` varchar(60) DEFAULT NULL,
  `automatricula` varchar(1) NOT NULL,
  `activat` varchar(1) NOT NULL,
  `contrasenya` varchar(25) NOT NULL,
  PRIMARY KEY (`id_mat_uf_pla`,`idplans_estudis`),
  UNIQUE KEY `codi_materia_UNIQUE` (`codi_materia`),
  KEY `fk_moduls_ufs_3` (`idplans_estudis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `moduls_ufs`
--

CREATE TABLE IF NOT EXISTS `moduls_ufs` (
  `idmoduls_ufs` int(4) NOT NULL AUTO_INCREMENT,
  `id_moduls` int(11) DEFAULT NULL,
  `id_ufs` int(11) DEFAULT NULL,
  PRIMARY KEY (`idmoduls_ufs`),
  KEY `fk_moduls_ufs_1` (`id_moduls`),
  KEY `fk_moduls_ufs_2` (`id_ufs`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `periodes_escolars`
--

CREATE TABLE IF NOT EXISTS `periodes_escolars` (
  `idperiodes_escolars` int(11) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(9) DEFAULT NULL,
  `Descripcio` varchar(45) DEFAULT NULL,
  `data_inici` date NOT NULL,
  `data_fi` date NOT NULL,
  `actual` varchar(1) NOT NULL,
  PRIMARY KEY (`idperiodes_escolars`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Bolcant dades de la taula `periodes_escolars`
--

INSERT INTO `periodes_escolars` (`idperiodes_escolars`, `Nom`, `Descripcio`, `data_inici`, `data_fi`, `actual`) VALUES
(2, '2013/2014', 'Curs 2013/2014', '2013-09-13', '2014-06-22', ''),
(3, '2014/2015', 'Curs 2014/2015', '2014-09-12', '2015-06-19', ''),
(4, '2015/2016', 'Curs 2015/2016', '2015-09-14', '2016-06-21', 'S');

-- --------------------------------------------------------

--
-- Estructura de la taula `periodes_escolars_festius`
--

CREATE TABLE IF NOT EXISTS `periodes_escolars_festius` (
  `id_festiu` int(11) NOT NULL AUTO_INCREMENT,
  `id_periode` int(11) NOT NULL,
  `festiu` date NOT NULL,
  PRIMARY KEY (`id_festiu`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `permisos`
--

CREATE TABLE IF NOT EXISTS `permisos` (
  `idpermis` int(11) NOT NULL,
  `nom` varchar(15) DEFAULT NULL,
  `descripcio` mediumtext,
  PRIMARY KEY (`idpermis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de la taula `plans_estudis`
--

CREATE TABLE IF NOT EXISTS `plans_estudis` (
  `idplans_estudis` int(11) NOT NULL AUTO_INCREMENT,
  `activat` varchar(1) DEFAULT NULL,
  `Nom_plan_estudis` varchar(80) DEFAULT NULL,
  `Acronim_pla_estudis` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idplans_estudis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `plantilles`
--

CREATE TABLE IF NOT EXISTS `plantilles` (
  `idplantilles` int(11) NOT NULL,
  `codi` varchar(9) DEFAULT NULL,
  `Descripció` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idplantilles`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de la taula `plantilles_grups_materies`
--

CREATE TABLE IF NOT EXISTS `plantilles_grups_materies` (
  `id_plantilla` int(11) NOT NULL,
  `id_grup_materia` int(11) NOT NULL,
  PRIMARY KEY (`id_plantilla`,`id_grup_materia`),
  KEY `fk_Plantilles_grups_matèries_1` (`id_grup_materia`),
  KEY `fk_Plantilles_grups_matèries_2` (`id_plantilla`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de la taula `professors`
--

CREATE TABLE IF NOT EXISTS `professors` (
  `idprofessors` int(11) NOT NULL AUTO_INCREMENT,
  `codi_professor` varchar(130) DEFAULT NULL,
  `activat` varchar(1) NOT NULL DEFAULT 'S',
  `historic` varchar(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`idprofessors`),
  UNIQUE KEY `codi_professor_UNIQUE` (`codi_professor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=420 ;

--
-- Bolcant dades de la taula `professors`
--

INSERT INTO `professors` (`idprofessors`, `codi_professor`, `activat`, `historic`) VALUES
(0, 'NO_PROF', 'N', 'N'),
(417, 'admin', 'S', 'N'),
(418, 'vlino', 'S', 'N');

-- --------------------------------------------------------

--
-- Estructura de la taula `professor_carrec`
--

CREATE TABLE IF NOT EXISTS `professor_carrec` (
  `idprofessor_carrec` int(11) NOT NULL AUTO_INCREMENT,
  `idprofessors` int(11) DEFAULT NULL,
  `idcarrecs` int(11) DEFAULT NULL,
  `idgrups` int(11) NOT NULL,
  `principal` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idprofessor_carrec`),
  KEY `fk_professor-carrec_1` (`idcarrecs`),
  KEY `fk_professor-carrec_2` (`idprofessors`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=119 ;

--
-- Bolcant dades de la taula `professor_carrec`
--

INSERT INTO `professor_carrec` (`idprofessor_carrec`, `idprofessors`, `idcarrecs`, `idgrups`, `principal`) VALUES
(117, 417, 4, 0, 0),
(118, 418, 4, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de la taula `prof_agrupament`
--

CREATE TABLE IF NOT EXISTS `prof_agrupament` (
  `idprof_grup_materia` int(11) NOT NULL AUTO_INCREMENT,
  `idprofessors` int(11) NOT NULL,
  `idagrups_materies` int(11) NOT NULL,
  PRIMARY KEY (`idprof_grup_materia`,`idprofessors`,`idagrups_materies`),
  KEY `fk_prof_grup_materia_1` (`idagrups_materies`),
  KEY `fk_prof_grup_materia_3` (`idprofessors`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `prof_atencions`
--

CREATE TABLE IF NOT EXISTS `prof_atencions` (
  `idprofatencio` int(11) NOT NULL AUTO_INCREMENT,
  `idprofessors` int(11) DEFAULT NULL,
  `id_dies_franges` int(11) DEFAULT NULL,
  `idespais_centre` int(11) NOT NULL,
  PRIMARY KEY (`idprofatencio`),
  KEY `fk_guardies_1` (`id_dies_franges`),
  KEY `fk_guardies_2` (`idprofessors`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `prof_coordinacions`
--

CREATE TABLE IF NOT EXISTS `prof_coordinacions` (
  `idprofcoordinacio` int(11) NOT NULL AUTO_INCREMENT,
  `idprofessors` int(11) DEFAULT NULL,
  `id_dies_franges` int(11) DEFAULT NULL,
  `idespais_centre` int(11) NOT NULL,
  PRIMARY KEY (`idprofcoordinacio`),
  KEY `fk_guardies_1` (`id_dies_franges`),
  KEY `fk_guardies_2` (`idprofessors`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `prof_direccio`
--

CREATE TABLE IF NOT EXISTS `prof_direccio` (
  `idprofdireccio` int(11) NOT NULL AUTO_INCREMENT,
  `idprofessors` int(11) DEFAULT NULL,
  `id_dies_franges` int(11) DEFAULT NULL,
  `idespais_centre` int(11) NOT NULL,
  PRIMARY KEY (`idprofdireccio`),
  KEY `fk_guardies_1` (`id_dies_franges`),
  KEY `fk_guardies_2` (`idprofessors`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `prof_permanencies`
--

CREATE TABLE IF NOT EXISTS `prof_permanencies` (
  `idprofpermanencia` int(11) NOT NULL AUTO_INCREMENT,
  `idprofessors` int(11) DEFAULT NULL,
  `id_dies_franges` int(11) DEFAULT NULL,
  `idespais_centre` int(11) NOT NULL,
  PRIMARY KEY (`idprofpermanencia`),
  KEY `fk_guardies_1` (`id_dies_franges`),
  KEY `fk_guardies_2` (`idprofessors`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `prof_reunions`
--

CREATE TABLE IF NOT EXISTS `prof_reunions` (
  `idprofreunio` int(11) NOT NULL AUTO_INCREMENT,
  `idprofessors` int(11) DEFAULT NULL,
  `id_dies_franges` int(11) DEFAULT NULL,
  `idespais_centre` int(11) NOT NULL,
  PRIMARY KEY (`idprofreunio`),
  KEY `fk_guardies_1` (`id_dies_franges`),
  KEY `fk_guardies_2` (`idprofessors`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `qp_seguiment`
--

CREATE TABLE IF NOT EXISTS `qp_seguiment` (
  `id_seguiment` int(11) NOT NULL AUTO_INCREMENT,
  `id_dia_franja` int(11) NOT NULL,
  `id_grup_materia` int(11) NOT NULL,
  `lectiva` int(1) NOT NULL,
  `seguiment` varchar(1000) NOT NULL,
  `data` date NOT NULL,
  PRIMARY KEY (`id_seguiment`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `sortides`
--

CREATE TABLE IF NOT EXISTS `sortides` (
  `idsortides` int(11) NOT NULL AUTO_INCREMENT,
  `data_inici` date DEFAULT NULL,
  `data_fi` date DEFAULT NULL,
  `hora_inici` time DEFAULT NULL,
  `hora_fi` time DEFAULT NULL,
  `lloc` varchar(55) COLLATE utf8_bin NOT NULL,
  `descripcio` varchar(256) COLLATE utf8_bin NOT NULL,
  `tancada` varchar(1) COLLATE utf8_bin NOT NULL DEFAULT 'N',
  PRIMARY KEY (`idsortides`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `sortides_alumne`
--

CREATE TABLE IF NOT EXISTS `sortides_alumne` (
  `idsortides_alumne` int(11) NOT NULL AUTO_INCREMENT,
  `id_sortida` int(11) NOT NULL,
  `id_alumne` int(11) NOT NULL,
  PRIMARY KEY (`idsortides_alumne`),
  KEY `alumnes_sortida` (`id_alumne`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `sortides_professor`
--

CREATE TABLE IF NOT EXISTS `sortides_professor` (
  `idprofessorat_sortides` int(11) NOT NULL AUTO_INCREMENT,
  `id_sortida` int(11) NOT NULL,
  `id_professorat` int(11) NOT NULL,
  `responsable` varchar(1) COLLATE utf8_bin NOT NULL DEFAULT 'N',
  PRIMARY KEY (`idprofessorat_sortides`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `textos_sms`
--

CREATE TABLE IF NOT EXISTS `textos_sms` (
  `idtextos` int(4) NOT NULL AUTO_INCREMENT,
  `nom` varchar(45) COLLATE utf8_bin NOT NULL,
  `descripcio` varchar(160) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`idtextos`),
  KEY `nom` (`nom`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=9 ;

--
-- Bolcant dades de la taula `textos_sms`
--

INSERT INTO `textos_sms` (`idtextos`, `nom`, `descripcio`) VALUES
(6, 'Fill absent', 'Estimats senyors/es, comunicarles que el seu fill avui no ha assistit a classe. Als efectes oportuns.'),
(7, 'Fill arriba tard', 'Estimats senyors/es, comunicarles que el seu fill avui ha arribat tard a classe. Als efectes oportuns.'),
(8, 'Mal comportament', 'Estimats senyors/es, comunicarles que el seu fill tè un comportament contrari a la convivència. Als efectes oportuns.');

-- --------------------------------------------------------

--
-- Estructura de la taula `tipus_contacte`
--

CREATE TABLE IF NOT EXISTS `tipus_contacte` (
  `idtipus_contacte` int(11) NOT NULL AUTO_INCREMENT,
  `dada_contacte` varchar(40) NOT NULL,
  `Nom_info_contacte` varchar(20) DEFAULT NULL COMMENT 'Identificador del SAGA',
  PRIMARY KEY (`idtipus_contacte`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Conté els diferents camps que poden fer falta en les taules ' AUTO_INCREMENT=28 ;

--
-- Bolcant dades de la taula `tipus_contacte`
--

INSERT INTO `tipus_contacte` (`idtipus_contacte`, `dada_contacte`, `Nom_info_contacte`) VALUES
(1, 'Nom complet', 'nom_complet'),
(2, '#', 'a_determinar'),
(3, 'Identificador', 'iden_ref'),
(4, '1r Cognom', 'cognom1_alumne'),
(5, '2n Cognom', 'cognom2_alumne'),
(6, 'Nom', 'nom_alumne'),
(7, 'Gènere', 'genere'),
(8, 'Nom del grup', 'nom_grup'),
(9, 'Adreça', 'adreca'),
(10, 'Nom del municipi', 'nom_municipi'),
(11, 'Codi postal', 'codi_postal'),
(12, 'Telèfon', 'telefon'),
(13, '1r Cognom', 'cognom1_pare'),
(14, '2n Cognom', 'cognom2_pare'),
(15, 'Nom pare/tutor', 'nom_pare'),
(16, '1r Cognom', 'cognom1_mare'),
(17, '2n Cognom', 'cognom2_mare'),
(18, 'Nom mare/tutora', 'nom_mare'),
(19, 'Correu electrònic', 'email'),
(20, 'Contrasenya', 'contrasenya'),
(21, 'Login', 'login'),
(22, 'Cognoms', 'cognoms_profe'),
(23, 'Nom', 'nom_profe'),
(24, 'Mòbil sms', 'mobil_sms'),
(25, 'Contrasenya notificar', 'contrasenya_notifica'),
(27, 'Data de naixement', 'data_naixement');

-- --------------------------------------------------------

--
-- Estructura de la taula `tipus_falta_alumne`
--

CREATE TABLE IF NOT EXISTS `tipus_falta_alumne` (
  `idtipus_falta_alumne` int(11) NOT NULL AUTO_INCREMENT,
  `tipus_falta` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idtipus_falta_alumne`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Bolcant dades de la taula `tipus_falta_alumne`
--

INSERT INTO `tipus_falta_alumne` (`idtipus_falta_alumne`, `tipus_falta`) VALUES
(1, 'Absència'),
(2, 'Retard'),
(3, 'Justificada'),
(4, 'Incident'),
(5, 'Sortida'),
(6, 'CCC');

-- --------------------------------------------------------

--
-- Estructura de la taula `tipus_falta_professor`
--

CREATE TABLE IF NOT EXISTS `tipus_falta_professor` (
  `idtipus_falta_professor` int(11) NOT NULL AUTO_INCREMENT,
  `tipus_falta` varchar(45) DEFAULT NULL,
  `Comentari` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idtipus_falta_professor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Bolcant dades de la taula `tipus_falta_professor`
--

INSERT INTO `tipus_falta_professor` (`idtipus_falta_professor`, `tipus_falta`, `Comentari`) VALUES
(1, 'Permís per formació', ' '),
(2, 'Permís sindical', ' '),
(3, 'Permís altres', ' '),
(4, 'Malaltia', ' '),
(5, 'Retard', ''),
(6, 'Passar llista guàrdia', NULL),
(7, 'Sortida', NULL);

-- --------------------------------------------------------

--
-- Estructura de la taula `tipus_incidents`
--

CREATE TABLE IF NOT EXISTS `tipus_incidents` (
  `idtipus_incident` int(11) NOT NULL AUTO_INCREMENT,
  `tipus_incident` varchar(132) DEFAULT NULL,
  PRIMARY KEY (`idtipus_incident`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Bolcant dades de la taula `tipus_incidents`
--

INSERT INTO `tipus_incidents` (`idtipus_incident`, `tipus_incident`) VALUES
(0, 'Per determinar'),
(7, 'No fa els deures'),
(8, 'Ha sonat el seu mòbil'),
(9, 'Sense llibre'),
(10, 'No porta el material específic'),
(11, 'Comunicat de conductes contràries a les normes de convivència'),
(12, 'No presenta les activitats plantejades'),
(13, 'No ha portat la roba per fer classe'),
(14, 'No troba els deures'),
(15, 'Problemes amb l''ordinador'),
(17, 'ENTREVISTA AMB PARES, NO ÉS A CLASSE');

-- --------------------------------------------------------

--
-- Estructura de la taula `tipus_motius_falta_professor`
--

CREATE TABLE IF NOT EXISTS `tipus_motius_falta_professor` (
  `idtipus_motius_professor` int(11) NOT NULL AUTO_INCREMENT,
  `tipus_motius` varchar(45) DEFAULT NULL,
  `Comentari` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idtipus_motius_professor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `torn`
--

CREATE TABLE IF NOT EXISTS `torn` (
  `idtorn` int(4) NOT NULL AUTO_INCREMENT,
  `nom_torn` varchar(50) NOT NULL,
  PRIMARY KEY (`idtorn`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Bolcant dades de la taula `torn`
--

INSERT INTO `torn` (`idtorn`, `nom_torn`) VALUES
(1, 'Torn global'),
(2, 'Mati'),
(3, 'Tarda');

-- --------------------------------------------------------

--
-- Estructura de la taula `unitats_classe`
--

CREATE TABLE IF NOT EXISTS `unitats_classe` (
  `idunitats_classe` int(11) NOT NULL AUTO_INCREMENT,
  `id_dies_franges` int(11) NOT NULL,
  `idespais_centre` int(11) NOT NULL DEFAULT '0',
  `idgrups_materies` int(11) NOT NULL,
  PRIMARY KEY (`idunitats_classe`,`id_dies_franges`,`idespais_centre`,`idgrups_materies`),
  KEY `fk_unitats_classe_2` (`id_dies_franges`),
  KEY `fk_unitats_classe_3` (`idespais_centre`),
  KEY `fk_unitats_classe_4` (`idgrups_materies`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de la taula `unitats_formatives`
--

CREATE TABLE IF NOT EXISTS `unitats_formatives` (
  `idunitats_formatives` int(11) NOT NULL DEFAULT '0',
  `nom_uf` varchar(60) DEFAULT NULL,
  `hores` smallint(6) DEFAULT '0',
  `codi_uf` varchar(30) DEFAULT NULL,
  `data_inici` date NOT NULL,
  `data_fi` date NOT NULL,
  PRIMARY KEY (`idunitats_formatives`),
  KEY `fk_unitats_formatives_1` (`idunitats_formatives`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Ha d''haver un camp que sigui: sense unitats formatives de ma';

-- --------------------------------------------------------

--
-- Estructura de la taula `versio_bdd`
--

CREATE TABLE IF NOT EXISTS `versio_bdd` (
  `versio` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Bolcant dades de la taula `versio_bdd`
--

INSERT INTO `versio_bdd` (`versio`) VALUES
(''),
('2.1'),
('2.2'),
('2.3'),
('2.3.1'),
('2.4');

--
-- Restriccions per taules bolcades
--

--
-- Restriccions per la taula `alumnes_families`
--
ALTER TABLE `alumnes_families`
  ADD CONSTRAINT `fk_alumnes_families_1` FOREIGN KEY (`idalumnes`) REFERENCES `alumnes` (`idalumnes`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_alumnes_families_2` FOREIGN KEY (`idfamilies`) REFERENCES `families` (`idfamilies`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restriccions per la taula `carrecs_permisos`
--
ALTER TABLE `carrecs_permisos`
  ADD CONSTRAINT `fk_carrecs_permisos_1` FOREIGN KEY (`idpermisos`) REFERENCES `permisos` (`idpermis`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_carrecs_permisos_2` FOREIGN KEY (`idcarrecs`) REFERENCES `carrecs` (`idcarrecs`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restriccions per la taula `ccc_limit_comunicacio`
--
ALTER TABLE `ccc_limit_comunicacio`
  ADD CONSTRAINT `fk_limit_comunicacio_1` FOREIGN KEY (`carrec`) REFERENCES `carrecs` (`idcarrecs`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restriccions per la taula `ccc_taula_principal`
--
ALTER TABLE `ccc_taula_principal`
  ADD CONSTRAINT `fk_ccc_taula_principal_1` FOREIGN KEY (`idalumne`) REFERENCES `alumnes` (`idalumnes`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ccc_taula_principal_2` FOREIGN KEY (`idprofessor`) REFERENCES `professors` (`idprofessors`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ccc_taula_principal_4` FOREIGN KEY (`id_falta`) REFERENCES `ccc_tipus` (`idccc_tipus`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ccc_taula_principal_5` FOREIGN KEY (`id_tipus_sancio`) REFERENCES `ccc_tipus_mesura` (`idccc_tipus_mesura`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restriccions per la taula `ccc_tipus_comunicacio_carrec`
--
ALTER TABLE `ccc_tipus_comunicacio_carrec`
  ADD CONSTRAINT `fk_ccc_tipus_comunicacio_carrec_1` FOREIGN KEY (`id_tipus`) REFERENCES `ccc_tipus` (`idccc_tipus`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ccc_tipus_comunicacio_carrec_2` FOREIGN KEY (`id_carrec`) REFERENCES `carrecs` (`idcarrecs`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restriccions per la taula `comentaris_de_grup`
--
ALTER TABLE `comentaris_de_grup`
  ADD CONSTRAINT `fk_comentaris_de_grup_1` FOREIGN KEY (`idagrupaments`) REFERENCES `grups_materies` (`idgrups_materies`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comentaris_de_grup_2` FOREIGN KEY (`idprofessors`) REFERENCES `professors` (`idprofessors`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restriccions per la taula `contacte_alumne`
--
ALTER TABLE `contacte_alumne`
  ADD CONSTRAINT `fk_contacte_alumne_1` FOREIGN KEY (`id_tipus_contacte`) REFERENCES `tipus_contacte` (`idtipus_contacte`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_contacte_alumne_2` FOREIGN KEY (`id_alumne`) REFERENCES `alumnes` (`idalumnes`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restriccions per la taula `contacte_families`
--
ALTER TABLE `contacte_families`
  ADD CONSTRAINT `fk_contacte_families_1` FOREIGN KEY (`id_tipus_contacte`) REFERENCES `tipus_contacte` (`idtipus_contacte`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_contacte_families_2` FOREIGN KEY (`id_families`) REFERENCES `families` (`idfamilies`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restriccions per la taula `contacte_professor`
--
ALTER TABLE `contacte_professor`
  ADD CONSTRAINT `fk_contacte_professor_1` FOREIGN KEY (`id_tipus_contacte`) REFERENCES `tipus_contacte` (`idtipus_contacte`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_contacte_professor_2` FOREIGN KEY (`id_professor`) REFERENCES `professors` (`idprofessors`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restriccions per la taula `dies_franges`
--
ALTER TABLE `dies_franges`
  ADD CONSTRAINT `fk_dies_franges_1` FOREIGN KEY (`iddies_setmana`) REFERENCES `dies_setmana` (`iddies_setmana`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_dies_franges_2` FOREIGN KEY (`idfranges_horaries`) REFERENCES `franges_horaries` (`idfranges_horaries`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_dies_franges_3` FOREIGN KEY (`idperiode_escolar`) REFERENCES `periodes_escolars` (`idperiodes_escolars`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restriccions per la taula `grups_materies`
--
ALTER TABLE `grups_materies`
  ADD CONSTRAINT `fk_agrupaments_1` FOREIGN KEY (`id_mat_uf_pla`) REFERENCES `moduls_materies_ufs` (`id_mat_uf_pla`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_agrupaments_2` FOREIGN KEY (`id_grups`) REFERENCES `grups` (`idgrups`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
