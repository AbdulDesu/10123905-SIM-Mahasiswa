<?php
declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\MahasiswaController;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\Lang;
use App\Core\View;
use App\Repositories\MahasiswaRepository;
use App\Repositories\UserRepository;
use App\Validators\MahasiswaValidator;

require __DIR__ . '/../app/bootstrap.php';

/**
 * Nama: Abdul Richard
 * NIM: 10123905
 * Kelas: IF12-K - Teknik Informatika
 */

$page   = isset($_GET['page']) && is_string($_GET['page']) ? $_GET['page'] : '';
$method = $_SERVER['REQUEST_METHOD'];
$id     = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Semua request POST wajib membawa token CSRF yang valid.
if ($method === 'POST' && !Csrf::verify($_POST['_token'] ?? '')) {
    http_response_code(419);
    View::render('error', ['title' => t('err.title'), 'message' => t('err.csrf')]);
    exit;
}

// Controller mahasiswa hanya dibuat setelah login terverifikasi (halaman terproteksi).
$makeMahasiswaController = static function (): MahasiswaController {
    Auth::requireLogin();
    $repo = new MahasiswaRepository(Database::connection());

    return new MahasiswaController($repo, new MahasiswaValidator($repo));
};

try {
    switch ($page) {
        case 'login':
            $auth = new AuthController(new UserRepository(Database::connection()));
            if ($method === 'POST') {
                $auth->login();
            } else {
                $auth->showLogin();
            }
            break;

        case 'logout':
            if ($method !== 'POST') {
                redirect(url('mahasiswa'));
            }
            (new AuthController(new UserRepository(Database::connection())))->logout();
            break;

        case 'lang':
            Lang::set(is_string($_GET['set'] ?? null) ? $_GET['set'] : '');
            redirect(safeBackUrl(url(Auth::check() ? 'mahasiswa' : 'login')));
            break;

        case 'mahasiswa':
            $makeMahasiswaController()->index();
            break;

        case 'mahasiswa-create':
            $controller = $makeMahasiswaController();
            if ($method === 'POST') {
                $controller->store();
            } else {
                $controller->create();
            }
            break;

        case 'mahasiswa-edit':
            $controller = $makeMahasiswaController();
            if ($method === 'POST') {
                $controller->update($id);
            } else {
                $controller->edit($id);
            }
            break;

        case 'mahasiswa-delete':
            if ($method !== 'POST') {
                redirect(url('mahasiswa'));
            }
            $makeMahasiswaController()->delete($id);
            break;

        default:
            redirect(url(Auth::check() ? 'mahasiswa' : 'login'));
    }
} catch (PDOException $e) {
    error_log('[DB] ' . $e->getMessage());
    http_response_code(500);
    View::render('error', ['title' => t('err.title'), 'message' => t('err.db')]);
}
