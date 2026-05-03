import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        // Asegúrate de tener tus rutas de frontend aquí
    ],

    theme: {
        extend: {
            fontFamily: {
                // Inter para textos generales (sobrescribe la sans por defecto)
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                // Playfair para los títulos elegantes
                serif: ['"Playfair Display"', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                // Si en el futuro quieres ajustar el "amber", lo haremos aquí
            }
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        // require('@tailwindcss/aspect-ratio'), <- Si lo tienes, ya no lo necesitamos
    ],
};