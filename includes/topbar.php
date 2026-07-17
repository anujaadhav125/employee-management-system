<div class="main">

<div class="topbar d-flex justify-content-between align-items-center">

    <!-- Left Side -->

    <div class="d-flex align-items-center">

        <button id="sidebarToggle" class="btn btn-light me-3">

            <i class="bi bi-list fs-4"></i>

        </button>

        <h4 class="mb-0 fw-bold">

            Dashboard

        </h4>

    </div>

    <!-- Right Side -->

    <div class="d-flex align-items-center gap-3">

        <!-- Dark Mode -->

        <button
            id="themeToggle"
            class="btn btn-light rounded-circle shadow-sm"
            title="Toggle Theme">

            <i class="bi bi-moon-fill"></i>

        </button>

        <!-- User -->

        <div class="text-end">

            <small class="text-muted d-block">

                Welcome,

            </small>

            <strong>

                <?= htmlspecialchars($user['name']); ?>

            </strong>

        </div>

        <!-- Logout -->

        <a
            href="../auth/logout.php"
            class="btn btn-outline-danger">

            <i class="bi bi-box-arrow-right"></i>

            Logout

        </a>

    </div>

</div>