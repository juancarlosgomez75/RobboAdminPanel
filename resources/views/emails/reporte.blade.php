<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Reporte de Quincena</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f4; font-family:Arial, sans-serif;">
  <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="padding:20px 0;">
    <tr>
      <td align="center">
        <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color:#ffffff; border-radius:10px; border-left:1px solid #ddd; border-right:1px solid #ddd;">
          
          <!-- Header -->
          <tr>
            <td bgcolor="#ca4a4c" style="padding:50px 20px 20px 20px; text-align:center;">
                <img src="https://clientcontrol.robbocock.com/media/Robbo_Logo_Completo.png" alt="Robbocock Logo" width="200" style="display:block; margin: 0 auto;"><br>
                <h1 style="color:#ffffff; font-size:30px; margin:10px 0 5px; text-align:center;">{{$reason}}</h1>
                <p style="color:#ffffff; font-size:16px; margin:0; text-align:center;">{{ucfirst($informacion["FechaInicio"] )}} - {{ucfirst($informacion["FechaFin"])}}</p>
              </td>              
          </tr>

          <!-- Content -->
          <tr>
            <td style="padding:20px;">
              <p style="font-size:22px; margin-top:0;">Hola, <b style="color:#ca4a4c;">{{$informacion["Contact"]}}</b></p>

              <p style="font-size:16px;">En Robbocock queremos que siempre tengas a tu alcance la información necesaria para tomar decisiones estratégicas sobre tu operación en <b style="color:#ca4a4c;">{{$informacion["StudyName"]}}</b>, por ello, te compartimos el reporte de ventas generadas por tus artistas mediante el uso de nuestros dispositivos.</p>

              <p style="font-size:16px;">Adjunto a este correo encontrarás el documento con todos los detalles.</p>

              <p style="font-size:16px;">Si tienes alguna duda, no dudes en ponerte en contacto con nosotros, te ayudaremos a resolver cualquier inquietud que tengas.</p>

              <p style="font-size:20px;">
                Un saludo,<br>
                <b style="color:#ca4a4c;">Equipo de Robbocock</b>
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td bgcolor="#dddddd" align="center" style="padding:20px; text-align:center">
              <p style="margin:5px 0; text-align:center; font-size:12px;">Este es un mensaje generado automáticamente por nuestro sistema, por favor no responder. Si tiene dudas, comuníquese con <a href="mailto:administracion@coolsofttechnology.com" style="color:#ca4a4c;"><b>administracion@coolsofttechnology.com</b></a></p>
              <p style="margin:5px 0; text-align:center; font-size:12px;">Visítanos en: <a href="https://www.robbocock.com/product/robbocock/" target="_blank" style="color:#ca4a4c;">www.robbocock.com</a></p>
              <p style="margin:5px 0; text-align:center; font-size:12px;">Síguenos en: <a href="https://www.instagram.com/robbo.cock/" target="_blank" style="color:#ca4a4c;">Instagram</a></p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
