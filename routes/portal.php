<?php

use Illuminate\Support\Facades\Route;

//portal routes
Route::group(['prefix'=>'/portal/frontend','namespace' => 'Portal\Frontend'], function(){
    Route::get('/header', 'FrontendController@header');
    Route::get('/footer', 'FrontendController@footer');
    Route::get('/service', 'FrontendController@service');
    Route::get('/service/show', 'ServiceController@show');
    Route::get('/customer-type-list', 'FrontendController@customerTypeList');
    Route::get('/service-list', 'FrontendController@serviceList');
    Route::get('/category-list', 'FrontendController@categoryList');
    Route::get('/org-list', 'FrontendController@orgList');
    Route::get('/qa-home', 'FAQController@faqListHome');
    Route::get('/news-home', 'NewsController@newsListHome');
    Route::get('/notice-home', 'NoticeController@noticeListHome');
    Route::get('/faq-list', 'FAQController@index');
    Route::get('/news-list', 'NewsController@index');
    Route::get('/notice-list', 'NoticeController@index');
    Route::get('/news/show', 'NewsController@show');
    Route::get('/news/popular', 'NewsController@popular');
});