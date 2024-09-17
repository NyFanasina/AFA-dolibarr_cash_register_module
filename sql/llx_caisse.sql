
CREATE TABLE `llx_caisse` (
  `id` int(11) NOT NULL,
  `motif` varchar(255) NOT NULL,
  `valeur` float NOT NULL,
  `entrant` BOOLEAN NOT NULL,
  `valide` BOOLEAN NOT NULL DEFAULT '0',
  `existe` BOOLEAN NOT NULL DEFAULT '1',
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

