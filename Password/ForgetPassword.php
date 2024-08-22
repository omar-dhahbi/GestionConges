<?php
session_start();
include "../connexion.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function resendEmail($email, $random, $id)
{
    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'omardhahbi68@gmail.com';
        $mail->Password   = 'raxiltdssragwwxj';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->setFrom('omardhahbi68@gmail.com', 'Omar');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Mise a jour de votre mot de passe';
        $email_template = "
        <!DOCTYPE html>
        <html lang='fr'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Mise a jour de votre mot de passe</title>
        </head>
        <body style='font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px;'>
        
            <div style='max-width: 600px; margin: 0 auto; background-color: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);'>
                <h1 style='color: #007bff; text-align: center;'>Trouver Votre Compte</h1>
                <div style='margin-top: 30px;'>
                    <p style='font-size: 16px; color: #333; margin-bottom: 20px;'>Votre code : <strong>$random</strong></p>
                    <p style='font-size: 16px; color: #333; margin-bottom: 20px;'>Pour vérifier votre mot de passe, veuillez cliquer sur le lien ci-dessous :</p>
                    <a href='http://localhost:3000/Password/updatepassword.php?id=$id' style='display: inline-block; background-color: #007bff; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-size: 16px; transition: background-color 0.3s;'>Cliquez ici</a>
                </div>
            </div>
        
        </body>
        </html>
        ";
        $mail->Body = $email_template;

        $mail->send();
        return true;
    } catch (Exception $e) {
        // error_log("Error sending email: {$mail->ErrorInfo}");
        return false;
    }
}

if (isset($_POST['envoyer'])) {
    if (!empty(trim($_POST['email']))) {
        $email = trim($_POST['email']);
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $random = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 5);
            $id = $user['id'];
            $updateStmt = $conn->prepare("UPDATE users SET code = :code WHERE email = :email");
            $updateStmt->bindParam(':code', $random);
            $updateStmt->bindParam(':email', $email);
            $updateStmt->execute();

            if (resendEmail($email, $random, $id)) {
                header("Location: updatepassword.php?id=$id");
                exit;
            } else {
                $_SESSION['status'] = "Erreur lors de l'envoi de l'email.";
            }
        } else {
            $_SESSION['status'] = "Email n'existe pas";
        }
    } else {
        $_SESSION['status'] = "Veuillez remplir le champ email.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 80px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            padding: 20px;
            font-size: 24px;
        }

        .card-body {
            padding: 30px;
        }

        .form-group label {
            font-weight: 600;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 20px;
            padding: 10px 20px;
            font-size: 18px;
            transition: all 0.3s ease;
            min-width: 120px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .error-message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Trouvez votre compte
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['status'])) : ?>
                            <p class="error-message"><?php echo $_SESSION['status']; ?></p>
                            <?php unset($_SESSION['status']); ?>
                        <?php endif; ?>
                        <form id="passwordResetForm" method="POST">
                            <div class="form-group" id="emailFormGroup">
                                <label for="email">Veuillez entrer votre e-mail pour rechercher votre compte:</label>
                                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Entrer votre email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary" name="envoyer">Rechercher</button>
                                <button type="button" class="btn btn-secondary ml-2" onclick="window.location.href='../index.php'">Annuler</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>