<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Home route
$routes->get('/', 'Home::index');

// ========================================
// DYNAMIC THEME CSS
// ========================================
$routes->get('theme.css', 'ThemeController::css');

// ========================================
// AUTHENTICATION ROUTES
// ========================================
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attemptLogin');
$routes->get('logout', 'AuthController::logout');

// ========================================
// ADMIN ROUTES (Protected)
// ========================================
// Admin dashboard and main pages
$routes->group('admin', ['namespace' => 'App\Controllers\Role', 'filter' => 'auth:admin'], function($routes) {
    // Dashboard
    $routes->get('dashboard', 'AdminController::dashboard');
    
    // Contestants management (CRUD)
    $routes->get('contestants', 'ContestantsController::index');
    $routes->get('contestants/create', 'ContestantsController::create');
    $routes->post('contestants/store', 'ContestantsController::store');
    $routes->get('contestants/view/(:num)', 'ContestantsController::view/$1');
    $routes->get('contestants/edit/(:num)', 'ContestantsController::edit/$1');
    $routes->post('contestants/update/(:num)', 'ContestantsController::update/$1');
    $routes->post('contestants/delete/(:num)', 'ContestantsController::delete/$1');
    $routes->post('contestants/remove-photo/(:num)', 'ContestantsController::removePhoto/$1');
    
    // Judges management (CRUD)
    $routes->get('judges', 'JudgesController::index');
    $routes->get('judges/create', 'JudgesController::create');
    $routes->post('judges/store', 'JudgesController::store');
    $routes->get('judges/view/(:num)', 'JudgesController::view/$1');
    $routes->get('judges/edit/(:num)', 'JudgesController::edit/$1');
    $routes->post('judges/update/(:num)', 'JudgesController::update/$1');
    $routes->post('judges/delete/(:num)', 'JudgesController::delete/$1');
    
    // Rounds & Criteria management
    $routes->get('rounds-criteria', 'RoundsCriteriaController::index');
    $routes->get('rounds-criteria/create', 'RoundsCriteriaController::create');
    $routes->post('rounds-criteria/store', 'RoundsCriteriaController::store');
    $routes->get('rounds-criteria/view/(:num)', 'RoundsCriteriaController::view/$1');
    $routes->post('rounds-criteria/delete/(:num)', 'RoundsCriteriaController::delete/$1');
    
    // Results & Rankings
    $routes->get('results', 'ResultsController::index');
    $routes->get('results/round/(:num)', 'ResultsController::viewRound/$1');
    $routes->get('results/contestant/(:num)/(:num)', 'ResultsController::viewContestantDetails/$1/$2');
    $routes->get('results/overall', 'ResultsController::overall');
    
    // Settings
    $routes->get('settings', 'SettingsController::index');
    $routes->post('settings/update-general', 'SettingsController::updateGeneral');
    $routes->post('settings/update-theme', 'SettingsController::updateTheme');
    $routes->post('settings/apply-preset', 'SettingsController::applyPreset');
    $routes->post('settings/remove-logo', 'SettingsController::removeLogo');
});

// ========================================
// JUDGE ROUTES (Protected)
// ========================================
$routes->group('judge', ['namespace' => 'App\Controllers\Role', 'filter' => 'auth:judge'], function($routes) {
    // Dashboard
    $routes->get('dashboard', 'JudgeController::dashboard');
    
    // Scoring
    $routes->get('select-round', 'JudgeController::selectRound');
    $routes->get('score-round/(:num)', 'JudgeController::scoreRound/$1');
    $routes->get('score-contestant/(:num)/(:num)', 'JudgeController::scoreContestant/$1/$2');
    $routes->post('submit-scores', 'JudgeController::submitScores');
    
    // Legacy routes
    $routes->get('contestants', 'JudgeController::contestants');
    
    // Submit scores
    $routes->get('submit-score', 'JudgeController::submitScore');
    
    // View scoring history
    $routes->get('history', 'JudgeController::history');
});
