<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>{{$headTitle}}</title>
        <script src="https://momentjs.com/downloads/moment-with-locales.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script>
            moment.locale('fr');
            var mainContent =
                "<p>Cher/Chère {{$user}},</p>" +
                "<p>Voudriez-vous bien confirmer votre inscription en cliquant sur <a href='{{$link}}'>ce lien</a> ?</p>" +
                "<p>Merci !</p>" +
                "<p>L'équipe de VisiteMonKot</p>";
            $(document).ready(function(){
                $("#main").html(mainContent);
            });

            function initPage(){
                document.getElementById('main').innerHTML = mainContent;
            }

        </script>
    </head>
    <body onload="initPage()">
    <div id="mailsub" class="notification" align="center">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="min-width: 320px;"><tr><td align="center" bgcolor="#e0fff5">
                    <!--[if gte mso 10]>
                    <table width="680" border="0" cellspacing="0" cellpadding="0">
                        <tr><td>
                    <![endif]-->

                    <table border="0" cellspacing="0" cellpadding="0" class="table_width_100" width="100%" style="max-width: 680px; min-width: 300px;">
                        <tr><td>
                                <!-- padding --><div style="height: 80px; line-height: 80px; font-size: 10px;"> </div>
                            </td></tr>
                        <!--header -->
                        <tr><td align="center" bgcolor="green">
                                <!-- padding --><div style="height: 30px; line-height: 30px; font-size: 10px;"> </div>
                                <table width="90%" border="0" cellspacing="0" cellpadding="0">
                                    <tr><td align="left"><!--

                Item --><div class="mob_center_bl" style="float: left; display: inline-block; width: 115px;">
                                                <table class="mob_center" width="115" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
                                                    <tr><td align="left" valign="middle">
                                                            <!-- padding --><div style="height: 20px; line-height: 20px; font-size: 10px;"> </div>
                                                            <table width="115" border="0" cellspacing="0" cellpadding="0" >
                                                                <tr><td align="left" valign="top" class="mob_center">
                                                                        <a href="#" target="_blank" style="color: #596167; font-family: Arial, Helvetica, sans-serif; font-size: 13px;">
                                                                            <font face="Arial, Helvetica, sans-seri; font-size: 13px;" size="3" color="#596167">
                                                                                <img src="http://localhost:3000/static/media/vmk_v5_1.bb6934da.svg" alt="vmk" border="0" style="display: block;" /></font></a>
                                                                    </td></tr>
                                                            </table>
                                                        </td></tr>
                                                </table></div><!-- Item END--><!--[if gte mso 10]>
                                            </td>
                                            <td align="right">
                                            <![endif]--><!--

                Item --><div class="mob_center_bl" style="float: right; display: inline-block; width: 88px;">
                                                <table width="88" border="0" cellspacing="0" cellpadding="0" align="right" style="border-collapse: collapse;">
                                                    <tr><td align="right" valign="middle">
                                                            <!-- padding --><div style="height: 20px; line-height: 20px; font-size: 10px;"> </div>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                                                                <tr><td align="right">
                                                                        <!--social -->
                                                                        <div class="mob_center_bl" style="width: 88px;">
                                                                            <table border="0" cellspacing="0" cellpadding="0">
                                                                                <tr><td align="left" valign="top" class="mob_center">
                                                                                        <a href="#" target="_blank" style="color: #596167; font-family: Arial, Helvetica, sans-serif; font-size: 13px;">
                                                                                            <font face="Arial, Helvetica, sans-serif; font-size: 15px;" size="3" color="#fff">
                                                                                                <div alt="Metronic" border="0" style="display: block;">visitemonkot.be</div></font></a>
                                                                                    </td></tr>
                                                                            </table>
                                                                        </div>
                                                                        <!--social END-->
                                                                    </td></tr>
                                                            </table>
                                                        </td></tr>
                                                </table></div><!-- Item END--></td>
                                    </tr>
                                </table>
                                <!-- padding --><div style="height: 50px; line-height: 50px; font-size: 10px;"> </div>
                            </td></tr>
                        <!--header END-->

                        <!--content 1 -->
                        <tr><td align="center" bgcolor="#fbfcfd">
                                <table width="90%" border="0" cellspacing="0" cellpadding="0">
                                    <tr><td align="center">
                                            <!-- padding --><div style="height: 60px; line-height: 60px; font-size: 10px;"> </div>
                                            <div style="line-height: 44px;">
                                                <font face="Arial, Helvetica, sans-serif" size="5" color="#57697e" style="font-size: 34px;">
                    <span style="font-family: Arial, Helvetica, sans-serif; font-size: 34px; color: #57697e;">
                        {{$title}}
                    </span></font>
                                            </div>
                                            <!-- padding --><div style="height: 40px; line-height: 40px; font-size: 10px;"> </div>
                                        </td></tr>
                                    <tr><td align="center">
                                            <div style="line-height: 24px;">
                                                <font face="Arial, Helvetica, sans-serif" size="4" color="#57697e" style="font-size: 15px;">
                    <span id="main" style="font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #57697e;">
                        <p>Cher/Chère {{$user}},</p>
                        <p>Voudriez-vous bien confirmer votre inscription en cliquant sur <a class="btn btn-success" href="http://localhost:8080{{$link}}">ce lien</a> ?</p>
                        <p>Merci !</p>
                        <p>L'équipe de VisiteMonKot</p>
                    </span></font>
                                            </div>
                                            <!-- padding --><div style="height: 40px; line-height: 40px;"> </div>
                                        </td></tr>
                                    <tr><td align="center">
                                            <div style="line-height: 24px; width: 50%" class='row'>

                                            </div>
                                            <!-- padding --><div style="height: 60px; line-height: 60px;"> </div>
                                        </td></tr>
                                </table>
                            </td></tr>
                        <!--content 1 END-->

                        <!--footer -->
                        <tr><td class="iage_footer" align="center" bgcolor="#ffffff">
                                <!-- padding --><div style="height: 80px; line-height: 80px; font-size: 10px;"> </div>

                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr><td align="center">
                                            <font face="Arial, Helvetica, sans-serif" size="3" color="#96a5b5" style="font-size: 13px;">
                <span style="font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #96a5b5;">
                    2019 © visitemonkot. Tous droits reservé.
                </span></font>
                                        </td></tr>
                                </table>

                                <!-- padding --><div style="height: 30px; line-height: 30px; font-size: 10px;"> </div>
                            </td></tr>
                        <!--footer END-->
                        <tr><td>
                                <!-- padding --><div style="height: 80px; line-height: 80px; font-size: 10px;"> </div>
                            </td></tr>
                    </table>
                    <!--[if gte mso 10]>
                    </td></tr>
                    </table>
                    <![endif]-->
                </td></tr>
        </table>
    </div>
    </body>
</html>