
<?php

declare(strict_types=1);

// Verifica se a requisição utiliza POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);

    header('Allow: POST');

    exit('Método não permitido. Envie o formulário utilizando POST.');
}

// Recebimento e normalização dos dados
$nome = trim((string) ($_POST['nome'] ?? ''));

$email = trim((string) ($_POST['email'] ?? ''));

$experiencia = trim((string) ($_POST['experiencia'] ?? ''));

$turno = trim((string) ($_POST['turno'] ?? ''));

$mensagem = trim((string) ($_POST['mensagem'] ?? ''));

$oficinasRecebidas = $_POST['oficinas'] ?? [];

$erros = [];

// Listas de valores permitidos
$experienciasPermitidas = [
    'iniciante',
    'intermediario',
    'avancado'
];

$turnosPermitidos = [
    'manha',
    'noite'
];

$oficinasPermitidas = [
    'html_css',
    'js_dom',
    'php_backend'
];

// Nomes para exibição
$nomesExperiencia = [
    'iniciante' => 'Iniciante',
    'intermediario' => 'Intermediário',
    'avancado' => 'Avançado'
];

$nomesTurno = [
    'manha' => 'Manhã',
    'noite' => 'Noite'
];

$nomesOficinas = [
    'html_css' => 'HTML e CSS',
    'js_dom' => 'JavaScript e DOM',
    'php_backend' => 'PHP e Backend'
];

// Validação do nome
if (mb_strlen($nome, 'UTF-8') < 3) {

    $erros[] = 'O nome deve possuir pelo menos 3 caracteres.';

}

// Validação do e-mail
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $erros[] = 'Informe um endereço de e-mail válido.';

}

// Validação da experiência
if (!in_array($experiencia, $experienciasPermitidas, true)) {

    $erros[] = 'Selecione um nível de experiência válido.';

}

// Validação do turno
if (!in_array($turno, $turnosPermitidos, true)) {

    $erros[] = 'Selecione um turno válido.';

}

// Validação das oficinas
$oficinas = [];

if (!is_array($oficinasRecebidas)) {

    $erros[] = 'A seleção de oficinas é inválida.';

} else {

    foreach ($oficinasRecebidas as $oficina) {

        if (
            !is_string($oficina) ||
            !in_array($oficina, $oficinasPermitidas, true)
        ) {

            $erros[] = 'Uma ou mais oficinas são inválidas.';

            break;
        }

        $oficinas[] = $oficina;
    }

    if (count($oficinas) === 0) {

        $erros[] = 'Selecione pelo menos uma oficina.';

    }
}

// Validação da mensagem
if (mb_strlen($mensagem, 'UTF-8') > 200) {

    $erros[] = 'A mensagem deve possuir no máximo 200 caracteres.';

}

// Função para proteger a saída HTML contra XSS
function e(string $dado): string {

    return htmlspecialchars(
        $dado,
        ENT_QUOTES,
        'UTF-8'
    );

}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Resultado da inscrição</title>

    <link rel="stylesheet" href="style.css">

    <style>

        .result-card {
            max-width: 720px;
            margin: 55px auto;
        }

        .result-card h1 {
            font-size: 2rem;
        }

        .success-mark {
            color: #217a55;
            font-weight: bold;
        }

        .error-list {
            color: #a52c25;
            padding-left: 22px;
        }

        .data-list {
            display: grid;
            gap: 14px;
            margin: 24px 0;
        }

        .data-item {
            padding: 13px 15px;
            background: #f4f7f9;
            border-radius: 9px;
        }

        .data-item strong {
            display: block;
            color: #17324d;
            margin-bottom: 3px;
        }

        .back-link {
            display: inline-block;
            margin-top: 12px;
            color: #23618a;
            font-weight: bold;
        }

    </style>

</head>

<body>

<header class="site-header">

    <div class="container header-content">

        <a class="brand" href="index.html">
            Tech<span>Experience</span>
        </a>

        <span class="event-year">
            EVENTO ACADÊMICO • 2026
        </span>

    </div>

</header>

<main class="container">

    <section class="form-card result-card">

        <?php if (!empty($erros)): ?>

            <p class="eyebrow">VERIFIQUE OS DADOS</p>

            <h1>Não foi possível concluir a inscrição</h1>

            <p>
                Corrija os itens abaixo e tente novamente:
            </p>

            <ul class="error-list">

                <?php foreach ($erros as $erro): ?>

                    <li><?= e($erro) ?></li>

                <?php endforeach; ?>

            </ul>

            <a class="back-link" href="index.html">
                ← Voltar ao formulário
            </a>

        <?php else: ?>

            <p class="eyebrow success-mark">
                INSCRIÇÃO RECEBIDA
            </p>

            <h1>Parabéns, <?= e($nome) ?>!</h1>

            <p>
                Sua inscrição no Tech Experience 2026
                foi enviada com sucesso.
            </p>

            <div class="data-list">

                <div class="data-item">

                    <strong>Nome completo</strong>

                    <?= e($nome) ?>

                </div>

                <div class="data-item">

                    <strong>E-mail</strong>

                    <?= e($email) ?>

                </div>

                <div class="data-item">

                    <strong>Nível de experiência</strong>

                    <?= e($nomesExperiencia[$experiencia]) ?>

                </div>

                <div class="data-item">

                    <strong>Turno de preferência</strong>

                    <?= e($nomesTurno[$turno]) ?>

                </div>

                <div class="data-item">

                    <strong>Oficinas selecionadas</strong>

                    <ul