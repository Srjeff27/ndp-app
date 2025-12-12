import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: false,
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                maroon: {
                    50: '#fdf2f4',
                    100: '#fce7eb',
                    200: '#fad0d9',
                    300: '#f5a9b8',
                    400: '#ee7891',
                    500: '#e2476c',
                    600: '#ce2652',
                    700: '#800020',
                    800: '#6b1c2c',
                    900: '#5c1c2a',
                    950: '#330a12',
                },
                gold: {
                    50: '#fefce8',
                    100: '#fef9c3',
                    200: '#fef08a',
                    300: '#fde047',
                    400: '#facc15',
                    500: '#d4a419',
                    600: '#b8860b',
                    700: '#a16207',
                    800: '#854d0e',
                    900: '#713f12',
                },
            },
        },
    },

    plugins: [forms],
};
