<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CustomClass;
use App\Models\SidebarModel;
use App\Models\DashboardModel;

use App\Models\LogActivity;
// use App\Http\Controllers\Carbon;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Load;

use Auth;
use Alert;
use Illuminate\Support\Facades\App;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    public function index()
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();


        $id = Auth::user()->id;

        $user = DB::table('users')
            ->where('id', $id)
            ->get();

        return view('dashboard')
            ->with($sidebarMenu)
            ->with('user', $user);
    }

    // untuk manajemen menu
    public function manajemenMenu()
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();

        $stmtListMenu = DB::table('tbl_master_menu')
            ->orderBy('urutan', 'asc')
            ->get();

        return view('manajemen.manajemenMenu')
            ->with($sidebarMenu)
            ->with('stmtListMenu', $stmtListMenu);
    }

    public function manajemenMenuTambah()
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();

        return view('manajemen.manajemenMenuForm')
            ->with($sidebarMenu);
    }

    public function manajemenMenuTambahProses(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|min:3|max:100',
            'link' => 'required|min:1|max:255',
            'status_data' => 'required',
            'urutan' => 'required'
        ]);
        DB::table('tbl_master_menu')->insert([
            'nama' => $request->nama,
            'link' => $request->link,
            'status_data' => $request->status_data,
            'urutan' => $request->urutan
        ]);

        $dt = Carbon::now();
        $todayDate = $dt->toDateString();
        $todayDate = date("Y-m-d H:i:s");
        $activity = 'Created Successfully';

        $activitylog = [
            'ip_access' => $request->ip(),
            'id_user' => auth()->check() ? auth()->user()->id : 0,
            'activity_content' => $activity,
            'url' => $request->fullUrl(),
            'user_agent' => $request->server('HTTP_USER_AGENT'),
            'method' => $request->method(),
            'date_time' => $todayDate
        ];

        DB::table('tbl_user_activity_log')->insert($activitylog);
        toast(app(CustomClass::class)->notifSuksesTambah(), 'success');
        return redirect()->action([HomeController::class, 'manajemenMenu']);
    }

    public function manajemenMenuEdit($id)
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();

        $id = app(CustomClass::class)->dekrip($id);
        $stmtGetMenu = DB::table('tbl_master_menu')->where('id', $id)->get();


        // cek menu ini dipakai di role atau tidak
        $cekRoleMenu = DB::select("select count(id) as jum_menu from tbl_master_role where id_menu REGEXP '[[:<:]]" . $id . "[[:>:]]' ");

        return view('manajemen.manajemenMenuForm')
            ->with($sidebarMenu)
            ->with('stmtGetMenu', $stmtGetMenu)
            ->with('cekRoleMenu', $cekRoleMenu);
    }

    public function manajemenMenuEditProses(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|min:3|max:100',
            'link' => 'required|min:1|max:255',
            'status_data' => 'required',
            'urutan' => 'required'
        ]);
        DB::table('tbl_master_menu')->where('id', $request->id)->update([
            'nama' => $request->nama,
            'link' => $request->link,
            'status_data' => $request->status_data,
            'urutan' => $request->urutan
        ]);

        $dt = Carbon::now();
        $todayDate = $dt->toDateString();
        $todayDate = date("Y-m-d H:i:s");
        $activity = 'Updated Successfully';

        $activitylog = [
            'ip_access' => $request->ip(),
            'id_user' => auth()->check() ? auth()->user()->id : 0,
            'activity_content' => $activity,
            'url' => $request->fullUrl(),
            'user_agent' => $request->server('HTTP_USER_AGENT'),
            'method' => $request->method(),
            'date_time' => $todayDate
        ];

        // return dd($activitylog);


        DB::table('tbl_user_activity_log')->insert($activitylog);
        toast(app(CustomClass::class)->notifSuksesEdit(), 'success');
        return redirect()->action([HomeController::class, 'manajemenMenu']);
    }

    public function manajemenMenuHapus(Request $request, $id)
    {
        $id = app(CustomClass::class)->dekrip($id);
        DB::table('tbl_master_menu')->where('id', $id)->delete();


        $dt = Carbon::now();
        $todayDate = $dt->toDateString();
        $todayDate = date("Y-m-d H:i:s");
        $activity = 'Deleted Successfully';

        $activitylog = [
            'ip_access' => $request->ip(),
            'id_user' => auth()->check() ? auth()->user()->id : 0,
            'activity_content' => $activity,
            'url' => $request->fullUrl(),
            'user_agent' => $request->server('HTTP_USER_AGENT'),
            'method' => $request->method(),
            'date_time' => $todayDate
        ];

        // return dd($activitylog);


        DB::table('tbl_user_activity_log')->insert($activitylog);
        toast(app(CustomClass::class)->notifSuksesHapus(), 'success');
        return redirect()->action([HomeController::class, 'manajemenMenu']);
    }









    // untuk manajemen submenu
    public function manajemenSubmenu()
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();

        $stmtListSubmenu = DB::table('tbl_master_submenu')
            ->orderBy('urutan', 'asc')
            ->orderBy('id_menu', 'asc')
            ->get();

        return view('manajemen.manajemenSubmenu')
            ->with($sidebarMenu)
            ->with('stmtListSubmenu', $stmtListSubmenu);
    }

    public function manajemenSubmenuTambah()
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();

        $stmtListMenu = DB::table('tbl_master_menu')
            ->orderBy('id', 'asc')
            ->get();

        return view('manajemen.manajemenSubmenuForm')
            ->with($sidebarMenu)
            ->with('stmtListMenu', $stmtListMenu);
    }

    public function manajemenSubmenuTambahProses(Request $request)
    {
        $this->validate($request, [
            'id_menu' => 'required',
            'nama' => 'required|min:3|max:100',
            'link' => 'required|min:1|max:255',
            'status_data' => 'required',
            'urutan' => 'required'
        ]);
        DB::table('tbl_master_submenu')->insert([
            'id_menu' => $request->id_menu,
            'nama' => $request->nama,
            'link' => $request->link,
            'status_data' => $request->status_data,
            'urutan' => $request->urutan
        ]);

        $dt = Carbon::now();
        $todayDate = $dt->toDateString();
        $todayDate = date("Y-m-d H:i:s");
        $activity = 'Created Successfully';

        $activitylog = [
            'ip_access' => $request->ip(),
            'id_user' => auth()->check() ? auth()->user()->id : 0,
            'activity_content' => $activity,
            'url' => $request->fullUrl(),
            'user_agent' => $request->server('HTTP_USER_AGENT'),
            'method' => $request->method(),
            'date_time' => $todayDate
        ];

        // return dd($activitylog);


        DB::table('tbl_user_activity_log')->insert($activitylog);
        toast(app(CustomClass::class)->notifSuksesTambah(), 'success');
        return redirect()->action([HomeController::class, 'manajemenSubmenu']);
    }

    public function manajemenSubmenuEdit($id)
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();

        $stmtListMenu = DB::table('tbl_master_menu')
            ->orderBy('id', 'asc')
            ->get();

        $id = app(CustomClass::class)->dekrip($id);
        $stmtGetSubmenu = DB::table('tbl_master_submenu')->where('id', $id)->get();

        // cek submenu ini dipakai di role atau tidak
        $cekRoleSubmenu = DB::select("select count(id) as jum_submenu from tbl_master_role where id_submenu ~ '[[:<:]]" . $id . "[[:>:]]' ");

        return view('manajemen.manajemenSubmenuForm')
            ->with($sidebarMenu)
            ->with('stmtListMenu', $stmtListMenu)
            ->with('stmtGetSubmenu', $stmtGetSubmenu)
            ->with('cekRoleSubmenu', $cekRoleSubmenu);
    }

    public function manajemenSubmenuEditProses(Request $request)
    {
        $this->validate($request, [
            'id_menu' => 'required',
            'nama' => 'required|min:3|max:100',
            'link' => 'required|min:1|max:255',
            'status_data' => 'required',
            'urutan' => 'required'
        ]);
        DB::table('tbl_master_submenu')->where('id', $request->id)->update([
            'id_menu' => $request->id_menu,
            'nama' => $request->nama,
            'link' => $request->link,
            'status_data' => $request->status_data,
            'urutan' => $request->urutan
        ]);

        $dt = Carbon::now();
        $todayDate = $dt->toDateString();
        $todayDate = date("Y-m-d H:i:s");
        $activity = 'Updated Successfully';

        $activitylog = [
            'ip_access' => $request->ip(),
            'id_user' => auth()->check() ? auth()->user()->id : 0,
            'activity_content' => $activity,
            'url' => $request->fullUrl(),
            'user_agent' => $request->server('HTTP_USER_AGENT'),
            'method' => $request->method(),
            'date_time' => $todayDate
        ];

        // return dd($activitylog);


        DB::table('tbl_user_activity_log')->insert($activitylog);
        toast(app(CustomClass::class)->notifSuksesEdit(), 'success');
        return redirect()->action([HomeController::class, 'manajemenSubmenu']);
    }

    public function manajemenSubmenuHapus(Request $request, $id)
    {
        $id = app(CustomClass::class)->dekrip($id);
        DB::table('tbl_master_submenu')->where('id', $id)->delete();

        $dt = Carbon::now();
        $todayDate = $dt->toDateString();
        $todayDate = date("Y-m-d H:i:s");
        $activity = 'Deleted Successfully';

        $activitylog = [
            'ip_access' => $request->ip(),
            'id_user' => auth()->check() ? auth()->user()->id : 0,
            'activity_content' => $activity,
            'url' => $request->fullUrl(),
            'user_agent' => $request->server('HTTP_USER_AGENT'),
            'method' => $request->method(),
            'date_time' => $todayDate
        ];

        // return dd($activitylog);


        DB::table('tbl_user_activity_log')->insert($activitylog);

        toast(app(CustomClass::class)->notifSuksesHapus(), 'success');
        return redirect()->action([HomeController::class, 'manajemenSubmenu']);
    }









    // untuk manajemen role
    public function manajemenRole()
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();

        $stmtListRole = DB::table('tbl_master_role')
            ->orderBy('id', 'asc')
            ->get();

        $stmtListMenu = DB::table('tbl_master_menu')
            ->where('status_data', '1')
            ->orderBy('id', 'asc')
            ->get();

        $stmtListSubmenu = DB::table('tbl_master_submenu')
            ->where('status_data', '1')
            ->orderBy('id', 'asc')
            ->get();

        return view('manajemen.manajemenRole')
            ->with($sidebarMenu)
            ->with('stmtListRole', $stmtListRole)
            ->with('stmtListMenu', $stmtListMenu)
            ->with('stmtListSubmenu', $stmtListSubmenu);
    }

    public function manajemenRoleTambah()
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();

        $stmtListRole = DB::table('tbl_master_role')
            ->orderBy('id', 'asc')
            ->get();

        $stmtListMenu = DB::table('tbl_master_menu')
            ->where('status_data', '1')
            ->orderBy('id', 'asc')
            ->get();

        $stmtListSubmenu = DB::table('tbl_master_submenu')
            ->where('status_data', '1')
            ->orderBy('id', 'asc')
            ->get();

        return view('manajemen.manajemenRoleForm')
            ->with($sidebarMenu)
            ->with('stmtListRole', $stmtListRole)
            ->with('stmtListMenu', $stmtListMenu)
            ->with('stmtListSubmenu', $stmtListSubmenu);
    }

    public function manajemenRoleTambahProses(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required'
        ]);

        $arrayMenu = array();
        $arraySubmenu = array();

        if ($request->id_menu) {
            for ($i = 0; $i < count($request->id_menu); $i++) { //looping untuk menu
                $kode_menu_loop = $request->id_menu[$i];
                $array_menu[] = $request->id_menu[$i];
            }
        } else {
            $array_menu[] = "0";
        }

        if ($request->id_submenu) {
            for ($j = 0; $j < count($request->id_submenu); $j++) { //looping untuk submenu
                $kode_submenu_loop = $request->id_submenu[$j];
                $array_submenu[] = $request->id_submenu[$j];
            }
        } else {
            $array_submenu[] = "0";
        }

        $arrMenu = implode(",", $array_menu);
        $arrSubmenu = implode(",", $array_submenu);

        DB::table('tbl_master_role')->insert([
            'nama' => $request->nama,
            'id_menu' => $arrMenu,
            'id_submenu' => $arrSubmenu
        ]);

        $dt = Carbon::now();
        $todayDate = $dt->toDateString();
        $todayDate = date("Y-m-d H:i:s");
        $activity = 'Created Successfully';

        $activitylog = [
            'ip_access' => $request->ip(),
            'id_user' => auth()->check() ? auth()->user()->id : 0,
            'activity_content' => $activity,
            'url' => $request->fullUrl(),
            'user_agent' => $request->server('HTTP_USER_AGENT'),
            'method' => $request->method(),
            'date_time' => $todayDate
        ];

        // return dd($activitylog);


        DB::table('tbl_user_activity_log')->insert($activitylog);
        toast(app(CustomClass::class)->notifSuksesTambah(), 'success');
        return redirect()->action([HomeController::class, 'manajemenRole']);
    }

    public function manajemenRoleEdit($id)
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();

        $id = app(CustomClass::class)->dekrip($id);

        $stmtGetRole = DB::table('tbl_master_role')
            ->where('id', $id)
            ->orderBy('id', 'asc')
            ->get();

        $stmtListMenu = DB::table('tbl_master_menu')
            ->where('status_data', '1')
            ->orderBy('id', 'asc')
            ->get();

        $stmtListSubmenu = DB::table('tbl_master_submenu')
            ->where('status_data', '1')
            ->orderBy('id', 'asc')
            ->get();

        return view('manajemen.manajemenRoleForm')
            ->with($sidebarMenu)
            ->with('stmtGetRole', $stmtGetRole)
            ->with('stmtListMenu', $stmtListMenu)
            ->with('stmtListSubmenu', $stmtListSubmenu);
    }

    public function manajemenRoleEditProses(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required'
        ]);

        $arrayMenu = array();
        $arraySubmenu = array();

        if ($request->id_menu) {
            for ($i = 0; $i < count($request->id_menu); $i++) { //looping untuk menu
                $kode_menu_loop = $request->id_menu[$i];
                $array_menu[] = $request->id_menu[$i];
            }
        } else {
            $array_menu[] = "0";
        }

        if ($request->id_submenu) {
            for ($j = 0; $j < count($request->id_submenu); $j++) { //looping untuk submenu
                $kode_submenu_loop = $request->id_submenu[$j];
                $array_submenu[] = $request->id_submenu[$j];
            }
        } else {
            $array_submenu[] = "0";
        }

        $arrMenu = implode(",", $array_menu);
        $arrSubmenu = implode(",", $array_submenu);

        DB::table('tbl_master_role')->where('id', $request->id)->update([
            'nama' => $request->nama,
            'id_menu' => $arrMenu,
            'id_submenu' => $arrSubmenu
        ]);

        $dt = Carbon::now();
        $todayDate = $dt->toDateString();
        $todayDate = date("Y-m-d H:i:s");
        $activity = 'Updated Successfully';

        $activitylog = [
            'ip_access' => $request->ip(),
            'id_user' => auth()->check() ? auth()->user()->id : 0,
            'activity_content' => $activity,
            'url' => $request->fullUrl(),
            'user_agent' => $request->server('HTTP_USER_AGENT'),
            'method' => $request->method(),
            'date_time' => $todayDate
        ];

        // return dd($activitylog);


        DB::table('tbl_user_activity_log')->insert($activitylog);
        toast(app(CustomClass::class)->notifSuksesTambah(), 'success');
        return redirect()->action([HomeController::class, 'manajemenRole']);
    }

    public function manajemenRoleHapus(Request $request, $id)
    {
        $id = app(CustomClass::class)->dekrip($id);
        DB::table('tbl_master_role')->where('id', $id)->delete();

        $dt = Carbon::now();
        $todayDate = $dt->toDateString();
        $todayDate = date("Y-m-d H:i:s");
        $activity = 'Deleted Successfully';

        $activitylog = [
            'ip_access' => $request->ip(),
            'id_user' => auth()->check() ? auth()->user()->id : 0,
            'activity_content' => $activity,
            'url' => $request->fullUrl(),
            'user_agent' => $request->server('HTTP_USER_AGENT'),
            'method' => $request->method(),
            'date_time' => $todayDate
        ];

        // return dd($activitylog);


        DB::table('tbl_user_activity_log')->insert($activitylog);
        toast(app(CustomClass::class)->notifSuksesHapus(), 'success');
        return redirect()->action([HomeController::class, 'manajemenRole']);
    }









    // untuk manajemen user
    public function manajemenUser()
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();

        $stmtListUser = DB::table('users')
            ->orderBy('name', 'asc')
            ->get();

        return view('manajemen.manajemenUser')
            ->with($sidebarMenu)
            ->with('stmtListUser', $stmtListUser);
    }

    public function manajemenUserTambah()
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();

        $stmtListRole = DB::table('tbl_master_role')
            ->orderBy('nama', 'asc')
            ->get();

        $stmtListUnitKerja = DB::table('tbl_master_unit_kerja')
            ->orderBy('nama', 'asc')
            ->get();

        return view('manajemen.manajemenUserForm')
            ->with($sidebarMenu)
            ->with('stmtListRole', $stmtListRole)
            ->with('stmtListUnitKerja', $stmtListUnitKerja);
    }

    public function manajemenUserTambahProses(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'tanggal_lahir' => 'required',
            'email' => 'required|unique:users|max:255',
            'id_role' => 'required',
            'id_unit_kerja' => 'required',
        ]);


        $passwordDefault = substr($request->tanggal_lahir, 8, 2) . substr($request->tanggal_lahir, 5, 2) . substr($request->tanggal_lahir, 0, 4);

        $today = Carbon::now();
        $bulanDepanFull = $today->addMonths(config('secure.APP_SEKURITI_PASSWORD_EXP'));
        $bulanDepanDate = $bulanDepanFull->toDateString();

        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'tanggal_lahir' => $request->tanggal_lahir,
            'password' => bcrypt($passwordDefault),
            'id_role' => $request->id_role,
            'id_unit_kerja' => $request->id_unit_kerja,
            'expired_password' => $bulanDepanDate,
        ]);

        toast(app(CustomClass::class)->notifSuksesTambah(), 'success');
        return redirect()->action([HomeController::class, 'manajemenUser']);
    }

    public function manajemenUserEdit($id)
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();

        $stmtListRole = DB::table('tbl_master_role')
            ->orderBy('nama', 'asc')
            ->get();

        $stmtListUnitKerja = DB::table('tbl_master_unit_kerja')
            ->orderBy('nama', 'asc')
            ->get();

        $id = app(CustomClass::class)->dekrip($id);
        $stmtGetUser = DB::table('users')->where('id', $id)->get();

        return view('manajemen.manajemenUserForm')
            ->with($sidebarMenu)
            ->with('stmtGetUser', $stmtGetUser)
            ->with('stmtListRole', $stmtListRole)
            ->with('stmtListUnitKerja', $stmtListUnitKerja);
    }

    public function manajemenUserEditProses(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'tanggal_lahir' => 'required',
            'email' => 'required|unique:users,email,' . $request->id . '|max:255',
            'id_role' => 'required',
            'id_unit_kerja' => 'required',
        ]);

        DB::table('users')->where('id', $request->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'tanggal_lahir' => $request->tanggal_lahir,
            'id_role' => $request->id_role,
            'id_unit_kerja' => $request->id_unit_kerja,
        ]);

        toast(app(CustomClass::class)->notifSuksesEdit(), 'success');
        return redirect()->action([HomeController::class, 'manajemenUser']);
    }

    public function manajemenUserBukaBlokir($id)
    {
        //ambil tanggal lahir user untuk generate reset password
        $id = app(CustomClass::class)->dekrip($id);
        $stmtTglLahir = DB::table('users')
            ->select('tanggal_lahir')
            ->where('id', $id)
            ->get();
        foreach ($stmtTglLahir as $dataTglLahir) {
            $tanggal_lahir = $dataTglLahir->tanggal_lahir;
        }
        $resetPassword = substr($tanggal_lahir, 8, 2) . substr($tanggal_lahir, 5, 2) . substr($tanggal_lahir, 0, 4);

        DB::table('users')->where('id', $id)->update([
            'password' => bcrypt($resetPassword),
            'is_blokir' => 0
        ]);

        toast(app(CustomClass::class)->notifSuksesBukaBlokir(), 'success');
        return redirect()->action([HomeController::class, 'manajemenUser']);
    }

    public function manajemenUserBukaIPNyangkut($id)
    {
        $id = app(CustomClass::class)->dekrip($id);
        DB::table('users')->where('id', $id)->update([
            'ip_address' => NULL
        ]);

        toast(app(CustomClass::class)->notifSuksesBukaIPNyangkut(), 'success');
        return redirect()->action([HomeController::class, 'manajemenUser']);
    }













    //master unit kerja
    public function manajemenUnker()
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();

        $stmtListUnker = DB::table('tbl_master_unit_kerja')
            ->orderBy('nama', 'asc')
            ->get();

        return view('manajemen.manajemenUnker')
            ->with($sidebarMenu)
            ->with('stmtListUnker', $stmtListUnker);
    }

    public function manajemenUnkerTambah()
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();
        $stmtgetMasterAreaId = DB::table('tbl_master_area')->where('status_data', 1)->get();

        return view('manajemen.manajemenUnkerForm')
            ->with($sidebarMenu)
            ->with('stmtgetMasterAreaId', $stmtgetMasterAreaId);
    }

    public function manajemenUnkerTambahProses(Request $request)
    {



        $this->validate($request, [
            'nama' => 'required|min:3|max:100',
            'singkatan' => 'required|min:1|max:10',
            'email' => 'required|min:1|max:100',
            'status_data' => 'required'
            // 'kode_branch' => 'required',
            // 'kode_branch_induk' => 'required',
            // 'master_area_id' => 'required'
        ]);



        DB::table('tbl_master_unit_kerja')->insert([
            'nama' => $request->nama,
            'singkatan' => $request->singkatan,
            'email' => $request->email,
            'status_data' => $request->status_data,
            // 'kode_branch' => $request->kode_branch,
            // 'kode_branch_induk' => $request->kode_branch_induk,
            // 'master_area_id' => $request->master_area_id
        ]);

        $dt = Carbon::now();
        $todayDate = $dt->toDateString();
        $todayDate = date("Y-m-d H:i:s");
        $activity = 'Created Successfully';

        $activitylog = [
            'ip_access' => $request->ip(),
            'id_user' => auth()->check() ? auth()->user()->id : 0,
            'activity_content' => $activity,
            'url' => $request->fullUrl(),
            'user_agent' => $request->server('HTTP_USER_AGENT'),
            'method' => $request->method(),
            'date_time' => $todayDate
        ];

        // return dd($activitylog);


        DB::table('tbl_user_activity_log')->insert($activitylog);
        toast(app(CustomClass::class)->notifSuksesTambah(), 'success');
        return redirect()->action([HomeController::class, 'manajemenUnker']);
    }

    public function manajemenUnkerEdit($id)
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();

        $id = app(CustomClass::class)->dekrip($id);
        $stmtGetUnitKerja = DB::table('tbl_master_unit_kerja')->where('id', $id)->get();
        $stmtgetMasterAreaId = DB::table('tbl_master_area')->where('status_data', 1)->get();

        // cek menu ini dipakai di role atau tidak
        $cekRoleMenu = DB::select("select count(id) as jum_menu from tbl_master_role where id_menu REGEXP '[[:<:]]" . $id . "[[:>:]]' ");

        return view('manajemen.manajemenUnkerForm')
            ->with($sidebarMenu)
            ->with('stmtGetUnitKerja', $stmtGetUnitKerja)
            ->with('cekRoleMenu', $cekRoleMenu)
            ->with('stmtgetMasterAreaId', $stmtgetMasterAreaId);
    }

    public function manajemenUnkerEditProses(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|min:3|max:100',
            'singkatan' => 'required|min:1|max:10',
            'email' => 'required|min:1|max:100',
            // 'kode_branch' => 'required',
            // 'kode_branch_induk' => 'required',
            // 'master_area_id' => 'required',
            'status_data' => 'required'
        ]);
        DB::table('tbl_master_unit_kerja')->where('id', $request->id)->update([
            'nama' => $request->nama,
            'singkatan' => $request->singkatan,
            'email' => $request->email,
            // 'kode_branch' => $request->kode_branch,
            // 'kode_branch_induk' => $request->kode_branch_induk,
            // 'master_area_id' => $request->master_area_id,
            'status_data' => $request->status_data
        ]);

        $dt = Carbon::now();
        $todayDate = $dt->toDateString();
        $todayDate = date("Y-m-d H:i:s");
        $activity = 'Updated Successfully';

        $activitylog = [
            'ip_access' => $request->ip(),
            'id_user' => auth()->check() ? auth()->user()->id : 0,
            'activity_content' => $activity,
            'url' => $request->fullUrl(),
            'user_agent' => $request->server('HTTP_USER_AGENT'),
            'method' => $request->method(),
            'date_time' => $todayDate
        ];

        // return dd($activitylog);


        DB::table('tbl_user_activity_log')->insert($activitylog);
        toast(app(CustomClass::class)->notifSuksesEdit(), 'success');
        return redirect()->action([HomeController::class, 'manajemenUnker']);
    }

    public function manajemenUnkerHapus(Request $request, $id)
    {
        $id = app(CustomClass::class)->dekrip($id);
        DB::table('tbl_master_unit_kerja')->where('id', $id)->delete();

        $dt = Carbon::now();
        $todayDate = $dt->toDateString();
        $todayDate = date("Y-m-d H:i:s");
        $activity = 'Deleted Successfully';

        $activitylog = [
            'ip_access' => $request->ip(),
            'id_user' => auth()->check() ? auth()->user()->id : 0,
            'activity_content' => $activity,
            'url' => $request->fullUrl(),
            'user_agent' => $request->server('HTTP_USER_AGENT'),
            'method' => $request->method(),
            'date_time' => $todayDate
        ];

        // return dd($activitylog);


        DB::table('tbl_user_activity_log')->insert($activitylog);
        toast(app(CustomClass::class)->notifSuksesHapus(), 'success');
        return redirect()->action([HomeController::class, 'manajemenUnker']);
    }



    //Jenis Debitur
    public function manajemenJenisDebitur()
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();

        $stmtListJenisDebitur = DB::table('tbl_master_jenis_debitur')
            ->orderBy('nama', 'asc')
            ->get();

        return view('manajemen.manajemenJenisDebitur')
            ->with($sidebarMenu)
            ->with('stmtListJenisDebitur', $stmtListJenisDebitur);
    }

    public function manajemenJenisDebiturTambah()
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();
        $stmtListJenisDebitur = DB::table('tbl_master_jenis_debitur')->where('status_data', 1)->get();

        return view('manajemen.manajemenJenisDebiturForm')
            ->with($sidebarMenu)
            ->with('stmtListJenisDebitur', $stmtListJenisDebitur);
    }

    public function manajemenJenisDebiturTambahProses(Request $request)
    {



        $this->validate($request, [
            'nama' => 'required|min:3|max:100',
            'status_data' => 'required'
        ]);



        DB::table('tbl_master_jenis_debitur')->insert([
            'nama' => $request->nama,
            'status_data' => $request->status_data
        ]);

        $dt = Carbon::now();
        $todayDate = $dt->toDateString();
        $todayDate = date("Y-m-d H:i:s");
        $activity = 'Created Successfully';

        $activitylog = [
            'ip_access' => $request->ip(),
            'id_user' => auth()->check() ? auth()->user()->id : 0,
            'activity_content' => $activity,
            'url' => $request->fullUrl(),
            'user_agent' => $request->server('HTTP_USER_AGENT'),
            'method' => $request->method(),
            'date_time' => $todayDate
        ];

        // return dd($activitylog);


        DB::table('tbl_user_activity_log')->insert($activitylog);
        toast(app(CustomClass::class)->notifSuksesTambah(), 'success');
        return redirect()->action([HomeController::class, 'manajemenJenisDebitur']);
    }

    public function manajemenJenisDebiturEdit($id)
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();

        $id = app(CustomClass::class)->dekrip($id);
        $stmtgetJenisDebitur = DB::table('tbl_master_jenis_debitur')->where('id', $id)->get();
        // $stmtgetMasterAreaId=DB::table('tbl_master_area')->where('status_data',1)->get();

        // cek menu ini dipakai di role atau tidak
        $cekRoleMenu = DB::select("select count(id) as jum_menu from tbl_master_role where id_menu ~ '[[:<:]]" . $id . "[[:>:]]' ");

        return view('manajemen.manajemenJenisDebiturForm')
            ->with($sidebarMenu)
            // ->with('stmtgetMasterArea', $stmtgetMasterArea)
            ->with('cekRoleMenu', $cekRoleMenu)
            ->with('stmtgetJenisDebitur', $stmtgetJenisDebitur);
    }

    public function manajemenJenisDebiturEditProses(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|min:3|max:100',
            'status_data' => 'required'
        ]);
        DB::table('tbl_master_jenis_debitur')->where('id', $request->id)->update([
            'nama' => $request->nama,
            'status_data' => $request->status_data
        ]);

        $dt = Carbon::now();
        $todayDate = $dt->toDateString();
        $todayDate = date("Y-m-d H:i:s");
        $activity = 'Updated Successfully';

        $activitylog = [
            'ip_access' => $request->ip(),
            'id_user' => auth()->check() ? auth()->user()->id : 0,
            'activity_content' => $activity,
            'url' => $request->fullUrl(),
            'user_agent' => $request->server('HTTP_USER_AGENT'),
            'method' => $request->method(),
            'date_time' => $todayDate
        ];

        // return dd($activitylog);


        DB::table('tbl_user_activity_log')->insert($activitylog);
        toast(app(CustomClass::class)->notifSuksesEdit(), 'success');
        return redirect()->action([HomeController::class, 'manajemenJenisDebitur']);
    }

    public function manajemenJenisDebiturHapus(Request $request, $id)
    {
        $id = app(CustomClass::class)->dekrip($id);
        DB::table('tbl_master_jenis_debitur')->where('id', $id)->delete();

        $dt = Carbon::now();
        $todayDate = $dt->toDateString();
        $todayDate = date("Y-m-d H:i:s");
        $activity = 'Deleted Successfully';

        $activitylog = [
            'ip_access' => $request->ip(),
            'id_user' => auth()->check() ? auth()->user()->id : 0,
            'activity_content' => $activity,
            'url' => $request->fullUrl(),
            'user_agent' => $request->server('HTTP_USER_AGENT'),
            'method' => $request->method(),
            'date_time' => $todayDate
        ];

        // return dd($activitylog);


        DB::table('tbl_user_activity_log')->insert($activitylog);
        toast(app(CustomClass::class)->notifSuksesHapus(), 'success');
        return redirect()->action([HomeController::class, 'manajemenJenisDebitur']);
    }

    //master area
    public function manajemenMasterArea()
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();

        $stmtListMasterArea = DB::table('tbl_master_area')
            ->orderBy('nama', 'asc')
            ->get();

        return view('manajemen.manajemenMasterArea')
            ->with($sidebarMenu)
            ->with('stmtListMasterArea', $stmtListMasterArea);
    }

    public function manajemenMasterAreaTambah()
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();
        $stmtListMasterArea = DB::table('tbl_master_area')->where('status_data', 1)->get();

        return view('manajemen.manajemenMasterAreaForm')
            ->with($sidebarMenu)
            ->with('stmtListMasterArea', $stmtListMasterArea);
    }

    public function manajemenMasterAreaTambahProses(Request $request)
    {



        $this->validate($request, [
            'nama' => 'required|min:3|max:100',
            'status_data' => 'required'
        ]);



        DB::table('tbl_master_area')->insert([
            'nama' => $request->nama,
            'status_data' => $request->status_data
        ]);

        $dt = Carbon::now();
        $todayDate = $dt->toDateString();
        $todayDate = date("Y-m-d H:i:s");
        $activity = 'Created Successfully';

        $activitylog = [
            'ip_access' => $request->ip(),
            'id_user' => auth()->check() ? auth()->user()->id : 0,
            'activity_content' => $activity,
            'url' => $request->fullUrl(),
            'user_agent' => $request->server('HTTP_USER_AGENT'),
            'method' => $request->method(),
            'date_time' => $todayDate
        ];

        // return dd($activitylog);


        DB::table('tbl_user_activity_log')->insert($activitylog);
        toast(app(CustomClass::class)->notifSuksesTambah(), 'success');
        return redirect()->action([HomeController::class, 'manajemenMasterArea']);
    }

    public function manajemenMasterAreaEdit($id)
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();

        $id = app(CustomClass::class)->dekrip($id);
        $stmtgetMasterAreaId = DB::table('tbl_master_area')->where('id', $id)->get();
        // $stmtgetMasterAreaId=DB::table('tbl_master_area')->where('status_data',1)->get();

        // cek menu ini dipakai di role atau tidak
        $cekRoleMenu = DB::select("select count(id) as jum_menu from tbl_master_role where id_menu ~ '[[:<:]]" . $id . "[[:>:]]' ");

        return view('manajemen.manajemenMasterAreaForm')
            ->with($sidebarMenu)
            // ->with('stmtgetMasterArea', $stmtgetMasterArea)
            ->with('cekRoleMenu', $cekRoleMenu)
            ->with('stmtgetMasterAreaId', $stmtgetMasterAreaId);
    }

    public function manajemenMasterAreaEditProses(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|min:3|max:100',
            'status_data' => 'required'
        ]);
        DB::table('tbl_master_area')->where('id', $request->id)->update([
            'nama' => $request->nama,
            'status_data' => $request->status_data
        ]);

        $dt = Carbon::now();
        $todayDate = $dt->toDateString();
        $todayDate = date("Y-m-d H:i:s");
        $activity = 'Updated Successfully';

        $activitylog = [
            'ip_access' => $request->ip(),
            'id_user' => auth()->check() ? auth()->user()->id : 0,
            'activity_content' => $activity,
            'url' => $request->fullUrl(),
            'user_agent' => $request->server('HTTP_USER_AGENT'),
            'method' => $request->method(),
            'date_time' => $todayDate
        ];

        // return dd($activitylog);


        DB::table('tbl_user_activity_log')->insert($activitylog);
        toast(app(CustomClass::class)->notifSuksesEdit(), 'success');
        return redirect()->action([HomeController::class, 'manajemenMasterArea']);
    }

    public function manajemenMasterAreaHapus(Request $request, $id)
    {
        $id = app(CustomClass::class)->dekrip($id);
        DB::table('tbl_master_area')->where('id', $id)->delete();

        $dt = Carbon::now();
        $todayDate = $dt->toDateString();
        $todayDate = date("Y-m-d H:i:s");
        $activity = 'Deleted Successfully';

        $activitylog = [
            'ip_access' => $request->ip(),
            'id_user' => auth()->check() ? auth()->user()->id : 0,
            'activity_content' => $activity,
            'url' => $request->fullUrl(),
            'user_agent' => $request->server('HTTP_USER_AGENT'),
            'method' => $request->method(),
            'date_time' => $todayDate
        ];

        // return dd($activitylog);


        DB::table('tbl_user_activity_log')->insert($activitylog);
        toast(app(CustomClass::class)->notifSuksesHapus(), 'success');
        return redirect()->action([HomeController::class, 'manajemenMasterArea']);
    }


    // activity log
    public function manajemenUserLog()
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();

        $stmtListUserLog = DB::table('tbl_user_activity_log')
            ->orderBy('id', 'desc')
            ->get();


        return view('manajemen.manajemenUserActivity')
            ->with($sidebarMenu)
            ->with('stmtListUserLog', $stmtListUserLog);
    }

    public function gantiPassword()
    {
        return view('auth.gantiPassword');
    }

    public function gantiPasswordProses(Request $request)
    {
        $this->validate($request, [
            'password_baru' => [
                'min:' . config('secure.APP_SEKURITI_LENGTH_PASS_MIN') . '',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!@#$%]).*$/'
            ],
        ]);

        $request->validate([
            // 'email' => 'required',
            'name' => 'required',
            'password' => 'required',
        ]);
        // $credentials = $request->only('email', 'password');
        $credentials = $request->only('name', 'password');
        if (!Auth::attempt($credentials)) { // jika password lama yang diinput tidak sama dengan password di database
            return redirect()->action([HomeController::class, 'gantiPassword'])
                ->with('notifResetPassword', app(CustomClass::class)->notifPasswordLamaSalah());
        } else {
            if ($request->password == $request->password_baru) { // jika password lama sama dengan password baru
                return redirect()->action([HomeController::class, 'gantiPassword'])
                    ->with('notifResetPassword', app(CustomClass::class)->notifPasswordLamaPasswordBaruSama());
            } else {
                if ($request->password_baru == $request->konfirmasi_password_baru) { // jika password baru sama dengan konfirmasi password
                    $today = Carbon::now();
                    $bulanDepanFull = $today->addMonths(config('secure.APP_SEKURITI_PASSWORD_EXP'));
                    $bulanDepanDate = $bulanDepanFull->toDateString();
                    // DB::table('users')->where('email', $request->email)->update([
                    DB::table('users')->where('name', $request->name)->update([
                        'password' => bcrypt($request->password_baru),
                        'expired_password' => $bulanDepanDate,
                        // 'is_blokir' => 0
                    ]);
                    toast(app(CustomClass::class)->notifBerhasilGantiPassword(), 'success');
                    return redirect()->action([HomeController::class, 'index']);
                } else { // jika password baru tidak sama dengan konfirmasi password baru
                    return redirect()->action([HomeController::class, 'gantiPassword'])
                        ->with('notifResetPassword', app(CustomClass::class)->notifKonfirmasiPasswordSalah());
                }
            }
        }
    }

    public function manajemenSekuriti()
    {
        $menuModel = new SidebarModel();
        $sidebarMenu = $menuModel->getMenu();

        return view('manajemen.manajemenSekuritiForm')
            ->with($sidebarMenu);
    }

    public function manajemenSekuritiProses(Request $request)
    {
        $this->validate($request, [
            'max_fail_login' => 'required|numeric|min:0|not_in:0',
            'session_timeout' => 'required|numeric|min:0|not_in:0',
            'min_pass' => 'required|numeric|min:0|not_in:0',
            'exp_pass' => 'required|numeric|min:0|not_in:0',
        ]);

        $path = base_path('config/secure.php');
        $openFile = fopen($path, 'w');
        $content = "<?php 
        return [ 
            'APP_SEKURITI_FAIL_LOGIN' => " . $request->max_fail_login . ",
            'APP_SEKURITI_SESSION_TIME' => " . $request->session_timeout . ",
            'APP_SEKURITI_LENGTH_PASS_MIN' => " . $request->min_pass . ",
            'APP_SEKURITI_PASSWORD_EXP' => " . $request->exp_pass . "
        ,];";
        fwrite($openFile, $content);
        fclose($openFile);

        toast(app(CustomClass::class)->notifSuksesEdit(), 'success');
        return redirect('/manajemenSekuriti');
    }
}
