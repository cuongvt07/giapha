<?php

use Illuminate\Support\Facades\Route;


Route::get('/mobile', \App\Livewire\MobileFamilyTree::class)->name('mobile.tree');
Route::get('/', \App\Livewire\FamilyTree::class)->name('home');
