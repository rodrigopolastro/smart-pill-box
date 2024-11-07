<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faça login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../../assets/css/general.css">
</head>

<body>
    <main>
        <section>
            <div class="container ">
                <div class="vh-100  d-flex justify-content-center align-items-center">
                    <div class="w-50 bg-white p-5 rounded-3">
                        <h1 class="mb-3">Faça Login</h1>
                        <form action="../../controllers/nursing-homes.php" method="POST" id="login-form">
                            <input type="hidden" name="nursing_homes_action" value="login">
                            <div class="mb-3">
                                <label for="txtEmail" class="form-label">E-mail</label>
                                <input type="email" id="txtEmail" name="email" class="form-control bg-grey">
                            </div>
                            <div class="mb-3">
                                <label for="txtPassword" class="form-label">Senha</label>
                                <input type="password" id="txtPassword" name="password" class="form-control">
                            </div>
                            <input type="submit" value="Entrar" class="btn btn-primary">
                        </form>
                        <?php if (isset($_GET['login_status']) && $_GET['login_status'] == 'incorrect_info') : ?>
                            <p id="errorMessage">Email ou Senha incorretos!</p>
                        <?php endif; ?>
                        <div class="d-flex justify-content-end">
                            <p>Não possui uma conta? <a href="./sign-up.php" class="text-primary">Cadastre-se</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>