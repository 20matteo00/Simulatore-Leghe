CREATE TABLE IF NOT EXISTS partita (
    ID_Squadra1 INT NOT NULL,
    ID_Campionato1 INT NOT NULL,
    GF1 INT NOT NULL,
    ID_Squadra2 INT NOT NULL,
    ID_Campionato2 INT NOT NULL,
    GF2 INT NOT NULL,
    PRIMARY KEY (ID_Squadra1, ID_Campionato1, ID_Squadra2, ID_Campionato2),
    FOREIGN KEY (ID_Campionato1, ID_Squadra1) REFERENCES squadra(ID_Campionato, ID),
    FOREIGN KEY (ID_Campionato2, ID_Squadra2) REFERENCES squadra(ID_Campionato, ID)
);