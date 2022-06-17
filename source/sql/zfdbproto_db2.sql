--
-- Base de données : zfdbproto
--

-- --------------------------------------------------------

--
-- Structure de la table dossier
--

CREATE SCHEMA zfdbproto;


CREATE OR REPLACE TABLE zfdbproto.dossier (
  id integer NOT NULL 
     GENERATED ALWAYS AS IDENTITY 
     ( START WITH 1 , INCREMENT BY 1 , CACHE 2 ),
  societe_id integer NOT NULL WITH DEFAULT,
  dossier_ref char(8) NOT NULL WITH DEFAULT,
  personne_id integer NOT NULL WITH DEFAULT,
  cde_ape char(4) NOT NULL WITH DEFAULT,
  cde_eta_jur char(3) NOT NULL WITH DEFAULT,
  cde_fam_prd char(3) NOT NULL WITH DEFAULT,
  statut char(3) NOT NULL WITH DEFAULT,
  typ_mat_id char(8) NOT NULL WITH DEFAULT,
  fam_ctx_id char(3) NOT NULL WITH DEFAULT,
  chgt_doss date DEFAULT NULL,
  cd_etat_cpt char(1) NOT NULL WITH DEFAULT,
  encours_mnt decimal(15,2) NOT NULL WITH DEFAULT,
  solde_brut decimal(15,2) NOT NULL WITH DEFAULT,
  top_cloture char(1) NOT NULL WITH DEFAULT
) ;


-- --------------------------------------------------------

--
-- Structure de la table prm_cod_ape
--

CREATE OR REPLACE TABLE zfdbproto.prm_cod_ape (
  id integer NOT NULL 
     GENERATED ALWAYS AS IDENTITY 
     ( START WITH 1 , INCREMENT BY 1 , CACHE 2 ),
  code char(3) NOT NULL WITH DEFAULT,
  libelle char(40) NOT NULL WITH DEFAULT
) ;

--
-- Déchargement des données de la table prm_cod_ape
--

INSERT INTO zfdbproto.prm_cod_ape (id, code, libelle) OVERRIDING USER VALUE VALUES
(12, '001', 'Activités générales de sécurité'),
(4, '002', 'Administration publique générale'),
(7, '003', 'Affaires étrangères'),
(8, '004', 'Défense'),
(14, '005', 'Distribution sociale de revenus'),
(15, '006', 'Enseignement primaire'),
(16, '007', 'Enseignement secondaire technique'),
(17, '008', 'Enseignement supérieur'),
(13, '009', 'Gestion des retraites complément'),
(9, '010', 'Justice'),
(10, '011', 'Police'),
(1, '012', 'Postes nationales'),
(11, '013', 'Protection civile'),
(3, '014', 'Recherche-développement en sciences'),
(2, '015', 'Recherche-développement en sciences'),
(6, '016', 'Tutelle des activités économique'),
(5, '017', 'Tutelle des activités sociales');

-- --------------------------------------------------------

--
-- Structure de la table prm_cod_soc
--

CREATE OR REPLACE TABLE zfdbproto.prm_cod_soc (
  id integer NOT NULL 
     GENERATED ALWAYS AS IDENTITY 
     ( START WITH 1 , INCREMENT BY 1 , CACHE 2 ),
  code char(3) NOT NULL WITH DEFAULT,
  libelle char(30) NOT NULL WITH DEFAULT
) ;

--
-- Déchargement des données de la table prm_cod_soc
--

INSERT INTO zfdbproto.prm_cod_soc (id, code, libelle) OVERRIDING USER VALUE VALUES
(1, '1', 'SOCIETE 1'),
(2, '2', 'SOCIETE 2'),
(3, '3', 'SOCIETE 3'),
(4, '4', 'SOCIETE 4');

-- --------------------------------------------------------

--
-- Structure de la table prm_eta_jur
--

CREATE OR REPLACE TABLE zfdbproto.prm_eta_jur (
  id integer NOT NULL 
     GENERATED ALWAYS AS IDENTITY 
     ( START WITH 1 , INCREMENT BY 1 , CACHE 2 ),
  code char(3) NOT NULL WITH DEFAULT,
  libelle char(30) NOT NULL WITH DEFAULT
) ;

--
-- Déchargement des données de la table prm_eta_jur
--

INSERT INTO zfdbproto.prm_eta_jur (id, code, libelle) OVERRIDING USER VALUE VALUES
(1, 'CTX', 'Contentieux'),
(2, 'DCD', 'Décédé'),
(3, 'LJ', 'Liquidation judiciaire'),
(4, 'PRE', 'Préoccupant'),
(5, 'RJ', 'Règlement judiciaire'),
(6, 'SAU', 'Sauvegarde');

-- --------------------------------------------------------

--
-- Structure de la table prm_personne
--

CREATE OR REPLACE TABLE zfdbproto.prm_personne (
  id integer NOT NULL 
     GENERATED ALWAYS AS IDENTITY 
     ( START WITH 1 , INCREMENT BY 1 , CACHE 2 ),
  code decimal(8,0) NOT NULL WITH DEFAULT,
  libelle char(15) NOT NULL WITH DEFAULT
) ;



-- --------------------------------------------------------

--
-- Structure de la table prm_prd_fin
--

CREATE OR REPLACE TABLE zfdbproto.prm_prd_fin (
  id integer NOT NULL 
     GENERATED ALWAYS AS IDENTITY 
     ( START WITH 1 , INCREMENT BY 1 , CACHE 2 ),
  code char(3) NOT NULL WITH DEFAULT,
  libelle char(40) NOT NULL WITH DEFAULT
) ;

--
-- Déchargement des données de la table prm_prd_fin
--

INSERT INTO zfdbproto.prm_prd_fin (id, code, libelle) OVERRIDING USER VALUE VALUES
(4, '055', 'AVANCE SUR FINANCEMENT'),
(1, '051', 'CREDIT CAUSE'),
(11, '059', 'CREDIT CAUSE AU PERSONNEL'),
(16, '093', 'CREDIT D''EQUIPEMENT PROF'),
(10, '093', 'CREDIT D''EQUIPEMENT PROF.'),
(3, '054', 'CREDIT DE FINANCMNT AUX PRESCRIP'),
(9, '092', 'CREDIT IMMOB POUR LOCAUX PROF'),
(8, '092', 'CREDIT IMMOB POUR LOCAUX PROF.'),
(2, '053', 'CREDIT STOCK'),
(14, '091', 'CREDIT SUR FOND CODEVI'),
(15, '091', 'CREDIT SUR FONDS CODEVI'),
(12, '071', 'PRET AU PERSONNEL'),
(6, '081', 'PRET IMMOBILIER A L''HABITAT'),
(7, '082', 'PRET IMMOBILIER A TAUX ZERO'),
(5, '072', 'PRET IMMOBILIER AU PERSONNEL'),
(13, '052', 'PRET PERSONNEL');

-- --------------------------------------------------------

--
-- Structure de la table prm_sta_maj
--

CREATE OR REPLACE TABLE zfdbproto.prm_sta_maj (
  id integer NOT NULL 
     GENERATED ALWAYS AS IDENTITY 
     ( START WITH 1 , INCREMENT BY 1 , CACHE 2 ),
  code char(3) NOT NULL WITH DEFAULT,
  libelle char(40) NOT NULL WITH DEFAULT
) ;

--
-- Déchargement des données de la table prm_sta_maj
--

INSERT INTO zfdbproto.prm_sta_maj (id, code, libelle) OVERRIDING USER VALUE VALUES
(2, 'ATT', 'En attente'),
(1, 'ENC', 'Encours'),
(3, 'TER', 'Terminé');

-- --------------------------------------------------------

--
-- Structure de la table prm_typ_sld
--

CREATE OR REPLACE TABLE zfdbproto.prm_typ_sld (
  id integer NOT NULL 
     GENERATED ALWAYS AS IDENTITY 
     ( START WITH 1 , INCREMENT BY 1 , CACHE 2 ),
  code char(3) NOT NULL WITH DEFAULT,
  libelle char(40) NOT NULL WITH DEFAULT
) ;

--
-- Déchargement des données de la table prm_typ_sld
--

INSERT INTO zfdbproto.prm_typ_sld (id, code, libelle) OVERRIDING USER VALUE VALUES
(2, 'C', 'Créditeur'),
(1, 'D', 'Débiteur'),
(4, 'I', 'Injustifié'),
(3, 'S', 'Soldé');

-- --------------------------------------------------------

--
-- Structure de la table users
--

CREATE OR REPLACE TABLE zfdbproto.users (
  id integer NOT NULL 
     GENERATED ALWAYS AS IDENTITY 
     ( START WITH 1 , INCREMENT BY 1 , CACHE 2 ),
  first_name varchar(32) NOT NULL,
  last_name varchar(32) NOT NULL,
  email varchar(64) NOT NULL,
  username varchar(32) NOT NULL,
  password varchar(32) NOT NULL
) ;

--
-- Déchargement des données de la table users
--

INSERT INTO zfdbproto.users (id, first_name, last_name, email, username, password) OVERRIDING USER VALUE VALUES
(1, '', '', '', 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229'),
(2, '', '', '', 'admin', '21232f297a57a5a743894a0e4a801fc3');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table dossier
--
CREATE INDEX zfdbproto.dossierL01 ON zfdbproto.dossier
  (societe_id,id) ;
CREATE INDEX zfdbproto.dossierL02 ON zfdbproto.dossier
  (societe_id,dossier_ref,id) ;
CREATE INDEX zfdbproto.dossierL03 ON zfdbproto.dossier
  (societe_id,personne_id,id) ;

--
-- Index pour la table prm_cod_ape
--
CREATE INDEX zfdbproto.prm_cod_apeL01 ON zfdbproto.prm_cod_ape
  (code,id) ;
CREATE INDEX zfdbproto.prm_cod_apeL02 ON zfdbproto.prm_cod_ape
  (libelle,code,id) ;

--
-- Index pour la table prm_cod_soc
--
CREATE INDEX zfdbproto.prm_cod_socL01 ON zfdbproto.prm_cod_soc
  (code,id) ;
CREATE INDEX zfdbproto.prm_cod_socL02 ON zfdbproto.prm_cod_soc
  (libelle,code,id) ;

--
-- Index pour la table prm_eta_jur
--
CREATE INDEX zfdbproto.prm_eta_jurL01 ON zfdbproto.prm_eta_jur
  (code,id) ;
CREATE INDEX zfdbproto.prm_eta_jurL02 ON zfdbproto.prm_eta_jur
  (libelle,code,id) ;

--
-- Index pour la table prm_personne
--
CREATE INDEX zfdbproto.prm_personneL01 ON zfdbproto.prm_personne
  (code,id) ;
CREATE INDEX zfdbproto.prm_personneL02 ON zfdbproto.prm_personne
  (libelle,code,id) ;

--
-- Index pour la table prm_prd_fin
--
CREATE INDEX zfdbproto.prm_prd_finL01 ON zfdbproto.prm_prd_fin
  (code,id) ;
CREATE INDEX zfdbproto.prm_prd_finL02 ON zfdbproto.prm_prd_fin
  (libelle,code,id) ;

--
-- Index pour la table prm_sta_maj
--
CREATE INDEX zfdbproto.prm_sta_majL01 ON zfdbproto.prm_sta_maj
  (code,id) ;
CREATE INDEX zfdbproto.prm_sta_majL02 ON zfdbproto.prm_sta_maj
  (libelle,code,id) ;

--
-- Index pour la table prm_typ_sld
--
CREATE INDEX zfdbproto.prm_typ_sldL01 ON zfdbproto.prm_typ_sld
  (code,id) ;
CREATE INDEX zfdbproto.prm_typ_sldL02 ON zfdbproto.prm_typ_sld
  (libelle,code,id) ;

--
-- Déchargement des données de la table dossier
--

INSERT INTO zfdbproto.dossier (id, societe_id, dossier_ref, personne_id, cde_ape, cde_eta_jur, cde_fam_prd, statut, typ_mat_id, fam_ctx_id, chgt_doss, cd_etat_cpt, encours_mnt, solde_brut, top_cloture) OVERRIDING USER VALUE VALUES
(1, 1, '060DA610', 40, '001', 'CTX', '', 'ENC', '', '006', '2011-03-22', 'D', '5922.48', '281.33', ''),
delete from zfdbproto.dossier;

INSERT INTO zfdbproto.dossier (id, societe_id, dossier_ref, personne_id, cde_ape, cde_eta_jur, cde_fam_prd, statut, typ_mat_id, fam_ctx_id, chgt_doss, cd_etat_cpt, encours_mnt, solde_brut, top_cloture) OVERRIDING USER VALUE VALUES
(2, 1, '380FT610', 21, '', 'CTX', '', 'ENC', '34102501', '002', '2004-06-01', 'D', '11326.61', '957.07', ''),
(3, 3, '950DA678', 361, '', 'CTX', '', 'ENC', '341021', '001', '2004-11-29', 'D', '20113.09', '682.25', ''),
(4, 3, '480BT574', 239, '', 'CTX', '', 'ENC', '34102501', '002', '2002-01-22', 'D', '11293.18', '324.13', ''),
(5, 3, 'AB236543', 115, '', 'CTX', '', 'ENC', '', '002', '2005-07-25', 'D', '10790.73', '968.27', ''),
(6, 1, '370CT425', 354, '', 'CTX', '', 'ENC', '341021', '001', '2000-12-28', 'D', '10972.75', '509.92', ''),
(7, 1, '430JT108', 427, '', 'CTX', '', 'ENC', '34102503', '002', '2004-06-01', 'D', '4068.77', '929.07', ''),
(8, 3, 'K2207297', 70, '', 'CTX', '', 'ENC', '35411201', '003', '2002-10-29', 'D', '1450.66', '83.08', ''),
(9, 3, 'K2203479', 71, '', 'CTX', '', 'ENC', '354112', '003', '2003-01-21', 'D', '7591.04', '136.93', ''),
(10, 3, 'H0110324', 144, '', 'CTX', '', 'ENC', '34102503', '002', '2005-09-27', 'D', '6524.53', '813.89', ''),
(11, 3, 'AB233458', 5, '', 'CTX', '', 'ENC', '34102502', '002', '2002-05-15', 'D', '11886.63', '256.50', ''),
(12, 3, '940BB130', 93, '', 'CTX', '', 'ENC', '341021', '001', '2004-06-09', 'D', '10003.62', '488.61', ''),
(13, 3, '910JA218', 448, '', 'CTX', '', 'ENC', '', '007', '2004-11-30', 'D', '13192.96', '960.00', ''),
(14, 1, '070AT246', 461, '', 'CTX', '', 'ENC', '', '007', '2000-11-23', 'D', '6166.51', '862.56', ''),
(15, 1, '250DT690', 462, '', 'CTX', '', 'ENC', '', '007', '2004-06-01', 'D', '5479.08', '738.71', ''),
(16, 1, '860CO019', 427, '', 'LJ', '', 'ENC', '331015', '010', '2004-06-01', 'D', '234155.53', '257.49', ''),
(17, 1, '130BO079', 247, '', 'LJ', '', 'ENC', '295230', '010', '2005-10-10', 'D', '29866.06', '188.84', ''),
(18, 3, '116CO058', 453, '', 'LJ', '', 'ENC', '341043', '008', '2002-03-13', 'D', '6044.20', '230.48', ''),
(19, 1, '880IO278', 25, '', 'CTX', '', 'ENC', '299999', '010', '2004-06-01', 'D', '40414.82', '644.65', ''),
(20, 1, '480MC044', 266, '', 'CTX', '', 'ENC', '299999', '010', '2004-06-01', 'D', '34166.38', '178.95', ''),
(21, 1, '360LS031', 252, '', 'LJ', '', 'ENC', '299999', '010', '2004-06-01', 'D', '33916.23', '538.28', ''),
(22, 1, '360LS019', 464, '', 'CTX', '', 'ENC', '299999', '010', '2004-06-01', 'D', '14334.50', '611.68', ''),
(23, 1, '360BO103', 63, '', 'CTX', '', 'ENC', '293122', '009', '2005-10-11', 'D', '56687.08', '762.39', ''),
(24, 1, '060BO037', 423, '', 'LJ', '', 'ENC', '341044', '008', '2000-03-31', 'D', '23666.88', '975.23', ''),
(25, 1, '060AO005', 426, '', 'LJ', '', 'ENC', '341043', '008', '2004-12-09', 'D', '9038.13', '677.93', ''),
(26, 1, '730HO048', 361, '', 'CTX', '', 'ENC', '299999', '010', '2004-06-01', 'D', '25674.68', '78.60', ''),
(27, 1, '730CO015', 27, '', 'CTX', '', 'ENC', '341044', '008', '2004-06-01', 'D', '33672.55', '321.95', ''),
(28, 1, '440LI011', 51, '', 'LJ', '', 'ENC', '341044', '008', '2004-06-01', 'D', '9512.90', '389.67', ''),
(29, 1, '280OG051', 174, '', 'CTX', '', 'ENC', '341044', '008', '2004-06-01', 'D', '2840.84', '481.18', ''),
(30, 1, '280HO018', 214, '', 'CTX', '', 'ENC', '295230', '010', '2004-06-01', 'D', '25564.39', '615.42', ''),
(31, 1, '125AO004', 50, '', 'LJ', '', 'ENC', '294011', '010', '2000-02-21', 'D', '9556.33', '352.37', ''),
(32, 1, '050FO352', 108, '', 'CTX', '', 'ENC', '295316', '010', '2004-06-01', 'D', '15073.67', '116.25', ''),
(33, 1, '050FO240', 387, '', 'RJ', '', 'ENC', '293121', '009', '2004-06-01', 'D', '6468.43', '982.41', ''),
(34, 1, '050EO212', 113, '', 'RJ', '', 'ENC', '293121', '009', '2004-06-01', 'D', '8007.74', '659.47', ''),
(35, 1, '050BO074', 401, '', 'RJ', '', 'ENC', '293122', '009', '2000-12-01', 'D', '14086.74', '658.28', ''),
(36, 1, '050BO055', 165, '', 'RJ', '', 'ENC', '293121', '009', '2000-12-01', 'D', '11536.79', '557.09', ''),
(37, 1, '640KO033', 121, '', 'CTX', '', 'ENC', '341044', '008', '2004-06-01', 'D', '52686.11', '635.27', ''),
(38, 1, '460BO024', 111, '', 'LJ', '', 'ENC', '294011', '010', '2002-01-28', 'D', '9088.41', '46.23', ''),
(39, 1, '450MO082', 193, '', 'RJ', '', 'ENC', '341044', '008', '2004-06-01', 'D', '45244.75', '91.06', ''),
(40, 1, '430AO028', 129, '', 'RJ', '', 'ENC', '341044', '008', '2005-01-15', 'D', '6549.23', '273.96', ''),
(41, 1, '250NV079', 67, '', 'CTX', '', 'ENC', '294011', '010', '2004-06-01', 'D', '31504.92', '360.73', ''),
(42, 1, '070OX196', 447, '', 'CTX', '', 'ENC', '293122', '009', '2004-06-01', 'D', '27958.90', '363.30', ''),
(43, 1, '070MZ135', 32, '', 'CTX', '', 'ENC', '341044', '008', '2004-06-01', 'D', '20479.59', '626.06', ''),
(44, 1, '040JO063', 321, '', 'CTX', '', 'ENC', '341044', '008', '2004-06-01', 'D', '22700.93', '620.47', ''),
(45, 1, '040DO183', 9, '', 'RJ', '', 'ENC', '341044', '008', '2004-06-01', 'D', '3526.96', '139.40', ''),
(46, 1, '770CO142', 81, '', 'CTX', '', 'ENC', '294011', '010', '2004-06-01', 'D', '19851.13', '999.43', ''),
(47, 1, '330IO112', 378, '', 'LJ', '', 'ENC', '292217', '010', '2004-06-01', 'D', '18461.15', '774.48', ''),
(48, 1, '330AO117', 145, '', 'RJ', '', 'ENC', '341044', '008', '2000-12-01', 'D', '44564.15', '700.30', ''),
(49, 1, '190MZ062', 93, '', 'CTX', '', 'ENC', '299999', '010', '2004-06-01', 'D', '7524.25', '320.35', ''),
(50, 1, '190BO053', 29, '', 'LJ', '', 'ENC', '295230', '010', '2000-07-07', 'D', '45237.42', '734.02', ''),
(51, 1, 'K2204234', 364, '', 'RJ', '', 'ENC', '29221301', '010', '2000-11-13', 'D', '15375.64', '73.13', ''),
(52, 1, '670HO034', 234, '', 'CTX', '', 'ENC', '341044', '008', '2004-06-01', 'D', '46216.16', '708.38', ''),
(53, 1, '670DO073', 75, '', 'CTX', '', 'ENC', '294011', '010', '2004-06-01', 'D', '9489.62', '815.99', ''),
(54, 1, '610FO005', 175, '', 'LJ', '', 'ENC', '292217', '010', '2004-06-01', 'D', '73812.67', '128.37', ''),
(55, 1, '350LK225', 149, '', 'CTX', '', 'ENC', '341044', '008', '2004-06-01', 'D', '7352.45', '25.13', ''),
(56, 1, '350LK224', 219, '', 'CTX', '', 'ENC', '299999', '010', '2004-06-01', 'D', '8082.30', '272.11', ''),
(57, 1, '130JO097', 148, '', 'CTX', '', 'ENC', '341044', '008', '2004-06-01', 'D', '19617.45', '918.93', ''),
(58, 1, '130FO022', 83, '', 'RJ', '', 'ENC', '295316', '010', '2004-06-01', 'D', '40597.68', '40.62', ''),
(59, 1, '130AO018', 470, '', 'PRE', '', 'ENC', '341044', '008', '2005-08-30', 'D', '6414.08', '416.53', ''),
(60, 1, '440LI016', 100, '', 'CTX', '', 'ENC', '299999', '010', '2004-06-01', 'D', '107454.79', '538.39', ''),
(61, 1, '630EO010', 89, '', 'RJ', '', 'ENC', '294011', '010', '2004-06-01', 'D', '65090.16', '465.64', ''),
(62, 1, '270NJ054', 145, '', 'CTX', '', 'ENC', '299999', '010', '2004-06-01', 'D', '13249.71', '971.64', ''),
(63, 1, '270BO078', 459, '', 'RJ', '', 'ENC', '295316', '010', '2004-06-01', 'D', '6034.05', '295.35', ''),
(64, 1, '240MZ076', 359, '', 'CTX', '', 'ENC', '295316', '010', '2004-06-01', 'D', '13876.40', '801.95', ''),
(65, 1, '119AO019', 417, '', 'LJ', '', 'ENC', '341043', '008', '2000-04-10', 'D', '31454.96', '593.92', ''),
(66, 1, '100HO079', 6, '', 'CTX', '', 'ENC', '299999', '010', '2004-06-01', 'D', '19260.95', '41.97', ''),
(67, 1, '470MZ182', 279, '', 'CTX', '', 'ENC', '295230', '010', '2004-06-01', 'D', '48928.99', '382.05', ''),
(68, 1, '200NJ045', 375, '', 'CTX', '', 'ENC', '299999', '010', '2004-06-01', 'D', '10453.70', '866.32', ''),
(69, 1, '200LI113', 39, '', 'CTX', '', 'ENC', '295230', '010', '2004-06-01', 'D', '35746.72', '41.63', ''),
(70, 1, '180OX022', 69, '', 'CTX', '', 'ENC', '341044', '008', '2004-06-01', 'D', '11144.98', '909.04', ''),
(71, 1, 'J0124502', 230, '', 'LJ', '', 'ENC', '341044', '008', '2005-12-11', 'D', '24716.04', '932.10', ''),
(72, 3, 'J0133964', 441, '', 'LJ', '', 'ENC', '', '000', '2002-01-10', 'D', '138705.37', '528.54', ''),
(73, 1, 'AC188095', 13, '', 'PRE', '', 'ENC', '34102501', '008', '2002-03-11', 'D', '23459.41', '613.43', ''),
(74, 3, 'J0188526', 241, '', 'PRE', '', 'ENC', '34102501', '002', '2004-06-09', 'D', '29561.13', '883.35', ''),
(75, 1, '330BO059', 168, '', 'PRE', '', 'ENC', '341044', '008', '2002-03-01', 'D', '1577.75', '272.70', ''),
(76, 1, 'AC192752', 115, '', 'PRE', '', 'ENC', '295230', '010', '2002-03-14', 'D', '11282.85', '171.17', ''),
(77, 1, '050DO245', 73, '', 'PRE', '', 'ENC', '293122', '009', '2002-01-02', 'D', '11339.10', '752.98', ''),
(78, 3, 'J0122216', 17, '', 'CTX', '', 'ENC', '35411201', '003', '2004-06-08', 'D', '12675.20', '781.06', ''),
(79, 1, '060AO056', 366, '', 'LJ', '', 'ENC', '293122', '009', '2002-04-10', 'D', '7130.52', '58.40', ''),
(80, 1, '060AO052', 279, '', 'LJ', '', 'ENC', '293122', '009', '2002-04-02', 'D', '8357.23', '999.95', ''),
(81, 1, 'K2248539', 298, '', 'LJ', '', 'ENC', '29531501', '010', '2002-08-23', 'D', '61203.65', '518.41', ''),
(82, 3, 'I0104532', 150, '', 'CTX', '', 'ENC', '', '007', '2004-04-09', 'D', '6686.09', '805.24', ''),
(83, 1, 'I0104988', 356, '', 'RJ', '', 'ENC', '341021', '008', '2003-01-12', 'D', '14291.51', '638.84', ''),
(84, 1, 'AB233641', 328, '', 'CTX', '', 'ENC', '293121', '009', '2005-09-28', 'D', '28551.08', '865.85', ''),
(85, 3, 'Y2198156', 73, '', 'CTX', '', 'ENC', '354111', '003', '2003-01-06', 'D', '2494.63', '706.60', ''),
(86, 1, 'K0083879', 382, '', 'CTX', '', 'ENC', '34102501', '002', '2003-01-06', 'S', '27601.93', '612.82', ''),
(87, 1, '060DO125', 188, '', 'RJ', '', 'ENC', '293122', '009', '2003-05-26', 'D', '8751.11', '264.11', ''),
(88, 1, 'J0202720', 294, '001', 'LJ', '', 'ENC', '341021', '008', '2003-07-29', 'D', '36641.39', '452.64', ''),
(89, 1, 'V2495399', 406, '', 'CTX', '', 'ENC', '74111421', '000', '2004-11-10', 'D', '74224.82', '208.20', ''),
(90, 1, 'V2463141', 148, '', 'RJ', '', 'ENC', '342023', '008', '2002-08-10', 'D', '2955.93', '832.05', ''),
(91, 1, 'V2463140', 23, '', 'RJ', '', 'ENC', '342023', '008', '2002-10-10', 'D', '1586.27', '955.21', ''),
(92, 1, 'V2463139', 170, '', 'RJ', '', 'ENC', '342023', '008', '2002-03-10', 'D', '5377.84', '665.54', ''),
(93, 1, 'V2415518', 279, '', 'RJ', '', 'ENC', '74111421', '000', '2005-07-20', 'D', '77730.21', '23.42', ''),
(94, 1, 'V2486923', 384, '', 'LJ', '', 'ENC', '45400001', '000', '2005-04-30', 'D', '21580.07', '380.08', ''),
(95, 1, 'V2486922', 83, '', 'LJ', '', 'ENC', '45400001', '000', '2005-03-10', 'D', '3993.40', '995.78', ''),
(96, 1, 'AC191776', 261, '', 'LJ', '', 'ENC', '292421', '010', '2004-08-21', 'D', '2418.07', '529.01', ''),
(97, 1, 'AC176801', 55, '', 'LJ', '', 'ENC', '295111', '010', '2004-03-13', 'D', '3838.07', '49.29', ''),
(98, 1, 'V2471854', 493, '', 'LJ', '', 'ENC', '74111421', '000', '2002-11-26', 'D', '49954.26', '110.41', ''),
(99, 1, 'V2469216', 298, '', 'LJ', '', 'ENC', '74111421', '000', '2002-11-21', 'D', '174557.29', '614.22', ''),
(100, 2, 'V2445120', 12, '', '', '', 'ENC', '74111422', '000', '1998-11-19', 'D', '171570.23', '681.51', ''),
(101, 2, 'V2439545', 166, '', 'RJ', '', 'ENC', '29312301', '009', '2003-01-28', 'D', '15890.03', '410.62', ''),
(102, 2, 'V2405114', 295, '', 'LJ', '', 'ENC', '74111421', '000', '1995-03-13', 'D', '110788.61', '628.22', ''),
(103, 2, 'KM140770', 474, '', 'CTX', '', 'ENC', '29312301', '009', '2003-01-06', 'D', '63223.83', '235.52', ''),
(104, 2, 'KM120457', 484, '', 'LJ', '', 'ENC', '293265', '009', '2002-10-25', 'D', '10379.91', '765.99', ''),
(105, 2, 'V2490253', 498, '', '', '', 'ENC', '29326201', '009', '2002-11-08', 'D', '6938.22', '449.11', ''),
(106, 2, 'V2495246', 37, '', 'RJ', '', 'ENC', '29326301', '009', '2002-02-21', 'D', '7200.11', '599.20', ''),
(107, 2, 'V2495245', 191, '', 'RJ', '', 'ENC', '29326201', '009', '2005-05-31', 'D', '7548.28', '332.79', ''),
(108, 2, 'V2484222', 344, '', 'RJ', '', 'ENC', '293265', '009', '2005-03-31', 'D', '2545.52', '885.22', ''),
(109, 2, 'V2474138', 146, '', 'LJ', '', 'ENC', '74111421', '000', '2004-12-09', 'D', '2987.34', '844.06', ''),
(110, 2, 'V2472625', 197, '', 'RJ', '', 'ENC', '293265', '009', '2000-12-01', 'D', '7800.86', '65.12', ''),
(111, 2, 'V2471907', 48, '', 'LJ', '', 'ENC', '29312301', '009', '2000-01-20', 'D', '17419.44', '560.85', ''),
(112, 2, 'V2470950', 146, '', 'LJ', '', 'ENC', '74111421', '000', '2002-11-12', 'D', '57762.31', '75.01', ''),
(113, 2, 'V2468727', 89, '', 'CTX', '', 'ENC', '29323301', '009', '2003-01-06', 'D', '17092.10', '312.48', ''),
(114, 2, 'V2454510', 3, '', 'RJ', '', 'ENC', '293262', '009', '2004-10-05', 'D', '17612.30', '242.05', ''),
(115, 2, 'V2454509', 249, '', 'RJ', '', 'ENC', '293262', '009', '2004-10-05', 'D', '29114.16', '746.64', ''),
(116, 2, 'V2454388', 236, '', 'RJ', '', 'ENC', '293265', '009', '1998-12-29', 'D', '2485.51', '22.65', '');



--
-- Déchargement des données de la table prm_personne
--

INSERT INTO zfdbproto.prm_personne (id, code, libelle) OVERRIDING USER VALUE VALUES
(40, '40', 'Adrianne Grolma'),
(351, '351', 'Agretha Ginger'),
(443, '443', 'Aileen Point'),
(208, '208', 'Ainslee Putland'),
(317, '317', 'Aland Mathiasse'),
(297, '297', 'Alastair Pethic'),
(8, '8', 'Aleta Phoebe'),
(227, '227', 'Alex Dumbrill'),
(77, '77', 'Alexandro Opden'),
(274, '274', 'Algernon Getcli'),
(26, '26', 'Alidia Scutchin'),
(388, '388', 'Allan Deakes'),
(69, '69', 'Almeda Yeabsley'),
(263, '263', 'Amelia Astbery'),
(75, '75', 'Amelina Greenst'),
(147, '147', 'Ammamaria Casbo'),
(236, '236', 'Anabella Ancell'),
(392, '392', 'Anastasia Fisby'),
(278, '278', 'Andeee Rosenbla'),
(42, '42', 'Andrey Pitceath'),
(430, '430', 'Angil Frude'),
(141, '141', 'Anthe Dutt'),
(231, '231', 'Antonella Sexto'),
(164, '164', 'Antoni Hayen'),
(331, '331', 'Antonia Salamon'),
(265, '265', 'Antony Coghill'),
(250, '250', 'Ardeen Ccomini'),
(373, '373', 'Arin Klyn'),
(189, '189', 'Arleen Deetlof'),
(221, '221', 'Arlina Wilsher'),
(420, '420', 'Artair Borlease'),
(216, '216', 'Artie Hartmann'),
(321, '321', 'Ashli Brusle'),
(185, '185', 'Audi Petri'),
(284, '284', 'Audry Wallbrook'),
(484, '484', 'Averil Le Sarr'),
(371, '371', 'Avictor Blinder'),
(4, '4', 'Baillie McKinle'),
(362, '362', 'Bald Standering'),
(191, '191', 'Bard Krienke'),
(12, '12', 'Barnaby De Mitr'),
(213, '213', 'Barney Bowgen'),
(394, '394', 'Barny Greeson'),
(459, '459', 'Bartel Ogus'),
(428, '428', 'Bartie Ransom'),
(101, '101', 'Barton de Courc'),
(458, '458', 'Beau Embra'),
(247, '247', 'Belicia Lashbro'),
(60, '60', 'Beltran Wingeat'),
(485, '485', 'Beniamino Firid'),
(115, '115', 'Benny Guiness'),
(202, '202', 'Bentley Busen'),
(66, '66', 'Bernie Bradick'),
(496, '496', 'Berton Dunton'),
(472, '472', 'Biddie Wenham'),
(486, '486', 'Bradney Fisher'),
(58, '58', 'Bree Towersey'),
(50, '50', 'Brien Rosbrough'),
(97, '97', 'Brooke Buesnel'),
(408, '408', 'Buddie Ofer'),
(230, '230', 'Buddy Cranson'),
(25, '25', 'Cacilia Spain-G'),
(377, '377', 'Calida Jacobs'),
(449, '449', 'Cally Scarsbric'),
(179, '179', 'Camille Riatt'),
(328, '328', 'Cammie Sket'),
(178, '178', 'Candie Cale'),
(281, '281', 'Candie Tuson'),
(267, '267', 'Caralie Oxbie'),
(376, '376', 'Caren Labuschag'),
(293, '293', 'Carena Chadd'),
(348, '348', 'Carita Dymick'),
(222, '222', 'Carlene Lyness'),
(383, '383', 'Carly Ivetts'),
(35, '35', 'Carlyle Whitten'),
(299, '299', 'Carmine Spandle'),
(142, '142', 'Carney Madgett'),
(87, '87', 'Carolyn Muris'),
(336, '336', 'Carrie Stark'),
(215, '215', 'Caryn Churchman'),
(176, '176', 'Case Tenniswood'),
(173, '173', 'Casi Addy'),
(435, '435', 'Casi Dybald'),
(196, '196', 'Casie Offill'),
(181, '181', 'Ced Nannini'),
(62, '62', 'Charles McIntee'),
(204, '204', 'Charlton Burnag'),
(206, '206', 'Cherrita Griffi'),
(497, '497', 'Chip Gilroy'),
(365, '365', 'Cindy Durnan'),
(423, '423', 'Cirilo Briatt'),
(33, '33', 'Clarance Winchc'),
(426, '426', 'Clark Fozard'),
(136, '136', 'Claudetta Witch'),
(73, '73', 'Claudianus Jost'),
(85, '85', 'Clementius Iann'),
(56, '56', 'Cleveland Hartn'),
(158, '158', 'Clevie Jamrowic'),
(354, '354', 'Cobby Burd'),
(83, '83', 'Cody Kubec'),
(113, '113', 'Colan Morrel'),
(326, '326', 'Colby Lockner'),
(88, '88', 'Colette Andren'),
(345, '345', 'Corri Elmes'),
(180, '180', 'Corrie Brantl'),
(402, '402', 'Cristen Danford'),
(271, '271', 'Cristen Kelling'),
(232, '232', 'Cybil Skey'),
(325, '325', 'Dacey Dallywate'),
(277, '277', 'Dacia Ashdown'),
(135, '135', 'Dalila Shoobrid'),
(364, '364', 'Dalli Eighteen'),
(100, '100', 'Dana Collman'),
(337, '337', 'Dani Aveling'),
(235, '235', 'Dannye Addess'),
(228, '228', 'Darlleen Riggs'),
(183, '183', 'Darryl Kittiman'),
(116, '116', 'Deana Bakster'),
(286, '286', 'Debbi Dysart'),
(106, '106', 'Decca Carwardin'),
(368, '368', 'Deedee Bouttell'),
(405, '405', 'Delainey Spehr'),
(456, '456', 'Demetre Manwell'),
(492, '492', 'Denis MacDuff'),
(264, '264', 'Derby Staniford'),
(234, '234', 'Derril Tumbridg'),
(150, '150', 'Devlen Bateson'),
(24, '24', 'Dianemarie Broo'),
(480, '480', 'Dierdre Lambal'),
(219, '219', 'Dillon Livens'),
(198, '198', 'Dimitry Carnili'),
(311, '311', 'Dinnie Rosborou'),
(462, '462', 'Dody Paunton'),
(269, '269', 'Donica Laming'),
(433, '433', 'Donielle McMurr'),
(315, '315', 'Donovan Willcot'),
(21, '21', 'Dora Sunley'),
(27, '27', 'Dori Terzo'),
(421, '421', 'Dorian Kolis'),
(358, '358', 'Dorolice Wylam'),
(280, '280', 'Dorothea Grabb'),
(55, '55', 'Dorothy Duffy'),
(63, '63', 'Dru Camier'),
(91, '91', 'Duffy Bryce'),
(301, '301', 'Duke Halleybone'),
(95, '95', 'Dulcea O''Cullin'),
(186, '186', 'Dunc Gifford'),
(481, '481', 'Dusty Biermatow'),
(233, '233', 'Eadie Staton'),
(303, '303', 'Early Hantusch'),
(341, '341', 'Egbert Compston'),
(201, '201', 'Eilis Lewens'),
(224, '224', 'Eldredge Gasken'),
(342, '342', 'Eldridge Meaney'),
(478, '478', 'Elizabet Szymon'),
(240, '240', 'Eloisa Satterle'),
(455, '455', 'Elyssa Shorter'),
(122, '122', 'Emile Vinden'),
(309, '309', 'Emlynn Baudains'),
(464, '464', 'Emlynn Widger'),
(429, '429', 'Engelbert Benoi'),
(18, '18', 'Ephrem Campbell'),
(220, '220', 'Erena Cowerd'),
(217, '217', 'Erin Piatto'),
(479, '479', 'Erinn Stather'),
(64, '64', 'Ernaline Romayn'),
(134, '134', 'Esma Sterling'),
(367, '367', 'Esmaria Lias'),
(395, '395', 'Estevan Barnish'),
(80, '80', 'Eugene Dilston'),
(410, '410', 'Ev Cockerton'),
(320, '320', 'Evangelina Kitt'),
(437, '437', 'Evelyn Grcic'),
(287, '287', 'Fayina Stockney'),
(453, '453', 'Ferdinande Crai'),
(127, '127', 'Filbert Gledsta'),
(312, '312', 'Filbert Randall'),
(30, '30', 'Filberte Dreoss'),
(125, '125', 'Filmore Wainman'),
(343, '343', 'Florella Lacrou'),
(2, '2', 'Francisca Casil'),
(17, '17', 'Fredrick McWhir'),
(361, '361', 'Friedrich Harse'),
(380, '380', 'Gabbie Geck'),
(169, '169', 'Gael Seamons'),
(171, '171', 'Galina Lemmertz'),
(352, '352', 'Galvin Jelley'),
(468, '468', 'Garner Tappin'),
(99, '99', 'Garret Ricci'),
(487, '487', 'Gay Buxcy'),
(153, '153', 'Georgeanna Bohl'),
(146, '146', 'Geralda Reyner'),
(110, '110', 'Giacopo Jorio'),
(15, '15', 'Gianina Jeannes'),
(166, '166', 'Gilberte Zarfat'),
(118, '118', 'Gilberto Lurrim'),
(324, '324', 'Giles Sneyd'),
(105, '105', 'Ginny Skoggings'),
(401, '401', 'Gino Twelves'),
(167, '167', 'Gran Grzegorczy'),
(212, '212', 'Granny Reucastl'),
(490, '490', 'Gretna Davidavi'),
(143, '143', 'Gussy Legon'),
(489, '489', 'Had Halgarth'),
(131, '131', 'Hailey Reymers'),
(163, '163', 'Hally Scading'),
(476, '476', 'Hamlen Cribbin'),
(425, '425', 'Harmon Whitwort'),
(469, '469', 'Harwilll Merigo'),
(48, '48', 'Heath Fritzer'),
(72, '72', 'Henrietta Santi'),
(84, '84', 'Hermie O''Lougha'),
(74, '74', 'Herrick Gindghi'),
(3, '3', 'Hilda Spera'),
(248, '248', 'Hillary Woodley'),
(102, '102', 'Hillel Ranshaw'),
(107, '107', 'Hillie Bockh'),
(252, '252', 'Holmes Augur'),
(11, '11', 'Horatia Cookson'),
(424, '424', 'Hurlee Walmsley'),
(187, '187', 'Ilario Hessenta'),
(482, '482', 'Imogen Odgaard'),
(419, '419', 'Indira Rothery'),
(36, '36', 'Iris Teggart'),
(333, '333', 'Irma Denisyuk'),
(211, '211', 'Isa Gare'),
(5, '5', 'Isabelita Rober'),
(259, '259', 'Issi Hale'),
(152, '152', 'Ivonne Bruinema'),
(237, '237', 'Izzy Byham'),
(349, '349', 'Jaime Earp'),
(154, '154', 'Jakob Whybrow'),
(104, '104', 'Jamima Vanne'),
(327, '327', 'Jasmine Letts'),
(330, '330', 'Jeddy Coleyshaw'),
(470, '470', 'Jehu Skrines'),
(257, '257', 'Jerrold Kmiecia'),
(473, '473', 'Jessika Cato'),
(195, '195', 'Jilli Stonall'),
(1, '1', 'Joaquin Danilon'),
(416, '416', 'Joby Duffit'),
(238, '238', 'Jodi Brimm'),
(174, '174', 'Jodie Duling'),
(488, '488', 'Jolene Fanshaw'),
(94, '94', 'Joletta Boig'),
(451, '451', 'Jordanna Borsi'),
(353, '353', 'Josi Imms'),
(495, '495', 'Joyce Billyeald'),
(53, '53', 'Joycelin Brimne'),
(340, '340', 'Julie Toffaloni'),
(441, '441', 'Justinian Madse'),
(305, '305', 'Jyoti Wrist'),
(45, '45', 'Kaine Folks'),
(254, '254', 'Kaiser Eyeingto'),
(475, '475', 'Kaja Mazella'),
(229, '229', 'Kala Pepys'),
(92, '92', 'Kaleena Dodge'),
(440, '440', 'Kalli Steynor'),
(138, '138', 'Kamillah Dando'),
(273, '273', 'Kanya Helleker'),
(31, '31', 'Karina Meijer'),
(307, '307', 'Karola Doe'),
(86, '86', 'Kassie Swadon'),
(258, '258', 'Katalin Oulet'),
(70, '70', 'Kath Attwell'),
(431, '431', 'Katrine Hatchet'),
(177, '177', 'Kayne Stansbury'),
(214, '214', 'Kean Iceton'),
(170, '170', 'Kerrin MacIan'),
(137, '137', 'Kessiah Kiljan'),
(108, '108', 'Killie Lawdham'),
(499, '499', 'Kimberley De Fe'),
(288, '288', 'Kirby Speke'),
(409, '409', 'Kristine Seabou'),
(199, '199', 'Kurtis de Juare'),
(323, '323', 'Laurena Disney'),
(184, '184', 'Leena Epilet'),
(81, '81', 'Lemuel Leyland'),
(132, '132', 'Lenee Malam'),
(145, '145', 'Leoine Heliet'),
(266, '266', 'Leon Knightsbri'),
(389, '389', 'Leonelle Truder'),
(466, '466', 'Leopold Othen'),
(57, '57', 'Letti Imlock'),
(261, '261', 'Lila Ritter'),
(375, '375', 'Lilia Wyldbore'),
(182, '182', 'Llywellyn Peril'),
(128, '128', 'Lois Heed'),
(406, '406', 'Lon Roke'),
(313, '313', 'Loralie Matias'),
(335, '335', 'Loreen Guinane'),
(397, '397', 'Lorene Casillis'),
(223, '223', 'Lorens Burdass'),
(385, '385', 'Lothaire Asplin'),
(357, '357', 'Louie Erlam'),
(20, '20', 'Louisa Skuse'),
(218, '218', 'Lucila Nyland'),
(249, '249', 'Lynde Durnell'),
(207, '207', 'Lyssa Dungee'),
(260, '260', 'Madeleine Heinz'),
(384, '384', 'Mady Reyner'),
(439, '439', 'Maggy Valti'),
(438, '438', 'Mahala Kingshot'),
(390, '390', 'Mahmud Hovey'),
(457, '457', 'Maia Reams'),
(381, '381', 'Malva Niccolls'),
(477, '477', 'Maren Heild'),
(148, '148', 'Margarita Palle'),
(165, '165', 'Marsh Cansfield'),
(272, '272', 'Martica Wilsher'),
(155, '155', 'Maryanna Morteo'),
(246, '246', 'Maryellen Dilks'),
(29, '29', 'Maryrose Goodge'),
(111, '111', 'Marys Batten'),
(332, '332', 'Massimo Barbier'),
(243, '243', 'Massimo Bruhnke'),
(319, '319', 'Mathe Borham'),
(65, '65', 'Mattias Sirmond'),
(356, '356', 'Max Loudon'),
(434, '434', 'Meara Belamy'),
(241, '241', 'Mel Norval'),
(378, '378', 'Mellicent Fisbe'),
(133, '133', 'Mellie Danby'),
(52, '52', 'Melloney Bucker'),
(32, '32', 'Meredeth Attfie'),
(355, '355', 'Mersey Cotman'),
(314, '314', 'Meryl Spaughton'),
(370, '370', 'Micky Tawton'),
(98, '98', 'Mimi Style'),
(38, '38', 'Mirella Skeermo'),
(270, '270', 'Mirelle Claywor'),
(393, '393', 'Mirna Chomicz'),
(411, '411', 'Mitchell Greger'),
(374, '374', 'Mohandas Pinnoc'),
(491, '491', 'Monah Matley'),
(149, '149', 'Monica Sabatier'),
(61, '61', 'Morry Perassi'),
(262, '262', 'Morten Gaunt'),
(329, '329', 'Muffin Barkwort'),
(461, '461', 'Murry Raymond'),
(82, '82', 'Mychal Bestar'),
(300, '300', 'Myrilla Bison'),
(139, '139', 'Myrvyn Hayen'),
(298, '298', 'Nadeen Jowsey'),
(28, '28', 'Nancey Rydings'),
(37, '37', 'Nanny Doogood'),
(41, '41', 'Naoma Shapcote'),
(366, '366', 'Natty Hurcombe'),
(244, '244', 'Neddie Windrus'),
(117, '117', 'Nevin Hibbart'),
(114, '114', 'Nicko Davenport'),
(432, '432', 'Nicol Angeau'),
(446, '446', 'Nikolos Redihou'),
(190, '190', 'Nils Searby'),
(54, '54', 'Noe McColgan'),
(422, '422', 'Noel Mouget'),
(140, '140', 'Noelle Rapinett'),
(39, '39', 'Obidiah Castero'),
(268, '268', 'Odilia Hindes'),
(121, '121', 'Odo Blackstone'),
(123, '123', 'Ogdan Ketteman'),
(67, '67', 'Olenolin Pollen'),
(360, '360', 'Olivero Bowne'),
(279, '279', 'Olly Cabotto'),
(89, '89', 'Onfroi Tomley'),
(322, '322', 'Orsola Davana'),
(126, '126', 'Otha Flucker'),
(292, '292', 'Paco Kase'),
(130, '130', 'Paolina Jaycock'),
(494, '494', 'Papagena Tomasi'),
(59, '59', 'Paton Beavan'),
(282, '282', 'Patrice Pietrza'),
(210, '210', 'Patsy Ricold'),
(172, '172', 'Pattie Volker'),
(386, '386', 'Pearline Walder'),
(306, '306', 'Pen Lowless'),
(14, '14', 'Pen Spier'),
(71, '71', 'Perceval Foxall'),
(498, '498', 'Perri Zuker'),
(302, '302', 'Perry Brampton'),
(256, '256', 'Pet Hindsberg'),
(465, '465', 'Peter Sibthorp'),
(193, '193', 'Petr Ormerod'),
(350, '350', 'Phebe Widdocks'),
(156, '156', 'Phillip Wharram'),
(412, '412', 'Phineas Bulgen'),
(463, '463', 'Piggy Von Der E'),
(290, '290', 'Portia Crocken'),
(49, '49', 'Preston Crotty'),
(471, '471', 'Quinn Brainsby'),
(316, '316', 'Quintina Ambrog'),
(205, '205', 'Rachael Fairn'),
(93, '93', 'Rafaelia Meriet'),
(407, '407', 'Ram Hinrich'),
(175, '175', 'Ramonda Icom'),
(460, '460', 'Raoul Rzehorz'),
(90, '90', 'Rebecka Kohnert'),
(474, '474', 'Reiko Puckring'),
(151, '151', 'Remington McCle'),
(109, '109', 'Remy Holywell'),
(398, '398', 'Riccardo Dines'),
(415, '415', 'Richmound Shapc'),
(239, '239', 'Riley Shorte'),
(403, '403', 'Roanna Skiplorn'),
(197, '197', 'Rochell Hurnell'),
(51, '51', 'Rochell Wesley'),
(159, '159', 'Rochelle Begwel'),
(68, '68', 'Rodd Merigon'),
(318, '318', 'Roderigo Wright'),
(359, '359', 'Rolfe Dyka'),
(162, '162', 'Rory Polsin'),
(467, '467', 'Rosabel Huge'),
(379, '379', 'Rosalie Halsho'),
(285, '285', 'Rosemaria Longm'),
(417, '417', 'Rozanna Yerson'),
(372, '372', 'Rutger Whymark'),
(9, '9', 'Ruthe Skilbeck'),
(46, '46', 'Salem Boshier'),
(500, '500', 'Salomo Liffey'),
(43, '43', 'Salomone Wiper'),
(144, '144', 'Sancho Maggorin'),
(96, '96', 'Sandro Emberton'),
(283, '283', 'Saraann Burnsid'),
(226, '226', 'Sarajane Esel'),
(119, '119', 'Sarina Rosingda'),
(344, '344', 'Scarface Michel'),
(7, '7', 'Sella Pilipets'),
(34, '34', 'Sergei Bradnum'),
(225, '225', 'Shalom Neathway'),
(251, '251', 'Sheree Doubrava'),
(255, '255', 'Sibella Garces'),
(493, '493', 'Sibelle Bodemea'),
(22, '22', 'Sid Mosedall'),
(203, '203', 'Sigvard Kain'),
(338, '338', 'Silvain Morrall'),
(295, '295', 'Silvana Bonnar'),
(13, '13', 'Silvio Orbell'),
(294, '294', 'Simon Chattoe'),
(78, '78', 'Smith Stonham'),
(483, '483', 'Sonia Doody'),
(161, '161', 'Sonni Meakin'),
(245, '245', 'Sonya Iacomi'),
(209, '209', 'Sosanna Orniz'),
(129, '129', 'Stephannie Fian'),
(289, '289', 'Stephannie Fook'),
(369, '369', 'Stephine Guidi'),
(10, '10', 'Sterling Gethin'),
(6, '6', 'Stevy Jakucewic'),
(253, '253', 'Stormie Warin'),
(391, '391', 'Stu Osgar'),
(334, '334', 'Suellen Alvaro'),
(19, '19', 'Sullivan Covoli'),
(452, '452', 'Susana Ilyin'),
(436, '436', 'Suzi Kempshall'),
(23, '23', 'Sydelle Mazzill'),
(418, '418', 'Tadd Antoshin'),
(304, '304', 'Talia Urvoy'),
(387, '387', 'Tammy Finlason'),
(339, '339', 'Tanya Kittel'),
(363, '363', 'Taryn Antoshin'),
(447, '447', 'Tawnya Jouannin'),
(124, '124', 'Teena Huggens'),
(47, '47', 'Teirtza Summerh'),
(399, '399', 'Teodor Dullingh'),
(168, '168', 'Teresita Thunde'),
(404, '404', 'Terrill Sweetin'),
(44, '44', 'Tessy Fulloway'),
(296, '296', 'Thomasina Loved'),
(445, '445', 'Tiertza Bernado'),
(450, '450', 'Titos Itzkowicz'),
(276, '276', 'Toiboid McLeman'),
(79, '79', 'Tome Saphin'),
(448, '448', 'Tony Fermoy'),
(160, '160', 'Tony Rylett'),
(275, '275', 'Torey Easter'),
(242, '242', 'Trumaine Aylesb'),
(382, '382', 'Tyler Manjin'),
(76, '76', 'Ursa Dursley'),
(400, '400', 'Ursola Klamp'),
(427, '427', 'Val Roycroft'),
(444, '444', 'Valdemar Ballha'),
(346, '346', 'Valentino Hugin'),
(192, '192', 'Vanda Halsho'),
(188, '188', 'Vanna Bertholin'),
(454, '454', 'Vera MacKibbon'),
(347, '347', 'Vinny Annies'),
(103, '103', 'Vito Hedau'),
(16, '16', 'Wally Noon'),
(414, '414', 'Wat Vido'),
(396, '396', 'Wilbur Ferneley'),
(413, '413', 'Willa Dingivan'),
(157, '157', 'Willi Bromehed'),
(442, '442', 'Win Dineges'),
(194, '194', 'Wright Garaway'),
(308, '308', 'Yolande Goning'),
(291, '291', 'Zacherie Woolri'),
(112, '112', 'Zolly Mouget');
