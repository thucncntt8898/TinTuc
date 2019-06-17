<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TinTuc;
use App\TheLoai;
use App\LoaiTin;
use App\Comment;

class TinTucController extends Controller
{
    public function getDanhSach()
    {
    	$tintuc=TinTuc::orderBy('id','DESC')->get();
    	return view('admin.tintuc.danhsach',['tintuc'=>$tintuc]);
    }
    public function getThem()
    {
        $theloai=TheLoai::all();
        $loaitin=LoaiTin::all();
        return view('admin.tintuc.them',['theloai'=>$theloai,'loaitin'=>$loaitin]);
    }
    public function postThem(Request $request)
    {
        $this->validate($request,[
            'LoaiTin'=>'required',
            'TieuDe'=>'required|min:3|unique:tintuc,TieuDe',
            'TomTat'=>'required',
            'NoiDung'=>'required'
        ],[
            'LoaiTin.required'=>'Ban chua chon loai tin',
            'TieuDe.required'=>'Ban chua nhap tieu de',
            'TieuDe.min'=>'Tieu de phai co it nhat 3 ki tu',
            'TieuDe.unique'=>'Tieu de da ton tai',
            'TomTat.required'=>'Ban chua nhap tom tat',
            'NoiDung.required'=>'Ban chua nhap noi dung'
        ]);
        $tintuc=new TinTuc;
        $tintuc->TieuDe=$request->TieuDe;
        $tintuc->TieuDeKhongDau=changeTitle($request->TieuDe);
        $tintuc->idLoaiTin=$request->LoaiTin;
        $tintuc->TomTat=$request->TomTat;
        $tintuc->NoiDung=$request->NoiDung;
        $tintuc->SoLuotXem=0;
        if($request->hasFile('Hinh'))
        {
            $file=$request->file('Hinh');
            $duoi=$file->getClientOriginalExtension();
            if($duoi!='jpg' && $duoi!='png' && $duoi!='jpeg')
            {
                // alert('Loi! Ban chi duoc chon file co duoi la jpg, png vaf jpeg.');
                return redirect('admin/tintuc/them')->with('thong bao','Loi! Ban chi duoc chon file co duoi la jpg, png vaf jpeg.');
            }
            $name=$file->getClientOriginalName();
            $Hinh=str_random(4)."_".$name;
            while(file_exists("upload/tintuc/".$Hinh));
            {
                $Hinh=str_random(4)."_".$name;
            }
            $file->move('upload/tintuc',$Hinh);
            $tintuc->Hinh=$Hinh;
            // echo $Hinh;
        }
        else
        {
            $tintuc->Hinh="";
        }

        $tintuc->save();
        return redirect('admin/tintuc/them')->with('thong bao','Success!');
    }
    public function getSua($id)
    {
        $tintuc=TinTuc::find($id);
        $theloai=TheLoai::all();
        $loaitin=LoaiTin::all();
        return view('admin.tintuc.sua',['tintuc'=>$tintuc,'theloai'=>$theloai,'loaitin'=>$loaitin]);
    }
    public function postSua(Request $request,$id)
    {
        $tintuc=TinTuc::find($id);
        $this->validate($request,[
            'LoaiTin'=>'required',
            'TieuDe'=>'required|min:3|unique:tintuc,TieuDe',
            'TomTat'=>'required',
            'NoiDung'=>'required'
        ],[
            'LoaiTin.required'=>'Ban chua chon loai tin',
            'TieuDe.required'=>'Ban chua nhap tieu de',
            'TieuDe.min'=>'Tieu de phai co it nhat 3 ki tu',
            'TieuDe.unique'=>'Tieu de da ton tai',
            'TomTat.required'=>'Ban chua nhap tom tat',
            'NoiDung.required'=>'Ban chua nhap noi dung'
        ]);
        $tintuc->TieuDe=$request->TieuDe;
        $tintuc->TieuDeKhongDau=changeTitle($request->TieuDe);
        $tintuc->idLoaiTin=$request->LoaiTin;
        $tintuc->TomTat=$request->TomTat;
        $tintuc->NoiDung=$request->NoiDung;
        if($request->hasFile('Hinh'))
        {
            $file=$request->file('Hinh');
            $duoi=$file->getClientOriginalExtension();
            if($duoi!='jpg' && $duoi!='png' && $duoi!='jpeg')
            {
                alert('Loi! Ban chi duoc chon file co duoi la jpg, png vaf jpeg.');
                // return redirect('admin/tintuc/them')->with('thong bao','Loi! Ban chi duoc chon file co duoi la jpg, png vaf jpeg.');
            }
            $name=$file->getClientOriginalName();
            $Hinh=str_random(4)."_".$name;
            while(file_exists("upload/tintuc/".$Hinh));
            {
                $Hinh=str_random(4)."_".$name;
            }
            $file->move('upload/tintuc',$Hinh);
            unlink("upload/tintuc/".$tintuc->Hinh);
            $tintuc->Hinh=$Hinh;
            echo $Hinh;
        }
        $tintuc->save();
        return redirect('admin/tintuc/sua/'.$id)->with('thong bao','Success!');
    }
    public function getXoa($id)
    {
        $tintuc=TinTuc::find($id);
        $tintuc->delete();
        return redirect('admin/tintuc/danhsach');
    }
}
