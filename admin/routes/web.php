<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	return view('admin/login');
});
Route::get('/getBirthdays', 'AdminController@getBirthdays');
//Login Admin
Route::resource('login','SessionController');

Route::group(['middleware' => ['auth']], function () {

	Route::get('/', 'AdminController@index');


	Route::get('/summary', 'AdminController@summary');


	Route::match(['get','post'],'/app_sidebar', 'AdminController@app_sidebar');

	Route::match(['get','post'],'/app_sidebar/add', 'AdminController@app_sidebar_add');



	Route::match(['get','post'],'/app_sidebar/edit/{id}', 'AdminController@app_sidebar_edit')->name('app_sidebar.edit');




	Route::match(['get','post'],'/app_sidebar/delete/{id}', 'AdminController@app_sidebar_delete')->name('app_sidebar.delete');







//Dashboard
	Route::resource('dashboard','AdminController');

//SubAdmin
	Route::resource('subadmin','SubAdminController');

	Route::post('/subadmin_update','SubAdminController@update');

//Faculties
	Route::resource('faculties','FacultiesController');

	Route::post('/faculties_update','FacultiesController@update');

//Users

	Route::resource('app_users','UserController');




		Route::match(['get','post'],'add_app_user','UserController@addUser')->name('add_app_user');

	

	Route::get('get_users','UserController@getUsers')->name('get_users');

	Route::match(['get','post'],'get_prime','UserController@get_prime')->name('get_prime');


	Route::get('reset_device/{id}','UserController@reset_device');

	Route::get('change_password/{id}','UserController@change_password');

	Route::get('export','UserController@export')->name('export_users');


	Route::post('/app_users_update','UserController@update');


//update user subription date 


	Route::post('/user_subcription','UserController@user_subcription');
	Route::get('deleteSubcription/{id}','UserController@deleteSubcription');




//Live Class Allocation
	Route::post('/allocate_live_class','FacultiesController@allocate_live_class');
	Route::get('faculties_view/{id}','FacultiesController@view')->name('faculties.view');


//PartnerApp
	Route::resource('partner','PartnerAppController');

	Route::post('/partner_update','PartnerAppController@update');

//Boards
	Route::resource('course','BoardsController');

	Route::post('/course_update','BoardsController@update');



//assignment
	Route::resource('assignment','AssignmentController');

	Route::post('/assignment_update','AssignmentController@update');




	Route::match(['get','post'],'/assignment/result/{id}','AssignmentController@result')->name('assignment.result');



	Route::get('get_assignment_users/{assignment_id}','AssignmentController@get_assignment_users')->name('get_assignment_users');






	//news & Feeds
	Route::resource('news','NewsController');
	Route::post('/news_update','NewsController@update');









	/////SIngle Chat
	Route::match(['get','post'],'/chats','ChatController@index')->name('chats.index');
	Route::match(['get','post'],'/get_user_list_from_program','ChatController@get_user_list_from_program')->name('chats.get_user_list_from_program');

	Route::match(['get','post'],'/get_chats','ChatController@get_chats')->name('chats.get_chats');
	Route::match(['get','post'],'/get_chat_by_user','ChatController@get_chat_by_user')->name('chats.get_chat_by_user');
	Route::match(['get','post'],'/get_user_name','ChatController@get_user_name')->name('chats.get_user_name');
	Route::match(['get','post'],'/send_message','ChatController@send_message')->name('chats.send_message');


	/////Group Chat

	Route::match(['get','post'],'/batch-wise-chats','ChatController@group_chat')->name('chats.group_chat');

	
	Route::match(['get','post'],'/block_user','ChatController@block_user')->name('chats.block_user');

	Route::match(['get','post'],'/get_groups','ChatController@get_groups')->name('chats.get_groups');
	Route::match(['get','post'],'/get_programchat_by_user','ChatController@get_programchat_by_user')->name('chats.get_programchat_by_user');
	
	Route::match(['get','post'],'/get_program_name','ChatController@get_program_name')->name('chats.get_program_name');
	Route::match(['get','post'],'/send_message_group','ChatController@send_message_group')->name('chats.send_message_group');



//Allocate Course/Board


	Route::match(['get','post'],'/allocate-user/{id}','BoardsController@allocate')->name('course.allocate');

	Route::match(['get','post'],'/allocate-delete/{id}/{board_id}','BoardsController@allocate_delete')->name('allocate.delete');




//Class
	Route::resource('class','ClassController');

	Route::post('/class_update','ClassController@update');


//Subject
	Route::resource('subject','SubjectController');

	Route::post('/subject_update','SubjectController@update');


//Get Subject List
	Route::post('/getSubjectList','SubjectController@getSubjectList');

//add Question In Exam
	Route::post('/addQuestionExam','ExamController@addQuestionExam');

//Chpater List
	Route::post('/getSubject','SubjectController@getSubject');

//Topic List
	Route::post('/getTopic','SubjectController@getTopic');

//Chapter
	Route::resource('chapter','ChapterController');

	Route::post('/chapter_update','ChapterController@update');





//Subject Topic
	Route::resource('subtopic','SubTopicController');

	Route::post('/subtopic_update','SubTopicController@update');






//Topic
	Route::resource('topic','TopicController');

	Route::post('/topic_update','TopicController@update');

//Promotional
	Route::resource('promotionalvideo','PromotionalController');

	Route::post('/promotionalvideo_update','PromotionalController@update');


//Testimonial
	Route::resource('testimonials','TestimonialController');

	Route::post('/testimonial_update','TestimonialController@update');




//monthweekpdf
	Route::resource('monthweekpdf','WeeklyController');

	Route::post('/monthweekpdf_update','WeeklyController@update');







//Subcription
	Route::resource('subcription','SubcriptionController');

	Route::post('/subcription_update','SubcriptionController@update');

//Subcription Type
	Route::resource('subcription_type','SubcriptionTypeController');

	Route::post('/subcription_type_update','SubcriptionTypeController@update');

//Subcription Packages
	Route::resource('subcription_packages','SubcriptionPackagesController');

	Route::post('/subcription_packages_update','SubcriptionPackagesController@update');

//Live Classes
	Route::resource('live_classes','LiveClassesController');

	Route::post('/live_classes_update','LiveClassesController@update');

//Content

// Route::match(['get','post'],'/new/content','ContentController@index')->name('content.index');
// 	Route::match(['get','post'],'/new/content/store','ContentController@store')->name('content.store');
// 	Route::match(['get','post'],'/new/content/create','ContentController@create')->name('content.create');

// 	Route::match(['get','post'],'/new/content/edit/{id}','ContentController@edit')->name('content.edit');
// 	Route::match(['get','post'],'/new/content/show/{id}','ContentController@show')->name('content.show');

// 	Route::match(['get','post'],'/new/content/create/{id}','ContentController@create');


// 	Route::match(['get','post'],'/new/content/destroy{id}','ContentController@destroy')->name('content.destroy');



	Route::match(['get','post'],'/contents/{topic_id}','TopicController@contents')->name('topic.contents');





	Route::prefix('new')->group(function () {

       Route::resource('content','ContentController');
 	Route::match(['get','post'],'/content/store','ContentController@store')->name('content.store');
	});


	Route::post('/new/content','ContentController@index');

	Route::post('/new/content','ContentController@delete_note')->name('note_delete');


//Update board status Ajax


	Route::post('/update-status','AdminController@update_status')->name('board.update_status');

	Route::post('/update_sub_status','AdminController@update_sub_status')->name('board.update_sub_status');


	Route::post('/update_chap_status','AdminController@update_chap_status')->name('board.update_chap_status');





	Route::post('/content_update','ContentController@update');

//Notes
	Route::resource('notes','NotesController');

	Route::post('/notes_update','NotesController@update');

//Tests
	Route::resource('test','TestController');

	Route::post('/test_update','TestController@update');


//Slides
	Route::resource('slides','SlidesController');

	Route::post('/slides_update','SlidesController@update');


//Question
	Route::resource('question','QuestionController');


	Route::post('deleteQuestion','QuestionController@destroy');

//update question

	Route::post('updateQuestion','QuestionController@update')->name('question.update');

	Route::post('uploadQuestionFile','QuestionController@uploadQuestionFile');



//getCustomFilter


	Route::get('getCustomFilter','QuestionController@questionList')->name('question.getCustomFilter');

//Exam
	Route::resource('exam','ExamController');

//exam update
	Route::post('exam_update','ExamController@update');

//exam report
	Route::match(['get','post'],'/reports/{id}','ExamController@reports')->name('report.show');

	Route::get('get_reports','BookController@get_reports')->name('get_reports');


//import question
	Route::post('importQuestionExamWise','ExamController@importQuestionExamWise');


//Instraction
	Route::resource('instraction','InstractionController');

//Instraction update
	Route::post('/instraction_update','InstractionController@update');


//faq
	Route::resource('faq','FaqController');

//faq update
	Route::post('/faq_update','FaqController@update');


//Corner
	Route::resource('corner','CornerController');

//Corner Update
	Route::post('/corner_update','CornerController@update');


//Book Category
	Route::resource('book_category','BookCategoryController');

//Book
	Route::resource('book','BookController');


////Prime
	Route::resource('primes','PrimeController');

	Route::match(['get','post'],'/prime/contents/{id}','PrimeController@contents')->name('primes.contents');
	Route::match(['get','post'],'save-content','PrimeController@contents_save')->name('primecontent.save');
	Route::match(['get','post'],'/prime/contents/delete/{content_id}','PrimeController@content_delete')->name('prime.content_delete');





////Prime
	Route::resource('feedback','FeedbackController');

	Route::match(['get','post'],'/feedback/contents/{id}','FeedbackController@contents')->name('feedback.contents');
	Route::match(['get','post'],'save-feedback-content','FeedbackController@contents_save')->name('feedbackcontent.save');
	Route::match(['get','post'],'/feedback/contents/delete/{content_id}','FeedbackController@content_delete')->name('feedback.content_delete');













// ger Books
	Route::get('get_books','BookController@getBooks')->name('get_books');


//Book Update
	Route::post('/book_update','BookController@update');

//Author
	Route::resource('author','AuthorController');

//Publisher
	Route::resource('publisher','PublisherController');

// App Banners
	Route::resource('app_banner','AppBannerController');

//Book Banner
	Route::resource('book_banner','BookBannerController');


//notification 

	Route::resource('notification','NotificationController');

//Change Profile 

	Route::resource('myprofile','MyProfileController');

//Setting 

	Route::resource('setting','SettingController');

// Quiz
	Route::resource('quiz','QuizController');


//Logout
	Route::get('/logout','SessionController@destroy');
	Route::get('/move_convert_hls','ContentController@move_convert_hls');



});
