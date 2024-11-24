<header class="d-block p-5 text-white">
    <div class="container">
        <div class="d-flex justify-content-between">
            <div class="">
                <h3>Smart Pill Box</h3>
            </div>
            <div class="">
                <ul class="nav me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li class="me-4">
                        <a href="./overview.php" class="btn btn-primary px-3 text-white">Início</a>
                    </li>
                    <li class="me-4">
                        <a href="./people-in-care.php" class="btn btn-primary px-3 text-white">Pessoas Sob Cuidado</a>
                    </li>
                    <li class="me-4">
                        <a href="./medicines.php" class="btn btn-primary px-3 text-white">Medicamentos</a>
                    </li>
                    <li class="me-4">
                        <a href="./doses.php" class="btn btn-primary px-3 text-white">Doses</a>
                    </li>
                </ul>
            </div>
            <div class="d-flex">
                <div class="me-3 d-flex align-items-center">
                    <span><?= $_SESSION['logged_nursing_home_company_name'] ?></span>
                </div>
                <form method="POST" action="../../controllers/nursing-homes.php">
                    <input type="hidden" name="nursing_homes_action" value="logout">
                    <input type="submit" value="Sair" class="btn btn-light">
                </form>
            </div>
        </div>
    </div>
</header>