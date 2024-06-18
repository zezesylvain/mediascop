<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Portfolio - Responsive Email Template</title>
    <style type="text/css">
        /* ----- Custom Font Import ----- */
        @import url(https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic&subset=latin,latin-ext);

        /* ----- Text Styles ----- */
        table{
            font-family: 'Lato', Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-font-smoothing: antialiased;
            font-smoothing: antialiased;
        }

        @media only screen and (max-width: 700px){
            /* ----- Base styles ----- */
            .full-width-container{
                padding: 0 !important;
            }

            .container{
                width: 100% !important;
            }

            /* ----- Header ----- */
            .header td{
                padding: 30px 15px 30px 15px !important;
            }

            /* ----- Projects list ----- */
            .projects-list{
                display: block !important;
            }

            .projects-list tr{
                display: block !important;
            }

            .projects-list td{
                display: block !important;
            }

            .projects-list tbody{
                display: block !important;
            }

            .projects-list img{
                margin: 0 auto 25px auto;
            }

            /* ----- Half block ----- */
            .half-block{
                display: block !important;
            }

            .half-block tr{
                display: block !important;
            }

            .half-block td{
                display: block !important;
            }

            .half-block__image{
                width: 100% !important;
                background-size: cover;
            }

            .half-block__content{
                width: 100% !important;
                box-sizing: border-box;
                padding: 25px 15px 25px 15px !important;
            }

            /* ----- Hero subheader ----- */
            .hero-subheader__title{
                padding: 7px 7px 7px 7px !important;
                font-size: 9pt !important;
            }

            .hero-subheader__content{
                padding: 0 15px 90px 15px !important;
            }

            /* ----- Title block ----- */
            .title-block{
                padding: 0 15px 0 15px;
            }

            /* ----- Paragraph block ----- */
            .paragraph-block__content{
                padding: 25px 15px 18px 15px !important;
            }

            /* ----- Info bullets ----- */
            .info-bullets{
                display: block !important;
            }

            .info-bullets tr{
                display: block !important;
            }

            .info-bullets td{
                display: block !important;
            }

            .info-bullets tbody{
                display: block;
            }

            .info-bullets__icon{
                text-align: center;
                padding: 0 0 15px 0 !important;
            }

            .info-bullets__content{
                text-align: center;
            }

            .info-bullets__block{
                padding: 25px !important;
            }

            /* ----- CTA block ----- */
            .cta-block__title{
                padding: 35px 15px 0 15px !important;
            }

            .cta-block__content{
                padding: 20px 15px 27px 15px !important;
            }

            .cta-block__button{
                padding: 0 15px 0 15px !important;
            }

            .header tr td{
                border-top: solid 5px #c9c9c9!important;
            }

            .hero-subheader{
                border-collapse: collapse!important;
            }
            .hero-subheader td{
                border: 1px solid #c9c9c9!important;
            }
        }
    </style>

    <!--[if gte mso 9]><xml>
    <o:OfficeDocumentSettings>
        <o:AllowPNG/>
        <o:PixelsPerInch>96</o:PixelsPerInch>
    </o:OfficeDocumentSettings>
    </xml><![endif]-->
</head>

<body style="padding: 0; margin: 0;" bgcolor="#eeeeee">
<span style="color:transparent !important; overflow:hidden !important; display:none !important; line-height:0px !important; height:0 !important; opacity:0 !important; visibility:hidden !important; width:0 !important; mso-hide:all;">This is your preheader text for this email (Read more about email preheaders here - https://goo.gl/e60hyK)</span>

<!-- / Full width container -->
<table class="full-width-container" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" bgcolor="#eeeeee" style="width: 100%; height: 100%; padding: 30px 0 30px 0;">
    <tr>
        <td align="center" valign="top">
            <!-- / 700px container -->
            <table class="container" border="0" cellpadding="0" cellspacing="0" width="700" bgcolor="#ffffff" style="width: 700px;">
                <tr>
                    <td align="center" valign="top">
                        <!-- / Header -->
                        <table class="container header" border="0" cellpadding="0" cellspacing="0" width="620" style="width: 620px;">
                            <tr>
                                <td style="padding: 30px 0 30px 0; border-bottom: solid 3px #c9c9c9;width: 20%;" align="left">
                                    <a href="#" style="font-size: 30px; text-decoration: none; color: #000000;">
                                        {{--<img src="{{ asset('medias/speednews.jpeg') }}" alt="SpeedNewsLogo" width="120" height="50">--}}
                                        <img src="https://zupimages.net/viewer.php?id=23/09/rdlb.jpeg" alt="SpeedNewsLogo" width="120" height="50">
                                    </a>
                                </td>
                                <td style="width: 60%;"></td>
                                <td style="padding: 30px 0 30px 0; border-bottom: solid 3px #c9c9c9;align-content: end;width: 20%; " align="left">
                                    <a href="#" style="font-size: 30px; text-decoration: none; color: #000000;display: block;width: 100%;float: left;">
                                        {{--<img src="{{ asset('client/index_fichiers/logo-nsc.gif') }}" style="" alt="NSC Logo" width="120" height="50">--}}
                                        <img src="https://zupimages.net/viewer.php?id=23/09/vq5a.gif" style="" alt="NSC Logo" width="120" height="50">
                                    </a>
                                </td>
                            </tr>
                        </table>
                        <!-- /// Header -->
                        <table class="container hero-subheader" border="0" cellpadding="0" cellspacing="0" width="620" style="width: 620px;border-collapse: collapse!important;margin-top: 4px;max-height: 25px!important;font-weight: 600;">
                            <tr>
                                <td class="hero-subheader__title" style="padding: 3px; border: solid 0 #c9c9c9;width:33.33%;min-height: 25px!important; background-color: #fe0000;color: #EEEEEE;font-size: 9pt!important;border-right: solid 5px #ffffff;" align="left">
                                    <p>ABIDJAN ET INTERIEUR</p>
                                </td>
                                <td class="hero-subheader__title" style="padding: 3px; border: solid 0 #c9c9c9;width:33.33%;min-height: 25px!important;background-color: #bdbaba;color: #1343a0;font-size: 9pt!important;border-right: solid 5px #ffffff;">
                                    <p>{{ $donnees['laDateDuJour'] ?? '' }}</p>
                                </td>
                                <td class="hero-subheader__title" style="padding: 3px; border-bottom: solid 3px #c9c9c9;align-content: end;width: 33.33%;min-height: 25px!important;color: #fe0000;background-color: #BDBABA;font-size: 9pt!important;" align="right">
                                    <p>{{ $donnees['secteur'] ?? 'TELECOMS' }}</p>
                                </td>
                            </tr>
                        </table>
                        <!-- / Hero subheader -->
                        <table class="container hero-subheader" border="0" cellpadding="0" cellspacing="0" width="620" style="width: 620px; border-collapse: collapse!important;margin-top: 4px;background-color: #f8f8f8;">
                            <tr>
                                <td class="hero-subheader__title" style="font-size: 8pt; font-weight: bold; padding: 5px 2px 3px 0;border: 1px solid #c9c9c9;width: 25%;" align="left">Annonceur</td>
                                <td class="hero-subheader__title" style="font-size: 13px; font-weight: bold; padding: 5px 0 3px 0;border: 1px solid #c9c9c9;width: 75%;" align="left">
                                    {{ $donnees['annonceur'] }}
                                </td>
                            </tr>
                            <tr>
                                <td class="hero-subheader__title" style="font-size: 8pt; font-weight: bold; padding: 5px 2px 3px 0;border: 1px solid #c9c9c9;width: 25%;" align="left">Titre d'opération(*)</td>
                                <td class="hero-subheader__title" style="font-size: 13px; font-weight: bold; padding: 5px 0 3px 0;border: 1px solid #c9c9c9;width: 75%;" align="left">
                                    {{ $donnees['operation'] }}
                                </td>
                            </tr>
                            <tr>
                                <td class="hero-subheader__title" style="font-size: 8pt; font-weight: bold; padding: 5px 2px 3px 0;border: 1px solid #c9c9c9;width: 25%;" align="left">Type de service</td>
                                <td class="hero-subheader__title" style="font-size: 13px; font-weight: bold; padding: 5px 0 3px 0;border: 1px solid #c9c9c9;width: 75%;" align="left">
                                    {{ $donnees['typeservice'] }}
                                </td>
                            </tr>
                            <tr>
                                <td class="hero-subheader__title" style="font-size: 8pt; font-weight: bold; padding: 5px 2px 3px 0;border: 1px solid #c9c9c9;width: 25%;" align="left">Cible</td>
                                <td class="hero-subheader__title" style="font-size: 13px; font-weight: bold; padding: 5px 0 3px 0;border: 1px solid #c9c9c9;width: 75%;" align="left">
                                    {{ $donnees['cible'] }}
                                </td>
                            </tr>
                            <tr>
                                <td class="hero-subheader__title" style="font-size: 8pt; font-weight: bold; padding: 5px 2px 3px 0;border: 1px solid #c9c9c9;width: 25%;" align="left">Medias / Support</td>
                                <td class="hero-subheader__title" style="font-size: 13px; font-weight: bold; padding: 5px 0 3px 0;border: 1px solid #c9c9c9;width: 75%;" align="left">
                                    {{ $donnees['media'] }} / {{ $donnees['support'] }}
                                </td>
                            </tr>
                            <tr>
                                <td class="hero-subheader__title" style="font-size: 8pt; font-weight: bold; padding: 5px 2px 3px 0;border: 1px solid #c9c9c9;width: 25%;" align="left">Format</td>
                                <td class="hero-subheader__title" style="font-size: 13px; font-weight: bold; padding: 5px 0 3px 0;border: 1px solid #c9c9c9;width: 75%;" align="left">
                                    {{ $donnees['format'] ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="hero-subheader__title" style="font-size: 8pt; font-weight: bold; padding: 5px 2px 3px 0;border: 1px solid #c9c9c9;width: 25%;" align="left">Emplacement / Tranche horaire</td>
                                <td class="hero-subheader__title" style="font-size: 13px; font-weight: bold; padding: 5px 0 3px 0;border: 1px solid #c9c9c9;width: 75%;" align="left"></td>
                            </tr>
                        </table>
                        <!-- /// Hero subheader -->

                        <!-- / Title -->
                        <table class="container title-block" style="border: 2px solid #c9c9c9;margin-top: 3px;min-height: 100px;width: 620px;" border="0" cellpadding="0" cellspacing="0" width="620">
                            @foreach($donnees['documents'] as $document)
                                @if(in_array(strtolower($document['type']),['jpg','jpeg','png']))
                                    <tr>
                                        <td align="center" valign="top" style="vertical-align: middle;">
                                            <img src="{{ asset("upload/campagnes/{$document['fichier']}") }}" alt="Visuel Campagne" >
                                        </td>
                                    </tr>
                                @endif
                                @if(in_array(strtolower($document['type']),['mp4','avi','vfw','mpg','mpeg']))
                                    <tr>
                                        <td align="center" valign="top" style="vertical-align: middle;">
                                            <video width="320" height="240" controls>
                                                <source src="{{ asset("upload/campagnes/{$document['fichier']}") }}" type="video/{{$document['type']}}">
                                                Error Message
                                            </video>
                                        </td>
                                    </tr>
                                @endif
                                @if(in_array(strtolower($document['type']),['mp3','wav','wma','mid','midi','ogg']))
                                    <tr>
                                        <td align="center" valign="top" style="vertical-align: middle;">
                                            <figure>
                                                <figcaption>Listen to the T-Rex:</figcaption>
                                                <audio
                                                        controls
                                                        src="{{asset("upload/campagnes/{$document['fichier']}")}}">
                                                    Your browser does not support the
                                                    <code>audio</code> element.
                                                </audio>
                                            </figure>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                        <!-- /// Title -->

                        <!-- / Projects list -->
                        <table class="container projects-list" border="0" cellpadding="0" cellspacing="0" width="620" style="border: 2px solid #c9c9c9;margin-top: 3px;min-height: 100px;width: 620px;background-color: #ffff01;font-size: 9pt;padding: 8px;">
                            <tr>
                                <td style="width: 80%;">
                                    <p>
                                        En 1 clic, accédez à votre solution de pige en ligne <span style="color: #f03012;">E-MediaScop</span> <a href="">(www.mediascop.net)</a>
                                    </p>
                                    <ul>
                                        <li>Retrouvez tous les Speednews(Alertes) et l'archive des rapports periodiques</li>
                                        <li>Visualisez toutes les campagnes illustrées selon vos critères de choix</li>
                                        <li>Créez vos propres rapports de Pige selon vos besoins (graphiques,tableaux)</li>
                                    </ul>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                        <!-- /// Projects list -->

                        <!-- / Footer -->
                        <table class="container" border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                            <tr>
                                <td align="center">
                                    <table class="container" border="0" cellpadding="0" cellspacing="0" width="620" align="center" style="border: 2px solid #c9c9c9; width: 620px;font-size: 8pt;margin-top: 5px;margin-bottom: 10px;">
                                        <tr>
                                            <td style="text-align: center; padding: 10px 20px 2px 20px;">
                                                <p>
                                                    <span style="color: #f03012;">@ NS CONSULTING.</span> <b>Tel:</b> (+225)22 49 94 84|<b>Fax:</b> (+225)22 49 94 85|<b>e-mail:</b> <a href="">info@nsconsulting.ci</a><br>
                                                    <b>Siège social</b> : RCI - Abidjan, Cocody Riviéra Palmeraie les Rosiers | <b>Url</b> :
                                                    <a href="">www.nsconsultingci.com</a>
                                                </p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td align="middle" width="100" height="2" style="width: 100%; height: 2px; font-size: 7.5pt;padding: 5px;">
                                                <p style="text-align: justify;">
                                                    <b>CONFIDENTIALITE</b> - Les informations contenues dans ce mail sont strictement confidentielles et destinées à l'usage exclusif de la (ou des) personnes designées ci-dessus. Si vous n'êtes pas le destinataire de ce document, ou chargé de le transmettre au destinataire, sachez que <span style="color: #f03012;">la diffusion, ou la copie de ces informations est strictement interdite.</span> Si vous avez reçu ce document par erreur, merci de nous en avertir et de detruire toute copie de ce document.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <!-- /// Footer -->
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>