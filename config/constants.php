<?php
// config/constants.php

// --- 1. Konfiguracija URL-a i Putanja (Za navigaciju i resurse) ---

/**
 * BASE_URL:
 * Osnovna putanja projekta.
 * - Prazan string ('') ako je projekt u rootu web servera (npr. http://mojprojekt.ddev.site/).
 * - Naziv podfoldera ako je projekt tamo (npr. '/ifosup-web-project').
 */
define('BASE_URL', '');
// (Ako je vaš ddev projekt direktno dostupan, ostavite prazno)

// --- 2. Konfiguracija okruženja (Environment) ---

/**
 * DEV_MODE:
 * Kontrolira mod rada aplikacije.
 * - true: Prikazuje detaljne greške, olakšava debugiranje.
 * - false: Skriva detaljne greške, logira ih (za produkciju/sigurnost).
 */
define('DEV_MODE', TRUE);

// --- 3. Konfiguracija Aplikacije i BD (Može biti i u posebnom fajlu) ---

/**
 * DB_NAME:
 * Ime baze podataka koju koristi aplikacija.
 * Vaš zadatak je bdd_projet_web.
 */
define('DB_NAME', 'bdd_projet_web');

// DB parametri koji se ne mijenjaju u ddev-u
define('DB_HOST', 'db'); // 'db' je hostname za BD unutar DDEV-a
define('DB_USER', 'db'); // DDEV standardni korisnik
define('DB_PASS', 'db'); // DDEV standardna lozinka

// Ime tablice za korisnike
define('TABLE_USERS', 't_utilisateur_uti');

// --- 4. Drugi globalni parametri (Npr. za sigurnost ili validaciju) ---

/**
 * PASSWORD_MAX_LENGTH:
 * Maksimalna duljina lozinke (72 znaka preporučuje dokument).
 * Ovo se koristi za definiciju polja u BD (`VARCHAR(255)`) i validaciju na formi.
 */
define('PASSWORD_MAX_LENGTH', 72);

// Maksimalna duljina pseudonima/emaila (255 znaka).
define('MAX_STRING_LENGTH', 255);

// Minimalna duljina pseudonima (2 znaka).
define('PSEUDO_MIN_LENGTH', 2);
