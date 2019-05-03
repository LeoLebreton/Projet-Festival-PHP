<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * renvoie une date au format jj/mm/aaaa (français) 
 * à partir d'une date au format aaaa-mm-jj (anglais, mysql)
 * @param date "aaaa-mm-jj"
 * @return date "jj/mm/aaaa"
 */
function dateMysql2Fr($date) {
    $date1 = new DateTime($date);
    return $date1->format('d/m/Y');
}

/*
 * renvoie une date au format aaaa-mm-jj (anglais, mysql) 
 * à partir d'une date au format jj/mm/aaaa (français)
 * @param date "jj/mm/aaaa"
 * @return date "aaaa-mm-jj"
 */
function dateFr2Mysql($date) {
    return date('Y-m-d', strtotime(str_replace ('/', '-', $date)));
}

/*
 * renvoie une heure 00:00:00 au format 00:00 
 * @param date "00:00:00"
 * @return date "00:00"
 */
function timeSansSecondes($time) {
    return substr($time,0,5);
}