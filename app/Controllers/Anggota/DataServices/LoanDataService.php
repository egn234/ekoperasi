<?php 
namespace App\Controllers\Anggota\DataServices;

use App\Controllers\BaseController;

use App\Models\M_user;
use App\Models\M_pinjaman;
use App\Models\M_param;

class LoanDataService extends BaseController
{
    protected $m_user;
    protected $account;
    protected $m_pinjaman;
    protected $m_param;

    function __construct()
    {
        $this->m_user = model(M_user::class);
        $this->m_pinjaman = model(M_pinjaman::class);
        $this->m_param = model(M_param::class);

        $this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
    }

    public function data_pinjaman()
    {
        $request = service('request');
        $model = new M_pinjaman();

        // Parameters from the DataTable
        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'];

        // Fetch data from the model using $start and $length
        $model->where('idanggota', $this->account->iduser);
        $model->whereNotIn('status', [0]);
        $data = $model->asArray()->findAll($length, $start);

        // Total records (you can also use $model->countAll() for exact total)
        $model->where('idanggota', $this->account->iduser);
        $recordsTotal = $model->countAllResults();

        // Records after filtering (if any)
        $model->where('idanggota', $this->account->iduser);
        $recordsFiltered = $model->countAllResults();

        // Prepare the response in the DataTable format
        $response = [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ];

        return $this->response->setJSON($response);
    }

    public function riwayat_penolakan()
    {
        $request = service('request');
        $model = new M_pinjaman();

        // Parameters from the DataTable
        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'];

        // Fetch data from the model using $start and $length
        $model->where('idanggota', $this->account->iduser);
        $model->where('status', 0);
        $data = $model->asArray()->findAll($length, $start);

        // Total records (you can also use $model->countAll() for exact total)
        $model->where('idanggota', $this->account->iduser);
        $recordsTotal = $model->countAllResults();

        // Records after filtering (if any)
        $model->where('idanggota', $this->account->iduser);
        $recordsFiltered = $model->countAllResults();

        // Prepare the response in the DataTable format
        $response = [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ];

        return $this->response->setJSON($response);
    }

    public function up_form()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $user = $this->m_pinjaman->getPinjamanById($id)[0];
            $data = [
                'a' => $user,
                'duser' => $this->account
            ];
            echo view('anggota/pinjaman/part-pinj-mod-upload', $data);
        }
    }

    public function detail_tolak()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
            $data = [
                'a' => $pinjaman,
                'duser' => $this->account
            ];
            echo view('anggota/pinjaman/part-pinj-mod-tolak', $data);
        }
    }
    
    public function add_pengajuan()
    {
        if ($_POST['id']) {
            $id = $_POST['id'];
            
            // Get parameters for display
            $provisi = $this->m_param->getParamById(5)[0]; // Provisi
            $bulan_kelipatan_asuransi = $this->m_param->getParamById(11)[0]; // Bulan kelipatan asuransi
            $nominal_asuransi = $this->m_param->getParamById(12)[0]; // Nominal asuransi
            
            $data = [
                'duser' => $this->account,
                'provisi' => $provisi,
                'bulan_kelipatan_asuransi' => $bulan_kelipatan_asuransi,
                'nominal_asuransi' => $nominal_asuransi
            ];
            echo view('anggota/pinjaman/part-pinj-mod-add', $data);
        }
    }
}
