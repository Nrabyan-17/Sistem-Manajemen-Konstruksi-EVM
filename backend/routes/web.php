<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\Projects;
use App\Livewire\ProjectDetail;
use App\Livewire\Reports;
use App\Livewire\Addendum;
use App\Livewire\ReportingCenter;

Route::get('/', function () {
    return redirect()->to('/login');
});

Route::get('/login', Login::class)->name('login');
Route::get('/dashboard', Dashboard::class)->name('dashboard');
Route::get('/projects', Projects::class)->name('projects');
Route::get('/projects/{projectId}', ProjectDetail::class)->name('projects.show');
Route::get('/reports', ReportingCenter::class)->name('reports');
Route::get('/reports/evm-performance', Reports::class)->name('reports.evm');
Route::get('/addendum', Addendum::class)->name('addendum');
