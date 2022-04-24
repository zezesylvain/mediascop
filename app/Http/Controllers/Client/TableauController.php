<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TableauController extends Controller
{
    private $titre;
    public $gabarit;
    private $contenu;
    private $entete;

    public function __construct($titre, $tabEntete, $tabData, $tabTotaux) {
        $this->titre = $titre;
        $this->contenu = $this->buildContent($tabData, $tabEntete, $tabTotaux);
        $this->entete($tabEntete);
        $this->template();
    }

    private function entete($tabEntete) {
        $entete = "";
        foreach ($tabEntete as $item) :
            $entete .= "
                    <th class=\"couleur_th\"> $item </th>";
        endforeach;
        $this->entete = $entete;
    }

    private function buildContent($tabData, $tabEntete, $tabTotaux) {
        $contenuTableau = "";
        foreach ($tabData as $Ann => $rowOF) :
            $contenuTableau .= "<tr><td class=\"tab-col-left\">$Ann</td>";
            foreach ($tabEntete as $offret) :
                $val = array_key_exists($offret, $rowOF) ? ReportingController::numberDisplayer($rowOF[$offret]) : "";
                $contenuTableau .= "<td>$val</td>";
            endforeach;
            $contenuTableau .= "<td>" .ReportingController::numberDisplayer(array_sum($rowOF)) . "</td></tr>";
        endforeach;
        $contenuTableau .= "<tr><th class=\"couleur_th\">Total</th>";
        foreach ($tabEntete as $offret) :
            $val = array_key_exists($offret, $tabTotaux) ? ReportingController::numberDisplayer($tabTotaux[$offret]) : "";
            $contenuTableau .= "<th class=\"couleur_th\">$val</th>";
        endforeach;
        $contenuTableau .= "<th class=\"couleur_th\">" . ReportingController::numberDisplayer(array_sum($tabTotaux)) . "</th></tr>";
        return $contenuTableau;
    }

    private function template() {
        $titre = $this->titre;
        $entete = $this->entete;
        $contenu = $this->contenu;
        $this->gabarit = view ("clients.tableaux.template", compact ('titre','entete','contenu'))->render ();
    }

}
