<?php

namespace App\Controllers\Admin\UserManagement;

class UserClosebook extends BaseUserController
{
    /**
     * Switch user flag (Active/Inactive)
     */
    public function flag_switch($iduser = false)
    {
        if (!$iduser) {
            $this->sendAlert('ID Pengguna tidak ditemukan', 'danger');
            return redirect()->back();
        }

        // Fetch user data directly with Query Builder for 100% reliability
        $db = \Config\Database::connect();
        $user = $db->table('tb_user')->where('iduser', $iduser)->get()->getRow();

        if (!$user) {
            $this->sendAlert('Data pengguna tidak ditemukan di database', 'danger');
            return redirect()->back();
        }

        // Current flag: 0 = Nonaktif, 1 = Aktif
        if ($user->flag == 0) {
            // ACTION: ACTIVATE

            // ACTION: ACTIVATE

            // Check for Closebook Rule (1 year wait)
            $bypass_note = "";
            if ($user->closebook_param_count >= 1 && !empty($user->closebook_last_updated)) {
                $last_update = strtotime($user->closebook_last_updated);
                $one_year_later = strtotime('+1 year', $last_update);

                if (time() < $one_year_later) {
                    $sisa_hari = ceil(($one_year_later - time()) / 86400);
                    // ADMIN BYPASS: We allow it, but note it.
                    $bypass_note = " (Bypassed 1-Year Rule: Sisa $sisa_hari hari)";
                }
            }

            // Update Database
            $db->table('tb_user')
                ->where('iduser', $iduser)
                ->update([
                    'flag' => 1,
                    'closebook_request' => null,
                    'updated' => date('Y-m-d H:i:s')
                ]);

            $this->sendAlert("User {$user->username} berhasil DIAKTIFKAN{$bypass_note}.", 'success');
        } else {
            // ACTION: DEACTIVATE (CLOSEBOOK)

            // Basic Closebook logic
            $db->table('tb_user')
                ->where('iduser', $iduser)
                ->update([
                    'flag' => 0,
                    'closebook_last_updated' => date('Y-m-d H:i:s'),
                    'closebook_param_count' => ($user->closebook_param_count == 0) ? 1 : ($user->closebook_param_count + 1)
                ]);

            $this->sendAlert("User {$user->username} berhasil DINONAKTIFKAN.", 'success');
        }

        // Final Redirect: Use Referrer to stay on the same list (User List OR Closebook List)
        $referer = $this->request->getUserAgent()->getReferrer();
        return redirect()->to($referer ?: url_to('admin_user_list'));
    }
}
