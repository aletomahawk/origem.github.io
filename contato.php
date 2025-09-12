<?php
header('Content-Type: application/json');

$response = array();

// Recebe o conteúdo JSON do corpo da requisição
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

// Verifica se os dados foram recebidos corretamente
if (!$data || !isset($data['name'], $data['email'], $data['subject'], $data['message'])) {
    $response['success'] = false;
    $response['message'] = 'Dados inválidos. Por favor, preencha todos os campos.';
    echo json_encode($response);
    exit;
}

$name = trim($data['name']);
$email = trim($data['email']);
$subject = trim($data['subject']);
$message = trim($data['message']);

// Endereço de e-mail para onde a mensagem será enviada
$destinatario = 'seu-email@dominio.com'; // **ATENÇÃO: SUBSTITUA PELO SEU E-MAIL**

// Validação dos dados
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    $response['success'] = false;
    $response['message'] = 'Por favor, preencha todos os campos obrigatórios.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['success'] = false;
    $response['message'] = 'Por favor, insira um endereço de e-mail válido.';
} else {
    // Cabeçalhos para o e-mail
    $headers = "From: " . $name . " <" . $email . ">\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $email_subject = "Formulário de Contato: " . $subject;
    
    // Corpo do e-mail
    $email_body = "Nome: " . $name . "\n";
    $email_body .= "Email: " . $email . "\n\n";
    $email_body .= "Mensagem:\n" . $message;

    // Envia o e-mail
    if (mail($destinatario, $email_subject, $email_body, $headers)) {
        $response['success'] = true;
        $response['message'] = 'Sua mensagem foi enviada com sucesso!';
    } else {
        $response['success'] = false;
        $response['message'] = 'Houve um erro no envio da mensagem. Por favor, tente novamente mais tarde.';
    }
}

echo json_encode($response);
?>
