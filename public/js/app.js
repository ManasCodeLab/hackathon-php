document.addEventListener('DOMContentLoaded', () => {
    // Initialize the application
    initializeApp();
});

function initializeApp() {
    // Add any initialization logic here
    console.log('CodeGenX Hackathon initialized');
}

// Handle registration form submission
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('hackathonRegistrationForm');
    if (form) {
        form.addEventListener('submit', handleRegistration);
    }
});

async function handleRegistration(e) {
    e.preventDefault();
    
    const form = e.target;
    const submitButton = form.querySelector('button[type="submit"]');
    
    try {
        // Disable submit button and show loading state
        submitButton.disabled = true;
        submitButton.textContent = 'Registering...';
        
        const formData = new FormData(form);
        const registrationData = {
            registrationType: document.getElementById('soloBtn').classList.contains('bg-accent/20') ? 'solo' : 'team',
            name: formData.get('name'),
            email: formData.get('email'),
            githubUsername: formData.get('githubUsername'),
            password: formData.get('password'),
            projectName: formData.get('projectName'),
            projectDescription: formData.get('projectDescription')
        };

        if (registrationData.registrationType === 'team') {
            registrationData.teamName = formData.get('teamName');
            registrationData.teamMembers = formData.get('teamMembers')?.split(',').map(member => member.trim()) || [];
        }

        // First register the user
        const authResponse = await api.auth.register({
            name: registrationData.name,
            email: registrationData.email,
            password: registrationData.password,
            githubUsername: registrationData.githubUsername
        });

        if (authResponse.token) {
            // Store auth data
            localStorage.setItem('token', authResponse.token);
            localStorage.setItem('user', JSON.stringify(authResponse.user));

            // Then create the registration
            const registrationResponse = await api.registration.create({
                registrationType: registrationData.registrationType,
                teamName: registrationData.teamName,
                teamMembers: registrationData.teamMembers,
                projectName: registrationData.projectName,
                projectDescription: registrationData.projectDescription
            });

            if (registrationResponse.registration) {
                showSuccessModal();
                form.reset();
            }
        }
    } catch (error) {
        console.error('Registration error:', error);
        showError(error.message || 'Registration failed. Please try again.');
    } finally {
        // Re-enable submit button and restore text
        submitButton.disabled = false;
        submitButton.textContent = 'Submit Registration';
    }
}

function showSuccessModal() {
    const modal = document.getElementById('modal');
    if (modal) {
        modal.style.display = 'flex';
    }
}

function showError(message) {
    // Create error message element if it doesn't exist
    let errorElement = document.getElementById('registrationError');
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.id = 'registrationError';
        errorElement.className = 'text-red-500 text-sm mt-2 text-center';
        const form = document.getElementById('hackathonRegistrationForm');
        form.insertBefore(errorElement, form.firstChild);
    }
    
    // Show error message
    errorElement.textContent = message;
    errorElement.style.display = 'block';
    
    // Hide error message after 5 seconds
    setTimeout(() => {
        errorElement.style.display = 'none';
    }, 5000);
}

// Mobile menu functionality
document.addEventListener('DOMContentLoaded', () => {
    const menuBtn = document.getElementById('menuBtn');
    const closeMenuBtn = document.getElementById('closeMenu');
    const mobileMenu = document.getElementById('mobileMenu');

    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener('click', () => {
            mobileMenu.style.display = 'flex';
        });
    }

    if (closeMenuBtn && mobileMenu) {
        closeMenuBtn.addEventListener('click', () => {
            mobileMenu.style.display = 'none';
        });
    }
});

// Theme toggle functionality
document.addEventListener('DOMContentLoaded', () => {
    const themeToggle = document.querySelector('[onclick="toggleTheme()"]');
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
        });
    }
});

// FAQ accordion functionality
document.addEventListener('DOMContentLoaded', () => {
    const faqItems = document.querySelectorAll('.accordion');
    faqItems.forEach((item, index) => {
        item.addEventListener('click', () => toggleFaq(index));
    });
});

function toggleFaq(idx) {
    for (let i = 0; i < 4; i++) {
        const icon = document.getElementById(`faq-icon-${i}`);
        const panel = document.getElementById(`faq-panel-${i}`);
        if (i === idx) {
            const expanded = panel.style.maxHeight && panel.style.maxHeight !== '0px';
            if (expanded) {
                panel.style.maxHeight = '0px';
                icon.style.transform = '';
            } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
                icon.style.transform = 'rotate(180deg)';
            }
        } else {
            panel.style.maxHeight = '0px';
            icon.style.transform = '';
        }
    }
}

// Countdown timer
function updateCountdown() {
    const eventTime = new Date('2025-07-20T10:00:00Z').getTime();
    const now = Date.now();
    let diff = Math.max(0, eventTime - now);
    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    diff -= days * (1000 * 60 * 60 * 24);
    const hours = Math.floor(diff / (1000 * 60 * 60));
    diff -= hours * (1000 * 60 * 60);
    const mins = Math.floor(diff / (1000 * 60));
    diff -= mins * (1000 * 60);
    const secs = Math.floor(diff / 1000);
    
    const daysElement = document.getElementById('days');
    const hoursElement = document.getElementById('hours');
    const minutesElement = document.getElementById('minutes');
    const secondsElement = document.getElementById('seconds');

    if (daysElement) daysElement.textContent = String(days).padStart(2, '0');
    if (hoursElement) hoursElement.textContent = String(hours).padStart(2, '0');
    if (minutesElement) minutesElement.textContent = String(mins).padStart(2, '0');
    if (secondsElement) secondsElement.textContent = String(secs).padStart(2, '0');
}

// Initialize countdown timer
document.addEventListener('DOMContentLoaded', () => {
    setInterval(updateCountdown, 1000);
    updateCountdown();
}); 