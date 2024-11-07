<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crie sua conta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../../assets/css/general.css">
</head>

<body>
    <main>
        <section class="">
            <div class="container">
                <div class="vh-100 d-flex justify-content-center align-items-center">
                    <div class="w-50 bg-white p-5 rounded-3">
                        <h1 class="mb-3">Cadastre-se</h1>
                        <form action="../../controllers/nursing-homes.php" method="POST" id="sign-up-form">
                            <input type="hidden" name="nursing_homes_action" value="sign_up">
                            <div class="mb-3">
                                <label for="txtCompanyName" class="form-label">Nome da Casa de Repouso</label>
                                <input type="text" id="txtCompanyName" name="company_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="txtEmail" class="form-label">E-mail</label>
                                <input type="email" id="txtEmail" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="txtPassword" class="form-label">Senha</label>
                                <input type="password" id="txtPassword" name="password" class="form-control" required>
                            </div>
                            <input type="submit" value="Cadastrar" class="btn btn-primary">
                        </form>
                        <?php if (isset($_GET['sign_up_status']) && $_GET['sign_up_status'] == 'already_registered') : ?>
                            <p id="errorMessage" class="mt-3 fw-bold">Casa de Repouso já cadastrada!</p>
                        <?php endif; ?>
                        <div class="d-flex justify-content-end">
                            <p>Já possui uma conta? <a href="./login.php" class="text-primary">Entre</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>