
-- Dump dati per tabella Cliente

INSERT INTO `cliente` (`mail`, `nome`, `cognome`, `data_nascita`, `cell`, `password`) VALUES 
('pierino@example.it' , 'pierino', 'pieroso', '2000-04-02', '+39 5679745623', '123'),
('marchino@example.it' , 'marchino', 'marcoso', '1998-05-12', '+39 3476744623', '123'),
('carlino@example.it' , 'carlino', 'carloso', '1965-08-19', '+39 5276747629', '123');

-- --------------------------------------------------------

-- Dumb dati per tabella Imprenditore

INSERT INTO `imprenditore` (`CF`, `nome`, `cognome`, `cell`, `password`) VALUES 
('MGCTDN34M19E972A', 'Magellano', 'Locatelli', '+39 375 3035065', '123'),
('CJSKRP54D16C567H', 'Carlo', 'Previtali', '+39 3451981698', '123'),
('MCRBWD35E01L599Z', 'Marco', 'Verdi', '+39 3461878938', '123');



-- --------------------------------------------------------

-- Dump dati per tabella Azienda

INSERT INTO `azienda` (`partita_iva`, `nome`, `imprenditoreCF`) VALUES 
('39385450968', 'azienda_1', 'MGCTDN34M19E972A'),
('48400450515', 'azienda_2', 'CJSKRP54D16C567H'), 
('25664670590', 'azienda_3', 'MCRBWD35E01L599Z');

-- --------------------------------------------------------

-- Dump dati per tabella Locale

INSERT INTO `locale` (`localeID`, `num_civico`, `via`, `postiMax`, `tipologia`, `id_comune`, `azienda_pIVA`) VALUES 
('1', ' 126', 'Via Belviglieri', '145', 'pizzeria', '20012', '25664670590'), 
('2', '35', 'Strada Bresciana', '55', 'osteria', '16024', '25664670590'), 
('3', '34', 'Via del Pontiere', '12', 'paninoteca', '76001', '39385450968');

-- --------------------------------------------------------

-- Dump dati per tabella Admin

INSERT INTO `admin` (`codice_operatore`, `password`, `localeID`) VALUES 
('001', '123', '1'), 
('002', '123', '2'), 
('003', '123', '3');

-- --------------------------------------------------------

-- Dump dati per tabella Turno

INSERT INTO `turno` (`turnoID`, `ora_inizio`, `ora_fine`) VALUES 
('1', '11:00:00', '15:00:00'), 
('2', '18:00:00', '24:00:00');

-- --------------------------------------------------------

-- Dump dati per tabella Prenotazione

INSERT INTO `prenotazione` (`mail_prenotazione`, `localeID`, `data_prenotazione`, `numero_posti`, `turnoID`) VALUES 
('carlino@example.it', '1', '2024-07-17 12:18:29', '3', '1'), 
('marchino@example.it', '1', '2024-07-17 12:18:29', '5', '1'), 
('pierino@example.it', '2', '2024-06-10 07:19:28', '6', '2');

-- --------------------------------------------------------