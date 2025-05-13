CREATE TABLE IF NOT EXISTS GCL (
    ID_Squadra INT NOT NULL,
    ID_Campionato INT NOT NULL,
    Eliminato INT NOT NULL DEFAULT 0,
    PRIMARY KEY (ID_Squadra, ID_Campionato),
    FOREIGN KEY (ID_Campionato, ID_Squadra) REFERENCES squadra(ID_Campionato, ID)
);

CREATE TABLE IF NOT EXISTS GEL (
    ID_Squadra INT NOT NULL,
    ID_Campionato INT NOT NULL,
    Eliminato INT NOT NULL DEFAULT 0,
    PRIMARY KEY (ID_Squadra, ID_Campionato),
    FOREIGN KEY (ID_Campionato, ID_Squadra) REFERENCES squadra(ID_Campionato, ID)
);

CREATE TABLE IF NOT EXISTS QCL (
    ID_Squadra INT NOT NULL,
    ID_Campionato INT NOT NULL,
    Eliminato INT NOT NULL DEFAULT 0,
    PRIMARY KEY (ID_Squadra, ID_Campionato),
    FOREIGN KEY (ID_Campionato, ID_Squadra) REFERENCES squadra(ID_Campionato, ID)
);

CREATE TABLE IF NOT EXISTS QEL (
    ID_Squadra INT NOT NULL,
    ID_Campionato INT NOT NULL,
    Eliminato INT NOT NULL DEFAULT 0,
    PRIMARY KEY (ID_Squadra, ID_Campionato),
    FOREIGN KEY (ID_Campionato, ID_Squadra) REFERENCES squadra(ID_Campionato, ID)
);

CREATE TABLE IF NOT EXISTS QECL (
    ID_Squadra INT NOT NULL,
    ID_Campionato INT NOT NULL,
    Eliminato INT NOT NULL DEFAULT 0,
    PRIMARY KEY (ID_Squadra, ID_Campionato),
    FOREIGN KEY (ID_Campionato, ID_Squadra) REFERENCES squadra(ID_Campionato, ID)
);

CREATE TABLE IF NOT EXISTS partitaqual (
    ID_Squadra1 INT NOT NULL,
    ID_Campionato1 INT NOT NULL,
    GF1 INT NOT NULL,
    ID_Squadra2 INT NOT NULL,
    ID_Campionato2 INT NOT NULL,
    GF2 INT NOT NULL,
    Coppa INT NOT NULL,
    PRIMARY KEY (ID_Squadra1, ID_Campionato1, ID_Squadra2, ID_Campionato2),
    FOREIGN KEY (ID_Campionato1, ID_Squadra1) REFERENCES squadra(ID_Campionato, ID),
    FOREIGN KEY (ID_Campionato2, ID_Squadra2) REFERENCES squadra(ID_Campionato, ID)
);

CREATE TABLE IF NOT EXISTS squadragirone (
    ID_Squadra INT NOT NULL,
    ID_Campionato INT NOT NULL,
    Coppa INT NOT NULL,
    Girone CHAR NOT NULL,
    PRIMARY KEY (ID_Squadra, ID_Campionato),
    FOREIGN KEY (ID_Campionato, ID_Squadra) REFERENCES squadra(ID_Campionato, ID)
);

CREATE TABLE IF NOT EXISTS statistichegirone (
    ID_Squadra INT NOT NULL,
    ID_Campionato INT NOT NULL,
    Pt INT NOT NULL,
    G INT NOT NULL,
    V INT NOT NULL,
    N INT NOT NULL,
    P INT NOT NULL,
    GF INT NOT NULL,
    GS INT NOT NULL,
    DR INT NOT NULL,
    Coppa INT NOT NULL,
    Girone CHAR NOT NULL,
    PRIMARY KEY (ID_Squadra, ID_Campionato),
    FOREIGN KEY (ID_Campionato, ID_Squadra) REFERENCES squadra(ID_Campionato, ID)
);

CREATE TABLE IF NOT EXISTS partitagirone (
    ID_Squadra1 INT NOT NULL,
    ID_Campionato1 INT NOT NULL,
    GF1 INT NOT NULL,
    ID_Squadra2 INT NOT NULL,
    ID_Campionato2 INT NOT NULL,
    GF2 INT NOT NULL,
    Coppa INT NOT NULL,
    Girone CHAR NOT NULL,
    PRIMARY KEY (ID_Squadra1, ID_Campionato1, ID_Squadra2, ID_Campionato2),
    FOREIGN KEY (ID_Campionato1, ID_Squadra1) REFERENCES squadra(ID_Campionato, ID),
    FOREIGN KEY (ID_Campionato2, ID_Squadra2) REFERENCES squadra(ID_Campionato, ID)
);

CREATE TABLE IF NOT EXISTS partitaelim (
    ID_Squadra1 INT NOT NULL,
    ID_Campionato1 INT NOT NULL,
    GF1 INT NOT NULL,
    ID_Squadra2 INT NOT NULL,
    ID_Campionato2 INT NOT NULL,
    GF2 INT NOT NULL,
    Coppa INT NOT NULL,
    PRIMARY KEY (ID_Squadra1, ID_Campionato1, ID_Squadra2, ID_Campionato2),
    FOREIGN KEY (ID_Campionato1, ID_Squadra1) REFERENCES squadra(ID_Campionato, ID),
    FOREIGN KEY (ID_Campionato2, ID_Squadra2) REFERENCES squadra(ID_Campionato, ID)
);