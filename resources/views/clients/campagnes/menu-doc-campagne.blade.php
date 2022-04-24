<?php
    $icone = [1 => 'television', 2 => 'broadcast-tower', 3 => 'list', 4 => '', 5 => '', 6 => '', 7 => ''];
?>

<ul class="nav nav-pills nav-secondary">
    <li>
        <span style="font-size: 1.2em; color:blue; font-weigth:bold; padding-right: 3.3em;"> Les Visuels </span> 
    </li>
    @php($compteur = 1)
    @foreach($documents as $mid => $tabM)
        <li class="@if($compteur)  active @endif">
            <a class="btn btn-default btn-xs" href="#media-{{ $mid }}-pills" data-toggle="tab">
                <i class="fa fa-{{ $icone[$mid] ?? 'search' }}"></i>  {{ $lesMedias[$mid] }}
            </a>
        </li>
        @php($compteur = 0)
    @endforeach
</ul>

<div class="timeline-heading">
    <h3 class="timeline-title"> 
        
    </h3>
</div>