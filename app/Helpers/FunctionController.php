<?php

namespace App\Helpers;

use App\Http\Controllers\Billboardmap\AdminController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\core\UtilisateursController;
use App\Http\Controllers\Messagerie\MessagerieController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\core\FunctionController as func;
use App\Http\Controllers\core\FormController as form;

class FunctionController extends Controller {


    public static function makeTabMenu() {
       return func::makeTabMenu();
    }

    public static function expansion($label) {
       return func::expansion($label);
    }

    public static function makeCurrentUrl() {
        return func::makeCurrentUrl();
    }

    public static function is_active($url) {
        return func::is_active($url);
    }

    public static function makeMenuSimple($idMenu = "") {
       return func::makeMenuSimple($idMenu);
    }

    public static function makeMenuItem($url, $label, $icone) {
       return func::makeMenuItem($url,$label,$icone);
    }

    public static function makeMenuItemLevel($tab, $label, $icone, $idMenu) {
       return func::makeMenuItemLevel($tab,$label,$icone,$idMenu);
    }

    public static function makeMenuItemThirdLevel($tab, $label) {
       return self::makeMenuItemThirdLevel($tab,$label);
    }

    public static function inputInline($id, $valeur, $colonne, $baseDeDonnees, $chemin, $position, $action) {

       return func::inputInline($id, $valeur, $colonne, $baseDeDonnees, $chemin, $position, $action);
    }

    public static function inlineTexte($id, $valeur, $column, $table, $type, $position) {
        return func::inlineTexte($id, $valeur, $column, $table, $type, $position);
    }

    public static function inlineInput($id, $valeur, $column, $table, $type, $position) {
       return func::inlineInput($id, $valeur, $column, $table, $type, $position);
    }

    public static function inlineSelect($id, $valeur, $column, $table, $type, $position) {
       return func::inlineSelect($id, $valeur, $column, $table, $type, $position);
    }


    public static function inlineUpdate($id, $valeur, $column, $table, $type, $position) {
        return func::inlineUpdate($id, $valeur, $column, $table, $type, $position);
    }

    public static function updateColumnInTable($table, $column, $valeur, $id) {
       return func::updateColumnInTable($table,$column,$valeur,$id);
    }

    public static function selectColumnInTable($table, $id, $column) {
       return func::selectColumnInTable($table,$id,$column);
    }

    public static function getChampTable($tabl, $id, $column = "name") {
       return func::getChampTable($tabl, $id, $column);
    }

    public static function getFieldDefaultParam() {
       return func::getFieldDefaultParam();
    }

    public static function getTableFieldParam() {
       return func::getTableFieldParam();
    }

    public static function is_displayable($table, $field) {
       return func::is_displayable($table,$field);
    }

    public static function is_inlinable($table, $field) {
        return func::is_inlinable($table,$field);
    }

    public static function arraySqlResult($sql) {
        return func::arraySqlResult($sql) ;
    }

    public static function getTypeField($type) {
        return func::getTypeField($type) ;

    }

    public static function fieldInput($champ, $type, $valeur = "", $param = []) {
        return func::fieldInput($champ, $type, $valeur, $param) ;
    }

    public static function fieldSelect($champ, $type, $valeur = "", $param = []) {
       return func::fieldSelect($champ, $type, $valeur, $param);
    }


    public static function trouver_ma_chaine($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }


    public static function ec($val, $column) {
        return func::ec($val, $column);
    }

    public static function getDatabaseTable() {
       return func::getDatabaseTable();
    }

    public static function getTableName($table) {
      return func::getTableName($table);
    }

    public static function getFieldOfTable($table) {
        return func::getFieldOfTable($table);
    }

    public static function is_foreignKey($field) {
      return func::is_foreignKey($field);
    }

    public static function dataTableActions($route,$options = []){
        return \route($route,$options);
    }

    public static function date2Fr($date,$format = "d/m/Y"){
        return func::date2Fr($date,$format);
    }

    public static function formatNumber($number,$decimal = 2,$dec_point = ".",$thousand = ","){
        return func::formatNumber($number,$decimal,$dec_point,$thousand);
    }

    public static function makeListeDate(){
        return func::makeListeDate();
    }

    public static function cleanStr($str){
        return func::cleanStr($str);
    }

    public static function truncateStr($str,$nbrCaractere=15){
        return func::truncateStr($str,$nbrCaractere);
    }

    public static function genererMDP($longueur = 8){
        return func::genererMDP($longueur);
    }

    public static function userName():string {
        if (Auth::check ()):
            $names = Auth::user ()->name;
            return $names;
        endif;
    }

    public static function writefilecode(string $filelink, string $type):string {
        return func::writefilecode ($filelink,$type);
    }

    public static function formOption($database, $type = 'select', $id = 0, $name = 'name', $formname = '', $cond = '', $order = 'name', $pre = '', $suf = ''):string {
        return form::formOption ($database,$type,$id,$name,$formname,$cond,$order,$pre,$suf);
    }

    public static function UsersConnected():string {
        return func::UsersConnected ();
    }

    public static function numberUserConnected():int{
        return func::numberUserConnected ();
    }

    public static function messageNotifier(){
        return MessagerieController::listMessageNotificateInbox ();
    }

    public static function chercherPhotosUser(int $userID, string $taille = "Moyen"):string {
        return UtilisateursController::chercherPhotoUtilisateur ($userID,$taille);
     }

     public static function getLigneeDeLocalite(int $localiteID){
        return func::getLigneeDeLocalite ($localiteID);
     }

     public static function getIconeHtml(int $iconeID){
        return func::getIconeHtml ($iconeID);
     }

     public static function getTableNameSansPrefixe(string $table){
        return func::getTableNameSansPrefixe ($table);
     }
     
     public static function icon(string $ext){
        return func::icone($ext);
     }

}
