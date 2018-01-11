
--
-- Struttura della tabella `lastviews`
--

CREATE TABLE `lastviews` (
  `ID` smallint(11) NOT NULL,
  `videoID` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
--
-- Indici per le tabelle `lastviews`
--
ALTER TABLE `lastviews`
  ADD PRIMARY KEY (`ID`) USING BTREE,
  ADD UNIQUE KEY `ID` (`ID`);