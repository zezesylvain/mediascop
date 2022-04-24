@extends('layouts.admin')
@section('container')
{!! $titreHtml($tbl) !!}
<style>
   .def-form .col-lg-6{
        margin-top: 10px!important;
    }
   .def-form .col-lg-6 label{
       font-weight: 700!important;
   }
</style>
@if($vue == "formulaire-tableau" || $vue == "formulaire")
    <div class="sparkline16-list responsive-mg-b-30 def-form">
        <div class="sparkline16-graph">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <form method="post" action="{{$laroute ?? ""}}" class="row">
                        {{csrf_field()}}
                        {!!  $formText !!}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@if($vue == "formulaire-tableau" || $vue == "tableau")
    {!! $tableau ?? ""!!}
@endif

@endsection
