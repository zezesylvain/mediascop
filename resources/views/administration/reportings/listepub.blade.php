@if(!$export)
    <div id="plisting" class="">
        <form method="post" action="{{route('dashbord.exportData')}}">
            {{csrf_field()}}
            <input type="hidden" name="formexport" value="formexport" />
            <input type="hidden" name="date_debut" value="{{$date_debut}}" />
            <input type="hidden" name="date_fin" value="{{$date_fin}}" />
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3></h3>
                    </div>
                    <div class="panel-body">
                        <div class="sparkline30-list">
                            <div class="sparkline30-graph">
                                <div class="table-responsive">

                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th width="10px"></th>
                                            <th>Date</th>
                                            <th>Support</th>
                                            <th>Annonceur</th>
                                            <th>Titre de campagne</th>
                                            <th>Tarif</th>
                                            <th>Coef</th>
                                            <th>
                                                <input type="checkbox" onChange="checkboxChecker('listpub', this.checked,'{{$nbt}}');" />
                                                Action
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $i = 0;
                                        $ij = 0;
                                        //dd($listpub)
                                        ?>
                                        @foreach ($listpub as $row)
                                            <?php
                                            $i++;
                                            ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $row['date']; ?></td>
                                                <td><?php echo $row['supportname']; ?></td>
                                                <td><?php echo $row['raisonsociale']; ?></td>
                                                <td><?php echo $row['campagnetitle']; ?></td>
                                                <td>
                                                    <?php echo $row['tarif']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['coeff']; ?>
                                                </td>
                                                <td>
                                                <span id="validebox<?php echo $row['id']; ?>">
                                                    <input id="listpub[<?php echo $i;?>]" type="checkbox" name="listpub[]" value="<?php echo $row['id']; ?>" />
                                                </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-xs-12">
                                <button class="btn btn-primary btn-block">Exporter</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endif