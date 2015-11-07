<?php

/* 
 * Kdyz uz bude bordel v aplikaci, tak at nemame bordel v databazich :)
 * 
 * Ve slozce 'model' je (obviously) ulozen datovy model
 * 
 * Ve sloze 'sql' jsou ulozeny sql skripty, pripravene jsou podslozky 'test' a 'production'
 * Workflow je nasledujici (predpokladam inkrementalni vyvoj - DB se nebude dropovat,
 * ale budeme delat change skripty, ktere ji povysi na novou verzi) :
 * 1)   Nejdriv se skript nahraje primo do slozky 'sql' kde zustane po dobu, kdy bude danou
 *      feature clovek vyvijet u sebe na lokalu.
 * 2)   Az se zmeny budou chtit vyzkouset na testu (tedy pri merge do trunku?), tak 
 *      se skripty spusti na test DB a presunou do adresare 'test'.
 * 3)   Pri nasazeni do produkce (pred sprint review?) se skripty spusti na produkcni
 *      DB a logicky presunou do slozky 'produkce'. 
 * 
 * Skripty prosim pojmenovat datumem a zakladnim popisem.
 */

