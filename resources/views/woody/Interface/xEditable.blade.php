<a href="#{{$column}}" class="x-edit-{{$column}}" id="{{$column}}" data-pk="{{$pk}}"  data-placeholder="{{$dataPlaceholder ?? ""}}" data-title="{{$dataTitle ?? ""}}" data-placement="{{$dataPlacement ?? ""}}" data-type="{{$type ?? 'text'}}">
    {!! $texte !!}
</a>
@php
    $htmlSource = $source != "" ? "source: [$source]" : "";
    @endphp
<script type="text/javascript">
    $(document).ready(function () {
        $.fn.editable.defaults.url = "{{route('xEditableUpdate')}}";
        $(".x-edit-"+"{{$column}}").editable.defaults.params = function(params){
            params._token = $("meta[name=_token]").attr("content");
            params.table = '{{$table}}';
            return params;
        } ;
    
        $(".x-edit-"+"{{$column}}").editable(
            {
                {!! $htmlSource !!}
            }
        );
    })
</script>
