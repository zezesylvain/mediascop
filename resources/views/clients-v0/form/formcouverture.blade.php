<fieldset>
    <legend><h4>Couverture</h4></legend>
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <label class="section-name" for="filterid">Filtre</label><br>
            <div id="parentfilter2">
                <select name="filterid" onchange="sendData('n1=filterid&amp;n2=localite&amp;z=2&amp;parent=' + this.value, 'form/filter.php', 'parentselect');
                                sendData('n1=filterid&amp;n2=localite&amp;z=1&amp;parent=' + this.value, 'form/filter.php', 'parentfilter2');">
                    <option value="" selected="selected"> Filtre</option>
                    <option value="1">LAGUNES</option>
                </select>
            </div>
        </div>
        <div class="col-md-12 col-sm-12">
            <br/>
            <label class="section-name" for="localite"> Localité </label>
            <div id="parentselect">
                <select name="localite[]" multiple="multiple" class="form-control" size="4">
                    <option value=""> Localité</option>
                    <option value="1">LAGUNES</option>
                    <option value="2">ABIDJAN</option>
                    <option value="3">ABOBO</option>
                    <option value="4">ANYAMA</option>
                    <option value="5">ADJAME</option>
                    <option value="6">ATTECOUBE</option>
                    <option value="7">BINGERVILLE</option>
                    <option value="8">COCODY</option>
                    <option value="9">KOUMASSI</option>
                    <option value="10">MARCORY</option>
                    <option value="11">PLATEAU</option>
                    <option value="12">PORT-BOUET</option>
                    <option value="13">SONGON</option>
                    <option value="14">TREICHVILLE</option>
                    <option value="15">YOPOUGON</option>
                    <option value="16">ANGRE</option>
                    <option value="17">DJIBI</option>
                    <option value="18">GRAND-BASSAM</option>
                    <option value="20">TOIT-ROUGE</option>
                    <option value="22">SICOGI</option>
                    <option value="23">Chateau</option>
                </select>
            </div>
        </div> <!-- END row -->
    </div>
</fieldset>