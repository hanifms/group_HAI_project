import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'white': '#ffffff',
                'primary': {
                    50: '#f0f7ff',
                    100: '#e0f0ff',
                    200: '#c7e1ff',
                    300: '#a4d1ff',
                    400: '#76b9ff',
                    500: '#479fff',
                    600: '#2181f7',
                    700: '#1466e0',
                    800: '#1554b6',
                    900: '#174a8f',
                    950: '#0c2c59',
                },
                'secondary': {
                    50: '#f0f7ff',
                    100: '#e0effe',
                    200: '#bae0fe',
                    300: '#7cc5fb',
                    400: '#36a7f5',
                    500: '#0d8ce9',
                    600: '#0271c7',
                    700: '#0259a0',
                    800: '#064c84',
                    900: '#0a406f',
                    950: '#072a4b',
                },
            },
        },
    },

    plugins: [forms, typography],
};
