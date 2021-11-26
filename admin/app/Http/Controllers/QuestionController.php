<?php

namespace App\Http\Controllers;

use App\Exams;
use Illuminate\Http\Request;
use App\Question;
use App\Boards;
use App\Classes;
use App\Solutions;
use App\Options;
use App\ExamQuestion;
use Yajra\DataTables\DataTables;
use DB;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $boards = Boards::latest()->get();
        $class = Classes::latest()->get();
        $data = Classes::latest()->get();
        $subtopics = DB::table('sub_topic')->get();

        $exams = Exams::get();


        return view('admin/question/list',array('boards'=>$boards,'class'=>$class,'data'=>$data,'subtopics'=>$subtopics,'exams'=>$exams));
    }


    public function questionList(Request $request)
    {
        $search_data = $request->search['value'];

        $board = isset($request->board) ? $request->board :'';
        $subject = isset($request->subject) ? $request->subject :'';
        $chapter = isset($request->chapter) ? $request->chapter :'';
        $exam_id = isset($request->exam_id) ? $request->exam_id :'';


        // if (isset($chapter)) {
        // $question = Question::where(['chapter'=>$chapter])
        //  ->orwhere('e_question', 'LIKE', "%{$search_data}%")
        //  ->orwhere('h_question', 'LIKE', "%{$search_data}%")
        //  ->orwhere('id', 'LIKE', "%{$search_data}%")
        //  ->get();

        // }
        // else{
       // DB::enableQueryLog(); // Enable query log


        $queids = [];


    if(isset($exam_id) && !empty($exam_id)){

        $exam_questions = ExamQuestion::where('exam_id',$exam_id)->get();
        if(!empty($exam_questions)){
            foreach($exam_questions as $que){
                $queids[] = $que->q_id;
            }
        }
    }





        $question = new Question;

        // }


        if(!empty($search_data)){
         $question->where('e_question', 'LIKE', "%{$search_data}%");
         $question->orwhere('h_question', 'LIKE', "%{$search_data}%");
         $question->orwhere('id', 'LIKE', "%{$search_data}%");
     }




     if(isset($board) && !empty($board)){
       $question->where('boards',$board); 
   }
   if(isset($subject) && !empty($subject)){
       $question->where('subject',$subject); 
   }

   if(isset($chapter) && !empty($chapter)){
       $question->where('chapter',$chapter); 
   }

    if(isset($exam_id) && !empty($exam_id)){
       $question->whereIn('id',$queids); 
   }


   $question = $question->get();

        //dd(DB::getQueryLog()); // Show results of log



   return Datatables::of($question)
   ->rawColumns(['e_question','h_question'])
   ->filter(function ($instance) use ($request) {

   })
   ->make(true);
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $boards = Boards::latest()->get();
        $class = Classes::latest()->get(); 
        return view('admin/question/add',array('boards'=>$boards,'class'=>$class));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

     $request->validate([
        'boards' =>'required',
        'subject' =>'required',
        'chapter' =>'required',
        //'topic' =>'required',
        // 'difficulty_level' =>'required',
        'e_question'=>'required_without:h_question',
        'h_question'=>'required_without:e_question',
        'e_solutions'=>'required_without:h_solutions',
        'h_solutions'=>'required_without:e_solutions',
        'e_option'=>'',
        'h_option'=>'',
        'correct'=>'required',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        'option_image'=>'',
    ]);
     $image = $request->input('image');
     if (!empty($image)) {   
        $imageName = time().'.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images/question'), $imageName);
    }
    else{
       $imageName = '';  
   }
   $id = auth()->user()->id;
   $data = Question::create([
    'boards'=>request('boards'),
    'subject'=>request('subject'),
    'chapter'=>request('chapter'),
    'subject'=>request('subject'),
    'topic'=>request('topic'),
    'type'=>1,
    'time'=>0,
    'e_question'=>request('e_question'),
    'h_question'=>request('h_question'),
    'difficulty_level'=>1,
    'image'=>$imageName,
    'create_by'=>'Admin', 
    'create_by_id'=>$id,
]);


   $q_id = $data->id;
   if(isset($q_id)){
    $noq=0;
    foreach(request('e_option') as $key => $value ) {
        if(isset($_POST['correct'][$key])) $correct=1;
        else $correct=0;

        if(!empty(request('h_option')[$key]) or !empty(request('e_option')[$key]))
        {

          if(!empty(request('option_image')[$key])){
            $opimageName[$key] = time().'_'.$key.'.'.request()->option_image[$key]->getClientOriginalExtension();
            request()->option_image[$key]->move(public_path('images/options'), $opimageName[$key]);
        }
        else{
         $opimageName[$key] = '';
     }
     Options::create([
        'q_id'=>$q_id,
        'option_h'=>'j',
        'option_e'=>request('e_option')[$key],
        'correct'=>$correct,
        'image'=>$opimageName[$key],
    ]);
     $noq=$noq+1;
 }
}
Solutions::create([
    'q_id'=>$q_id,
    'e_solutions'=>request('e_solutions'),
    'h_solutions'=>request('h_question'),
    'admitted_by'=>$id,
    'image'=>'',
]);
}
return redirect()->route('question.index')
->with('success','Question Added successfully.');
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {



        $options = Options::where(['q_id'=>$id])->get();    
        $question = Question::where(['id'=>$id])->first(); 
        $solution = Solutions::where(['q_id'=>$id])->first();
        return view('admin/question/view',array('options'=>$options,'question'=>$question,'solution'=>$solution));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
     $request->validate([
        'e_question'=>'required',
        'h_question'=>'',
        'e_question'=>'required_without:h_question',
        'h_question'=>'required_without:e_question',
        'e_solutions'=>'required_without:h_solutions',
        'h_solutions'=>'required_without:e_solutions',
        'correct'=>'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        'option_image'=>'',
        'id'=>'required',
        'op_id'=>'',
    ]);
     $image = $request->input('image');
     if (!empty($image)) {   
        $imageName = time().'.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images/question'), $imageName);
    }
    else{
       $imageName = '';  
   }
   $id = auth()->user()->id;
   $data = array(
    'e_question'=>request('e_question'),
    'h_question'=>request('h_question'),
    'image'=>$imageName,
);

   $solution = array(
    'h_solutions'=>request('h_solutions'),
    'e_solutions'=>request('e_solutions'),
);
   $q_id = request('id');
   if(isset($q_id)){
        //       Question::where(['id'=>$q_id])->update($data);
        //       Solutions::where(['q_id'=>$q_id])->update($solution);
        //     $noq=0;
        //     foreach(request('e_option') as $key => $value ) {
        //     if(isset($_POST['correct'][$key])) $correct=1;
        //     else $correct=0;

        //     if(!empty(request('h_option')[$key]) or !empty(request('e_option')[$key]) or !empty(request('op_id')[$key]))
        //     {

        //       if(!empty(request('option_image')[$key])){
        //         $opimageName[$key] = time().'_'.$key.'.'.request()->option_image[$key]->getClientOriginalExtension();
        //         request()->option_image[$key]->move(public_path('images/options'), $opimageName[$key]);
        //         }
        //         else{
        //              $opimageName[$key] = '';
        //         }
        //       Options::where(['id'=>request('op_id')[$key]])->update([
        //             'option_h'=>request('h_option')[$key],
        //             'option_e'=>request('e_option')[$key],
        //             'correct'=>$correct,
        //             'image'=>$opimageName[$key],
        //         ]);
        //       $noq=$noq+1;
        //     }
        // } 
    Solutions::where(['q_id'=>$q_id])->update($solution);
    Question::where(['id'=>$q_id])->update($data);
    Options::where(['q_id'=>$q_id])->delete();
    $noq=0;
    foreach(request('e_option') as $key => $value ) {
        if(isset($_POST['correct'][$key])) $correct=1;
        else $correct=0;

        if(!empty(request('h_option')[$key]) or !empty(request('e_option')[$key]))
        {

          if(!empty(request('option_image')[$key])){
            $opimageName[$key] = time().'_'.$key.'.'.request()->option_image[$key]->getClientOriginalExtension();
            request()->option_image[$key]->move(public_path('images/options'), $opimageName[$key]);
        }
        else{
         $opimageName[$key] = '';
     }
     Options::create([
        'q_id'=>$q_id,
        'option_h'=>request('h_option')[$key],
        'option_e'=>request('e_option')[$key],
        'correct'=>$correct,
        'image'=>$opimageName[$key],
    ]);
     $noq=$noq+1;
 }
}

}
return redirect()->route('question.index')
->with('success','Question Updated successfully.');
}

public function uploadQuestionFile(Request $request)
{
 $request->validate([
    'csv_import' => 'required|max:8192',
    'boards' =>'required',
    'subject' =>'required',
    'chapter' =>'required',
]);
 $fileName = time().'.'.request()->csv_import->getClientOriginalExtension();
 request()->csv_import->move(public_path('/uploads'), $fileName);

 $filepath = public_path('uploads/'.$fileName);

 $file = fopen($filepath,"r");

 $importData_arr = array();
 $i = 0;

 while (($filedata = fgetcsv($file)) !== FALSE) {
     $num = count($filedata);
     for ($c=0; $c < $num; $c++) {
        $importData_arr[$i][] = $filedata [$c];
    }
    $i++;

}
fclose($file);
for ($i=1; $i <count($importData_arr) ; $i++) { 
    $insertData = array(
        'boards'=>request('boards'),
        'subject'=>request('subject'),
        'topic'=>request('chapter'),
        'subject_name'=>request('subject_name'),

        
            //'topic'=>request('topic'),
        'type'=>1,
        'difficulty_level'=>1,
        'e_question'=>htmlentities($importData_arr[$i][2]),
        'h_question'=>htmlentities($importData_arr[$i][2]),
        'create_by'=>'Admin', 
        'create_by_id'=>1,
    );


    $data = Question::create($insertData);
           //print_r($data);
    $q_id = $data->id;

    if(isset($q_id)){
        $correct = 0;
        for ($k=3; $k <=7 ; $k++) { 
           if($importData_arr[$i][$k]==$importData_arr[$i][8]) {
            $correct =1;
        }
        else{
            $correct=0;
        }
        $op=  Options::create([
            'q_id'=>$q_id,
            'option_h'=>htmlentities($importData_arr[$i][$k]),
            'option_e'=>htmlentities($importData_arr[$i][$k]),
            'correct'=>$correct,
            'image'=>"",
        ]);
    }      
    Solutions::create([
        'q_id'=>$q_id,
        'e_solutions'=>htmlentities($importData_arr[$i][9]),
        'h_solutions'=>htmlentities($importData_arr[$i][9]),
        'admitted_by'=>1,
        'image'=>'',
    ]);

}
}
return redirect()->route('question.index')
->with('success','Question Import successfully.');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $id = request('id');
        Question::where('id',$id)->delete();
        echo 1;
    }
}
