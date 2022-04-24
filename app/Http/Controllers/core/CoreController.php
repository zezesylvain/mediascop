<?php

namespace App\Http\Controllers\core;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CoreController extends Controller
{

    public function gestionTable(string $databaseTable):string {
        $databaseTable = FunctionController::getTableName ($databaseTable);
        return ModuleController::makeForm ($databaseTable);
    }

    public function gestionTableForm(string $databaseTable):string {
        $databaseTable = FunctionController::getTableName ($databaseTable);
        return ModuleController::formStandart($databaseTable);
    }

    public function gestionTableDatas(string $databaseTable):string {
        $databaseTable = FunctionController::getTableName ($databaseTable);
        return ModuleController::tableauStandart ($databaseTable);
    }

    public function update(string $table, int $id):string {
        return ModuleController::update ($table,$id);
    }
}
