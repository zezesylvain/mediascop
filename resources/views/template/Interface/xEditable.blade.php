
<a href="#{{$column}}" class="x-edit-{{$column}}{{$cpt}}" id="{{$column}}" data-pk="{{$pk}}"  data-placeholder="{{$dataPlaceholder ?? ""}}" data-title="{{$dataTitle ?? ""}}" data-placement="{{$dataPlacement ?? ""}}" data-type="{{$type ?? 'text'}}">
    {!! $texte !!}
</a>
@php
    $htmlSource = $source != "" ? "source: [$source]" : "";
    @endphp
<script type="text/javascript">
    $(document).ready(function () {
        $.fn.editable.defaults.url = "{{route('xEditableUpdate')}}";
        $(".x-edit-{{$column}}{{$cpt}}").editable.defaults.params = function(params){
            params._token = $("meta[name=_token]").attr("content");
            params.table = '{{$table}}';
            return params;
        } ;
    
        $(".x-edit-{{$column}}{{$cpt}}").editable(
            {
                value: this.value,
                {!! $htmlSource !!}
            }
        );
    })
</script>
