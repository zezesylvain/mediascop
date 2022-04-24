<div class="col-sm-12">
    <label for="date">Date:</label><br />
    <select name="date" id="date" onchange="sendData('plus={{$plus}}&dt=' + this.value, '{{route('ajax.changeDate')}}', 'dateBlog')" class="form-control">
        <?php
        $limit = 30 * $plus;
        for ($j = 0; $j <= $limit; $j++):
            $temps = time() - $j * 60 * 60 * 24;
            $dateV = date("Y-m-d", $temps);
            $dateO = date("d-m-Y", $temps);
            $selected = $dateV == $dt ? " selected=\"selected\"" : "";
            echo "<option value=\"$dateV\"$selected>$dateO</option>";
        endfor;
        ?>
        <option value="YES">Plus loin</option>
    </select>
</div>
