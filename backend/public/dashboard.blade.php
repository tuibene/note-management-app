<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #f1f3f5;
            --text-color: #333;
            --bg-color: #f8f9fa;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
            min-height: 100vh;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
        }
        .dark-mode {
            --primary-color: #74b9ff;
            --secondary-color: #2c2c2c;
            --text-color: #e0e0e0;
            --bg-color: #1a1a1a;
            background-color: var(--bg-color);
            color: var(--text-color);
        }
        .dark-mode .card {
            background-color: var(--secondary-color);
            color: var(--text-color);
        }
        .dark-mode .alert {
            background-color: #4a3c1c;
            color: #f0e4c8;
        }
        .dark-mode .list-group-item {
            background-color: var(--secondary-color);
            color: var(--text-color);
            border-color: #3a3a3a;
        }
        .dark-mode .list-group-item:hover {
            background-color: #3a3a3a;
        }
        .dark-mode .badge.bg-primary {
            background-color: #5a9bd4 !important;
            color: #ffffff;
        }
        .dark-mode .sidebar .list-group-item a {
            color: var(--text-color);
        }
        .dark-mode .sidebar .list-group-item a:hover {
            color: var(--primary-color);
        }
        .dark-mode .search-bar .form-control {
            background-color: #2c2c2c;
            color: var(--text-color);
            border-color: #3a3a3a;
        }
        .dark-mode .search-bar .form-control:focus {
            background-color: #2c2c2c;
            border-color: var(--primary-color);
            box-shadow: 0 0 5px rgba(116, 185, 255, 0.3);
        }
        .dark-mode .search-bar .fa-search {
            color: #a0a0a0;
        }
        .dark-mode .search-bar .form-control::placeholder {
            color: #a0a0a0;
        }
        .dark-mode .modal-content {
            background-color: var(--secondary-color);
            color: var(--text-color);
            border-color: #3a3a3a;
        }
        .dark-mode .modal-header {
            border-bottom-color: #3a3a3a;
        }
        .dark-mode .modal-footer {
            border-top-color: #3a3a3a;
        }
        .dark-mode .modal .form-control {
            background-color: #2c2c2c;
            color: var(--text-color);
            border-color: #3a3a3a;
        }
        .dark-mode .modal .form-control:focus {
            background-color: #2c2c2c;
            border-color: var(--primary-color);
            box-shadow: 0 0 5px rgba(116, 185, 255, 0.3);
        }
        .dark-mode .modal .form-control::placeholder {
            color: #a0a0a0;
        }
        .dark-mode .modal .form-select {
            background-color: #2c2c2c;
            color: var(--text-color);
            border-color: #3a3a3a;
        }
        .dark-mode .modal .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .dark-mode .modal .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: #ffffff;
        }
        .dark-mode .modal .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .dark-mode .modal .btn-primary:hover {
            background-color: #3a7bc8;
            border-color: #3a7bc8;
        }
        .dark-mode .modal .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .dark-mode .modal .btn-danger:hover {
            background-color: #c82333;
            border-color: #c82333;
        }
        .dark-mode .modal .form-check-input {
            background-color: #2c2c2c;
            border-color: #3a3a3a;
        }
        .dark-mode .modal .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .dark-mode .modal .dropdown-menu {
            background-color: var(--secondary-color);
            border-color: #3a3a3a;
        }
        .dark-mode .modal .dropdown-item {
            color: var(--text-color);
        }
        .dark-mode .modal .dropdown-item:hover {
            background-color: #3a3a3a;
        }
        .dark-mode .ql-toolbar, .dark-mode .ql-container {
            background-color: #2c2c2c;
            border-color: #3a3a3a;
        }
        .dark-mode .ql-toolbar .ql-picker-label, .dark-mode .ql-toolbar .ql-icon {
            color: var(--text-color);
        }
        .dark-mode .ql-container .ql-editor {
            color: var(--text-color);
        }
        .navbar {
            background: linear-gradient(90deg, var(--primary-color), #6ab7f5);
            color: white;
            padding: 0.5rem 1rem;
        }
        .navbar-brand, .nav-link {
            color: white !important;
            font-size: 1rem;
        }
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }
        .sidebar {
            position: sticky;
            top: 20px;
            width: 250px;
            transition: all 0.3s;
        }
        .sidebar.collapsed {
            display: none;
        }
        .sidebar .card-body {
            max-height: 70vh;
            overflow-y: auto;
        }
        .sidebar-toggle {
            display: none;
            font-size: 1.25rem;
            cursor: pointer;
            margin-bottom: 10px;
        }
        #notesContainer {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 12px;
        }
        .note-card {
            flex: 0 0 30%;
            min-width: 220px;
            max-width: 100%;
            transition: transform 0.2s, opacity 0.3s;
            opacity: 0;
            animation: fadeIn 0.5s forwards;
        }
        .note-card.list-view {
            flex: 0 0 100%;
            min-width: 200px;
        }
        .search-bar {
            position: relative;
            width: 40%;
        }
        .search-bar .form-control {
            padding-left: 2.5rem;
            border-radius: 20px;
        }
        .search-bar .fa-search {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .dropzone {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            border-radius: 8px;
            background-color: var(--secondary-color);
        }
        .dropzone.dragover {
            background-color: #e9ecef;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .alert-dismissible {
            padding: 1rem 2rem;
            border-radius: 8px;
        }
        .ql-container {
            min-height: 150px;
            border-radius: 5px;
        }
        .ql-editor {
            padding: 10px;
        }
        .empty-state {
            text-align: center;
            padding: 50px;
            color: #6c757d;
        }
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s infinite;
        }
        @keyframes skeleton-loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        .avatar-wrapper {
            position: relative;
            display: inline-block;
        }
        .avatar-progress {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: var(--primary-color);
            animation: spin 1s infinite linear;
            display: none;
        }
        @keyframes spin {
            100% { transform: rotate(360deg); }
        }
        .password-mismatch {
            border-color: #dc3545 !important;
        }
        .custom-multiselect .selected-labels {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-bottom: 10px;
            min-height: 30px;
        }
        .custom-multiselect .dropdown-menu {
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
        }
        .view-note-content {
            border: 1px solid #ced4da;
            padding: 10px;
            min-height: 150px;
            border-radius: 5px;
        }
        .undo-toast .toast-body {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .undo-toast .btn {
            margin-left: 10px;
        }
        @media (max-width: 767px) {
            .sidebar-toggle {
                display: block;
            }
            .search-bar {
                width: 100%;
            }
            .note-card {
                flex: 0 0 100%;
            }
        }
    </style>
</head>
<body class="font-size-md">
    <nav class="navbar navbar-expand-lg shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Notes App</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#settingsModal">Settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" id="logoutBtn">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="verificationWarning" class="alert alert-warning alert-dismissible fade show text-center d-none" role="alert">
        Your account is not verified. Please check your email to activate!
        <a href="#" id="resendLink" class="btn btn-sm btn-outline-primary ms-2">Resend</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="appToast" class="toast" role="alert">
            <div class="toast-body"></div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row main-content-row" id="mainContentRow">
            <div class="col-md-3 sidebar" id="sidebar">
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <h5 class="mb-3">Labels</h5>
                        <ul id="labelList" class="list-group mb-3">
                            <li class="list-group-item"><a href="#" onclick="filterNotes('')"><i class="fas fa-folder me-2"></i><span>All</span></a></li>
                            <li class="list-group-item"><a href="#" onclick="filterNotes('shared')"><i class="fas fa-share-alt me-2"></i><span>Shared</span></a></li>
                        </ul>
                        <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#labelModal">Manage Labels</button>
                    </div>
                </div>
            </div>
            <div class="col-md-9 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                    <div class="search-bar mb-2">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" class="form-control" placeholder="Search notes..." aria-label="Search notes">
                    </div>
                    <div class="d-flex align-items-center mb-2 action-buttons">
                        <button class="btn btn-outline-primary me-2" id="gridViewBtn" aria-label="Switch to grid view"><i class="fas fa-th"></i></button>
                        <button class="btn btn-outline-primary me-2" id="listViewBtn" aria-label="Switch to list view"><i class="fas fa-list"></i></button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#noteModal">New Note</button>
                    </div>
                </div>
                <div id="notesContainer" class="position-relative">
                    <div id="loadingSpinner" class="d-none">
                        <div class="d-flex flex-row">
                            <div class="note-card"><div class="card"><div class="card-body"><div class="skeleton" style="height: 20px; width: 60%; margin-bottom: 10px;"></div><div class="skeleton" style="height: 15px; width: 80%;"></div></div></div></div>
                            <div class="note-card"><div class="card"><div class="card-body"><div class="skeleton" style="height: 20px; width: 60%; margin-bottom: 10px;"></div><div class="skeleton" style="height: 15px; width: 80%;"></div></div></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="avatar-wrapper">
                            <img id="profileAvatar" src="default-avatar.png" class="rounded-circle" width="120" height="120" alt="User Avatar">
                            <div class="avatar-progress"></div>
                        </div>
                        <input type="file" id="avatarInput" accept="image/*" class="d-none">
                        <button class="btn btn-outline-primary mt-2" onclick="document.getElementById('avatarInput').click()">Change Avatar</button>
                    </div>
                    <form id="profileForm">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" id="profileName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" id="profileEmail" class="form-control" readonly>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                    <hr>
                    <h6>Change Password</h6>
                    <form id="changePasswordForm">
                        <div class="mb-3">
                            <label class="form-label">Old Password</label>
                            <input type="password" id="oldPassword" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" id="newPassword" class="form-control" required>
                            <small class="form-text text-muted">Minimum 6 characters</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" id="confirmNewPassword" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="settingsModalLabel">Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Font Size</label>
                        <select id="fontSizeSelect" class="form-select">
                            <option value="sm">Small</option>
                            <option value="md">Medium</option>
                            <option value="lg">Large</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Note Color</label>
                        <input type="color" id="noteColorInput" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Theme</label>
                        <select id="themeSelect" class="form-select">
                            <option value="light">Light</option>
                            <option value="dark">Dark</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button id="saveSettingsBtn" class="btn btn-primary">Save</button>
                        <button id="resetSettingsBtn" class="btn btn-outline-secondary">Reset to Default</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noteModalLabel">Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="noteForm">
                        <input type="hidden" id="noteId">
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" id="noteTitle" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Content <span class="text-danger">*</span></label>
                            <div id="noteContent" class="ql-container"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Images</label>
                            <div class="dropzone" id="imageDropzone">Drag & drop images or click to upload</div>
                            <input type="file" id="noteImages" multiple accept="image/*" class="d-none">
                            <div class="progress mt-2 d-none" id="imageUploadProgress">
                                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                            <div id="imagePreview" class="image-preview mt-2"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Labels</label>
                            <div class="custom-multiselect">
                                <div id="selectedLabels" class="selected-labels"></div>
                                <button type="button" class="btn btn-outline-primary w-100 dropdown-toggle" data-bs-toggle="dropdown">
                                    Select Labels
                                </button>
                                <ul id="labelDropdown" class="dropdown-menu"></ul>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label">
                                <input type="checkbox" id="lockNote"> Lock Note
                            </label>
                            <div id="lockOptions" class="d-none mt-2">
                                <div class="mb-3">
                                    <label class="form-label">Note Password</label>
                                    <input type="password" id="notePassword" class="form-control" placeholder="Enter Password" required>
                                    <small id="passwordStrength" class="form-text"></small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" id="confirmPassword" class="form-control" placeholder="Confirm Password" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label">
                                <input type="checkbox" id="pinNote"> Pin Note
                            </label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Share With</label>
                            <div class="input-group mb-2">
                                <input type="text" id="shareEmail" class="form-control" placeholder="Enter emails (comma-separated)">
                                <select id="sharePermission" class="form-select" style="width: auto;">
                                    <option value="read">View Only</option>
                                    <option value="edit">Edit</option>
                                </select>
                                <button type="button" id="shareNoteBtn" class="btn btn-outline-primary">Share</button>
                            </div>
                            <div id="sharedUsers" class="mt-2"></div>
                            <div id="shareLink" class="d-none">
                                <label class="form-label">Shareable Link</label>
                                <div class="input-group">
                                    <input type="text" id="shareLinkInput" class="form-control" readonly>
                                    <button type="button" class="btn btn-outline-secondary" onclick="copyShareLink()">Copy</button>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="labelModal" tabindex="-1" aria-labelledby="labelModalLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="labelModalLabel">Manage Labels</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="labelForm">
                        <div class="mb-3">
                            <label class="form-label">Label Name</label>
                            <input type="text" id="labelName" class="form-control" required>
                            <input type="hidden" id="originalLabelName">
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                    <hr>
                    <ul id="labelManageList" class="list-group"></ul>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this note?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="unlockNoteModal" tabindex="-1" aria-labelledby="unlockNoteModalLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="unlockNoteModalLabel">Unlock Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="unlockNoteForm">
                        <input type="password" id="unlockPassword" class="form-control" placeholder="Enter Note Password" required>
                        <button type="submit" class="btn btn-primary mt-2">Unlock</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewNoteModal" tabindex="-1" aria-labelledby="viewNoteModalLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewNoteModalLabel">View Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <div id="viewNoteContent" class="view-note-content"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Labels</label>
                        <div id="viewNoteLabels"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Images</label>
                        <div id="viewNoteImages" class="view-note-images"></div>
                    </div>
                    <div class="mb-3 view-note-shared">
                        <label class="form-label">Shared With</label>
                        <div id="viewNoteShared"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Last Edited</label>
                        <div id="viewNoteLastEdited" class="text-muted"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script>
        const API_BASE_URL = '/api';
        let authToken = localStorage.getItem('auth_token');
        let user = null;
        let notes = [];
        let labels = [];
        let settings = { fontSize: 'md', noteColor: '#ffffff', theme: 'light' };
        let currentView = 'grid';
        let selectedLabels = [];
        let deletedNote = null;
        let unlockedNotes = new Set();

        const notesContainer = document.getElementById('notesContainer');
        const searchInput = document.getElementById('searchInput');
        const noteForm = document.getElementById('noteForm');
        const profileForm = document.getElementById('profileForm');
        const changePasswordForm = document.getElementById('changePasswordForm');
        const labelForm = document.getElementById('labelForm');
        const imageDropzone = document.getElementById('imageDropzone');
        const noteImagesInput = document.getElementById('noteImages');
        const imagePreview = document.getElementById('imagePreview');
        const imageUploadProgress = document.getElementById('imageUploadProgress');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const toast = new bootstrap.Toast(document.getElementById('appToast'));
        const noteModal = new bootstrap.Modal(document.getElementById('noteModal'));
        const labelModal = new bootstrap.Modal(document.getElementById('labelModal'));
        const deleteConfirmModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        const unlockNoteModal = new bootstrap.Modal(document.getElementById('unlockNoteModal'));
        const viewNoteModal = new bootstrap.Modal(document.getElementById('viewNoteModal'));
        const lockNote = document.getElementById('lockNote');
        const notePassword = document.getElementById('notePassword');
        const confirmPassword = document.getElementById('confirmPassword');
        const passwordStrength = document.getElementById('passwordStrength');
        const selectedLabelsEl = document.getElementById('selectedLabels');
        const labelDropdown = document.getElementById('labelDropdown');

        let quill = null;
        function initializeQuill() {
            quill = new Quill('#noteContent', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        ['link', 'image'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['clean']
                    ]
                }
            });
        }

        async function apiRequest(endpoint, method = 'GET', data = null, includeToken = true, isMultipart = false) {
            const headers = {
                'Accept': 'application/json',
            };
            if (includeToken && authToken) {
                headers['Authorization'] = `Bearer ${authToken}`;
            }
            if (!isMultipart) {
                headers['Content-Type'] = 'application/json';
            }
            const options = { method, headers };
            if (data && !isMultipart) {
                options.body = JSON.stringify(data);
            } else if (data && isMultipart) {
                options.body = data;
            }
            try {
                const response = await fetch(`${API_BASE_URL}${endpoint}`, options);
                const json = await response.json();
                if (!response.ok) {
                    throw new Error(json.message || `Request failed: ${response.status}`);
                }
                return json;
            } catch (error) {
                throw error;
            }
        }

        function showToast(message, isError = false, undoCallback = null) {
            const toastEl = document.getElementById('appToast');
            const toastBody = toastEl.querySelector('.toast-body');
            toastEl.classList.remove('undo-toast');
            toastBody.className = `toast-body ${isError ? 'text-danger' : 'text-success'}`;
            if (undoCallback) {
                toastEl.classList.add('undo-toast');
                toastBody.innerHTML = `
                    ${message}
                    <button class="btn btn-sm btn-outline-primary" onclick="undoCallback()">Undo</button>
                `;
            } else {
                toastBody.textContent = message;
            }
            toast.show();
        }

        function removeBackdrops() {
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
            document.body.classList.remove('modal-open');
            document.body.style.paddingRight = '';
        }

        function applySettings() {
            document.body.className = `font-size-${settings.fontSize} ${settings.theme === 'dark' ? 'dark-mode' : ''}`;
            document.querySelectorAll('.note-card .card').forEach(card => {
                card.style.backgroundColor = settings.noteColor;
            });
        }

        async function initializeUser() {
            try {
                user = await apiRequest('/user');
                document.getElementById('profileName').value = user.displayName || '';
                document.getElementById('profileEmail').value = user.email || '';
                document.getElementById('profileAvatar').src = user.avatar || 'default-avatar.png';
                if (!user.email_verified_at) {
                    document.getElementById('verificationWarning').classList.remove('d-none');
                }
            } catch (error) {
                showToast('Failed to load user data', true);
                console.error(error);
                window.location.href = 'login.html';
            }
        }

        async function loadNotes(filterLabel = '') {
            loadingSpinner.classList.remove('d-none');
            try {
                let endpoint = '/notes';
                if (filterLabel === 'shared') {
                    endpoint = '/shared-notes';
                } else if (filterLabel) {
                    const label = labels.find(l => l.name === filterLabel);
                    if (label) endpoint = `/notes?label_id=${label.id}`;
                }
                if (searchInput.value.trim()) {
                    endpoint = `/notes/search?keyword=${encodeURIComponent(searchInput.value.trim())}`;
                }
                notes = await apiRequest(endpoint);
                renderNotes();
            } catch (error) {
                showToast('Failed to load notes: ' + error.message, true);
                console.error(error);
            } finally {
                loadingSpinner.classList.add('d-none');
            }
        }

        function renderNotes() {
            notesContainer.innerHTML = '';
            if (notes.length === 0) {
                notesContainer.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-sticky-note fa-3x mb-3"></i>
                        <h5>No Notes Found</h5>
                        <p>Create your first note to get started!</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#noteModal">New Note</button>
                    </div>
                `;
                return;
            }
            notes.forEach((note, index) => {
                const card = document.createElement('div');
                card.className = `note-card ${currentView === 'grid' ? '' : 'list-view'}`;
                card.style.animationDelay = `${index * 0.1}s`;
                const sharedWith = note.shared_with || note.shared_notes || [];
                card.innerHTML = `
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                ${note.pinned ? '<i class="fas fa-thumbtack note-icon text-warning" data-bs-toggle="tooltip" title="Pinned"></i>' : ''}
                                ${note.is_locked ? '<i class="fas fa-lock note-icon text-danger" data-bs-toggle="tooltip" title="Locked"></i>' : ''}
                                ${sharedWith.length ? '<i class="fas fa-share-alt note-icon text-primary" data-bs-toggle="tooltip" title="Shared"></i>' : ''}
                                ${note.title}
                            </h5>
                            <div class="card-text">${note.content.replace(/<[^>]+>/g, '').substring(0, 100)}${note.content.length > 100 ? '...' : ''}</div>
                            <div class="mb-2 mt-2">
                                ${(note.labels || []).map(label => `<span class="badge bg-primary me-1">${label.name}</span>`).join('')}
                            </div>
                            <div class="mb-2">
                                ${(note.images || []).map(img => `<img src="${img}" class="img-thumbnail me-1" width="60" alt="Note Image">`).join('')}
                            </div>
                            <small class="text-muted">Last edited: ${new Date(note.updated_at).toLocaleString()}</small>
                            <div class="mt-2">
                                <button class="btn btn-outline-primary btn-sm me-1" onclick="viewNote('${note.id}')">View</button>
                                <button class="btn btn-outline-primary btn-sm me-1" onclick="editNote('${note.id}')">Edit</button>
                                <button class="btn btn-outline-danger btn-sm" onclick="deleteNote('${note.id}')">Delete</button>
                            </div>
                        </div>
                    </div>
                `;
                notesContainer.appendChild(card);
            });
            initializeTooltips();
        }

        async function viewNote(id) {
            const note = notes.find(n => n.id === id);
            if (note.is_locked && !unlockedNotes.has(id)) {
                unlockNoteModal.show();
                document.getElementById('unlockNoteForm').onsubmit = async e => {
                    e.preventDefault();
                    const password = document.getElementById('unlockPassword').value;
                    try {
                        const response = await apiRequest(`/notes/${id}/verify-password`, 'POST', { password });
                        if (response.verified) {
                            unlockedNotes.add(id);
                            unlockNoteModal.hide();
                            removeBackdrops();
                            displayNote(note);
                        } else {
                            showToast('Incorrect password', true);
                        }
                    } catch (error) {
                        showToast('Failed to verify password: ' + error.message, true);
                        console.error(error);
                    }
                };
            } else {
                displayNote(note);
            }
        }

        function displayNote(note) {
            document.getElementById('viewNoteModalLabel').textContent = note.title;
            document.getElementById('viewNoteContent').innerHTML = note.content;
            document.getElementById('viewNoteLabels').innerHTML = (note.labels || []).map(label => `<span class="badge bg-primary me-1">${label.name}</span>`).join('');
            document.getElementById('viewNoteImages').innerHTML = (note.images || []).map(img => `<img src="${img}" alt="Note Image">`).join('');
            const sharedWith = note.shared_with || note.shared_notes || [];
            document.getElementById('viewNoteShared').innerHTML = sharedWith.length
                ? `<ul>${sharedWith.map(s => `<li>${s.shared_with_email || s.email} (${s.permission})</li>`).join('')}</ul>`
                : '<p>Not shared</p>';
            document.getElementById('viewNoteLastEdited').textContent = `Last edited: ${new Date(note.updated_at).toLocaleString()}`;
            viewNoteModal.show();
        }

        document.getElementById('gridViewBtn').addEventListener('click', () => {
            currentView = 'grid';
            renderNotes();
        });
        document.getElementById('listViewBtn').addEventListener('click', () => {
            currentView = 'list';
            renderNotes();
        });

        searchInput.addEventListener('input', () => loadNotes());

        profileForm.addEventListener('submit', async e => {
            e.preventDefault();
            const display_name = document.getElementById('profileName').value.trim();
            if (!display_name) {
                showToast('Name cannot be empty', true);
                return;
            }
            const formData = new FormData();
            formData.append('display_name', display_name);
            const avatarFile = document.getElementById('avatarInput').files[0];
            if (avatarFile) {
                if (avatarFile.size > 2 * 1024 * 1024) {
                    showToast('Avatar image must be less than 2MB', true);
                    return;
                }
                formData.append('avatar', avatarFile);
            }
            try {
                const progress = document.querySelector('.avatar-progress');
                if (avatarFile) progress.style.display = 'block';
                const updatedUser = await apiRequest('/user/profile', 'POST', formData, true, true);
                user = { ...user, ...updatedUser };
                document.getElementById('profileAvatar').src = user.avatar || 'default-avatar.png';
                showToast('Profile updated successfully');
                progress.style.display = 'none';
            } catch (error) {
                showToast('Failed to update profile: ' + error.message, true);
                console.error(error);
            }
        });

        changePasswordForm.addEventListener('submit', async e => {
            e.preventDefault();
            const current_password = document.getElementById('oldPassword').value;
            const password = document.getElementById('newPassword').value;
            const confirm_password = document.getElementById('confirmNewPassword').value;
            if (password.length < 6) {
                showToast('New password must be at least 6 characters', true);
                return;
            }
            if (password !== confirm_password) {
                showToast('New password and confirmation do not match', true);
                document.getElementById('confirmNewPassword').classList.add('password-mismatch');
                return;
            }
            try {
                await apiRequest('/user/password', 'POST', { current_password, password, password_confirmation: confirm_password });
                showToast('Password changed successfully');
                changePasswordForm.reset();
                document.getElementById('confirmNewPassword').classList.remove('password-mismatch');
            } catch (error) {
                showToast('Failed to change password: ' + error.message, true);
                console.error(error);
            }
        });

        document.getElementById('saveSettingsBtn').addEventListener('click', async () => {
            settings.fontSize = document.getElementById('fontSizeSelect').value;
            settings.noteColor = document.getElementById('noteColorInput').value;
            settings.theme = document.getElementById('themeSelect').value;
            try {
                await apiRequest('/preferences', 'POST', {
                    font_size: settings.fontSize,
                    note_color: settings.noteColor,
                    theme: settings.theme
                });
                applySettings();
                showToast('Settings saved');
                bootstrap.Modal.getInstance(document.getElementById('settingsModal')).hide();
            } catch (error) {
                showToast('Failed to save settings: ' + error.message, true);
                console.error(error);
            }
        });

        document.getElementById('resetSettingsBtn').addEventListener('click', () => {
            settings = { fontSize: 'md', noteColor: '#ffffff', theme: 'light' };
            document.getElementById('fontSizeSelect').value = settings.fontSize;
            document.getElementById('noteColorInput').value = settings.noteColor;
            document.getElementById('themeSelect').value = settings.theme;
            applySettings();
            showToast('Settings reset to default');
        });

        imageDropzone.addEventListener('click', () => noteImagesInput.click());
        imageDropzone.addEventListener('dragover', e => {
            e.preventDefault();
            imageDropzone.classList.add('dragover');
        });
        imageDropzone.addEventListener('dragleave', () => imageDropzone.classList.remove('dragover'));
        imageDropzone.addEventListener('drop', e => {
            e.preventDefault();
            imageDropzone.classList.remove('dragover');
            noteImagesInput.files = e.dataTransfer.files;
            previewImages();
        });
        noteImagesInput.addEventListener('change', previewImages);

    async function previewImages() {
        imagePreview.innerHTML = '';
        const files = Array.from(noteImagesInput.files);
        if (files.length === 0) return;
        for (const file of files) {
            if (!file.type.startsWith('image/')) {
                showToast('Only image files are allowed', true);
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                showToast('Each image must be less than 5MB', true);
                return;
            }
        }
        imageUploadProgress.classList.remove('d-none');
        const progressBar = imageUploadProgress.querySelector('.progress-bar');
        let progress = 0;
        const interval = setInterval(() => {
            progress += 10;
            progressBar.style.width = `${progress}%`;
            if (progress >= 100) clearInterval(interval);
        }, 100);
        try {
            const formData = new FormData();
            files.forEach(file => formData.append('images[]', file));
            const response = await apiRequest('/upload-images', 'POST', formData, true, true);
            imagePreview.innerHTML = response.urls.map(url => `<img src="${url}" />`).join('');
            imageUploadProgress.classList.add('d-none');
        } catch (error) {
            showToast('Failed to upload images: ' + error.message, true);
            console.error(error);
            imageUploadProgress.classList.add('d-none');
        }
    }

    noteForm.addEventListener('submit', async e => {
        e.preventDefault();
        const noteId = document.getElementById('noteId').value;
        const title = document.getElementById('noteTitle').value.trim();
        const content = quill.root.innerHTML.trim();
        const is_locked = document.getElementById('lockNote').checked;
        const pinned = document.getElementById('pinNote').checked;
        const images = Array.from(document.querySelectorAll('#imagePreview img')).map(img => img.src);
        if (!title) {
            showToast('Title is required', true);
            return;
        }
        if (content === '<p><br></p>') {
            showToast('Content is required', true);
            return;
        }
        let password = null;
        if (is_locked) {
            const pwd = notePassword.value;
            const confirm = confirmPassword.value;
            if (pwd !== confirm) {
                confirmPassword.classList.add('password-mismatch');
                showToast('Passwords do not match', true);
                return;
            }
            if (!pwd) {
                showToast('Password is required when locking a note', true);
                return;
            }
            password = pwd;
            confirmPassword.classList.remove('password-mismatch');
        }
        const data = {
            title,
            content,
            pinned,
            is_locked,
            password,
            images,
            labels: selectedLabels
        };
        try {
            const endpoint = noteId ? `/notes/${noteId}` : '/notes';
            const method = noteId ? 'PUT' : 'POST';
            const saveBtn = noteForm.querySelector('button[type="submit"]');
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            await apiRequest(endpoint, method, data);
            showToast(`Note ${noteId ? 'updated' : 'created'}`);
            noteModal.hide();
            removeBackdrops();
            loadNotes();
        } catch (error) {
            showToast('Failed to save note: ' + error.message, true);
            console.error(error);
        } finally {
            const saveBtn = noteForm.querySelector('button[type="submit"]');
            saveBtn.disabled = false;
            saveBtn.innerHTML = 'Save';
        }
    });

    async function editNote(id) {
        const note = notes.find(n => n.id === id);
        if (note.is_locked && !unlockedNotes.has(id)) {
            unlockNoteModal.show();
            document.getElementById('unlockNoteForm').onsubmit = async e => {
                e.preventDefault();
                const password = document.getElementById('unlockPassword').value;
                try {
                    const response = await apiRequest(`/notes/${id}/verify-password`, 'POST', { password });
                    if (response.verified) {
                        unlockedNotes.add(id);
                        unlockNoteModal.hide();
                        removeBackdrops();
                        openNoteForm(note);
                    } else {
                        showToast('Incorrect password', true);
                    }
                } catch (error) {
                    showToast('Failed to verify password: ' + error.message, true);
                    console.error(error);
                }
            };
        } else {
            openNoteForm(note);
        }
    }

    function openNoteForm(note) {
        document.getElementById('noteId').value = note.id || '';
        document.getElementById('noteTitle').value = note.title || '';
        quill.root.innerHTML = note.content || '';
        document.getElementById('lockNote').checked = note.is_locked || false;
        document.getElementById('notePassword').value = '';
        document.getElementById('confirmPassword').value = '';
        document.getElementById('pinNote').checked = note.pinned || false;
        document.getElementById('lockOptions').classList.toggle('d-none', !note.is_locked);
        selectedLabels = (note.labels || []).map(l => l.name);
        renderSelectedLabels();
        renderLabelDropdown();
        imagePreview.innerHTML = (note.images || []).map(img => `<img src="${img}" />`).join('');
        const shareLink = document.getElementById('shareLink');
        const shareLinkInput = document.getElementById('shareLinkInput');
        const sharedUsers = document.getElementById('sharedUsers');
        if (note.id) {
            shareLink.classList.remove('d-none');
            shareLinkInput.value = `${window.location.origin}/notes/${note.id}`;
            const sharedWith = note.shared_with || note.shared_notes || [];
            sharedUsers.innerHTML = sharedWith.map(s => `
                <div class="d-flex justify-content-between mb-1">
                    <span>${s.shared_with_email || s.email} (${s.permission})</span>
                    <button class="btn btn-sm btn-outline-danger" onclick="revokeShare('${note.id}', '${s.shared_with_email || s.email}')">Revoke</button>
                </div>
            `).join('');
        } else {
            shareLink.classList.add('d-none');
            sharedUsers.innerHTML = '';
        }
        noteModal.show();
    }

    document.getElementById('shareNoteBtn').addEventListener('click', async () => {
        const noteId = document.getElementById('noteId').value;
        const emailInput = document.getElementById('shareEmail').value.trim();
        const permission = document.getElementById('sharePermission').value;
        const emails = emailInput.split(',').map(email => email.trim()).filter(email => email);
        if (emails.length === 0 || !emails.every(email => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email))) {
            showToast('Please enter valid email addresses', true);
            return;
        }
        try {
            await apiRequest(`/notes/${noteId}/share`, 'POST', { emails, permission });
            showToast('Note shared successfully');
            loadNotes();
            const note = notes.find(n => n.id === noteId);
            openNoteForm(note);
        } catch (error) {
            showToast('Failed to share note: ' + error.message, true);
            console.error(error);
        }
    });

    async function revokeShare(noteId, email) {
        try {
            await apiRequest(`/notes/${noteId}/share`, 'DELETE', { email });
            showToast('Share revoked successfully');
            loadNotes();
            const note = notes.find(n => n.id === noteId);
            openNoteForm(note);
        } catch (error) {
            showToast('Failed to revoke share: ' + error.message, true);
            console.error(error);
        }
    }

    function copyShareLink() {
        const shareLinkInput = document.getElementById('shareLinkInput');
        shareLinkInput.select();
        document.execCommand('copy');
        showToast('Share link copied to clipboard');
    }

    async function deleteNote(id) {
        const note = notes.find(n => n.id === id);
        deleteConfirmModal.show();
        document.getElementById('confirmDeleteBtn').onclick = async () => {
            try {
                deletedNote = { ...note };
                await apiRequest(`/notes/${id}`, 'DELETE');
                notes = notes.filter(n => n.id !== id);
                renderNotes();
                deleteConfirmModal.hide();
                removeBackdrops();
                showToast('Note deleted', false, async () => {
                    try {
                        const response = await apiRequest('/notes', 'POST', deletedNote);
                        notes.push(response);
                        renderNotes();
                        showToast('Note restored');
                        deletedNote = null;
                    } catch (error) {
                        showToast('Failed to restore note: ' + error.message, true);
                        console.error(error);
                    }
                });
            } catch (error) {
                showToast('Failed to delete note: ' + error.message, true);
                console.error(error);
            }
        };
    }

    async function loadLabels() {
        try {
            labels = await apiRequest('/labels');
            const labelList = document.getElementById('labelList');
            labelList.innerHTML = `
                <li class="list-group-item"><a href="#" onclick="filterNotes('')"><i class="fas fa-folder me-2"></i><span>All</span></a></li>
                <li class="list-group-item"><a href="#" onclick="filterNotes('shared')"><i class="fas fa-share-alt me-2"></i><span>Shared</span></a></li>
                ${labels.map(label => `
                    <li class="list-group-item">
                        <a href="#" onclick="filterNotes('${label.name}')">
                            <i class="fas fa-tag me-2"></i><span>${label.name}</span>
                        </a>
                    </li>
                `).join('')}
            `;
            const labelManageList = document.getElementById('labelManageList');
            labelManageList.innerHTML = labels.map(label => `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    ${label.name}
                    <div>
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="editLabel('${label.name}')">Edit</button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteLabel('${label.name}')">Delete</button>
                    </div>
                </li>
            `).join('');
            renderLabelDropdown();
        } catch (error) {
            showToast('Failed to load labels: ' + error.message, true);
            console.error(error);
        }
    }

    labelForm.addEventListener('submit', async e => {
        e.preventDefault();
        const originalLabelName = document.getElementById('originalLabelName').value;
        const name = document.getElementById('labelName').value.trim();
        if (!name) {
            showToast('Label name cannot be empty', true);
            return;
        }
        try {
            const endpoint = originalLabelName ? `/labels/${encodeURIComponent(originalLabelName)}` : '/labels';
            const method = originalLabelName ? 'PUT' : 'POST';
            await apiRequest(endpoint, method, { name });
            showToast(`Label ${originalLabelName ? 'updated' : 'created'}`);
            labelForm.reset();
            document.getElementById('originalLabelName').value = '';
            labelModal.hide();
            removeBackdrops();
            loadLabels();
        } catch (error) {
            showToast('Failed to save label: ' + error.message, true);
            console.error(error);
        }
    });

    function editLabel(name) {
        // Hide other modals to prevent backdrop stacking
        [noteModal, deleteConfirmModal, unlockNoteModal, viewNoteModal].forEach(modal => {
            const instance = bootstrap.Modal.getInstance(modal._element);
            if (instance) instance.hide();
        });
        removeBackdrops();
        document.getElementById('originalLabelName').value = name;
        document.getElementById('labelName').value = name;
        labelModal.show();
    }

    async function deleteLabel(name) {
        try {
            await apiRequest(`/labels/${encodeURIComponent(name)}`, 'DELETE');
            showToast('Label deleted');
            loadLabels();
        } catch (error) {
            showToast('Failed to delete label: ' + error.message, true);
            console.error(error);
        }
    }

    function renderSelectedLabels() {
        selectedLabelsEl.innerHTML = selectedLabels.map(label => `
            <span class="badge bg-primary" onclick="removeLabel('${label}')">${label} <i class="fas fa-times ms-1"></i></span>
        `).join('');
    }

    function renderLabelDropdown() {
        labelDropdown.innerHTML = labels.map(label => `
            <li class="dropdown-item">
                <input type="checkbox" class="form-check-input" id="label-${label.id}" value="${label.name}" 
                    ${selectedLabels.includes(label.name) ? 'checked' : ''}>
                <label class="form-check-label" for="label-${label.id}">${label.name}</label>
            </li>
        `).join('');
        labelDropdown.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', e => {
                const label = e.target.value;
                if (e.target.checked) {
                    if (!selectedLabels.includes(label)) selectedLabels.push(label);
                } else {
                    selectedLabels = selectedLabels.filter(l => l !== label);
                }
                renderSelectedLabels();
            });
        });
    }

    function removeLabel(label) {
        selectedLabels = selectedLabels.filter(l => l !== label);
        renderSelectedLabels();
        renderLabelDropdown();
    }

    lockNote.addEventListener('change', function () {
        const lockOptions = document.getElementById('lockOptions');
        lockOptions.classList.toggle('d-none', !this.checked);
        if (!this.checked) {
            notePassword.value = '';
            confirmPassword.value = '';
            confirmPassword.classList.remove('password-mismatch');
            passwordStrength.textContent = '';
        }
    });

    function checkPasswordStrength() {
        const pwd = notePassword.value;
        if (!pwd) {
            passwordStrength.textContent = '';
            return;
        }
        const strength = pwd.length < 8 ? 'Weak' : pwd.match(/[A-Z]/) && pwd.match(/[0-9]/) ? 'Strong' : 'Medium';
        passwordStrength.textContent = `Password strength: ${strength}`;
        passwordStrength.className = `form-text text-${strength === 'Weak' ? 'danger' : strength === 'Medium' ? 'warning' : 'success'}`;
    }

    [notePassword, confirmPassword].forEach(input => {
        input.addEventListener('input', () => {
            checkPasswordStrength();
            if (lockNote.checked && notePassword.value !== confirmPassword.value) {
                confirmPassword.classList.add('password-mismatch');
            } else {
                confirmPassword.classList.remove('password-mismatch');
            }
        });
    });

    function filterNotes(label) {
        loadNotes(label);
    }

    document.getElementById('resendLink').addEventListener('click', async e => {
        e.preventDefault();
        try {
            await apiRequest('/email/verification-notification', 'POST');
            showToast('Verification email resent');
        } catch (error) {
            showToast('Failed to resend verification email: ' + error.message, true);
            console.error(error);
        }
    });

    document.getElementById('logoutBtn').addEventListener('click', async () => {
        try {
            await apiRequest('/logout', 'POST');
            localStorage.removeItem('auth_token');
            authToken = null;
            showToast('Logged out');
            setTimeout(() => window.location.href = 'login.html', 1000);
        } catch (error) {
            showToast('Failed to logout: ' + error.message, true);
            console.error(error);
        }
    });

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('collapsed');
    }

    const sidebarToggle = document.createElement('i');
    sidebarToggle.className = 'fas fa-bars sidebar-toggle';
    sidebarToggle.onclick = toggleSidebar;
    document.getElementById('mainContentRow').prepend(sidebarToggle);

    function initializeTooltips() {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el);
        });
    }

    async function init() {
        if (!authToken) {
            window.location.href = 'login.html';
            return;
        }
        try {
            const prefs = await apiRequest('/preferences');
            settings = {
                fontSize: prefs.font_size || 'md',
                noteColor: prefs.note_color || '#ffffff',
                theme: prefs.theme || 'light'
            };
            document.getElementById('fontSizeSelect').value = settings.fontSize;
            document.getElementById('noteColorInput').value = settings.noteColor;
            document.getElementById('themeSelect').value = settings.theme;
            applySettings();
            await initializeUser();
            await loadNotes();
            await loadLabels();
            initializeQuill();
            initializeTooltips();
        } catch (error) {
            showToast('Initialization failed: ' + error.message, true);
            console.error(error);
            window.location.href = 'login.html';
        }
    }

    window.undoCallback = function () {
        toast.hide();
        if (deletedNote) {
            apiRequest('/notes', 'POST', deletedNote)
                .then(response => {
                    notes.push(response);
                    renderNotes();
                    showToast('Note restored');
                    deletedNote = null;
                })
                .catch(error => {
                    showToast('Failed to restore note: ' + error.message, true);
                    console.error(error);
                });
        }
    };

    init();
</script>
</body>
</html>