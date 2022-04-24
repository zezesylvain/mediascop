<style>
    table{width: 100%; font-family: "Roboto",
        Helvetica, Arial, sans-serif;line-height: 6mm;
        border-collapse: collapse;}
    .tpl1{
        display: inline-block;
        text-align: center;
        background: rgba(212, 212, 212, 0.33);
        margin-bottom: 3px;
    }
    .corps{
        border: 1px #4f4f4f solid;
    }
    .infos th, .infos td{
        border: 1px #ffffff solid;
        background-color: rgba(212, 212, 212, 0.33);
    }
    .infos th{
        text-align: left;
    }
</style>
<table>
    <tr>
        <td style="width: 25%">
            <img src="{{asset("mail/images/image002.jpg")}}" alt="">
            <h5>Alerte nouvelle action de communication</h5>
        </td>
        <td style="width: 50%"></td>
        <td style="width: 25%">
            <img src="{{asset("mail/images/image003.jpg")}}" alt="">
        </td>
    </tr>
</table>
<div class="tpl1" style="width: 35%;background-color: red;color: #ffffff;">ABIDJAN ET INTERIEUR</div>
<div class="tpl1" style="width: 28%;">{{date("d-m-Y")}}</div>
<div class="tpl1" style="width: 35%;">{{$msg['secteur']}}</div>
<br>
<div class="corps">
    <table class="infos">
        <tr>
            <th style="width: 40%;">Annonceur</th>
            <td style="width: 2%;">:</td>
            <td style="width: 58%;">{{$msg['annonceur']}}</td>
        </tr>
        <tr>
            <th>Titre d'opération</th>
            <td>:</td>
            <td>{{$msg['titre_op']}}</td>
        </tr>
        <tr>
            <th>Nature</th>
            <td>:</td>
            <td>{{$msg['nature']}}</td>
        </tr>
        <tr>
            <th>Type de service</th>
            <td>:</td>
            <td>{{$msg['type_service']}}</td>
        </tr>
        <tr>
            <th>Cible</th>
            <td>:</td>
            <td>{{$msg['cible']}}</td>
        </tr>
        <tr>
            <th>Média / Support</th>
            <td>:</td>
            <td>{{$msg['media-support']}}</td>
        </tr>
        <tr>
            <th>Format</th>
            <td>:</td>
            <td>{{$msg['format']}}</td>
        </tr>
        <tr>
            <th>Emplacement / Tranche horaire</th>
            <td>:</td>
            <td>{{$msg['emplacement_trancheH']}}</td>
        </tr>
    </table>
</div>

