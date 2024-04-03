<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
  /**
   * Display the active users.
   */
  public function index(Request $request): Response
  {
    return Inertia::render('Users/Index', [
      'users' => User::all(),
    ]);
  }

  /**
   * Edit the user account.
   */
  public function edit(Request $request): Response
  {
    return Inertia::render('Users/Edit', [
      'user' => User::find($request->user),
    ]);
  }

  /**
   * Update the user information.
   */
  public function update(UserUpdateRequest $request): RedirectResponse
  {
    // Removes password field if it's null
    if (!$request->password) {
      unset($request['password']);
    }

    // Update the User details
    $request->user()->update($request->all());

    // Redirect to the User Index page
    return Redirect::route('users.index');
  }

  /**
   * Delete the user account.
   */
  public function delete(Request $request): RedirectResponse
  {
    User::find($request->user)->delete();

    return Redirect::route('users.index');
  }

  /**
   * Create the user account.
   */
  public function create(): Response
  {
    return Inertia::render('Users/Create');
  }

  /**
   * Store the user account.
   */
  public function store(Request $request): RedirectResponse
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:' . User::class,
      'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);

    return Redirect::route('users.index');
  }

  /**
   * Display deleted (Soft Delete) from users.
   */
  public function trashed(Request $request): Response
  {
    return Inertia::render('Users/Trashed', [
      'users' => User::onlyTrashed()->get(),
    ]);
  }

  /**
   * Destroy the user account.
   */
  public function destroy(Request $request): RedirectResponse
  {
    User::withTrashed()->find($request->user)->forcedelete();

    return Redirect::route('users.trashed');
  }

  /**
   * Restore the trashed user.
   */
  public function restore(Request $request): RedirectResponse
  {
    User::withTrashed()->find($request->user)->restore();

    return Redirect::route('users.trashed');
  }
}
