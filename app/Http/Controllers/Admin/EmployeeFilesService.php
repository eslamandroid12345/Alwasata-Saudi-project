<?php

namespace App\Http\Controllers\Admin;

use App\Ask;
use App\EmployeeFile;
use App\Http\Controllers\Controller;
use Datatables;

class EmployeeFilesService extends Controller
{
    protected $userRole;
    public function files($userId,$userRole)
    {
        $asks = EmployeeFile::where('user_id',$userId)
            ->orderBy('created_at', 'ASC')->where("deleted_at",null);

        $this->userRole =$userRole;

        return DataTables::of($asks)
            ->addColumn('idn', function () {
                static $var = 1;
                return $var++;
            })
            ->addColumn('filename', function ($asks) {
                return $asks->filename;
            })
            ->addColumn('uploaded_at', function ($asks) {
                return date("Y-m-d",strtotime($asks->upload_date));
            })
            ->editColumn('deleted_at', function ($asks) {
                return $asks->deleted_at !=null ?date("Y-m-d",strtotime($asks->deleted_at)) :'-';
            })
            ->addColumn('action', function ($asks) {
                $data='';

                $status = '<span data-toggle="tooltip" data-placement="top" title="تحميل المرفق"><a   href="'.route('HumanResource.openDownloadFile',['download',$asks->id]).'" target="_blank">
                            <i class="fas fa-download"></i>
                              </a></span>
                       <span data-toggle="tooltip" data-placement="top" title=" فتح المرفق"> <a    href="'.route('HumanResource.openDownloadFile',['file',$asks->id]).'" target="_blank">
                            <i class="fa fa-eye"></i> </a></span>';
                if ( $this->userRole == 7 && $asks->deleted_at !=null ){
                    $data = '<span data-toggle="tooltip" data-placement="top" title="إستعادة المرفق"><a onclick="deleteRestoreData('.$asks->id.')"   target="_blank">
                            <i class="fas fa-recycle"></i>
                              </a></span>';
                }
                return '<div class="tableAdminOption"><span data-toggle="tooltip" data-placement="top" title="أرشفة المرفق"><a  onclick="deleteData('.$asks->id.')">
                            <i class="fas fa-trash"></i>
                              </a></span>'. $status.$data.'</div>';


            })
            ->rawColumns(['idn', 'uploaded_at', 'action'])->make(true);
    }
    public function archives($userId,$userRole)
    {
        $asks = EmployeeFile::where('user_id',$userId)
            ->orderBy('created_at', 'ASC')->where("deleted_at",'<>',null);
        $this->userRole =$userRole;
        return DataTables::of($asks)
            ->addColumn('idn', function () {
                static $var = 1;
                return $var++;
            })
            ->addColumn('filename', function ($asks) {
                return $asks->filename;
            })
            ->addColumn('uploaded_at', function ($asks) {
                return date("Y-m-d",strtotime($asks->upload_date));
            })
            ->editColumn('deleted_at', function ($asks) {
                return $asks->deleted_at !=null ?date("Y-m-d",strtotime($asks->deleted_at)) :'-';
            })
            ->addColumn('action', function ($asks) {

                $data = '<span data-toggle="tooltip" data-placement="top" title="تحميل المرفق"><a   href="'.route('HumanResource.openDownloadFile',['download',$asks->id]).'" target="_blank">
                            <i class="fas fa-download"></i>
                              </a></span>
                       <span data-toggle="tooltip" data-placement="top" title=" فتح المرفق"> <a    href="'.route('HumanResource.openDownloadFile',['file',$asks->id]).'" target="_blank">
                            <i class="fa fa-eye"></i> </a></span><span data-toggle="tooltip" data-placement="top" title="إستعادة المرفق"><a onclick="deleteRestoreData('.$asks->id.')"   target="_blank">
                        <i class="fas fa-recycle"></i>
                          </a></span>';
                if ($this->userRole == 7){
                    $data.='<span data-toggle="tooltip" data-placement="top" title="مسح المرفق نهائياً"><a  onclick="deleteData('.$asks->id.',1)">
                            <i class="fas fa-trash"></i>
                              </a></span>';
                }


                return '<div class="tableAdminOption">'.$data.'</div>';


            })
            ->rawColumns(['idn', 'uploaded_at', 'action'])->make(true);
    }
}
