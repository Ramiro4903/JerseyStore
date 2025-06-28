<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluir el autoload de PHPMailer
require 'vendor/autoload.php';

// Verificar si se recibe un formulario por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $comentarios = $_POST['comentarios'];

    // Validar el formato del correo electrónico
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo 'error_email';  // Responder con 'error_email' si el correo no tiene un formato válido
        exit;
    }

    // Instanciar PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();  // Usar SMTP
        $mail->Host = 'smtp.gmail.com';  // Servidor SMTP de Gmail
        $mail->SMTPAuth = true;  // Activar autenticación SMTP
        $mail->Username = 'ramiro.ramirez.guzman@gmail.com';  // Tu correo de Gmail
        $mail->Password = 'jkdy zdfn bocp jgqs';  // Contraseña o contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Seguridad STARTTLS
        $mail->Port = 587;  // Puerto SMTP de Gmail

        // Remitente y destinatarios
        $mail->setFrom('ramiro.ramirez.guzman@gmail.com', 'La Casa de los Jerseys');
        $mail->addAddress('ramiro.ramirez0283@alumnos.udg.mx', 'Destinatario');  // Dirección de correo a donde se enviará

        // Campo de "Reply-To"
        $mail->addReplyTo($correo, $nombre);

        // Contenido del correo
        $mail->isHTML(true);  // Indicar que el contenido será en HTML
        $mail->Subject = 'Formulario de Contacto - La Casa de los Jerseys';
        $mail->Body = "Nombre: $nombre<br>Correo: $correo<br>Comentarios: $comentarios";

        // Intentar enviar el correo
        if ($mail->send()) {
            echo 'success';  // Responder con 'success' si el correo se envió correctamente
        } else {
            echo 'error';  // En caso de fallo, responder con 'error'
        }

    } catch (Exception $e) {
        // En caso de excepción, responder con 'error'
        echo 'error';
    }
}
?>
