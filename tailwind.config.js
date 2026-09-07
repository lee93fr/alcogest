import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

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
                sans: ['Manrope', 'ui-sans-serif', 'system-ui'],
                display: ['DM Serif Display', 'Georgia', 'serif'],
            },
            colors: {
                wine: { 50: '#fbf5f7', 100: '#f6e8ec', 600: '#92324c', 700: '#76243a', 800: '#5a192b', 900: '#421321', 950: '#2b0d16' },
                copper: '#c77a43',
                cream: '#f8f3eb',
            },
        },
    },
    plugins: [
        forms,
        typography,
    ],
}

