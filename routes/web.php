<?php

use Illuminate\Support\Facades\Auth;
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

Route::get('/', 'WelcomeController@index')->name('welcome');

// Authentication Routes...
/*Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');*/
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('not-pending');
Route::get('/pending', 'HomeController@pending')->name('pending')->middleware('pending');

// Classes
Route::get('/classes', 'ClassController@index')->name('classes')->middleware('professor');
Route::get('/classes/{class}/members', 'ClassController@members')->name('classes.members');
Route::post('/classes', 'ClassController@store')->name('classes.store')->middleware('admin');
Route::get('/classes/{class}/edit', 'ClassController@edit')->name('classes.edit')->middleware('professor');
Route::patch('/classes/{class}', 'ClassController@update')->name('classes.update')->middleware('professor');
Route::get('/classes/d/{class}', 'ClassController@destroy')->name('classes.destroy')->middleware('admin');
// Professors
Route::get('/search/professors/{value}', 'ProfessorController@search')->name('search.professors');
Route::post('/members', 'ClassController@add_professors')->name('add.professors')->middleware('professor');
Route::get('/members/{class}/{professor}', 'ClassController@kick_professor')->name('kick.professors')->middleware('professor');
Route::get('/professors', 'ProfessorController@index')->name('professors')->middleware('professor');
// Students
Route::get('/search/students/{class}/{value}', 'StudentController@search')->name('search.students');
Route::get('/students/kick/{student}', 'StudentController@kick')->name('students.kick');
// Discussions
Route::get('/classes/{class}/discussions', 'DiscussionController@index')->name('classes.discussions');
Route::post('/discussions', 'DiscussionController@store')->name('discussions');
Route::get('/discussions/{discussion}/favorite', 'DiscussionController@favorite')->name('discussions.favorite');
Route::get('/discussions/{discussion}/bookmark', 'DiscussionController@bookmark')->name('discussions.bookmark');
Route::get('/discussions/{discussion}', 'DiscussionController@show')->name('discussions.show');
Route::get('/discussions/{discussion}/edit', 'DiscussionController@edit')->name('discussions.edit');
Route::patch('/discussions/{discussion}', 'DiscussionController@update')->name('discussions.update');
Route::get('/discussions/d/{discussion}', 'DiscussionController@destroy')->name('discussions.destroy');
// Resources
Route::get('/classes/{class}/resources', 'ResourceController@index')->name('classes.resources');
Route::post('/resources', 'ResourceController@store')->name('resources');
Route::get('/resources/{resource}', 'ResourceController@show')->name('resources.show');
Route::get('/resources/{resource}/edit', 'ResourceController@edit')->name('resources.edit')->middleware('professor');
Route::patch('/resources/{resource}', 'ResourceController@update')->name('resources.update')->middleware('professor');
Route::get('/resources/d/{resource}', 'ResourceController@destroy')->name('resources.destroy')->middleware('professor');
Route::get('/resources/{resource}/views', 'ResourceController@studentsViewHistory')->name('resources.views')->middleware('professor');
// Assignments
Route::get('/classes/{class}/assignments', 'AssignmentController@index')->name('classes.assignments');
Route::post('/assignments', 'AssignmentController@store')->name('assignments');
Route::get('/assignments/{assignment}', 'AssignmentController@show')->name('assignments.show');
Route::get('/assignments/{assignment}/edit', 'AssignmentController@edit')->name('assignments.edit');
Route::patch('/assignments/{assignment}', 'AssignmentController@update')->name('assignments.update');
Route::get('/assignments/d/{assignment}', 'AssignmentController@destroy')->name('assignments.destroy');
Route::get('/assignments/{assignment}/views', 'AssignmentController@studentsViewHistory')->name('assignments.views')->middleware('professor');
// Submissions
Route::get('/assignments/{assignment}/submissions', 'SubmissionController@index')->name('assignments.submissions')->middleware('professor');
Route::post('/submission', 'SubmissionController@store')->name('submissions')->middleware('student');
// Files
Route::get('/files/r/{file}', 'FileController@resource')->name('files.resource');
Route::get('/files/a/{file}', 'FileController@assignment')->name('files.assignment');
Route::get('/files/s/{file}', 'FileController@submission')->name('files.submission');
// Courses
Route::get('/classes/{class}/courses', 'CourseController@index')->name('classes.courses');
Route::post('/courses', 'CourseController@store')->name('courses')->middleware('professor');
Route::get('/courses/{course}/edit', 'CourseController@edit')->name('courses.edit')->middleware('professor');
Route::patch('/courses/{course}', 'CourseController@update')->name('courses.update')->middleware('professor');
Route::get('/courses/d/{course}', 'CourseController@destroy')->name('courses.destroy')->middleware('professor');
// Groups
Route::get('/classes/{class}/groups', 'GroupController@index')->name('classes.groups');
Route::post('/groups', 'GroupController@store')->name('groups')->middleware('professor');
Route::get('/groups/{group}/edit', 'GroupController@edit')->name('groups.edit')->middleware('professor');
Route::patch('/groups/{group}', 'GroupController@update')->name('groups.update')->middleware('professor');
Route::get('/groups/d/{group}', 'GroupController@destroy')->name('groups.destroy')->middleware('professor');
// Comment
Route::get('/comments', 'CommentsController@create')->name('comments.create');
Route::get('/comments/{comment}/update', 'CommentsController@update')->name('comments.update');
Route::get('/comments/d/{comment}', 'CommentsController@destroy')->name('comments.destroy');
// Bookmark
//Route::get('/bookmarks/{user}', 'BookmarksController@show')->name('bookmarks.show');
// Admin
Route::get('/admin/professors', 'AdminController@professors')->name('admin.professors')->middleware('admin');
Route::get('/admin/students', 'AdminController@students')->name('admin.students')->middleware('admin');
Route::post('/admin/user/accept/{user:cin}', 'AdminController@acceptUser')->name('admin.users.accept')->middleware('admin');
Route::get('/users/d/{user:cin}', 'AdminController@destroyUser')->name('admin.users.destroy');
// Profile
Route::get('/profile/{user:cin}/edit', 'ProfileController@edit')->name('profile.edit');
Route::patch('/profile/{user:cin}', 'ProfileController@update')->name('profile.update');
Route::patch('/profile/security/{user:cin}', 'ProfileController@updateSecurity')->name('profile.update.security');
// Announcement
Route::get('/announcements', 'AnnouncementController@index')->name('announcements');
Route::post('/announcements', 'AnnouncementController@store')->name('announcements')->middleware('professor');
Route::get('/announcements/{announcement}/edit', 'AnnouncementController@edit')->name('announcements.edit')->middleware('professor');
Route::patch('/announcements/{announcement}', 'AnnouncementController@update')->name('announcements.update')->middleware('professor');
Route::get('/announcements/d/{announcement}', 'AnnouncementController@destroy')->name('announcements.destroy')->middleware('professor');