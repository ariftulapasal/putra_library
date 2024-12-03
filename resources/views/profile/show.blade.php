@extends('layouts.app')

@section('content')
<div class="wrapper">
    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>User Profile</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Profile Information -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-user"></i> Profile Information</h3>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                    <img id="facePreview" class="img-circle img-bordered-sm" src="/storage/profile-photos/default-avatar.jpg" alt="User Image" style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                                <ul class="list-group list-group-unbordered mt-3">
                                    <li class="list-group-item">
                                        <b>Name</b> <a class="float-right" id="userName">Momo</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Email</b> <a class="float-right" id="userEmail">momo@gmail.com</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Member Since</b> <a class="float-right" id="userCreated">1 week ago</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Last Login</b> <a class="float-right" id="userLastLogin">Never</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Update Profile -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-edit"></i> Update Profile</h3>
                            </div>
                            <form id="updateProfileForm">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <h5>Change Password</h5>
                                    <div class="form-group">
                                        <label for="current_password">Current Password</label>
                                        <input type="password" class="form-control" id="current_password" name="current_password">
                                    </div>
                                    <div class="form-group">
                                        <label for="new_password">New Password</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password">
                                    </div>
                                    <div class="form-group">
                                        <label for="new_password_confirmation">Confirm New Password</label>
                                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary float-right">Update Profile</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    // JavaScript code remains the same as before
    async function loadUserInfo() {
        try {
            const response = await fetch('/current-user');
            const data = await response.json();

            document.getElementById('userName').textContent = data.user.name;
            document.getElementById('userEmail').textContent = data.user.email;
            document.getElementById('userCreated').textContent = data.user.created_at;
            document.getElementById('userLastLogin').textContent = data.user.last_login || 'Never';

            document.getElementById('name').value = data.user.name;
            document.getElementById('email').value = data.user.email;

            const facePreview = document.getElementById('facePreview');
            facePreview.src = `/storage/profile-photos/${data.user.id}.jpg`;
            facePreview.onerror = function() {
                this.src = '/storage/profile-photos/default-avatar.png';
            };
        } catch (error) {
            console.error('Error loading user info:', error);
        }
    }

    document.getElementById('updateProfileForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        try {
            const formData = new FormData(e.target);
            const response = await fetch('/profile/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(Object.fromEntries(formData))
            });

            if (response.ok) {
                alert('Profile updated successfully!');
                loadUserInfo();
            } else {
                alert('Failed to update profile.');
            }
        } catch (error) {
            console.error('Error updating profile:', error);
        }
    });

    document.addEventListener('DOMContentLoaded', loadUserInfo);
</script>

@endsection
