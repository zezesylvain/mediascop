@inject('admin','App\Http\Controllers\Client\AdminController')
<fieldset>
    <legend><h4>Date</h4></legend>
    <div class="row">
        <div class="col-sm-12">
            <label class="dateDebutItem" for="">DATE DEBUT</label>
            <input type="text" name="dateDebut" class="form-control datepickerjs" value="{{ $dateDebut ?? date('Y-m-d')  }}" id="dateDebutItem">
        </div>
        <div class="col-sm-12 pb">
            <br>
            <label class="dateFinItem" for="">DATE FIN</label>
            <input type="text" name="dateFin" class="form-control datepickerjs" value="{{ $dateFin ?? date('Y-m-d') }}" id="dateFinItem">
            <br>
        </div>
    </div> <!-- END row -->
</fieldset>
