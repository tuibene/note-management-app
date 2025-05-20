const API_URL = 'http://localhost:8000/api';
let token = localStorage.getItem('token');
let unlockedNotes = new Set();
let lockedNotes = new Set();
let allNotes = [];

document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM loaded, initializing app');
    const urlParams = new URLSearchParams(window.location.search);
    const email = urlParams.get('email');
    const resetToken = urlParams.get('token');

    if (email || resetToken) {
        const resetEmail = document.getElementById('resetEmail');
        const resetTokenInput = document.getElementById('resetToken');
        if (resetEmail && resetTokenInput) {
            resetEmail.value = email ? decodeURIComponent(email) : '';
            resetTokenInput.value = resetToken || '';
            const resetModal = new bootstrap.Modal(document.getElementById('resetPasswordModal'));
            resetModal.show();
        }
    }

    if (token) {
        console.log('Token found, restoring UI state');
        const savedView = localStorage.getItem('noteView') || 'grid';
        const viewToggle = document.getElementById('noteViewToggle');
        if (viewToggle) {
            viewToggle.value = savedView;
            setNoteView(savedView);
        }

        const savedFilter = localStorage.getItem('labelFilter') || '';
        const labelFilter = document.getElementById('labelFilter');
        if (labelFilter) {
            labelFilter.value = savedFilter;
        }

        const savedSearch = localStorage.getItem('titleSearch') || '';
        const titleSearch = document.getElementById('titleSearch');
        if (titleSearch) {
            titleSearch.value = savedSearch;
        }

        getNotes();
    }

    const createNoteModal = document.getElementById('createNoteModal');
    if (createNoteModal) {
        createNoteModal.addEventListener('shown.bs.modal', () => {
            populateLabelsDropdown('createNoteLabels', JSON.parse(localStorage.getItem('createNoteLabels') || '[]'));
        });
    }

    const createNoteLabels = document.getElementById('createNoteLabels');
    if (createNoteLabels) {
        createNoteLabels.addEventListener('change', () => {
            const selected = Array.from(createNoteLabels.selectedOptions).map(option => option.value);
            try {
                localStorage.setItem('createNoteLabels', JSON.stringify(selected));
            } catch (e) {
                console.error('Failed to save createNoteLabels to localStorage:', e);
            }
        });
    }

    const labelFilter = document.getElementById('labelFilter');
    if (labelFilter) {
        labelFilter.addEventListener('change', () => {
            try {
                localStorage.setItem('labelFilter', labelFilter.value);
            } catch (e) {
                console.error('Failed to save labelFilter to localStorage:', e);
            }
            filterNotes();
        });
    }

    const titleSearch = document.getElementById('titleSearch');
    if (titleSearch) {
        titleSearch.addEventListener('input', () => {
            console.log('Title search input:', titleSearch.value);
            try {
                localStorage.setItem('titleSearch', titleSearch.value);
            } catch (e) {
                console.error('Failed to save titleSearch to localStorage:', e);
            }
            filterNotes();
        });
    } else {
        console.error('titleSearch element not found');
    }
});

function setNoteView(view) {
    const notesContainer = document.getElementById('notes-container');
    if (!notesContainer) {
        console.error('notes-container not found');
        return;
    }
    notesContainer.classList.remove('notes-grid', 'notes-list');
    notesContainer.classList.add(`notes-${view}`);
    try {
        localStorage.setItem('noteView', view);
    } catch (e) {
        console.error('Failed to save noteView to localStorage:', e);
    }
    filterNotes();
}

function toggleNoteView() {
    const viewSelect = document.getElementById('noteViewToggle');
    if (!viewSelect) {
        console.error('noteViewToggle not found');
        return;
    }
    const view = viewSelect.value;
    setNoteView(view);
}

async function register() {
    const email = document.getElementById('registerEmail')?.value;
    const display_name = document.getElementById('registerName')?.value;
    const password = document.getElementById('registerPassword')?.value;
    const password_confirmation = document.getElementById('registerPasswordConfirmation')?.value;
    const statusElement = document.getElementById('registerStatus');

    if (!email || !display_name || !password || !password_confirmation || !statusElement) {
        if (statusElement) statusElement.textContent = 'All fields are required.';
        return;
    }

    try {
        const response = await fetch(`${API_URL}/register`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ email, display_name, password, password_confirmation })
        });
        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.message || `HTTP error ${response.status}`);
        }
        const data = await response.json();
        token = data.token;
        localStorage.setItem('token', token);
        statusElement.textContent = 'Registration successful! Please verify your email.';
        statusElement.className = 'text-success';
        alert('Registration successful!');
    } catch (error) {
        statusElement.textContent = `Error: ${error.message}`;
        alert('Failed to register: ' + error.message);
        console.error('Registration error:', error);
    }
}

async function login() {
    const email = document.getElementById('loginEmail')?.value;
    const password = document.getElementById('loginPassword')?.value;
    if (!email || !password) {
        alert('Email and password are required.');
        return;
    }
    try {
        const response = await fetch(`${API_URL}/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        });
        const data = await response.json();
        if (data.token) {
            token = data.token;
            localStorage.setItem('token', token);
            alert('Login successful!');
            getUserInfo();
            getNotes();
        } else {
            alert('Login failed: ' + data.message);
        }
    } catch (error) {
        console.error('Login error:', error);
        alert('Failed to login: ' + error.message);
    }
}

async function resetRequest() {
    const email = document.getElementById('resetRequestEmail')?.value;
    if (!email) {
        alert('Email is required.');
        return;
    }
    try {
        const response = await fetch(`${API_URL}/password/reset-request`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email })
        });
        const data = await response.json();
        alert(data.message);
    } catch (error) {
        console.error('Reset request error:', error);
        alert('Failed to request reset: ' + error.message);
    }
}

async function resetPassword() {
    const email = document.getElementById('resetEmail')?.value;
    const token = document.getElementById('resetToken')?.value;
    const password = document.getElementById('resetPassword')?.value;
    const password_confirmation = document.getElementById('resetPasswordConfirmation')?.value;
    const statusElement = document.getElementById('resetStatus');

    if (!email || !token || !password || !password_confirmation || !statusElement) {
        if (statusElement) statusElement.textContent = 'All fields are required.';
        return;
    }

    try {
        const response = await fetch(`${API_URL}/password/reset`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ email, token, password, password_confirmation })
        });
        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.message || `HTTP error ${response.status}`);
        }
        const data = await response.json();
        statusElement.textContent = 'Password reset successfully.';
        statusElement.className = 'text-success';
        alert('Password reset successful!');
    } catch (error) {
        statusElement.textContent = `Error: ${error.message}`;
        alert('Failed to reset password: ' + error.message);
        console.error('Password reset error:', error);
    }
}

async function getUserInfo() {
    if (!token) return;
    try {
        const response = await fetch(`${API_URL}/user`, {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || `HTTP error ${response.status}`);
        }
        const data = await response.json();
        console.log('User Info:', data);
        const userContainer = document.getElementById('user-container');
        if (!userContainer) return;
        userContainer.innerHTML = '';
        const userCard = document.createElement('div');
        userCard.className = 'user-card';
        userCard.innerHTML = `
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">User Profile</h5>
                    <p class="card-text"><strong>Email:</strong> ${data.email || 'N/A'}</p>
                    <p class="card-text"><strong>Display Name:</strong> ${data.displayName || 'N/A'}</p>
                    <p class="card-text"><strong>Avatar:</strong> ${data.avatar ? `<img src="${data.avatar}" alt="Avatar" class="img-thumbnail" style="max-width: 100px;">` : 'No avatar'}</p>
                    <p class="card-text"><strong>Email Verified:</strong> ${data.email_verified_at ? new Date(data.email_verified_at).toLocaleString() : 'Not verified'}</p>
                </div>
            </div>
        `;
        userContainer.appendChild(userCard);
    } catch (error) {
        console.error('Get User Info Error:', error);
        const userContainer = document.getElementById('user-container');
        if (userContainer) userContainer.innerHTML = '<p class="text-danger">Error fetching user info.</p>';
    }
}

async function logout() {
    if (!token) return alert('Please log in first.');
    try {
        const response = await fetch(`${API_URL}/logout`, {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${token}` }
        });
        const data = await response.json();
        localStorage.removeItem('token');
        localStorage.removeItem('labelFilter');
        localStorage.removeItem('createNoteLabels');
        localStorage.removeItem('titleSearch');
        token = null;
        unlockedNotes.clear();
        lockedNotes.clear();
        const userContainer = document.getElementById('user-container');
        const notesContainer = document.getElementById('notes-container');
        if (userContainer) userContainer.innerHTML = '';
        if (notesContainer) notesContainer.innerHTML = '';
        alert(data.message);
    } catch (error) {
        console.error('Logout error:', error);
        alert('Failed to logout: ' + error.message);
    }
}

async function sendEmailVerification() {
    if (!token) return alert('Please log in first.');
    try {
        const response = await fetch(`${API_URL}/email/verification-notification`, {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${token}` }
        });
        const data = await response.json();
        alert(data.message);
    } catch (error) {
        console.error('Email verification error:', error);
        alert('Failed to send email verification: ' + error.message);
    }
}

async function updateProfile() {
    if (!token) return alert('Please log in first.');
    const displayNameElement = document.getElementById('updateDisplayName');
    const avatarElement = document.getElementById('updateAvatar');
    const statusElement = document.getElementById('updateProfileStatus');
    if (!displayNameElement || !avatarElement || !statusElement) {
        alert('One or more form elements are missing.');
        return;
    }
    const display_name = displayNameElement.value;
    const avatar = avatarElement.files[0];
    if (!display_name) {
        alert('Please enter a new display name.');
        return;
    }
    const formData = new FormData();
    formData.append('display_name', display_name);
    if (avatar) formData.append('avatar', avatar);
    try {
        const response = await fetch(`${API_URL}/user/profile`, {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' },
            body: formData
        });
        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.message || `HTTP error ${response.status}`);
        }
        const data = await response.json();
        statusElement.textContent = `Profile updated: ${data.displayName}${data.avatar ? ', avatar uploaded' : ''}`;
        statusElement.className = 'text-success';
        alert('Profile updated successfully!');
        getUserInfo();
    } catch (error) {
        console.error('Update Profile Error:', error);
        statusElement.textContent = `Error: ${error.message}`;
        statusElement.className = 'text-danger';
        alert('Failed to update profile: ' + error.message);
    }
}

async function changePassword() {
    if (!token) return alert('Please log in first.');
    const currentPassword = document.getElementById('currentPassword')?.value;
    const password = document.getElementById('newPassword')?.value;
    const password_confirmation = document.getElementById('confirmNewPassword')?.value;
    if (!currentPassword || !password || !password_confirmation) {
        alert('All password fields are required.');
        return;
    }
    try {
        const response = await fetch(`${API_URL}/user/password`, {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json' },
            body: JSON.stringify({ current_password: currentPassword, password, password_confirmation })
        });
        const data = await response.json();
        alert(data.message);
    } catch (error) {
        console.error('Change password error:', error);
        alert('Failed to change password: ' + error.message);
    }
}

async function populateLabelsDropdown(selectId, selectedLabels = []) {
    if (!token) return;
    const select = document.getElementById(selectId);
    if (!select) {
        console.error(`Select element with ID ${selectId} not found`);
        return;
    }
    try {
        const response = await fetch(`${API_URL}/labels`, {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        if (!response.ok) throw new Error(`HTTP error ${response.status}`);
        const labels = await response.json();
        console.log('Labels fetched for dropdown:', labels);
        select.innerHTML = '';
        if (!Array.isArray(labels)) {
            console.error('Labels is not an array:', labels);
            return;
        }
        labels.forEach(label => {
            if (!label.name) {
                console.warn('Label missing name property:', label);
                return;
            }
            const option = document.createElement('option');
            option.value = label.name;
            option.textContent = label.name;
            if (selectedLabels.includes(label.name)) option.selected = true;
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Populate Labels Dropdown Error:', error);
        alert('Failed to load labels: ' + error.message);
    }
}

async function getNotes() {
    if (!token) {
        console.log('No token, skipping getNotes');
        return;
    }
    const notesContainer = document.getElementById('notes-container');
    if (!notesContainer) {
        console.error('notes-container not found');
        return;
    }
    try {
        const response = await fetch(`${API_URL}/notes`, {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        if (!response.ok) {
            const errorData = await response.json();
            if (errorData.message.includes('Unauthenticated')) {
                alert('Session expired. Please log in again.');
                localStorage.removeItem('token');
                token = null;
                notesContainer.innerHTML = '<p class="text-muted">Please log in to view notes.</p>';
                return;
            }
            throw new Error(errorData.message || `HTTP error ${response.status}`);
        }
        const data = await response.json();
        console.log('Notes fetched:', data);
        allNotes = data.map(note => ({
            ...note,
            title: note.title || '',
            labels: Array.isArray(note.labels) ? note.labels.map(label => (typeof label === 'string' ? label : label.name || 'Unknown Label')) : []
        }));
        const labelFilter = document.getElementById('labelFilter');
        if (!labelFilter) {
            console.error('labelFilter not found');
            return;
        }
        const uniqueLabels = [...new Set(allNotes.flatMap(note => note.labels))];
        console.log('Unique labels for filter:', uniqueLabels);
        labelFilter.innerHTML = '<option value="">All Notes</option>';
        uniqueLabels.forEach(label => {
            if (typeof label !== 'string') {
                console.warn('Invalid label in filter:', label);
                return;
            }
            const option = document.createElement('option');
            option.value = label;
            option.textContent = label;
            labelFilter.appendChild(option);
        });
        const savedFilter = localStorage.getItem('labelFilter');
        if (savedFilter && uniqueLabels.includes(savedFilter)) {
            labelFilter.value = savedFilter;
        } else {
            labelFilter.value = '';
        }
        filterNotes();
        await populateLabelsDropdown('createNoteLabels', JSON.parse(localStorage.getItem('createNoteLabels') || '[]'));
    } catch (error) {
        console.error('Get Notes Error:', error);
        notesContainer.innerHTML = '<p class="text-danger">Error fetching notes: ' + error.message + '</p>';
    }
}

function filterNotes() {
    const labelFilterElement = document.getElementById('labelFilter');
    const titleSearchElement = document.getElementById('titleSearch');
    const notesContainer = document.getElementById('notes-container');
    if (!labelFilterElement || !titleSearchElement || !notesContainer) {
        console.error('Missing elements: labelFilter, titleSearch, or notes-container');
        return;
    }
    const labelFilter = labelFilterElement.value;
    const titleSearch = titleSearchElement.value.trim().toLowerCase();
    const isListView = notesContainer.classList.contains('notes-list');
    notesContainer.innerHTML = '';
    let filteredNotes = allNotes;
    console.log('All notes:', allNotes);
    console.log('Filtering with label:', labelFilter, 'title:', titleSearch);
    if (labelFilter) {
        filteredNotes = filteredNotes.filter(note => note.labels.includes(labelFilter));
        console.log('After label filter:', filteredNotes);
    }
    if (titleSearch) {
        filteredNotes = filteredNotes.filter(note => {
            const title = (note.title || 'Untitled').toLowerCase();
            const matches = title.includes(titleSearch);
            console.log(`Note ID ${note.id}, Title: ${title}, Matches: ${matches}`);
            return matches;
        });
        console.log('After title filter:', filteredNotes);
    }
    console.log('Rendering', filteredNotes.length, 'notes');
    if (filteredNotes.length === 0) {
        notesContainer.innerHTML = '<p class="text-muted">No notes found.</p>';
        return;
    }
    filteredNotes.forEach(note => {
        if (note.is_locked) lockedNotes.add(note.id.toString());
        const isUnlocked = unlockedNotes.has(note.id.toString());
        const noteCard = document.createElement('div');
        noteCard.className = isListView ? 'note-card-container' : 'col-md-6 col-lg-4 note-card-container';
        noteCard.innerHTML = `
            <div class="card note-card h-100">
                <div class="card-body">
                    <h5 class="card-title">${note.title || 'Untitled'}</h5>
                    <p class="card-text">${note.content || 'No content'}</p>
                    <p class="card-text"><small class="text-muted">Created: ${new Date(note.created_at).toLocaleString()}</small></p>
                    <p class="card-text"><small class="text-muted">ID: ${note.id}</small></p>
                    <div class="mb-2">
                        ${
                            note.labels && note.labels.length > 0
                                ? note.labels.map(label => `<span class="badge bg-secondary">${label}</span>`).join(' ')
                                : '<span class="text-muted">No labels</span>'
                        }
                    </div>
                    <p class="card-text"><small class="text-muted"><i class="bi ${note.is_locked && !isUnlocked ? 'bi-lock-fill' : 'bi-unlock-fill'}"></i></small></p>
                    <div class="note-actions">
                        <button class="btn btn-outline-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#updateNoteModal" onclick="openUpdateNoteModal('${note.id}', '${(note.title || '').replace(/'/g, "\\'")}', '${(note.content || '').replace(/'/g, "\\'")}', [${note.labels.map(label => `'${label.replace(/'/g, "\\'")}'`).join(',')}] )" ${note.is_locked && !isUnlocked ? 'disabled' : ''}><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteNoteModal" onclick="openDeleteNoteModal('${note.id}')" ${note.is_locked && !isUnlocked ? 'disabled' : ''}><i class="bi bi-trash"></i></button>
                    </div>
                </div>
            </div>
        `;
        notesContainer.appendChild(noteCard);
    });
}

async function createNote() {
    if (!token) return alert('Please log in first.');
    const title = document.getElementById('createNoteTitle')?.value;
    const content = document.getElementById('createNoteContent')?.value;
    const labels = Array.from(document.getElementById('createNoteLabels')?.selectedOptions || []).map(option => option.value);
    if (!title || !content) {
        alert('Title and content are required.');
        return;
    }
    try {
        const response = await fetch(`${API_URL}/notes`, {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json' },
            body: JSON.stringify({ title, content, labels })
        });
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || `HTTP error ${response.status}`);
        }
        alert('Note created!');
        document.getElementById('createNoteTitle').value = '';
        document.getElementById('createNoteContent').value = '';
        document.getElementById('createNoteLabels').selectedIndex = -1;
        localStorage.removeItem('createNoteLabels');
        const modal = bootstrap.Modal.getInstance(document.getElementById('createNoteModal'));
        modal.hide();
        getNotes();
    } catch (error) {
        console.error('Create Note Error:', error);
        alert('Failed to create note: ' + error.message);
    }
}

function openUpdateNoteModal(id, title, content, labels) {
    const updateNoteId = document.getElementById('updateNoteId');
    const updateNoteTitle = document.getElementById('updateNoteTitle');
    const updateNoteContent = document.getElementById('updateNoteContent');
    if (!updateNoteId || !updateNoteTitle || !updateNoteContent) {
        console.error('Update note modal elements missing');
        return;
    }
    updateNoteId.value = id;
    updateNoteTitle.value = title;
    updateNoteContent.value = content;
    const savedLabels = JSON.parse(localStorage.getItem(`updateNoteLabels_${id}`) || '[]');
    populateLabelsDropdown('updateNoteLabels', savedLabels.length > 0 ? savedLabels : (Array.isArray(labels) ? labels : []));
    const updateNoteLabels = document.getElementById('updateNoteLabels');
    if (updateNoteLabels) {
        updateNoteLabels.addEventListener('change', () => {
            const selected = Array.from(updateNoteLabels.selectedOptions).map(option => option.value);
            try {
                localStorage.setItem(`updateNoteLabels_${id}`, JSON.stringify(selected));
            } catch (e) {
                console.error('Failed to save updateNoteLabels to localStorage:', e);
            }
        });
    }
}

async function updateNote() {
    if (!token) return alert('Please log in first.');
    const noteId = document.getElementById('updateNoteId')?.value;
    const title = document.getElementById('updateNoteTitle')?.value;
    const content = document.getElementById('updateNoteContent')?.value;
    const labels = Array.from(document.getElementById('updateNoteLabels')?.selectedOptions || []).map(option => option.value);
    if (!noteId || !title || !content) {
        alert('All fields are required.');
        return;
    }
    if (unlockedNotes.has(noteId) || !lockedNotes.has(noteId)) {
        try {
            const response = await fetch(`${API_URL}/notes/${noteId}`, {
                method: 'PUT',
                headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json' },
                body: JSON.stringify({ title, content, labels })
            });
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || `HTTP error ${response.status}`);
            }
            alert('Note updated!');
            localStorage.removeItem(`updateNoteLabels_${noteId}`);
            const modal = bootstrap.Modal.getInstance(document.getElementById('updateNoteModal'));
            modal.hide();
            getNotes();
        } catch (error) {
            console.error('Update Note Error:', error);
            alert('Failed to update note: ' + error.message);
        }
    } else {
        alert('Note is locked. Please unlock it first.');
    }
}

function openDeleteNoteModal(id) {
    const deleteNoteId = document.getElementById('deleteNoteId');
    if (deleteNoteId) deleteNoteId.value = id;
}

async function deleteNote() {
    if (!token) return alert('Please log in first.');
    const noteId = document.getElementById('deleteNoteId')?.value;
    if (!noteId) {
        alert('Note ID is missing.');
        return;
    }
    if (unlockedNotes.has(noteId) || !lockedNotes.has(noteId)) {
        try {
            const response = await fetch(`${API_URL}/notes/${noteId}`, {
                method: 'DELETE',
                headers: { 'Authorization': `Bearer ${token}` }
            });
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || `HTTP error ${response.status}`);
            }
            const data = await response.json();
            alert(data.message);
            localStorage.removeItem(`updateNoteLabels_${noteId}`);
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteNoteModal'));
            modal.hide();
            getNotes();
        } catch (error) {
            console.error('Delete Note Error:', error);
            alert('Failed to delete note: ' + error.message);
        }
    } else {
        alert('Note is locked. Please unlock it first.');
    }
}

async function shareNote() {
    if (!token) return alert('Please log in first.');
    const noteId = document.getElementById('shareNoteId')?.value;
    const emails = document.getElementById('shareEmails')?.value.split(',').map(email => email.trim()).filter(email => email);
    const permission = document.getElementById('sharePermission')?.value;
    const statusElement = document.getElementById('shareStatus');
    if (!noteId || emails.length === 0 || !statusElement) {
        alert('Please enter a Note ID and at least one email.');
        return;
    }
    if (unlockedNotes.has(noteId) || !lockedNotes.has(noteId)) {
        try {
            const response = await fetch(`${API_URL}/notes/${noteId}/share`, {
                method: 'POST',
                headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ emails, permission })
            });
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || `HTTP error ${response.status}`);
            }
            const data = await response.json();
            const successEmails = data.shared_with.filter(item => item.status === 'email_sent').map(item => `${item.email} (${item.permission})`);
            const failedEmails = data.shared_with.filter(item => item.status === 'email_failed').map(item => `${item.email} (${item.permission})`);
            let statusMessage = '';
            if (successEmails.length > 0) statusMessage += `Shared with: ${successEmails.join(', ')}`;
            if (failedEmails.length > 0) statusMessage += `${successEmails.length > 0 ? '; ' : ''}Failed to send email to: ${failedEmails.join(', ')}`;
            statusElement.textContent = statusMessage;
            statusElement.className = failedEmails.length > 0 ? 'text-danger' : 'text-success';
            alert(data.message || 'Note shared successfully!');
        } catch (error) {
            console.error('Share Note Error:', error);
            statusElement.textContent = `Error: ${error.message}`;
            statusElement.className = 'text-danger';
            alert('Failed to share note: ' + error.message);
        }
    } else {
        alert('Note is locked. Please unlock it first.');
    }
}

async function revokeShare() {
    if (!token) return alert('Please log in first.');
    const noteId = document.getElementById('revokeShareNoteId')?.value;
    const email = document.getElementById('revokeShareEmail')?.value;
    if (!noteId || !email) {
        alert('Note ID and email are required.');
        return;
    }
    if (unlockedNotes.has(noteId) || !lockedNotes.has(noteId)) {
        try {
            const response = await fetch(`${API_URL}/notes/${noteId}/share`, {
                method: 'DELETE',
                headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json' },
                body: JSON.stringify({ email })
            });
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || `HTTP error ${response.status}`);
            }
            const data = await response.json();
            alert(data.message);
        } catch (error) {
            console.error('Revoke Share Error:', error);
            alert('Failed to revoke share: ' + error.message);
        }
    } else {
        alert('Note is locked. Please unlock it first.');
    }
}

async function getSharedNotes() {
    if (!token) return alert('Please log in first.');
    try {
        const response = await fetch(`${API_URL}/shared-notes`, {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        const data = await response.json();
        console.log('Shared Notes:', data);
    } catch (error) {
        console.error('Get shared notes error:', error);
        alert('Failed to get shared notes: ' + error.message);
    }
}

async function createPassword() {
    if (!token) return alert('Please log in first.');
    const noteId = document.getElementById('createPasswordNoteId')?.value;
    const password = document.getElementById('createNotePassword')?.value;
    const password_confirmation = document.getElementById('createNotePasswordConfirmation')?.value;
    if (!noteId || !password || !password_confirmation) {
        alert('All fields are required.');
        return;
    }
    if (unlockedNotes.has(noteId) || !lockedNotes.has(noteId)) {
        try {
            const response = await fetch(`${API_URL}/notes/${noteId}/update-password`, {
                method: 'POST',
                headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json' },
                body: JSON.stringify({ password, password_confirmation })
            });
            const data = await response.json();
            if (response.ok) {
                lockedNotes.add(noteId);
                unlockedNotes.delete(noteId);
                getNotes();
                alert(data.message);
            } else {
                alert(data.message || 'Failed to create password');
            }
        } catch (error) {
            console.error('Create password error:', error);
            alert('Failed to create password: ' + error.message);
        }
    } else {
        alert('Note is locked. Please unlock it first.');
    }
}

async function changeNotePassword() {
    if (!token) return alert('Please log in first.');
    const noteId = document.getElementById('changePasswordNoteId')?.value;
    const currentPassword = document.getElementById('currentNotePassword')?.value;
    const password = document.getElementById('newNotePassword')?.value;
    const password_confirmation = document.getElementById('newNotePasswordConfirmation')?.value;
    if (!noteId || !currentPassword || !password || !password_confirmation) {
        alert('All fields are required.');
        return;
    }
    if (unlockedNotes.has(noteId) || !lockedNotes.has(noteId)) {
        try {
            const response = await fetch(`${API_URL}/notes/${noteId}/update-password`, {
                method: 'POST',
                headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json' },
                body: JSON.stringify({ password, password_confirmation })
            });
            const data = await response.json();
            if (response.ok) {
                lockedNotes.add(noteId);
                unlockedNotes.delete(noteId);
                getNotes();
                alert(data.message);
            } else {
                alert(data.message || 'Failed to change password');
            }
        } catch (error) {
            console.error('Change note password error:', error);
            alert('Failed to change password: ' + error.message);
        }
    } else {
        alert('Note is locked. Please unlock it first.');
    }
}

async function unlockNote() {
    if (!token) return alert('Please log in first.');
    const noteId = document.getElementById('unlockNoteId')?.value;
    const password = document.getElementById('unlockNotePassword')?.value;
    const statusElement = document.getElementById('unlockStatus');
    if (!noteId || !password || !statusElement) {
        alert('Note ID and password are required.');
        return;
    }
    try {
        const response = await fetch(`${API_URL}/notes/${noteId}/verify-password`, {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json' },
            body: JSON.stringify({ password })
        });
        if (!response.ok) {
            const errorData = await response.json();
            if (errorData.message.includes('No password set')) {
                statusElement.textContent = `Note ${noteId} has no password and is accessible.`;
                statusElement.className = 'text-success';
                alert('Note has no password and is accessible.');
                unlockedNotes.add(noteId);
                lockedNotes.delete(noteId);
                getNotes();
                return;
            }
            throw new Error(errorData.message || 'Verification failed');
        }
        const data = await response.json();
        if (data.verified) {
            unlockedNotes.add(noteId);
            lockedNotes.add(noteId);
            statusElement.textContent = `Note ${noteId} unlocked.`;
            statusElement.className = 'text-success';
            alert('Note unlocked successfully!');
            getNotes();
        } else {
            lockedNotes.add(noteId);
            statusElement.textContent = 'Incorrect password.';
            statusElement.className = 'text-danger';
            alert('Incorrect password.');
        }
    } catch (error) {
        console.error('Unlock Note Error:', error);
        statusElement.textContent = `Error: ${error.message}`;
        statusElement.className = 'text-danger';
        alert('Failed to unlock note: ' + error.message);
    }
}

async function uploadImagesForNote() {
    if (!token) return alert('Please log in first.');
    const noteIdElement = document.getElementById('uploadNoteId');
    const fileInput = document.getElementById('noteImageUpload');
    const statusElement = document.getElementById('uploadStatus');
    if (!noteIdElement || !fileInput || !statusElement) {
        alert('One or more form elements are missing.');
        return;
    }
    const noteId = noteIdElement.value;
    const files = fileInput.files;
    if (!noteId || files.length === 0) {
        alert('Please enter a Note ID and select at least one image.');
        return;
    }
    if (unlockedNotes.has(noteId) || !lockedNotes.has(noteId)) {
        const formData = new FormData();
        formData.append('note_id', noteId);
        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]);
        }
        try {
            const response = await fetch(`${API_URL}/notes/${noteId}/upload-images`, {
                method: 'POST',
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' },
                body: formData
            });
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.message || `HTTP error ${response.status}`);
            }
            const data = await response.json();
            statusElement.textContent = 'Images uploaded successfully.';
            statusElement.className = 'text-success';
            alert('Images uploaded successfully!');
            noteIdElement.value = '';
            fileInput.value = '';
        } catch (error) {
            statusElement.textContent = `Error: ${error.message}`;
            statusElement.className = 'text-danger';
            alert('Failed to upload images: ' + error.message);
            console.error('Upload Images Error:', error);
        }
    } else {
        statusElement.textContent = 'Note is locked. Please unlock it first.';
        statusElement.className = 'text-danger';
        alert('Note is locked. Please unlock it first.');
    }
}

async function getLabels() {
    if (!token) return alert('Please log in first.');
    const labelsContainer = document.getElementById('labels-container');
    if (!labelsContainer) {
        console.error('labels-container not found');
        return;
    }
    try {
        const response = await fetch(`${API_URL}/labels`, {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || `HTTP error ${response.status}`);
        }
        const data = await response.json();
        console.log('Labels:', data);
        labelsContainer.innerHTML = '';
        if (data.length === 0) {
            labelsContainer.innerHTML = '<p class="text-muted">No labels found.</p>';
            return;
        }
        data.forEach(label => {
            const labelCard = document.createElement('div');
            labelCard.className = 'col-md-6 col-lg-4';
            labelCard.innerHTML = `
                <div class="card label-card h-100">
                    <div class="card-body">
                        <h5 class="card-title">${label.name}</h5>
                        <p class="card-text"><small class="text-muted">Created: ${new Date(label.created_at).toLocaleString()}</small></p>
                    </div>
                </div>
            `;
            labelsContainer.appendChild(labelCard);
        });
        await populateLabelsDropdown('createNoteLabels', JSON.parse(localStorage.getItem('createNoteLabels') || '[]'));
        await populateLabelsDropdown('updateNoteLabels');
    } catch (error) {
        console.error('Get Labels Error:', error);
        labelsContainer.innerHTML = '<p class="text-danger">Error fetching labels.</p>';
    }
}

async function createLabel() {
    if (!token) return alert('Please log in first.');
    const name = document.getElementById('createLabelName')?.value;
    if (!name) {
        alert('Please enter a label name.');
        return;
    }
    try {
        const response = await fetch(`${API_URL}/labels`, {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json' },
            body: JSON.stringify({ name })
        });
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || `HTTP error ${response.status}`);
        }
        alert('Label created!');
        document.getElementById('createLabelName').value = '';
        const modal = bootstrap.Modal.getInstance(document.getElementById('createLabelModal'));
        modal.hide();
        getLabels();
        getNotes();
    } catch (error) {
        console.error('Create Label Error:', error);
        alert('Failed to create label: ' + error.message);
    }
}

async function updateLabel() {
    if (!token) return alert('Please log in first.');
    const currentName = document.getElementById('updateLabelName')?.value;
    const newName = document.getElementById('newLabelName')?.value;
    if (!currentName || !newName) {
        alert('Please enter both current and new label names.');
        return;
    }
    try {
        const response = await fetch(`${API_URL}/labels/${currentName}`, {
            method: 'PUT',
            headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json' },
            body: JSON.stringify({ name: newName })
        });
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || `HTTP error ${response.status}`);
        }
        alert('Label updated!');
        document.getElementById('updateLabelName').value = '';
        document.getElementById('newLabelName').value = '';
        const modal = bootstrap.Modal.getInstance(document.getElementById('updateLabelModal'));
        modal.hide();
        getLabels();
        getNotes();
    } catch (error) {
        console.error('Update Label Error:', error);
        alert('Failed to update label: ' + error.message);
    }
}

async function deleteLabel() {
    if (!token) return alert('Please log in first.');
    const name = document.getElementById('deleteLabelName')?.value;
    if (!name) {
        alert('Please enter a label name.');
        return;
    }
    try {
        const response = await fetch(`${API_URL}/labels/${name}`, {
            method: 'DELETE',
            headers: { 'Authorization': `Bearer ${token}` }
        });
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || `HTTP error ${response.status}`);
        }
        alert('Label deleted!');
        document.getElementById('deleteLabelName').value = '';
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteLabelModal'));
        modal.hide();
        getLabels();
        getNotes();
    } catch (error) {
        console.error('Delete Label Error:', error);
        alert('Failed to delete label: ' + error.message);
    }
}

async function getPreferences() {
    if (!token) return alert('Please log in first.');
    try {
        const response = await fetch(`${API_URL}/preferences`, {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || `HTTP error ${response.status}`);
        }
        const data = await response.json();
        const statusElement = document.getElementById('getPreferencesStatus');
        if (statusElement) {
            statusElement.textContent = `Preferences: Font Size: ${data.font_size}, Note Color: ${data.note_color}, Theme: ${data.theme}`;
            statusElement.className = 'text-success';
        }
    } catch (error) {
        console.error('Get Preferences Error:', error);
        const statusElement = document.getElementById('getPreferencesStatus');
        if (statusElement) {
            statusElement.textContent = `Error: ${error.message}`;
            statusElement.className = 'text-danger';
        }
    }
}

async function updatePreferences() {
    if (!token) return alert('Please log in first.');
    const fontSize = document.getElementById('fontSizePreference')?.value;
    const noteColor = document.getElementById('noteColorPreference')?.value;
    const theme = document.getElementById('themePreference')?.value;
    if (!fontSize || !noteColor || !theme) {
        alert('All preference fields are required.');
        return;
    }
    try {
        const response = await fetch(`${API_URL}/preferences`, {
            method: 'PUT',
            headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json' },
            body: JSON.stringify({ font_size: fontSize, note_color: noteColor, theme })
        });
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || `HTTP error ${response.status}`);
        }
        alert('Preferences updated!');
        const modal = bootstrap.Modal.getInstance(document.getElementById('updatePreferencesModal'));
        modal.hide();
    } catch (error) {
        console.error('Update Preferences Error:', error);
        alert('Failed to update preferences: ' + error.message);
    }
}