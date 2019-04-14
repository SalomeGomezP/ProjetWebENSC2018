create database if not exists bddprojet character set utf8 collate utf8_unicode_ci;
use bddprojet;

grant all privileges on bddprojet.* to 'testutilisateur'@'localhost' identified by 'test';