/**
 * scripts.js - Funcionalidades JavaScript para SmartPark
 * Sistema de Gestão de Estacionamento Inteligente IoT
 */

document.addEventListener('DOMContentLoaded', function() {
    // Navbar scrolling animation
    const navbar = document.querySelector('.navbar');

    window.addEventListener('scroll', function () {
        if (window.scrollY > 50) {
            navbar.classList.add('shadow-sm');
        } else {
            navbar.classList.remove('shadow-sm');
        }
    });

    // Smooth scrolling para links de ancoragem
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            if (this.getAttribute('href') === '#') return;

            if (this.getAttribute('data-bs-toggle') === 'modal') return;

            e.preventDefault();

            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                const navbarHeight = document.querySelector('.navbar').offsetHeight;
                const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - navbarHeight;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Alternar entre modais
    const loginLink = document.querySelectorAll('[data-bs-target="#loginModal"]');
    const registoLink = document.querySelectorAll('[data-bs-target="#registoModal"]');

    loginLink.forEach(link => {
        link.addEventListener('click', function () {
            const registoModal = bootstrap.Modal.getInstance(document.querySelector('#registoModal'));
            if (registoModal) {
                registoModal.hide();
            }
        });
    });

    registoLink.forEach(link => {
        link.addEventListener('click', function () {
            const loginModal = bootstrap.Modal.getInstance(document.querySelector('#loginModal'));
            if (loginModal) {
                loginModal.hide();
            }
        });
    });

    // Validação do formulário de registo
    const registoForm = document.getElementById('registoForm');

    if (registoForm) {
        registoForm.addEventListener('submit', function (e) {
                const password = document.getElementById('registoPassword').value;
                const confirmPassword = document.getElementById('registoConfirmPassword').value;
                const passwordError = document.getElementById('passwordError');
                const confirmPasswordError = document.getElementById('confirmPasswordError');
                let valid = true;
                passwordError.textContent = '';
                confirmPasswordError.textContent = '';

                if (password.length < 8) {
                    passwordError.textContent = 'A senha deve ter pelo menos 8 caracteres.';
                    valid = false;
                }
                if (password !== confirmPassword) {
                    confirmPasswordError.textContent = 'As senhas não coincidem.';
                    valid = false;
                }
                if (!valid) {
                    e.preventDefault();
                }
            }
        );
        // Validação do formulário de login
        const loginForm = document.getElementById('loginForm');

        if (loginForm) {

        }
    }
})
