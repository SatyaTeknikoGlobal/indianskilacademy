<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});





 // Route::match(['get','post'],'change_password', 'ApiController@change_password');



Route::match(['get','post'],'social_login', 'ApiController@social_login');


Route::get('active_users', 'ApiController@activeUsers');

// Route::group(['middleware' => ['sessions']], function () {
    Route::get('getState', 'ApiController@getState');
    Route::post('getCity', 'ApiController@getCity');
    Route::get('getCourses', 'ApiController@getCourses');




    Route::get('send_notification', 'ApiController@send_notification_all');


    Route::post('send_otp', 'ApiController@send_otp');



    Route::get('send_email_attachment', 'InstaMojoController@send_email_attachment');





    Route::match(['get','post'],'settings', 'ApiController@settings');



    Route::match(['get','post'],'forget_password', 'ApiController@forget_password');
    Route::match(['get','post'],'verify_otp_fp', 'ApiController@verify_otp_fp');




    Route::post('send_otp_v2', 'ApiController@send_otp2');
    Route::post('login', 'ApiController@login');










///////New Api


    Route::post('login_v2', 'ApiController@login2');
    Route::post('login_with_pw', 'ApiController@login_with_pw');

    Route::post('course_list_v2', 'ApiController@course_list_v2');
    Route::post('get_batch_from_course', 'ApiController@get_batch_from_course');




    Route::post('pdf_list_v2', 'ApiController@pdf_list_v2');



///////New Api End






    Route::post('verify_otp', 'ApiController@verifyOtp');
    Route::post('register', 'ApiController@register');
    Route::post('register_v2', 'ApiController@register2');
    Route::post('validateEmail', 'ApiController@validateEmail');
    Route::post('validateEmail2', 'ApiController@validateEmail2');
    Route::get('app_version', 'ApiController@app_version');
    Route::post('contactUs', 'ApiController@contactUs');

//Instamojo
    Route::post('instamojo/create', 'InstaMojoController@createPayment');
    Route::post('instamojo/webhook', 'InstaMojoController@webHook');
    Route::get('instamojo/redirect', 'InstaMojoController@redirectUrl');
    Route::match(['get','post'],'donations', 'InstaMojoController@donations');
    Route::match(['get','post'],'get_live_classes', 'ApiController@get_live_classes');












// Teacher
    Route::post('loginTeacher', 'TeacherController@teacherLogin');
    Route::post('listLiveClassTeacher', 'TeacherController@listLiveClassTeacher');
    Route::post('homeTeacher', 'TeacherController@home');
    Route::post('addLiveClass', 'TeacherController@addLiveClass');
    Route::post('getInstraction', 'TeacherController@getInstraction');
    Route::post('getCourse', 'TeacherController@getCourse');
    Route::post('getSubject', 'TeacherController@getSubject');
    Route::post('addExam', 'TeacherController@addExam');
    Route::post('teacherExamList', 'TeacherController@teacherExamList');
    Route::post('updateExam', 'TeacherController@updateExam');
    Route::post('teacherProfile', 'TeacherController@teacherProfile');
    Route::post('updateTeacherProfile', 'TeacherController@updateTeacherProfile');


    Route::get('reSubmitForSubject', 'ExamController@reSubmitForSubject');


    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::post('addUserNotes', 'ApiController@addUserNotes');
        Route::post('getUserNoteSingle', 'ApiController@getSingleNote');
        Route::post('listUserNotes', 'ApiController@listUserNotes');
        Route::post('topicContents', 'ApiController@topicContents');
        Route::post('appSearch', 'ApiController@appSearch');
        Route::post('changeCourse', 'ApiController@changeCourse');
        Route::post('allCourses', 'ApiController@allCourses');
        Route::post('resumeVideo', 'ApiController@resumeVideo');
        Route::post('resumeVideosList', 'ApiController@resumeVideosList');
        Route::post('request_money', 'ApiController@requestMoney');
        Route::post('logout', 'ApiController@logout');
        Route::post('profile', 'ApiController@profile');
        Route::post('home', 'ApiController@home');
        Route::post('classes', 'ApiController@classes');
        Route::post('topic', 'ApiController@topic');
        Route::post('contents', 'ApiController@contents');
        Route::post('open_content', 'ApiController@openContent');
        Route::post('related_content', 'ApiController@relatedVideos');
        Route::post('courses', 'ApiController@courses');
        Route::post('send_message', 'ApiController@add_chat');
        Route::post('get_message', 'ApiController@get_chat');
        Route::post('join_user', 'ApiController@join_user');
        Route::post('join_user_list', 'ApiController@join_user_list');
        Route::post('contact', 'ApiController@contact');
        Route::post('subscribeWallet', 'InstaMojoController@subscribeWallet');
        Route::post('mySubscriptions', 'InstaMojoController@mySubscriptions');


        Route::match(['get','post'],'check_payment', 'InstaMojoController@check_payment');
        Route::match(['get','post'],'notification/list', 'ApiController@notification');


        Route::match(['get','post'],'app_sidebar', 'ApiController@app_sidebar');

        
        Route::match(['get','post'],'chat_program', 'ApiController@chat_program');
        Route::match(['get','post'],'get_group_chat', 'ApiController@get_group_chat');
        Route::match(['get','post'],'submit_chat', 'ApiController@submit_chat');




        Route::match(['get','post'],'offer_zone', 'ApiController@offer_zone');
        Route::match(['get','post'],'monthlypdf', 'ApiController@monthlypdf');




        Route::match(['get','post'],'books_list', 'ApiController@books_list');
        Route::match(['get','post'],'get_exam_groups', 'ApiController@get_exam_groups');
        Route::match(['get','post'],'add_user_to_exam_group', 'ApiController@add_user_to_exam_group');
        Route::match(['get','post'],'get_exam_type_chat', 'ApiController@get_exam_type_chat');
        Route::match(['get','post'],'save_chats', 'ApiController@save_chats');






        Route::match(['get','post'],'program_list', 'ApiController@program_list');

        Route::match(['get','post'],'get_prime', 'InstaMojoController@get_prime');


        Route::match(['get','post'],'get_prime_content', 'ApiController@get_prime_content');
        Route::match(['get','post'],'get_feedback_content', 'ApiController@get_feedback_content');



        
        Route::match(['get','post'],'promotional_video', 'ApiController@promotional_video');



        /////////////////////AI DASHBOARD/////////////////////////////
        Route::match(['get','post'],'add_get_subject', 'ApiController@add_get_subject');
        Route::match(['get','post'],'add_date_wise_events', 'ApiController@add_date_wise_events');
        Route::match(['get','post'],'get_student_events', 'ApiController@get_student_events');

        Route::match(['get','post'],'get_student_events_date_wise', 'ApiController@get_student_events_date_wise');
        Route::match(['get','post'],'add_notes', 'ApiController@add_notes');
        Route::match(['get','post'],'add_reminders', 'ApiController@add_reminders');



        Route::match(['get','post'],'update_events', 'ApiController@update_events');
        Route::match(['get','post'],'my_notes', 'ApiController@my_notes');
        Route::match(['get','post'],'dashboard', 'ApiController@dashboard');
        Route::match(['get','post'],'daily_goals', 'ApiController@daily_goals');
        Route::match(['get','post'],'show_revision_data', 'ApiController@show_revision_data');
        Route::match(['get','post'],'get_pattern', 'ApiController@get_pattern');
        Route::match(['get','post'],'reminder_notification', 'ApiController@reminder_notification');
        Route::match(['get','post'],'daily_goal_notification', 'ApiController@daily_goal_notification');













        /////////////////////End AI DASHBOARD/////////////////////////////

        
        Route::match(['get','post'],'video_rating', 'ApiController@video_rating');
        Route::match(['get','post'],'user_video_rating', 'ApiController@user_video_rating');



        ///////////////////////Razorpay


    Route::match(['get','post'],'razorpay_create_payment', 'InstaMojoController@razorpay_create_payment');
    Route::match(['get','post'],'initiate_txn', 'InstaMojoController@initiate_txn');
    Route::match(['get','post'],'razor_webhook', 'InstaMojoController@razor_webhook');

    
    Route::match(['get','post'],'add_wallet', 'InstaMojoController@add_wallet');


    

///////////////////////////////////////




    Route::match(['get','post'],'ask_doubt/list', 'ApiController@ask_doubt');
    Route::match(['get','post'],'ask_doubt/send_message', 'ApiController@send_messages');


    Route::match(['get','post'],'monthlypdf_list', 'ApiController@monthlypdf_list');





        Route::get('about_us', 'ApiController@aboutUs');



    Route::match(['get','post'],'course_list', 'ApiController@course_list');
    Route::match(['get','post'],'program_list_by_course', 'ApiController@program_list_by_course');
    Route::match(['get','post'],'assignment_list', 'ApiController@assignment_list');
    Route::match(['get','post'],'solveed_assignment', 'ApiController@solveed_assignment');





    Route::match(['get','post'],'quiz_list', 'ApiController@quiz_list');

        Route::match(['get','post'],'get_quiz_list_from_month', 'ApiController@get_quiz_list_from_month');



    Route::match(['get','post'],'change_password', 'ApiController@change_password');

        

    Route::match(['get','post'],'video_list', 'ApiController@video_list');
    Route::match(['get','post'],'pdf_list', 'ApiController@pdf_list');


        //Route::match(['get','post'],'quiz_list', 'ApiController@quiz_list');






        Route::match(['get','post'],'exam_list_user', 'ApiController@exam_list');
        Route::match(['get','post'],'subscription_list', 'ApiController@subscription_list');




        /////////////////News Section
        Route::match(['get','post'],'news_list', 'ApiController@newsList');
        Route::match(['get','post'],'get_news_list_from_month', 'ApiController@getNewsListFromMonth');
        Route::match(['get','post'],'news_details', 'ApiController@newsDetail');




        Route::match(['get','post'],'bookmark_news', 'ApiController@bookmarkNews');

        Route::match(['get','post'],'daily_news_analysis', 'ApiController@DailyNewsAnalyisis');
        Route::match(['get','post'],'bookmark_list', 'ApiController@bookmark_list');
        Route::match(['get','post'],'bookmark_master', 'ApiController@bookmark_master');

        Route::match(['get','post'],'news_search', 'ApiController@news_search');


        Route::match(['get','post'],'check_subscription', 'SubscriptionController@check_subscription');
        Route::match(['get','post'],'program_details', 'ApiController@program_details');




        ///////////////end news section

        Route::match(['get','post'],'testimonials', 'ApiController@testimonials');


        //Examination
        Route::post('Courses', 'ExamController@Courses');
        Route::post('exam_list', 'ExamController@ExamList');
        Route::post('exam_details', 'ExamController@ExamDetails');

        Route::post('open_exam', 'ExamController@OpenExam');
        Route::post('submit_exam', 'ExamController@SubmitExam');
        Route::post('view_particept', 'ExamController@ViewParticept');
        Route::post('review_question', 'ExamController@review_question');
        Route::post('view_analysis', 'ExamController@view_analysis');
        Route::post('custom_filter_list', 'ExamController@Custom_Filter_List');
        Route::post('openMockTest', 'ExamController@OpenMockTest');
        Route::post('QbankSubjectList', 'ExamController@QbankSubjectList');
        Route::post('qbank_topic', 'ExamController@qbank_topic');

        Route::post('subjectList', 'ExamController@subjectList');
        Route::post('chapterList', 'ExamController@chapterList');
        Route::post('topicList', 'ExamController@topicList');
        Route::post('videoList', 'ExamController@videoList');
        Route::post('getVideoReportList', 'ExamController@getVideoReportList');
        Route::post('notesList', 'ExamController@notesList');
        Route::post('getReportList', 'ExamController@getReportList');
        Route::post('reportQuestion', 'ExamController@reportQuestion');
        Route::post('graphData', 'ExamController@graphData');

        //Rank Screen
        Route::post('viewRank', 'ExamController@viewRank');
        Route::post('examRank', 'ExamController@examRank');





        Route::post('ExamDetails', 'ExamController@ExamDetails');
        Route::post('getExamInstruction', 'ExamController@getExamInstruction');
        Route::post('viewResult', 'ExamController@viewResult');

        Route::post('update_profile', 'ApiController@update_profile');
        Route::post('viewAllFreeVideoes', 'ApiController@viewAllFreeVideoes');
        Route::post('viewAllCorner', 'ApiController@viewAllCorner');
        Route::post('faqList', 'ApiController@faqList');




        Route::match(['get','post'],'video_categories', 'ApiController@video_categories');




        //Analyse
        Route::post('analyseTest', 'ExamController@analyseTest');

        //Subscription
        Route::prefix('subscription')->group(function () {
            Route::post('type','SubscriptionController@subscription_type');
            Route::post('subscription_packages','SubscriptionController@subscription_packages');
            Route::post('apply_coupon_code','SubscriptionController@apply_coupon_code');
            Route::post('apply_coupon','SubscriptionController@apply_coupon');
            Route::post('couponList','SubscriptionController@couponList');
            Route::post('user_wallet','SubscriptionController@user_wallet');
            Route::post('purchase_subscription','SubscriptionController@purchase_subscription');
            Route::post('purchase_subscription_new','SubscriptionController@purchase_subscription_new');
            Route::post('subscripton_history','SubscriptionController@subscripton_history');
        });

        //Discussion Forum
        Route::prefix('discussion')->namespace('Discussion')->group(function () {
            Route::post('topics', 'TopicController@index');
            Route::post('my_topics', 'TopicController@myDoubts');
            Route::post('add_topics', 'TopicController@addDoubts');
            Route::post('answers', 'AnswerController@index');
            Route::post('add_answers', 'AnswerController@addAnswer');
            Route::post('vote', 'VotesController@index');
        });


        //Books Forum
        Route::prefix('books')->group(function () {
            Route::post('home', 'BookController@home');
            Route::post('getCategoryWiseBooks', 'BookController@getCategoryWiseBooks');
            Route::post('getBookDetails', 'BookController@getBookDetails');
            Route::post('getCartCount', 'BookController@getCartCount');
            Route::post('addToCart', 'BookController@addToCart');
            Route::post('removeCart', 'BookController@removeCart');
            Route::post('getCart', 'BookController@getCart');
            Route::post('addAddress', 'BookController@addAddress');
            Route::post('updateAddress', 'BookController@updateAddress');
            Route::post('getDefaultAddress', 'BookController@getDefaultAddress');
            Route::post('getAddress', 'BookController@getAddress');
            Route::post('getCoupons', 'BookController@getCoupons');
            Route::post('myOrders', 'BookController@myOrders');

        });

    // });

    Route::post('purchase_subscription1','SubscriptionController@purchase_subscription1');

    Route::post("store_video",'VideoController@store');

});