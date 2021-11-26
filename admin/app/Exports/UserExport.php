<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\AppUser;


// class UserExport implements FromCollection
// {


//     public function collection()
//     {

//          ini_set ('memory_limit','-1');
//          set_time_limit (0);




//         return AppUser::select('id','name','email')->get();
//     }
// }




class UserExport implements FromArray, WithHeadings {

    use Exportable;

    public function __construct($usersArr, $headings){
        //$this->request = $request;
        $this->usersArr = $usersArr;
        //$this->productQuery = $productQuery;
        $this->headings = $headings;
    }

    public function query(){
    	$productQuery = $this->productQuery;
        return $productQuery;
    }

    public function array(): array {

        $usersArr = $this->usersArr;
        return $usersArr;
    }

    public function headings(): array {
        $headings = $this->headings;

        return $headings;
    }
}