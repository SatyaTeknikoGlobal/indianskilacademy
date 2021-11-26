<?php

namespace App\Imports;

use App\Question;
use App\ExamQuestion;
use App\Solutions;
use App\Options;
use App\Exams;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use PhpOffice\PhpSpreadsheet\Shared\Date;
HeadingRowFormatter::default('none');
use Auth;
class QuestionImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    private function checkNull($string1){
        if(trim($string1) == "" || trim($string1) == null){
            $string1 = null;
        }
        return $string1;
    }

    private function setString($string1){
        if($string1 == null){
            $string1 = null;
        }

        // if ($string2 == null) {
        //     $string2 = $string1;
        // }

        return array($string1);

    }


    public function model(array $row)
    {    
        $type =  1;            
        $diff = 1;
        $ExamId = $row['ExamId'];
        $Equestion = $this->checkNull($row['Equestion']);
        // $Hquestion = $this->checkNull($row['Hquestion']);

        if($Equestion == null && $Hquestion == null){
            return;
        }

        $array = $this->setString($Equestion);
        $Equestion = nl2br($array[0]);
        $eo1 = $this->checkNull($row['EOption1']);
        $array = $this->setString($eo1);
        $eo1 = $array[0];
        $eo2 = $this->checkNull($row['EOption2']);
        $array = $this->setString($eo2);
        $eo2 = $array[0];
        $eo3 = $this->checkNull($row['EOption3']);
        $array = $this->setString($eo3);
        $eo3 = $array[0];
        $eo4 = $this->checkNull($row['EOption4']);
        $array = $this->setString($eo4);
        $eo4 = $array[0];

        $correct = $row['Correct'];

        if($correct == 'a'){
            $correct = 1;
        }
        if($correct == 'b'){
            $correct = 2;
        }
        if($correct == 'c'){
            $correct = 3;
        }
        if($correct == 'd'){
            $correct = 4;
        }
        if($correct == 'e'){
            $correct = 5;
        }




        $Esolution = $this->checkNull($row['ESolution']);

        $array = $this->setString($Esolution);
        $Esolution = $array[0];
        $subject_name = $this->checkNull($row['SubjectName']);

        if($subject_name == null){
            return;
        }

        $examData = Exams::where(['id'=>$ExamId])->first();
        $marks = $examData->marks;
        $nagative_marks = $examData->negetive_marks;

        $q = Question::create([
            'h_question'=>'',
            "e_question"=>$Equestion,
            "boards"=>isset($examData->board_id)?$examData->board_id:0,
            "class_id"=>1,
            "subject"=>isset($examData->sub_id)?$examData->sub_id:0,
            "chapter"=>1,
            "topic"=>isset($examData->topic_id)?$examData->topic_id:0,
            "time"=>isset($examData->session_time)?$examData->session_time:60,
            "marks"=>$marks,
            "subject_name"=>$subject_name,
            "create_by"=>"admin",
            "create_by_id"=>1,
            "difficulty_level"=>$diff,
            "type"=>$type,
        ]);
        if($eo1 != null){
            Options::create([
                'q_id'=>$q->id,
                "option_h"=>'',
                "option_e"=>$eo1,
                "correct"=>($correct==1)?1:0,
                "del"=>0,
            ]);
        }
        if($eo2 != null){
            Options::create([
                'q_id'=>$q->id,
                "option_h"=>'',
                "option_e"=>$eo2,
                "correct"=>($correct==2)?1:0,
                "del"=>0,
            ]);
        }
        if($eo3 != null){
            Options::create([
                'q_id'=>$q->id,
                "option_h"=>'',
                "option_e"=>$eo3,
                "correct"=>($correct==3)?1:0,
                "del"=>0,
            ]);
        }
        
        if($eo4 != null){
            Options::create([
                'q_id'=>$q->id,
                "option_h"=>'',
                "option_e"=>$eo4,
                "correct"=>($correct==4)?1:0,
                "del"=>0,
            ]);
        }

        $solution = Solutions::create([
            "q_id"=>$q->id,
            "e_solutions"=>$Esolution,
            "h_solutions"=>'',
            "admitted_by"=>'admin',
            "del"=>0
        ]);

        $exam = ExamQuestion::create([
            'exam_id'=>$ExamId,
            'q_id'=>$q->id,
            'marks'=>$marks,
            'negative_mark'=>$nagative_marks,
        ]);
        return ;
    }


    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
