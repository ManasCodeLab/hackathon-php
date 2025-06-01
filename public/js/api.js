// API Configuration
const API_BASE_URL = '/api';

// API Client
const api = {
    // Auth endpoints
    auth: {
        async register(userData) {
            try {
                const response = await fetch(`${API_BASE_URL}/auth/register`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(userData)
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || 'Registration failed');
                }

                return await response.json();
            } catch (error) {
                console.error('Auth registration error:', error);
                throw error;
            }
        },

        async login(credentials) {
            try {
                const response = await fetch(`${API_BASE_URL}/auth/login`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(credentials)
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || 'Login failed');
                }

                return await response.json();
            } catch (error) {
                console.error('Auth login error:', error);
                throw error;
            }
        }
    },

    // Registration endpoints
    registration: {
        async create(registrationData) {
            try {
                const token = localStorage.getItem('token');
                if (!token) {
                    throw new Error('Authentication required');
                }

                const response = await fetch(`${API_BASE_URL}/registration`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(registrationData)
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || 'Registration creation failed');
                }

                return await response.json();
            } catch (error) {
                console.error('Registration creation error:', error);
                throw error;
            }
        },

        async getRegistrations() {
            try {
                const token = localStorage.getItem('token');
                if (!token) {
                    throw new Error('Authentication required');
                }

                const response = await fetch(`${API_BASE_URL}/registration`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || 'Failed to fetch registrations');
                }

                return await response.json();
            } catch (error) {
                console.error('Get registrations error:', error);
                throw error;
            }
        }
    }
};

// Utility functions
const utils = {
  isAuthenticated() {
    return !!localStorage.getItem('token');
  },

  logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = '/';
  },

  getToken() {
    return localStorage.getItem('token');
  },

  getUser() {
    const user = localStorage.getItem('user');
    return user ? JSON.parse(user) : null;
  },
};

// Export the API functions
window.api = {
    auth: api.auth,
    registration: api.registration,
    utils
}; 