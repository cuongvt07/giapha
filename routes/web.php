<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\FamilyTree::class)->name('home');
Route::get('/mobile', \App\Livewire\MobileFamilyTree::class)->name('mobile');
