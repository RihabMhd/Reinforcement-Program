<?php
/**
 * EXERCICE 2 — Methodes statiques, constantes et pattern Registry
 *
 * Contexte :
 * Un systeme de gestion de configurations pour une application web.
 * Les configurations sont globales, uniques par cle, et doivent
 * etre accessibles partout sans instanciation.
 *
 * A implementer :
 *
 * Classe Config (toutes les methodes et proprietes sont statiques)
 *   Propriete : items (array, private static)
 *   Constantes :
 *     ENV_DEV = 'development'
 *     ENV_PROD = 'production'
 *     ENV_TEST = 'test'
 *
 *   Methodes statiques :
 *     set(string $cle, mixed $valeur) : void
 *       => Supporte la notation pointee : Config::set('db.host', 'localhost')
 *       => Cree la structure imbriquee correspondante dans items
 *     get(string $cle, mixed $defaut = null) : mixed
 *       => Supporte la notation pointee : Config::get('db.host')
 *       => Retourne $defaut si la cle n existe pas
 *     has(string $cle) : bool
 *       => Verifie si une cle existe (notation pointee supportee)
 *     all() : array
 *       => Retourne toutes les configs
 *     load(array $config) : void
 *       => Charge un tableau de config (merge avec l existant, notation pointee)
 *     reset() : void
 *       => Vide toutes les configs (utile pour les tests)
 *
 * Classe AppEnvironment
 *   Propriete statique : env (string, defaut = Config::ENV_DEV)
 *   Methodes statiques :
 *     setEnv(string $env) : void => valider avec les constantes de Config
 *     getEnv() : string
 *     isProduction() : bool
 *     isDevelopment() : bool
 *     getDebugLevel() : int => 3 en dev, 1 en test, 0 en prod
 *
 * Classe final DatabaseConfig
 *   (Final : personne ne doit etendre cette classe de config critique)
 *   Methodes statiques :
 *     configure(string $host, string $db, string $user, string $pass, int $port = 3306) : void
 *       => Stocke dans Config avec le prefixe 'database.'
 *     getDSN() : string
 *       => Retourne "mysql:host=host;dbname=db;port=port;charset=utf8"
 *     isConfigured() : bool
 *       => Verifie que host, db, user, pass sont tous definis
 *
 * Tests a realiser :
 * 1. Configurer l environnement en production
 * 2. Charger un tableau de config avec db.host, db.name, app.name, app.version
 * 3. Utiliser DatabaseConfig::configure() puis afficher le DSN
 * 4. Verifier Config::has('db.host') et Config::get('app.name')
 * 5. Verifier que DatabaseConfig ne peut pas etre etendue (commentaire explicatif suffit)
 * 6. Tenter de setEnv() avec une valeur invalide => verifier l exception
 */

// Votre code ici