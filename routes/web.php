<?php

use App\Http\Controllers\Admin\EventAdminController;
use App\Http\Controllers\User\EventUserController;
use Illuminate\Routing\Router;

/** @var Router $router */
$router = app('router');

$router->get('/', function () {
    return view('example');
});

$router->get('/events', [EventUserController::class, 'indexEvents'])->name('events.index');
$router->get('/events/{id}', [EventUserController::class, 'showEvent'])->name('events.show')
    ->where('id', '[0-9]+');

$router->get('/event-registrations', [EventUserController::class, 'indexEventRegistrations'])->name('event-registrations.index');
$router->get('/event-registrations/{id}', [EventUserController::class, 'showEventRegistration'])->name('event-registrations.show')
    ->where('id', '[0-9]+');
$router->post('/event-registrations', [EventUserController::class, 'registerForEvent'])->name('event-registrations.register');
$router->delete('/event-registrations/{id}', [EventUserController::class, 'unregisterForEvent'])->name('event-registrations.unregister')
    ->where('id', '[0-9]+');

$router->post('/event-reviews', [EventUserController::class, 'storeEventReview'])->name('event-reviews.store');

$router->group([
    'middleware' => 'admin',
    'prefix' => 'admin',
], function () use ($router) {
    $router->get('/events', [EventAdminController::class, 'indexEvents'])->name('admin.events.index');
    $router->get('/events/{id}', [EventAdminController::class, 'showEvent'])->name('admin.events.show')
        ->where('id', '[0-9]+');
    $router->post('/events', [EventAdminController::class, 'storeEvent'])->name('admin.events.store');
    $router->put('/events/{id}', [EventAdminController::class, 'updateEvent'])->name('admin.events.update')
        ->where('id', '[0-9]+');
    $router->delete('/events/{id}', [EventAdminController::class, 'destroyEvent'])->name('admin.events.destroy')
        ->where('id', '[0-9]+');

    $router->get('/event-reviews', [EventAdminController::class, 'indexEventReviews'])->name('admin.event-reviews.index');
    $router->get('/event-reviews/{id}', [EventAdminController::class, 'showEventReview'])->name('admin.event-reviews.show')
        ->where('id', '[0-9]+');
    $router->patch('/event-reviews/moderate', [EventAdminController::class, 'moderateEventReview'])->name('admin.event-reviews.moderate');
});
