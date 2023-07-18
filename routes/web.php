<?php

use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ExamsController;
use App\Http\Controllers\FormativeAssessmentController;
use App\Http\Controllers\LearnerController;
use App\Http\Controllers\LearningActivitiesController;
use App\Http\Controllers\PerformanceLevelsController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StrandController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\SubStrandController;
use App\Http\Controllers\SummativeAssessmentController;
use App\Http\Controllers\SummativePerformanceLevelsController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\TermsSubjectsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionController;

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
    return view('home');
});


Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

Route::get('password/forget', function () {
    return view('pages.forgot-password');
})->name('password.forget');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');


Route::group(['middleware' => 'auth'], function () {
    // logout route
    Route::get('/logout', [LoginController::class, 'logout']);
    Route::get('/clear-cache', [HomeController::class, 'clearCache']);

    // dashboard route
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    //only those have manage_user permission will get access
    Route::group(['middleware' => 'can:manage_admins'], function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/user/get-list', [UserController::class, 'getUserList']);
        Route::get('/user/create', [UserController::class, 'create']);
        Route::post('/user/create', [UserController::class, 'store'])->name('create-user');
        Route::get('/user/{id}', [UserController::class, 'edit']);
        Route::post('/user/update', [UserController::class, 'update']);
        Route::get('/user/delete/{id}', [UserController::class, 'delete']);
    });

    Route::group(['middleware' => 'can:manage_teachers', 'as' => 'teachers.', 'prefix' => 'teachers'], function () {
        Route::get('/', [TeacherController::class, 'index'])->name('index');
        Route::get('/get-list', [TeacherController::class, 'getList'])->name('get-list');
        Route::post('/store', [TeacherController::class, 'store'])->name('store');
        Route::post('/update/{id}', [TeacherController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [TeacherController::class, 'destroy'])->name('delete');
        Route::get('/view/{id}', [TeacherController::class, 'show'])->name('show');
        Route::get('/change-status/{id}', [TeacherController::class, 'changeStatus'])->name('change-status');
    });

    Route::group(['middleware' => 'can:manage_learners', 'as' => 'learners.', 'prefix' => 'learners'], function () {
        Route::get('/', [LearnerController::class, 'index'])->name('index');
        Route::get('/get-list', [LearnerController::class, 'getList'])->name('get-list');
        Route::post('/store', [LearnerController::class, 'store'])->name('store');
        Route::post('/update/{id}', [LearnerController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [LearnerController::class, 'destroy'])->name('delete');
        Route::get('/change-status/{id}', [LearnerController::class, 'changeStatus'])->name('change-status');
        Route::post('/import', [LearnerController::class, 'import'])->name('import');
    });

    Route::group(['middleware' => 'can:manage_settings', 'as' => 'settings.', 'prefix' => 'settings'], function () {
        Route::group(['as' => 'schools.', 'prefix' => 'schools'], function () {
            Route::get('/', [SchoolController::class, 'index'])->name('index');
            Route::get('/create', [SchoolController::class, 'create'])->name('create');
            Route::get('/edit/{id}', [SchoolController::class, 'edit'])->name('edit');
            Route::get('/get-list', [SchoolController::class, 'getList'])->name('get-list');
            Route::post('/store', [SchoolController::class, 'store'])->name('store');
            Route::post('/update/{id}', [SchoolController::class, 'update'])->name('update');
            Route::get('/delete/{id}', [SchoolController::class, 'destroy'])->name('delete');
        });
        Route::group(['as' => 'branches.', 'prefix' => 'branches'], function () {
            Route::get('/', [SchoolController::class, 'index'])->name('index');
            Route::get('/create', [SchoolController::class, 'create'])->name('create');
            Route::get('/edit/{id}', [SchoolController::class, 'edit'])->name('edit');
            Route::get('/get-list', [SchoolController::class, 'getList'])->name('get-list');
            Route::post('/store', [SchoolController::class, 'store'])->name('store');
            Route::post('/update/{id}', [SchoolController::class, 'update'])->name('update');
            Route::get('/delete/{id}', [SchoolController::class, 'destroy'])->name('delete');
        });
    });

    Route::group(['middleware' => 'can:manage_subjects', 'as' => 'subjects.', 'prefix' => 'subjects'], function () {
        Route::get('/', [SubjectsController::class, 'index'])->name('index');
        Route::get('/get-list', [SubjectsController::class, 'getList'])->name('get-list');
        Route::post('/store', [SubjectsController::class, 'store'])->name('store');
        Route::post('/update/{id}', [SubjectsController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [SubjectsController::class, 'destroy'])->name('delete');
    });

    Route::group(['middleware' => 'can:manage_formative_assessments', 'as' => 'formative-assessments.', 'prefix' => 'formative-assessments'], function () {
        Route::get('/{class?}/{stream?}/{subject?}', [FormativeAssessmentController::class, 'index'])->name('index');
        Route::post('/save-formative-assessment', [FormativeAssessmentController::class, 'save'])->name('save');
    });

    Route::group(['middleware' => 'can:manage_summative_assessments', 'as' => 'summative-assessments.', 'prefix' => 'summative-assessments'], function () {
        Route::get('/{class?}/{stream?}/{subject?}', [SummativeAssessmentController::class, 'index'])->name('index');
        Route::post('/save-formative-assessment', [SummativeAssessmentController::class, 'save'])->name('save');
    });

    Route::post('/get-summative-assessments', [SummativeAssessmentController::class, 'getAssessments']);
    Route::post('/get-assessments', [FormativeAssessmentController::class, 'getAssessments']);

    Route::group(['middleware' => 'can:manage_formative_assessments', 'as' => 'reports.', 'prefix' => 'reports'], function () {
        Route::get('/', [FormativeAssessmentController::class, 'reports'])->name('index');
        Route::get('/get-list', [FormativeAssessmentController::class, 'getLearners']);
        Route::get('/view-learner-subjects/{learner_id}/{stream_id}/{term_id}', [FormativeAssessmentController::class, 'viewSubjects'])->name('view-subjects');
        Route::get('/view-learner-result/{subject_id}/{learner_id}/{term_id}/{stream_id}', [FormativeAssessmentController::class, 'viewResult'])->name('view-result');
        Route::get('/download-pdf/{learner_id}/{stream_id}/{term_id}/{send_email?}', [FormativeAssessmentController::class, 'downloadPdf'])->name('download-pdf');
    });

    Route::group(['middleware' => 'can:manage_summative_assessments', 'as' => 'summative-reports.', 'prefix' => 'summative-reports'], function () {
        Route::get('/', [SummativeAssessmentController::class, 'learnersView'])->name('index');
        Route::get('/get-list', [SummativeAssessmentController::class, 'getLearners']);
        Route::get('/view-learner-subjects/{learner_id}/{stream_id}/{term_id}', [FormativeAssessmentController::class, 'viewSubjects'])->name('view-subjects');
        Route::get('/view-learner-result/{learner_id}/{term_id}/{stream_id}/{exam_id}', [SummativeAssessmentController::class, 'viewResult'])->name('view-result');
        Route::get('/download-pdf/{learner_id}/{stream_id}/{term_id}/{exam_id}/{send_email?}', [SummativeAssessmentController::class, 'downloadPdf'])->name('download-pdf');
    });

    Route::group(['middleware' => 'can:manage_students_subjects', 'as' => 'learners-subjects.', 'prefix' => 'learners-subjects'], function () {
        Route::get('/', [LearnerController::class, 'addLearnerSubjects'])->name('index');
        Route::post('/save-learners-subjects', [LearnerController::class, 'saveLearnerSubjects'])->name('save');
        Route::post('/update-learners-subjects', [LearnerController::class, 'updateLearnerSubjects'])->name('update');
    });

    Route::get('/get-school-classes/{id}', [SchoolController::class, 'getClasses']);
    Route::get('/get-streams/{id}', [ClassesController::class, 'getStreams']);
    Route::get('/get-sub-strands/{id}', [StrandController::class, 'getSubStrands']);
    Route::get('/get-learning-activities/{id}', [SubStrandController::class, 'getLearningActivities']);
    Route::get('/get-learners/{id}', [StreamController::class, 'getLearners']);
    Route::get('/get-term-exams/{id}', [TermsController::class, 'getExams']);

    Route::group(['middleware' => 'can:manage_classes', 'as' => 'classes.', 'prefix' => 'classes'], function () {
        Route::get('/', [ClassesController::class, 'index'])->name('index');
        Route::get('/get-list', [ClassesController::class, 'getList'])->name('get-list');
        Route::post('/store', [ClassesController::class, 'store'])->name('store');
        Route::post('/update/{id}', [ClassesController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [ClassesController::class, 'destroy'])->name('delete');
    });

    Route::group(['middleware' => 'can:manage_assigned_teachers', 'as' => 'teachers.', 'prefix' => 'teachers'], function () {
        Route::get('/manage-assigned-teachers', [TeacherController::class, 'manageTeachers'])->name('manage-assigned-teachers');
        Route::post('/save-manage-assigned-teachers', [TeacherController::class, 'saveManageTeachers'])->name('save-manage-assigned-teachers');
        Route::post('/save-manage-assigned-teachers-subjects', [TeacherController::class, 'saveManageTeachersSubjects'])->name('save-manage-assigned-teachers-subjects');
        Route::get('/remove/{id}', [TeacherController::class, 'removeClass'])->name('remove-class');
        Route::get('/remove-subject/{id}', [TeacherController::class, 'removeSubject'])->name('remove-subject');
    });

    Route::group(['middleware' => 'can:manage_streams', 'as' => 'streams.', 'prefix' => 'streams'], function () {
        Route::get('/', [StreamController::class, 'index'])->name('index');
        Route::get('/get-list', [StreamController::class, 'getList'])->name('get-list');
        Route::post('/store', [StreamController::class, 'store'])->name('store');
        Route::post('/update/{id}', [StreamController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [StreamController::class, 'destroy'])->name('delete');
    });

    Route::group(['middleware' => 'can:manage_performance_levels', 'as' => 'performance-levels.', 'prefix' => 'performance-levels'], function () {
        Route::get('/', [PerformanceLevelsController::class, 'index'])->name('index');
        Route::get('/get-list', [PerformanceLevelsController::class, 'getList'])->name('get-list');
        Route::post('/store', [PerformanceLevelsController::class, 'store'])->name('store');
        Route::post('/update/{id}', [PerformanceLevelsController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [PerformanceLevelsController::class, 'destroy'])->name('delete');
    });

    Route::group(['middleware' => 'can:manage_summative_performance_levels', 'as' => 'summative-performance-levels.', 'prefix' => 'summative-performance-levels'], function () {
        Route::get('/', [SummativePerformanceLevelsController::class, 'index'])->name('index');
        Route::get('/get-list', [SummativePerformanceLevelsController::class, 'getList'])->name('get-list');
        Route::post('/store', [SummativePerformanceLevelsController::class, 'store'])->name('store');
        Route::post('/update/{id}', [SummativePerformanceLevelsController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [SummativePerformanceLevelsController::class, 'destroy'])->name('delete');
    });

    Route::group(['middleware' => 'can:manage_strands', 'as' => 'strands.', 'prefix' => 'strands'], function () {
        Route::get('/', [StrandController::class, 'index'])->name('index');
        Route::get('/get-list', [StrandController::class, 'getList'])->name('get-list');
        Route::post('/store', [StrandController::class, 'store'])->name('store');
        Route::post('/update/{id}', [StrandController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [StrandController::class, 'destroy'])->name('delete');
    });

    Route::group(['middleware' => 'can:manage_strands', 'as' => 'sub-strands.', 'prefix' => 'sub-strands'], function () {
        Route::get('/', [SubStrandController::class, 'index'])->name('index');
        Route::get('/get-list', [SubStrandController::class, 'getList'])->name('get-list');
        Route::post('/store', [SubStrandController::class, 'store'])->name('store');
        Route::post('/update/{id}', [SubStrandController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [SubStrandController::class, 'destroy'])->name('delete');
    });

    Route::group(['middleware' => 'can:manage_strands', 'as' => 'learning-activities.', 'prefix' => 'learning-activities'], function () {
        Route::get('/', [LearningActivitiesController::class, 'index'])->name('index');
        Route::get('/get-list', [LearningActivitiesController::class, 'getList'])->name('get-list');
        Route::post('/store', [LearningActivitiesController::class, 'store'])->name('store');
        Route::post('/update/{id}', [LearningActivitiesController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [LearningActivitiesController::class, 'destroy'])->name('delete');
    });

    Route::group(['middleware' => 'can:manage_terms', 'as' => 'terms.', 'prefix' => 'terms'], function () {
        Route::get('/', [TermsController::class, 'index'])->name('index');
        Route::get('/get-list', [TermsController::class, 'getList'])->name('get-list');
        Route::post('/store', [TermsController::class, 'store'])->name('store');
        Route::post('/update/{id}', [TermsController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [TermsController::class, 'destroy'])->name('delete');
    });

    Route::group(['middleware' => 'can:manage_terms', 'as' => 'term-subjects.', 'prefix' => 'term-subjects'], function () {
        Route::get('/', [TermsSubjectsController::class, 'index'])->name('index');
        Route::get('/get-list', [TermsSubjectsController::class, 'getList'])->name('get-list');
        Route::post('/store', [TermsSubjectsController::class, 'store'])->name('store');
        Route::post('/update/{id}', [TermsSubjectsController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [TermsSubjectsController::class, 'destroy'])->name('delete');
    });


    Route::group(['middleware' => 'can:manage_terms', 'as' => 'exams.', 'prefix' => 'exams'], function () {
        Route::get('/', [ExamsController::class, 'index'])->name('index');
        Route::get('/get-list', [ExamsController::class, 'getList'])->name('get-list');
        Route::post('/store', [ExamsController::class, 'store'])->name('store');
        Route::post('/update/{id}', [ExamsController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [ExamsController::class, 'destroy'])->name('delete');
    });

    //only those have manage_role permission will get access
    Route::group(['middleware' => 'can:manage_role|manage_user'], function () {
        Route::get('/roles', [RolesController::class, 'index']);
        Route::get('/role/get-list', [RolesController::class, 'getRoleList']);
        Route::post('/role/create', [RolesController::class, 'create']);
        Route::get('/role/edit/{id}', [RolesController::class, 'edit']);
        Route::post('/role/update', [RolesController::class, 'update']);
        Route::get('/role/delete/{id}', [RolesController::class, 'delete']);
    });

    //only those have manage_permission permission will get access
    Route::group(['middleware' => 'can:manage_permission|manage_user'], function () {
        Route::get('/permission', [PermissionController::class, 'index']);
        Route::get('/permission/get-list', [PermissionController::class, 'getPermissionList']);
        Route::post('/permission/create', [PermissionController::class, 'create']);
        Route::get('/permission/update', [PermissionController::class, 'update']);
        Route::get('/permission/delete/{id}', [PermissionController::class, 'delete']);
    });

    // get permissions
    Route::get('get-role-permissions-badge', [PermissionController::class, 'getPermissionBadgeByRole']);

    // permission examples
    Route::get('/permission-example', function () {
        return view('permission-example');
    });
    // API Documentation
    Route::get('/rest-api', function () {
        return view('api');
    });
    // Editable Datatable
    Route::get('/table-datatable-edit', function () {
        return view('pages.datatable-editable');
    });

    // Themekit demo pages
    Route::get('/calendar', function () {
        return view('pages.calendar');
    });
    Route::get('/charts-amcharts', function () {
        return view('pages.charts-amcharts');
    });
    Route::get('/charts-chartist', function () {
        return view('pages.charts-chartist');
    });
    Route::get('/charts-flot', function () {
        return view('pages.charts-flot');
    });
    Route::get('/charts-knob', function () {
        return view('pages.charts-knob');
    });
    Route::get('/forgot-password', function () {
        return view('pages.forgot-password');
    });
    Route::get('/form-addon', function () {
        return view('pages.form-addon');
    });
    Route::get('/form-advance', function () {
        return view('pages.form-advance');
    });
    Route::get('/form-components', function () {
        return view('pages.form-components');
    });
    Route::get('/form-picker', function () {
        return view('pages.form-picker');
    });
    Route::get('/invoice', function () {
        return view('pages.invoice');
    });
    Route::get('/layout-edit-item', function () {
        return view('pages.layout-edit-item');
    });
    Route::get('/layouts', function () {
        return view('pages.layouts');
    });

    Route::get('/navbar', function () {
        return view('pages.navbar');
    });
    Route::get('/profile', function () {
        return view('pages.profile');
    });
    Route::get('/project', function () {
        return view('pages.project');
    });
    Route::get('/view', function () {
        return view('pages.view');
    });

    Route::get('/table-bootstrap', function () {
        return view('pages.table-bootstrap');
    });
    Route::get('/table-datatable', function () {
        return view('pages.table-datatable');
    });
    Route::get('/taskboard', function () {
        return view('pages.taskboard');
    });
    Route::get('/widget-chart', function () {
        return view('pages.widget-chart');
    });
    Route::get('/widget-data', function () {
        return view('pages.widget-data');
    });
    Route::get('/widget-statistic', function () {
        return view('pages.widget-statistic');
    });
    Route::get('/widgets', function () {
        return view('pages.widgets');
    });

    // themekit ui pages
    Route::get('/alerts', function () {
        return view('pages.ui.alerts');
    });
    Route::get('/badges', function () {
        return view('pages.ui.badges');
    });
    Route::get('/buttons', function () {
        return view('pages.ui.buttons');
    });
    Route::get('/cards', function () {
        return view('pages.ui.cards');
    });
    Route::get('/carousel', function () {
        return view('pages.ui.carousel');
    });
    Route::get('/icons', function () {
        return view('pages.ui.icons');
    });
    Route::get('/modals', function () {
        return view('pages.ui.modals');
    });
    Route::get('/navigation', function () {
        return view('pages.ui.navigation');
    });
    Route::get('/notifications', function () {
        return view('pages.ui.notifications');
    });
    Route::get('/range-slider', function () {
        return view('pages.ui.range-slider');
    });
    Route::get('/rating', function () {
        return view('pages.ui.rating');
    });
    Route::get('/session-timeout', function () {
        return view('pages.ui.session-timeout');
    });
    Route::get('/pricing', function () {
        return view('pages.pricing');
    });


    // new inventory routes
    Route::get('/inventory', function () {
        return view('inventory.dashboard');
    });
    Route::get('/pos', function () {
        return view('inventory.pos');
    });
    Route::get('/products', function () {
        return view('inventory.product.list');
    });
    Route::get('/products/create', function () {
        return view('inventory.product.create');
    });
    Route::get('/categories', function () {
        return view('inventory.category.index');
    });
    Route::get('/sales', function () {
        return view('inventory.sale.list');
    });
    Route::get('/sales/create', function () {
        return view('inventory.sale.create');
    });
    Route::get('/purchases', function () {
        return view('inventory.purchase.list');
    });
    Route::get('/purchases/create', function () {
        return view('inventory.purchase.create');
    });
    Route::get('/customers', function () {
        return view('inventory.people.customers');
    });
    Route::get('/suppliers', function () {
        return view('inventory.people.suppliers');
    });

});


Route::get('/register', function () {
    return view('pages.register');
});
Route::get('/login-1', function () {
    return view('pages.login');
});

Route::get('/send-mail', [\App\Http\Controllers\TestController::class, 'basic_email']);
Route::get('/test-email-view', function () {
   return view('emails.template');
});
