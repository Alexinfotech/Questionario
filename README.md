# Questionario
Simil Questionario
# Gestionale Questionario

Il Gestionale Questionario è un'applicazione web che consente agli utenti di compilare e gestire questionari online. Gli utenti possono accedere, visualizzare i questionari disponibili, compilare e visualizzare i risultati.

## Funzionalità principali

- **Autenticazione degli utenti**: Gli utenti devono autenticarsi per accedere al sistema.
- **Visualizzazione dei questionari**: Gli utenti possono visualizzare i questionari disponibili.
- **Compilazione dei questionari**: Gli utenti possono compilare i questionari non completati.
- **Visualizzazione dei risultati**: Gli utenti admin possono visualizzare i risultati dei questionari completati.

## Tecnologie utilizzate

- **Linguaggi di programmazione**: PHP, HTML, CSS, JavaScript
- **Database**: MySQL
- **Framework**: Bootstrap per il design responsivo




## Tabelle nel Database `questionario`

- `assegnazioni_questionario`
- `domande`
- `domande_aperte`
- `domande_questionario`
- `domande_vero_falso`
- `questionari`
- `questionario_domande`
- `risposte_aperte`
- `risposte_vero_falso`
- `utenti`

## Descrizione della Tabella `assegnazioni_questionario`

| Campo               | Tipo        | Null | Key | Default             | Extra |
|---------------------|-------------|------|-----|---------------------|-------|
| id_utente           | int(11)     | NO   | PRI | NULL                |       |
| id_questionario     | int(11)     | NO   | PRI | NULL                |       |
| data_assegnazione   | timestamp   | YES  |     | current_timestamp() |       |
| completato          | tinyint(1)  | YES  |     | 0                   |       |
| data_completamento  | datetime    | YES  |     | NULL                |       |


## Creazione della Tabella `assegnazioni_questionario`

```sql
CREATE TABLE `assegnazioni_questionario` (
  `id_utente` int(11) NOT NULL,
  `id_questionario` int(11) NOT NULL,
  `data_assegnazione` timestamp NULL DEFAULT current_timestamp(),
  `completato` tinyint(1) DEFAULT 0,
  `data_completamento` datetime DEFAULT NULL,
  PRIMARY KEY (`id_utente`,`id_questionario`),
  KEY `id_questionario` (`id_questionario`),
  CONSTRAINT `assegnazioni_questionario_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id`),
  CONSTRAINT `assegnazioni_questionario_ibfk_2` FOREIGN KEY (`id_questionario`) REFERENCES `questionari` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


## Descrizione della Tabella `domande`

| Campo          | Tipo                            | Null | Key | Default             | Extra          |
|----------------|---------------------------------|------|-----|---------------------|----------------|
| id             | int(11)                         | NO   | PRI | NULL                | auto_increment |
| testo_domanda  | varchar(255)                    | NO   |     | NULL                |                |
| tipo_domanda   | enum('aperta','vero_falso')     | NO   |     | NULL                |                |
| id_utente      | int(11)                         | YES  | MUL | NULL                |                |
| data_creazione | timestamp                       | YES  |     | current_timestamp() |                |

## Creazione della Tabella `domande`

```sql
CREATE TABLE `domande` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `testo_domanda` varchar(255) NOT NULL,
  `tipo_domanda` enum('aperta','vero_falso') NOT NULL,
  `id_utente` int(11) DEFAULT NULL,
  `data_creazione` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_utente` (`id_utente`),
  CONSTRAINT `domande_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

## Descrizione della Tabella `domande_aperte`

| Campo          | Tipo         | Null | Key | Default             | Extra          |
|----------------|--------------|------|-----|---------------------|----------------|
| id             | int(11)      | NO   | PRI | NULL                | auto_increment |
| testo_domanda  | varchar(255) | NO   |     | NULL                |                |
| data_creazione | timestamp    | YES  |     | current_timestamp() |                |
| id_domanda     | int(11)      | YES  |     | NULL                |                |

## Creazione della Tabella `domande_aperte`

```sql
CREATE TABLE `domande_aperte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `testo_domanda` varchar(255) NOT NULL,
  `data_creazione` timestamp NULL DEFAULT current_timestamp(),
  `id_domanda` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

## Descrizione della Tabella `domande_questionario`

| Campo                 | Tipo    | Null | Key | Default | Extra          |
|-----------------------|---------|------|-----|---------|----------------|
| id                    | int(11) | NO   | PRI | NULL    | auto_increment |
| id_questionario       | int(11) | YES  | MUL | NULL    |                |
| id_domanda_aperta     | int(11) | YES  | MUL | NULL    |                |
| id_domanda_multiple   | int(11) | YES  | MUL | NULL    |                |
| id_domanda_vero_falso | int(11) | YES  | MUL | NULL    |                |


## Creazione della Tabella `domande_questionario`

```sql
CREATE TABLE `domande_questionario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_questionario` int(11) DEFAULT NULL,
  `id_domanda_aperta` int(11) DEFAULT NULL,
  `id_domanda_multiple` int(11) DEFAULT NULL,
  `id_domanda_vero_falso` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_questionario` (`id_questionario`),
  KEY `id_domanda_aperta` (`id_domanda_aperta`),
  KEY `id_domanda_multiple` (`id_domanda_multiple`),
  KEY `id_domanda_vero_falso` (`id_domanda_vero_falso`),
  CONSTRAINT `domande_questionario_ibfk_1` FOREIGN KEY (`id_questionario`) REFERENCES `questionari` (`id`),
  CONSTRAINT `domande_questionario_ibfk_2` FOREIGN KEY (`id_domanda_aperta`) REFERENCES `domande_aperte` (`id`),
  CONSTRAINT `domande_questionario_ibfk_3` FOREIGN KEY (`id_domanda_multiple`) REFERENCES `domande_multiple` (`id`),
  CONSTRAINT `domande_questionario_ibfk_4` FOREIGN KEY (`id_domanda_vero_falso`) REFERENCES `domande_vero_falso` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

## Descrizione della Tabella `domande_vero_falso`

| Campo             | Tipo                 | Null | Key | Default             | Extra          |
|-------------------|----------------------|------|-----|---------------------|----------------|
| id                | int(11)              | NO   | PRI | NULL                | auto_increment |
| testo_domanda     | varchar(255)         | NO   |     | NULL                |                |
| risposta_corretta | enum('vero','falso') | NO   |     | NULL                |                |
| data_creazione    | timestamp            | YES  |     | current_timestamp() |                |
| id_domanda        | int(11)              | YES  |     | NULL                |                |

## Creazione della Tabella `domande_vero_falso`

```sql
CREATE TABLE `domande_vero_falso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `testo_domanda` varchar(255) NOT NULL,
  `risposta_corretta` enum('vero','falso') NOT NULL,
  `data_creazione` timestamp NULL DEFAULT current_timestamp(),
  `id_domanda` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

## Descrizione della Tabella `questionari`

| Campo          | Tipo         | Null | Key | Default             | Extra          |
|----------------|--------------|------|-----|---------------------|----------------|
| id             | int(11)      | NO   | PRI | NULL                | auto_increment |
| titolo         | varchar(255) | NO   |     | NULL                |                |
| data_creazione | timestamp    | YES  |     | current_timestamp() |                |


## Creazione della Tabella `questionari`

```sql
CREATE TABLE `questionari` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titolo` varchar(255) NOT NULL,
  `data_creazione` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

## Descrizione della Tabella `questionario_domande`

| Campo           | Tipo                            | Null | Key | Default | Extra          |
|-----------------|---------------------------------|------|-----|---------|----------------|
| id              | int(11)                         | NO   | PRI | NULL    | auto_increment |
| id_questionario | int(11)                         | NO   | MUL | NULL    |                |
| id_domanda      | int(11)                         | NO   | MUL | NULL    |                |
| tipo_domanda    | enum('aperta','vero_falso')     | YES  |     | NULL    |                |

## Creazione della Tabella `questionario_domande`

```sql
CREATE TABLE `questionario_domande` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_questionario` int(11) NOT NULL,
  `id_domanda` int(11) NOT NULL,
  `tipo_domanda` enum('aperta','vero_falso') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_questionario` (`id_questionario`),
  KEY `id_domanda` (`id_domanda`),
  CONSTRAINT `questionario_domande_ibfk_1` FOREIGN KEY (`id_questionario`) REFERENCES `questionari` (`id`),
  CONSTRAINT `questionario_domande_ibfk_2` FOREIGN KEY (`id_domanda`) REFERENCES `domande` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

## Descrizione della Tabella `risposte_aperte`

| Campo           | Tipo         | Null | Key | Default             | Extra          |
|-----------------|--------------|------|-----|---------------------|----------------|
| id              | int(11)      | NO   | PRI | NULL                | auto_increment |
| risposta        | varchar(160) | NO   |     | NULL                |                |
| id_utente       | int(11)      | YES  | MUL | NULL                |                |
| data_risposta   | timestamp    | YES  |     | current_timestamp() |                |
| id_questionario | int(11)      | YES  |     | NULL                |                |
| id_domanda      | int(11)      | YES  |     | NULL                |                |
## Creazione della Tabella `risposte_aperte`

```sql
CREATE TABLE `risposte_aperte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `risposta` varchar(160) NOT NULL,
  `id_utente` int(11) DEFAULT NULL,
  `data_risposta` timestamp NULL DEFAULT current_timestamp(),
  `id_questionario` int(11) DEFAULT NULL,
  `id_domanda` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_utente` (`id_utente`),
  CONSTRAINT `risposte_aperte_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

## Descrizione della Tabella `risposte_vero_falso`

| Campo           | Tipo                 | Null | Key | Default             | Extra          |
|-----------------|----------------------|------|-----|---------------------|----------------|
| id              | int(11)              | NO   | PRI | NULL                | auto_increment |
| risposta        | enum('vero','falso') | NO   |     | NULL                |                |
| id_utente       | int(11)              | YES  | MUL | NULL                |                |
| data_risposta   | timestamp            | YES  |     | current_timestamp() |                |
| id_questionario | int(11)              | YES  |     | NULL                |                |
| id_domanda      | int(11)              | YES  |     | NULL                |                |

## Creazione della Tabella `risposte_vero_falso`

```sql
CREATE TABLE `risposte_vero_falso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `risposta` enum('vero','falso') NOT NULL,
  `id_utente` int(11) DEFAULT NULL,
  `data_risposta` timestamp NULL DEFAULT current_timestamp(),
  `id_questionario` int(11) DEFAULT NULL,
  `id_domanda` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_utente` (`id_utente`),
  CONSTRAINT `risposte_vero_falso_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

## Descrizione della Tabella `utenti`

| Campo              | Tipo                              | Null | Key | Default             | Extra          |
|--------------------|-----------------------------------|------|-----|---------------------|----------------|
| id                 | int(11)                           | NO   | PRI | NULL                | auto_increment |
| nome               | varchar(255)                      | NO   |     | NULL                |                |
| cognome            | varchar(255)                      | NO   |     | NULL                |                |
| email              | varchar(255)                      | NO   | UNI | NULL                |                |
| password           | varchar(255)                      | NO   |     | NULL                |                |
| ruolo              | enum('admin','utente_navigatore') | NO   |     | utente_navigatore   |                |
| data_registrazione | timestamp                         | YES  |     | current_timestamp() |                |

## Creazione della Tabella `utenti`

```sql
CREATE TABLE `utenti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `cognome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ruolo` enum('admin','utente_navigatore') NOT NULL DEFAULT 'utente_navigatore',
  `data_registrazione` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

