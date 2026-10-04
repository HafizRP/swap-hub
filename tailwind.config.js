import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'selector',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
            sans: ['Inter', ...defaultTheme.fontFamily.sans],
            display: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50: '#f0fdfa',
                    100: '#ccfbf1',
                    200: '#99f6e4',
                    300: '#5eead4',
                    400: '#2dd4bf',
                    500: '#14b8a6',
                    600: '#0d9488',
                    700: '#0f766e',
                    800: '#115e59',
                    900: '#134e4a',
                    950: '#042f2e',
                },
                surface: {
                    dark: '#1a1917',
                    card: '#242220',
                    border: '#2e2c29',
                },
            },
            boxShadow: {
                'card': '0 1px 2px 0 rgba(0,0,0,0.04)',
                'card-hover': '0 4px 16px -4px rgba(0,0,0,0.08)',
            }
        },
    },

    plugins: [forms],
};
