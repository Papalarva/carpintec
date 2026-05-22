import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                // Inter para textos generales (sobrescribe la sans por defecto)
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                // Playfair Display para los títulos elegantes
                serif: ['"Playfair Display"', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                brand: {
                    DEFAULT: '#78350f', // Equivalente a amber-900
                    hover: '#92400e',   // Equivalente a amber-800
                    light: '#fef3c7',   // Equivalente a amber-50
                }
            },
            boxShadow: {
                // Sombras ultra suaves para tarjetas y contenedores boutique
                'premium': '0 4px 20px -2px rgba(0, 0, 0, 0.03)',
                'premium-hover': '0 10px 25px -5px rgba(0, 0, 0, 0.05)',
            }
        },
    },

    plugins: [
        forms,
    ],
};