<script language="Javascript">
    $(document).ready(function () {
        
        setTimeout(function(){
            ui.notify('Notification 01', 'Cette premiere notification s\'affichera quelques secondes puis disparaitra.')
                .effect('slide');
            
            setTimeout(function(){
                ui.notify('Notification 02', 'Cette deuxieme notification s\'affichera plus longtemps que la premiere.')
                    .closable()
                    .hide(8000)
                    .effect('slide');
                
                setTimeout(function(){
                    ui.notify('Bienvenue sur le 41Mag', 'C\'est votre '+lit_cook('visite')+' visite(s) sur notre demo de notification like Growl')
                        .sticky()
                        .effect('slide');
                }, 200);
            }, 200);
        }, 200);
        
        
    });
</script>
