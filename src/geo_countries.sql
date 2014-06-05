
CREATE TABLE IF NOT EXISTS `geo_countries` (
  `iso` char(2) CHARACTER SET utf8 NOT NULL,
  `en_UK` varchar(80) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name in English',
  `de_DE` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'German',
  `fi_FI` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Finnish',
  `es_ES` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Spanish',
  `ru_RU` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Russian',
  `lt_LT` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Lithuanian',
  `iso3` char(3) CHARACTER SET utf8 DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL,
  `continent` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `lat` float DEFAULT NULL COMMENT 'in google projection',
  `lon` float DEFAULT NULL COMMENT 'in google projection',
  `zoom` int(11) DEFAULT '5',
  `bBoxWest` float DEFAULT NULL,
  `bBoxNorth` float DEFAULT NULL,
  `bBoxEast` float DEFAULT NULL,
  `bBoxSouth` float DEFAULT NULL,
  PRIMARY KEY (`iso`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
